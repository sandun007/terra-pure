<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\WholesalerProduct;
use App\Models\Wholesaler;
use App\Models\Category;
use App\Models\Brand;
use App\Models\WholesalerProductPriceChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;

class EmployeeWholesalerProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
    }

    /**
     * Display all wholesaler products
     */
    public function index(Request $request)
    {
        try {
            $employee = Auth::guard('employee')->user();

            $query = WholesalerProduct::with([
                'wholesaler',
                'category',
                'brand',
                'lastPriceUpdatedBy'
            ]);

            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%")
                        ->orWhereHas('wholesaler', function($q2) use ($search) {
                            $q2->where('business_name', 'like', "%{$search}%")
                                ->orWhere('name', 'like', "%{$search}%");
                        });
                });
            }

            // Wholesaler filter
            if ($request->filled('wholesaler_id') && $request->wholesaler_id != 'all') {
                $query->where('wholesaler_id', $request->wholesaler_id);
            }

            // Category filter
            if ($request->filled('category_id') && $request->category_id != 'all') {
                $query->where('category_id', $request->category_id);
            }

            // Brand filter
            if ($request->filled('brand_id') && $request->brand_id != 'all') {
                $query->where('brand_id', $request->brand_id);
            }

            // Status filter
            if ($request->filled('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            // Stock filter
            if ($request->filled('stock_status')) {
                switch ($request->stock_status) {
                    case 'in_stock':
                        $query->where('qty', '>', 0);
                        break;
                    case 'low_stock':
                        $query->whereRaw('qty <= min_stock_level');
                        break;
                    case 'out_of_stock':
                        $query->where('qty', '<=', 0);
                        break;
                }
            }

            // Exchange filter
            if ($request->filled('exchanged')) {
                if ($request->exchanged === 'yes') {
                    $query->where('exchange_count', '>', 0);
                } elseif ($request->exchanged === 'no') {
                    $query->where(function($q) {
                        $q->whereNull('exchange_count')
                            ->orWhere('exchange_count', 0);
                    });
                }
            }

            // Price changed filter
            if ($request->filled('price_changed')) {
                if ($request->price_changed === 'yes') {
                    $query->where('price_changes_count', '>', 0);
                } elseif ($request->price_changed === 'no') {
                    $query->where(function($q) {
                        $q->whereNull('price_changes_count')
                            ->orWhere('price_changes_count', 0);
                    });
                }
            }

            // Sorting
            $sortField = $request->get('sort_field', 'created_at');
            $sortDirection = $request->get('sort_direction', 'desc');

            $allowedSortFields = [
                'name', 'code', 'selling_price', 'qty',
                'created_at', 'price_changes_count', 'exchange_count'
            ];

            if (in_array($sortField, $allowedSortFields)) {
                $query->orderBy($sortField, $sortDirection);
            } else {
                $query->latest();
            }

            $products = $query->paginate(20)->withQueryString();

            // Get filter data
            $wholesalers = Wholesaler::orderBy('business_name')->get(['id', 'business_name as name']);
            $categories = Category::orderBy('name')->get(['id', 'name']);
            $brands = Brand::orderBy('name')->get(['id', 'name']);

            // Stats for the view
            $stats = [
                'total_products' => WholesalerProduct::count(),
                'total_wholesalers' => Wholesaler::count(),
                'total_exchanges' => \App\Models\WholesalerProductExchange::count(),
                'total_price_changes' => WholesalerProductPriceChange::count(),
                'today_price_changes' => WholesalerProductPriceChange::whereDate('created_at', today())->count(),
            ];

            return view('employee.wholesaler.products.index', compact(
                'products',
                'wholesalers',
                'categories',
                'brands',
                'stats',
                'employee'
            ));

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerProductController@index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading products: ' . $e->getMessage());
        }
    }

    /**
     * Show single product details with price history and exchange history
     */
    public function show($id)
    {
        try {
            $employee = Auth::guard('employee')->user();

            // Debug: Log the ID being accessed
            Log::info('Attempting to show product with ID: ' . $id);

            $product = WholesalerProduct::with([
                'wholesaler',
                'category',
                'brand',
                'lastPriceUpdatedBy',
                'lastExchangedBy',
                'originalWholesaler'
            ])->findOrFail($id);

            // Debug: Log successful product retrieval
            Log::info('Product found: ' . $product->name);

            // Get price changes
            $priceChanges = WholesalerProductPriceChange::where('product_id', $id)
                ->with(['employee', 'approvedBy'])
                ->orderBy('created_at', 'desc')
                ->paginate(20, ['*'], 'price_page');

            // Get exchanges (as source or destination)
            $sourceExchanges = \App\Models\WholesalerProductExchange::where('source_product_id', $id)
                ->with(['sourceWholesaler', 'destinationWholesaler', 'employee'])
                ->orderBy('created_at', 'desc')
                ->get();

            $destinationExchanges = \App\Models\WholesalerProductExchange::where('destination_product_id', $id)
                ->with(['sourceWholesaler', 'destinationWholesaler', 'employee'])
                ->orderBy('created_at', 'desc')
                ->get();

            $exchanges = $sourceExchanges->concat($destinationExchanges)
                ->sortByDesc('created_at');

            // Paginate exchanges
            $currentPage = request()->get('exchange_page', 1);
            $perPage = 20;
            $exchanges = new \Illuminate\Pagination\LengthAwarePaginator(
                $exchanges->forPage($currentPage, $perPage),
                $exchanges->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'query' => request()->query(), 'pageName' => 'exchange_page']
            );

            // Stats
            $stats = [
                'total_price_changes' => $priceChanges->total(),
                'total_exchanges' => $sourceExchanges->count() + $destinationExchanges->count(),
                'last_price_change' => $priceChanges->first(),
                'last_exchange' => $exchanges->first(),
                'profit_margin' => $product->profit_margin_percentage ?? 0,
                'total_profit' => ($product->final_price - ($product->cost_price ?? 0)) * ($product->qty ?? 0),
            ];

            return view('employee.wholesaler.products.show', compact(
                'product',
                'priceChanges',
                'exchanges',
                'stats',
                'employee'
            ));

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerProductController@show error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->route('employee.wholesaler.products.index')
                ->with('error', 'Product not found: ' . $e->getMessage());
        }
    }

    /**
     * Show price history for a product
     */
    public function priceHistory($id, Request $request)
    {
        try {
            $product = WholesalerProduct::findOrFail($id);

            $query = WholesalerProductPriceChange::where('product_id', $id)
                ->with(['employee', 'approvedBy']);

            // Filter by date range
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Filter by employee
            if ($request->filled('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }

            // Filter by change type
            if ($request->filled('change_type')) {
                $query->where('change_type', $request->change_type);
            }

            $priceChanges = $query->orderBy('created_at', 'desc')->paginate(50);

            // Get employees for filter
            $employees = \App\Models\Employee::orderBy('name')->get(['id', 'name']);

            return view('employee.wholesaler.products.price-history', compact(
                'product',
                'priceChanges',
                'employees'
            ));

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerProductController@priceHistory error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading price history: ' . $e->getMessage());
        }
    }

    /**
     * Export products to CSV
     */
    public function export(Request $request)
    {
        try {
            $query = WholesalerProduct::with(['wholesaler', 'category', 'brand']);

            // Apply filters (same as index)
            if ($request->filled('wholesaler_id')) {
                $query->where('wholesaler_id', $request->wholesaler_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $products = $query->get();

            $filename = 'wholesaler-products-' . date('Y-m-d') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}",
            ];

            $callback = function() use ($products) {
                $file = fopen('php://output', 'w');

                // Headers
                fputcsv($file, [
                    'ID',
                    'Product Code',
                    'Product Name',
                    'Wholesaler',
                    'Category',
                    'Brand',
                    'Selling Price',
                    'Display Price',
                    'Final Price',
                    'Discount (%)',
                    'Stock Qty',
                    'Min Stock',
                    'Status',
                    'Price Changes',
                    'Exchanges',
                    'Last Price Change',
                    'Last Exchange',
                    'Created At'
                ]);

                // Data
                foreach ($products as $product) {
                    fputcsv($file, [
                        $product->id,
                        $product->code,
                        $product->name,
                        $product->wholesaler->business_name ?? 'N/A',
                        $product->category->name ?? 'N/A',
                        $product->brand->name ?? 'N/A',
                        $product->selling_price,
                        $product->display_price ?? $product->selling_price,
                        $product->final_price,
                        $product->discount ?? 0,
                        $product->qty ?? 0,
                        $product->min_stock_level ?? 5,
                        $product->status,
                        $product->price_changes_count ?? 0,
                        $product->exchange_count ?? 0,
                        $product->last_price_updated_at ? $product->last_price_updated_at->format('Y-m-d H:i') : 'Never',
                        $product->last_exchanged_at ? $product->last_exchanged_at->format('Y-m-d H:i') : 'Never',
                        $product->created_at->format('Y-m-d H:i')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerProductController@export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting products: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update products (status, etc.)
     */
    public function bulkUpdate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_ids' => 'required|array',
                'product_ids.*' => 'exists:wholesaler_products,id',
                'action' => 'required|in:activate,deactivate,update_stock',
                'value' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $productIds = $request->product_ids;
            $action = $request->action;
            $count = 0;

            DB::beginTransaction();

            foreach ($productIds as $id) {
                $product = WholesalerProduct::find($id);

                if (!$product) continue;

                switch ($action) {
                    case 'activate':
                        $product->status = 'active';
                        $product->save();
                        $count++;
                        break;

                    case 'deactivate':
                        $product->status = 'inactive';
                        $product->save();
                        $count++;
                        break;

                    case 'update_stock':
                        // This would need more logic
                        break;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully updated {$count} products",
                'count' => $count
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmployeeWholesalerProductController@bulkUpdate error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating products: ' . $e->getMessage()
            ], 500);
        }
    }
}
