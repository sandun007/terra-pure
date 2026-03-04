<?php

namespace App\Http\Controllers;

use App\Models\Wholesaler;
use App\Models\WholesalerProduct;
use App\Models\WholesalerProductPriceChange;
use App\Models\WholesalerProductExchange;
use App\Models\Order;
use App\Models\WholesalerOrder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\EmployeePriceChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Employee;
use App\Models\SellerProduct;
use App\Models\EmployeeSellerProduct;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    protected $storageService;

    public function __construct()
    {
        $this->middleware('auth:employee');
    }

    /**
     * Get the correct image column name from database
     */
    private function getImageColumnName()
    {
        $columns = Schema::getColumnListing('employees');

        // Check for possible column names in order of preference
        $possibleColumns = ['profile_image', 'image', 'img', 'photo', 'avatar', 'picture'];

        foreach ($possibleColumns as $column) {
            if (in_array($column, $columns)) {
                return $column;
            }
        }

        // Default to 'img' if none found (will be created later)
        return 'img';
    }

    /**
     * Get profile image URL
     */
    private function getProfileImageUrl($employee)
    {
        $imageColumn = $this->getImageColumnName();

        if (isset($employee->$imageColumn) && $employee->$imageColumn) {
            // Check if it's a full URL
            if (filter_var($employee->$imageColumn, FILTER_VALIDATE_URL)) {
                return $employee->$imageColumn;
            }

            // Check if file exists in storage
            if (Storage::disk('public')->exists($employee->$imageColumn)) {
                return asset('storage/' . $employee->$imageColumn);
            }

            // Check if it's a direct public path
            if (file_exists(public_path($employee->$imageColumn))) {
                return asset($employee->$imageColumn);
            }
        }

        // Return default avatar
        return asset('asset/img/default-avatar.png');
    }

    /**
     * Delete old profile image
     */
    private function deleteOldImage($employee)
    {
        $imageColumn = $this->getImageColumnName();

        if (isset($employee->$imageColumn) && $employee->$imageColumn) {
            // Try to delete from storage
            if (Storage::disk('public')->exists($employee->$imageColumn)) {
                Storage::disk('public')->delete($employee->$imageColumn);
                Log::info('Deleted old image from storage', ['path' => $employee->$imageColumn]);
            }

            // Try to delete from public directory
            $publicPath = public_path($employee->$imageColumn);
            if (file_exists($publicPath) && is_file($publicPath)) {
                @unlink($publicPath);
                Log::info('Deleted old image from public', ['path' => $publicPath]);
            }
        }
    }

    /**
     * Display employee dashboard
     */
    public function dashboard()
    {
        try {
            $employee = Auth::guard('employee')->user();

            // Calculate statistics
            $stats = [
                'total_products' => WholesalerProduct::count(),
                'total_orders' => Order::count() + WholesalerOrder::count(),
                'confirmed_orders' => Order::where('status', 'confirmed')->count() + WholesalerOrder::where('status', 'confirmed')->count(),
                'total_revenue' => Order::where('status', 'completed')->sum('total_amount') + WholesalerOrder::where('status', 'completed')->sum('total_amount'),
                'pending_orders' => Order::where('status', 'pending')->count() + WholesalerOrder::where('status', 'pending')->count(),
                'total_wholesalers' => Wholesaler::count(),
                'today_orders' => Order::whereDate('created_at', today())->count() + WholesalerOrder::whereDate('created_at', today())->count(),
                'my_price_changes' => WholesalerProductPriceChange::where('employee_id', $employee->id)->count(),
                'today_price_changes' => WholesalerProductPriceChange::where('employee_id', $employee->id)->whereDate('created_at', today())->count(),
                'total_price_changes' => WholesalerProductPriceChange::count(),
                'total_exchanges' => WholesalerProductExchange::count(),
                'pending_price_changes' => WholesalerProductPriceChange::where('status', 'pending')->count(),
                'pending_exchanges' => WholesalerProductExchange::where('status', 'pending')->count(),
            ];

            // Recent orders
            $recentOrders = Order::with('customer')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Recent wholesaler orders
            $recentWholesalerOrders = WholesalerOrder::with('wholesaler')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Recent wholesalers
            $recentWholesalers = Wholesaler::withCount('products')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Recent price changes
            $recentPriceChanges = WholesalerProductPriceChange::where('employee_id', $employee->id)
                ->with(['product', 'employee', 'wholesaler'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Recent exchanges
            $recentExchanges = WholesalerProductExchange::with([
                'sourceProduct',
                'sourceWholesaler',
                'destinationProduct',
                'destinationWholesaler'
            ])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Recent activities (combined timeline)
            $recentActivities = collect()
                ->merge(
                    WholesalerProductPriceChange::where('employee_id', $employee->id)
                        ->latest()
                        ->limit(3)
                        ->get()
                        ->map(function($change) {
                            return (object)[
                                'type' => 'price',
                                'created_at' => $change->created_at,
                                'title' => 'Price Change',
                                'description' => ($change->product->name ?? 'Unknown') . ': Rs. ' .
                                    number_format($change->old_final_price, 2) . ' → Rs. ' .
                                    number_format($change->new_final_price, 2)
                            ];
                        })
                )
                ->merge(
                    WholesalerProductExchange::where('employee_id', $employee->id)
                        ->latest()
                        ->limit(3)
                        ->get()
                        ->map(function($exchange) {
                            return (object)[
                                'type' => 'exchange',
                                'created_at' => $exchange->created_at,
                                'title' => 'Product Exchange',
                                'description' => ($exchange->sourceProduct->name ?? 'Unknown') . ' → ' .
                                    ($exchange->destinationProduct->name ?? 'Unknown')
                            ];
                        })
                )
                ->sortByDesc('created_at')
                ->take(5)
                ->values();

            return view('employee.dashboard', compact(
                'employee',
                'stats',
                'recentOrders',
                'recentWholesalerOrders',
                'recentWholesalers',
                'recentPriceChanges',
                'recentExchanges',
                'recentActivities'
            ));

        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());

            // Return with empty data on error
            $employee = Auth::guard('employee')->user();
            $stats = [
                'total_products' => 0,
                'total_orders' => 0,
                'total_revenue' => 0,
                'total_wholesalers' => 0,
                'my_price_changes' => 0,
                'total_price_changes' => 0,
                'total_exchanges' => 0,
                'pending_price_changes' => 0,
                'pending_exchanges' => 0,
            ];
            $recentOrders = collect();
            $recentWholesalerOrders = collect();
            $recentWholesalers = collect();
            $recentPriceChanges = collect();
            $recentExchanges = collect();
            $recentActivities = collect();

            return view('employee.dashboard', compact(
                'employee',
                'stats',
                'recentOrders',
                'recentWholesalerOrders',
                'recentWholesalers',
                'recentPriceChanges',
                'recentExchanges',
                'recentActivities'
            ))->with('error', 'Some data could not be loaded');
        }
    }

    /**
     * Get chart data for dashboard
     */
    public function getChartData(Request $request)
    {
        try {
            $period = $request->get('period', 'daily');

            switch ($period) {
                case 'weekly':
                    $labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
                    $orders = [100, 120, 150, 180];
                    $revenue = [100000, 120000, 150000, 180000];
                    break;
                case 'monthly':
                    $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    $orders = [180, 240, 320, 450, 500, 600, 550, 650, 700, 800, 850, 900];
                    $revenue = [180000, 240000, 320000, 450000, 500000, 600000, 550000, 650000, 700000, 800000, 850000, 900000];
                    break;
                default: // daily
                    $labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                    $orders = [12, 19, 15, 25, 22, 30, 28];
                    $revenue = [12000, 19000, 15000, 25000, 22000, 30000, 28000];
            }

            return response()->json([
                'success' => true,
                'labels' => $labels,
                'orders' => $orders,
                'revenue' => $revenue
            ]);

        } catch (\Exception $e) {
            Log::error('Chart data error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ========== WHOLESALER PRODUCT MANAGEMENT ==========

    /**
     * Display wholesaler products for employee with price management
     */
    public function wholesalerProducts(Request $request)
    {
        try {
            // Get all wholesalers for the filter dropdown
            $wholesalers = Wholesaler::orderBy('business_name')->get(['id', 'business_name as name']);

            $query = WholesalerProduct::with(['wholesaler', 'category', 'brand', 'lastPriceUpdatedBy'])
                ->latest();

            // Apply search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%")
                        ->orWhereHas('wholesaler', function($q2) use ($search) {
                            $q2->where('business_name', 'like', "%{$search}%");
                        });
                });
            }

            // Apply status filter
            if ($request->filled('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            // Apply wholesaler filter
            if ($request->filled('wholesaler_id') && $request->wholesaler_id != 'all') {
                $query->where('wholesaler_id', $request->wholesaler_id);
            }

            // Apply category filter
            if ($request->filled('category_id') && $request->category_id != 'all') {
                $query->where('category_id', $request->category_id);
            }

            // Apply stock filter
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

            // Apply exchange filter
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

            // Apply price changed filter
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

            $allowedSortFields = ['name', 'code', 'selling_price', 'qty', 'created_at', 'price_changes_count', 'exchange_count'];

            if (in_array($sortField, $allowedSortFields)) {
                $query->orderBy($sortField, $sortDirection);
            } else {
                $query->latest();
            }

            $products = $query->paginate(20)->withQueryString();

            // Get categories for filter
            $categories = Category::orderBy('name')->get(['id', 'name']);

            // Stats for the view
            $stats = [
                'total_products' => WholesalerProduct::count(),
                'total_wholesalers' => Wholesaler::count(),
                'total_exchanges' => WholesalerProductExchange::count(),
                'total_price_changes' => WholesalerProductPriceChange::count(),
                'today_price_changes' => WholesalerProductPriceChange::whereDate('created_at', today())->count(),
            ];

            return view('employee.wholesaler.products.index', compact(
                'products',
                'wholesalers',
                'categories',
                'stats'
            ));

        } catch (\Exception $e) {
            Log::error('Wholesaler products error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading products: ' . $e->getMessage());
        }
    }

    /**
     * View wholesaler product details with price and exchange history
     */
    public function viewWholesalerProduct($id)
    {
        try {
            $product = WholesalerProduct::with([
                'wholesaler',
                'category',
                'brand',
                'lastPriceUpdatedBy',
                'lastExchangedBy',
                'originalWholesaler'
            ])->findOrFail($id);

            // Get price changes
            $priceChanges = WholesalerProductPriceChange::where('product_id', $id)
                ->with(['employee', 'approvedBy'])
                ->orderBy('created_at', 'desc')
                ->paginate(20, ['*'], 'price_page');

            // Get exchanges (as source or destination)
            $sourceExchanges = WholesalerProductExchange::where('source_product_id', $id)
                ->with(['sourceWholesaler', 'destinationWholesaler', 'employee'])
                ->orderBy('created_at', 'desc')
                ->get();

            $destinationExchanges = WholesalerProductExchange::where('destination_product_id', $id)
                ->with(['sourceWholesaler', 'destinationWholesaler', 'employee'])
                ->orderBy('created_at', 'desc')
                ->get();

            $exchanges = $sourceExchanges->concat($destinationExchanges)
                ->sortByDesc('created_at')
                ->paginate(20, ['*'], 'exchange_page');

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
                'stats'
            ));

        } catch (\Exception $e) {
            Log::error('View wholesaler product error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Product not found: ' . $e->getMessage());
        }
    }

    /**
     * Show price history for a product
     */
    public function wholesalerProductPriceHistory($id, Request $request)
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
            $employees = Employee::orderBy('name')->get(['id', 'name']);

            return view('employee.wholesaler.products.price-history', compact(
                'product',
                'priceChanges',
                'employees'
            ));

        } catch (\Exception $e) {
            Log::error('Product price history error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading price history: ' . $e->getMessage());
        }
    }

    /**
     * Export products to CSV
     */
    public function exportWholesalerProducts(Request $request)
    {
        try {
            $query = WholesalerProduct::with(['wholesaler', 'category', 'brand']);

            // Apply filters (same as index)
            if ($request->filled('wholesaler_id') && $request->wholesaler_id != 'all') {
                $query->where('wholesaler_id', $request->wholesaler_id);
            }

            if ($request->filled('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            if ($request->filled('category_id') && $request->category_id != 'all') {
                $query->where('category_id', $request->category_id);
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
            Log::error('Export products error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting products: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update products (status, etc.)
     */
    public function bulkUpdateWholesalerProducts(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_ids' => 'required|array',
                'product_ids.*' => 'exists:wholesaler_products,id',
                'action' => 'required|in:activate,deactivate',
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
            Log::error('Bulk update products error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating products: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========== WHOLESALER PRICE CHANGE MANAGEMENT ==========

    /**
     * Display price change management dashboard
     */
    public function wholesalerPriceChanges(Request $request)
    {
        try {
            $employee = Auth::guard('employee')->user();

            $query = WholesalerProductPriceChange::with([
                'product',
                'wholesaler',
                'employee',
                'approvedBy'
            ]);

            // Filters
            if ($request->filled('wholesaler_id') && $request->wholesaler_id != 'all') {
                $query->where('wholesaler_id', $request->wholesaler_id);
            }

            if ($request->filled('employee_id') && $request->employee_id != 'all') {
                $query->where('employee_id', $request->employee_id);
            }

            if ($request->filled('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            if ($request->filled('change_type') && $request->change_type != 'all') {
                $query->where('change_type', $request->change_type);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('product', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            }

            $priceChanges = $query->orderBy('created_at', 'desc')->paginate(20);

            // Get filter data
            $wholesalers = Wholesaler::orderBy('business_name')->get(['id', 'business_name as name']);
            $employees = Employee::orderBy('name')->get(['id', 'name']);

            // Stats
            $stats = [
                'total_changes' => WholesalerProductPriceChange::count(),
                'today_changes' => WholesalerProductPriceChange::whereDate('created_at', today())->count(),
                'pending_changes' => WholesalerProductPriceChange::where('status', 'pending')->count(),
                'approved_changes' => WholesalerProductPriceChange::where('status', 'approved')->count(),
                'total_increase' => WholesalerProductPriceChange::where('price_difference', '>', 0)->sum('price_difference'),
                'total_decrease' => WholesalerProductPriceChange::where('price_difference', '<', 0)->sum('price_difference'),
                'my_changes' => WholesalerProductPriceChange::where('employee_id', $employee->id)->count(),
            ];

            return view('employee.wholesaler.price-change.index', compact(
                'priceChanges',
                'wholesalers',
                'employees',
                'stats',
                'employee'
            ));

        } catch (\Exception $e) {
            Log::error('Price changes index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading price changes: ' . $e->getMessage());
        }
    }

    /**
     * Show form to create price change
     */
    public function createWholesalerPriceChange(Request $request)
    {
        try {
            $productId = $request->get('product_id');
            $product = null;

            if ($productId) {
                $product = WholesalerProduct::with('wholesaler')->find($productId);
            }

            $wholesalers = Wholesaler::orderBy('business_name')->get();

            return view('employee.wholesaler.price-change.create', compact('product', 'wholesalers'));

        } catch (\Exception $e) {
            Log::error('Create price change form error: ' . $e->getMessage());
            return redirect()->route('employee.wholesaler.price-change.index')
                ->with('error', 'Error loading form: ' . $e->getMessage());
        }
    }

    /**
     * Store a new price change (single product)
     */
    public function storeWholesalerPriceChange(Request $request)
    {
        DB::beginTransaction();

        try {
            $employee = Auth::guard('employee')->user();

            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:wholesaler_products,id',
                'new_selling_price' => 'required|numeric|min:0',
                'new_display_price' => 'nullable|numeric|min:0',
                'new_discount' => 'nullable|numeric|min:0|max:100',
                'change_reason' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:1000',
                'require_approval' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $product = WholesalerProduct::findOrFail($request->product_id);

            // Store old values
            $oldSellingPrice = $product->selling_price;
            $oldDisplayPrice = $product->display_price ?? $product->selling_price;
            $oldDiscount = $product->discount ?? 0;
            $oldFinalPrice = $product->final_price;

            // Calculate new final price
            $newSellingPrice = $request->new_selling_price;
            $newDisplayPrice = $request->new_display_price ?? $newSellingPrice;
            $newDiscount = $request->new_discount ?? $oldDiscount;

            $newFinalPrice = $newDiscount > 0
                ? $newDisplayPrice * (1 - $newDiscount / 100)
                : $newDisplayPrice;

            $priceDifference = $newFinalPrice - $oldFinalPrice;
            $percentageChange = $oldFinalPrice > 0
                ? ($priceDifference / $oldFinalPrice) * 100
                : 0;

            // Determine if approval needed
            $status = $request->boolean('require_approval') ? 'pending' : 'approved';

            // Create price change record
            $priceChange = WholesalerProductPriceChange::create([
                'product_id' => $product->id,
                'wholesaler_id' => $product->wholesaler_id,
                'employee_id' => $employee->id,
                'old_selling_price' => $oldSellingPrice,
                'old_display_price' => $oldDisplayPrice,
                'old_discount' => $oldDiscount,
                'old_final_price' => $oldFinalPrice,
                'new_selling_price' => $newSellingPrice,
                'new_display_price' => $newDisplayPrice,
                'new_discount' => $newDiscount,
                'new_final_price' => $newFinalPrice,
                'price_difference' => $priceDifference,
                'percentage_change' => $percentageChange,
                'change_type' => 'manual',
                'change_reason' => $request->change_reason,
                'notes' => $request->notes,
                'status' => $status,
                'change_data' => [
                    'old_cost_price' => $product->cost_price,
                    'new_cost_price' => $product->cost_price,
                    'old_qty' => $product->qty,
                    'new_qty' => $product->qty,
                    'old_min_stock' => $product->min_stock_level,
                    'new_min_stock' => $product->min_stock_level,
                ]
            ]);

            // If approved immediately, update the product
            if ($status === 'approved') {
                $product->update([
                    'previous_selling_price' => $oldSellingPrice,
                    'selling_price' => $newSellingPrice,
                    'display_price' => $newDisplayPrice,
                    'discount' => $newDiscount,
                    'last_price_updated_by' => $employee->id,
                    'last_price_updated_at' => now(),
                ]);

                $product->increment('price_changes_count');

                $priceChange->update([
                    'approved_by' => $employee->id,
                    'approved_at' => now()
                ]);
            }

            DB::commit();

            // Redirect based on status
            if ($status === 'pending') {
                return redirect()->route('employee.wholesaler.price-change.pending')
                    ->with('success', 'Price change submitted for approval.');
            } else {
                return redirect()->route('employee.wholesaler.products.show', $product->id)
                    ->with('success', 'Price updated successfully!');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store price change error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error updating price: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show bulk price update form
     */
    public function createBulkWholesalerPriceChange(Request $request)
    {
        try {
            $wholesalers = Wholesaler::orderBy('business_name')->get();

            $selectedProducts = [];
            if ($request->filled('product_ids')) {
                $productIds = explode(',', $request->product_ids);
                $selectedProducts = WholesalerProduct::with('wholesaler')
                    ->whereIn('id', $productIds)
                    ->get();
            }

            return view('employee.wholesaler.price-change.bulk', compact('wholesalers', 'selectedProducts'));

        } catch (\Exception $e) {
            Log::error('Bulk price change form error: ' . $e->getMessage());
            return redirect()->route('employee.wholesaler.price-change.index')
                ->with('error', 'Error loading bulk update form: ' . $e->getMessage());
        }
    }

    /**
     * Process bulk price update
     */
    public function storeBulkWholesalerPriceChange(Request $request)
    {
        DB::beginTransaction();

        try {
            $employee = Auth::guard('employee')->user();

            $validator = Validator::make($request->all(), [
                'product_ids' => 'required|array',
                'product_ids.*' => 'exists:wholesaler_products,id',
                'update_type' => 'required|in:fixed,percentage,formula',
                'price_field' => 'required|in:selling_price,display_price,final_price',
                'value' => 'required|numeric',
                'operation' => 'required|in:set,increase,decrease',
                'apply_discount' => 'nullable|boolean',
                'discount_value' => 'nullable|numeric|min:0|max:100',
                'change_reason' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:1000',
                'require_approval' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $productIds = $request->product_ids;
            $updateType = $request->update_type;
            $priceField = $request->price_field;
            $value = $request->value;
            $operation = $request->operation;
            $applyDiscount = $request->boolean('apply_discount');
            $discountValue = $request->discount_value;
            $requireApproval = $request->boolean('require_approval');

            $updatedCount = 0;
            $priceChanges = [];

            foreach ($productIds as $productId) {
                $product = WholesalerProduct::find($productId);

                if (!$product) continue;

                // Store old values
                $oldSellingPrice = $product->selling_price;
                $oldDisplayPrice = $product->display_price ?? $product->selling_price;
                $oldDiscount = $product->discount ?? 0;
                $oldFinalPrice = $product->final_price;

                // Calculate new price based on update type
                $basePrice = $priceField === 'selling_price' ? $oldSellingPrice :
                    ($priceField === 'display_price' ? $oldDisplayPrice : $oldFinalPrice);

                $newBasePrice = $this->calculateNewPrice($basePrice, $updateType, $value, $operation);

                // Set new prices
                if ($priceField === 'selling_price') {
                    $newSellingPrice = $newBasePrice;
                    $newDisplayPrice = $oldDisplayPrice;
                } elseif ($priceField === 'display_price') {
                    $newSellingPrice = $oldSellingPrice;
                    $newDisplayPrice = $newBasePrice;
                } else {
                    // final_price - need to back-calculate
                    $newDisplayPrice = $applyDiscount && $discountValue > 0
                        ? $newBasePrice / (1 - $discountValue / 100)
                        : $newBasePrice;
                    $newSellingPrice = $oldSellingPrice;
                }

                // Apply discount if requested
                $newDiscount = $applyDiscount ? ($discountValue ?? $oldDiscount) : $oldDiscount;

                // Calculate new final price
                $newFinalPrice = $newDiscount > 0
                    ? $newDisplayPrice * (1 - $newDiscount / 100)
                    : $newDisplayPrice;

                $priceDifference = $newFinalPrice - $oldFinalPrice;
                $percentageChange = $oldFinalPrice > 0
                    ? ($priceDifference / $oldFinalPrice) * 100
                    : 0;

                // Create price change record
                $priceChange = WholesalerProductPriceChange::create([
                    'product_id' => $product->id,
                    'wholesaler_id' => $product->wholesaler_id,
                    'employee_id' => $employee->id,
                    'old_selling_price' => $oldSellingPrice,
                    'old_display_price' => $oldDisplayPrice,
                    'old_discount' => $oldDiscount,
                    'old_final_price' => $oldFinalPrice,
                    'new_selling_price' => $newSellingPrice ?? $oldSellingPrice,
                    'new_display_price' => $newDisplayPrice ?? $oldDisplayPrice,
                    'new_discount' => $newDiscount,
                    'new_final_price' => $newFinalPrice,
                    'price_difference' => $priceDifference,
                    'percentage_change' => $percentageChange,
                    'change_type' => 'bulk',
                    'change_reason' => $request->change_reason,
                    'notes' => $request->notes,
                    'status' => $requireApproval ? 'pending' : 'approved',
                    'change_data' => [
                        'update_type' => $updateType,
                        'price_field' => $priceField,
                        'value' => $value,
                        'operation' => $operation,
                        'apply_discount' => $applyDiscount,
                        'discount_value' => $discountValue,
                    ]
                ]);

                // If approved immediately, update product
                if (!$requireApproval) {
                    $updateData = [];

                    if (isset($newSellingPrice)) {
                        $updateData['selling_price'] = $newSellingPrice;
                    }

                    if (isset($newDisplayPrice)) {
                        $updateData['display_price'] = $newDisplayPrice;
                    }

                    $updateData['discount'] = $newDiscount;
                    $updateData['last_price_updated_by'] = $employee->id;
                    $updateData['last_price_updated_at'] = now();

                    $product->update($updateData);
                    $product->increment('price_changes_count');

                    $priceChange->update([
                        'approved_by' => $employee->id,
                        'approved_at' => now()
                    ]);
                }

                $priceChanges[] = $priceChange;
                $updatedCount++;
            }

            DB::commit();

            $message = $requireApproval
                ? "{$updatedCount} price changes submitted for approval."
                : "Successfully updated prices for {$updatedCount} products.";

            return redirect()->route('employee.wholesaler.price-change.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk price change store error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error in bulk update: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Calculate new price based on update type
     */
    private function calculateNewPrice($currentPrice, $updateType, $value, $operation)
    {
        switch ($updateType) {
            case 'fixed':
                if ($operation === 'set') {
                    return $value;
                } elseif ($operation === 'increase') {
                    return $currentPrice + $value;
                } elseif ($operation === 'decrease') {
                    return max(0, $currentPrice - $value);
                }
                break;

            case 'percentage':
                if ($operation === 'increase') {
                    return $currentPrice * (1 + $value / 100);
                } elseif ($operation === 'decrease') {
                    return $currentPrice * (1 - $value / 100);
                }
                break;

            case 'formula':
                // Simple formula evaluation (be careful with this)
                try {
                    $formula = str_replace(['{price}', '{value}'], [$currentPrice, $value], $value);
                    // Only allow basic math operations
                    if (preg_match('/^[0-9\+\-\*\/\(\)\.]+$/', $formula)) {
                        return eval("return {$formula};");
                    }
                } catch (\Exception $e) {
                    return $currentPrice;
                }
                break;
        }

        return $currentPrice;
    }

    /**
     * Show pending price changes for approval
     */
    public function pendingWholesalerPriceChanges(Request $request)
    {
        try {
            $query = WholesalerProductPriceChange::with(['product', 'wholesaler', 'employee'])
                ->where('status', 'pending');

            if ($request->filled('wholesaler_id') && $request->wholesaler_id != 'all') {
                $query->where('wholesaler_id', $request->wholesaler_id);
            }

            if ($request->filled('employee_id') && $request->employee_id != 'all') {
                $query->where('employee_id', $request->employee_id);
            }

            $pendingChanges = $query->orderBy('created_at', 'asc')->paginate(20);

            $wholesalers = Wholesaler::orderBy('business_name')->get();
            $employees = Employee::orderBy('name')->get();

            return view('employee.wholesaler.price-change.pending', compact(
                'pendingChanges',
                'wholesalers',
                'employees'
            ));

        } catch (\Exception $e) {
            Log::error('Pending price changes error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading pending changes: ' . $e->getMessage());
        }
    }

    /**
     * Approve a price change
     */
    public function approveWholesalerPriceChange($id)
    {
        DB::beginTransaction();

        try {
            $employee = Auth::guard('employee')->user();

            $priceChange = WholesalerProductPriceChange::findOrFail($id);

            if ($priceChange->status !== 'pending') {
                return redirect()->back()->with('error', 'This price change is not pending.');
            }

            $product = $priceChange->product;

            // Update product
            $updateData = [
                'selling_price' => $priceChange->new_selling_price,
                'display_price' => $priceChange->new_display_price,
                'discount' => $priceChange->new_discount,
                'last_price_updated_by' => $employee->id,
                'last_price_updated_at' => now(),
            ];

            $product->update($updateData);
            $product->increment('price_changes_count');

            // Approve the change
            $priceChange->status = 'approved';
            $priceChange->approved_by = $employee->id;
            $priceChange->approved_at = now();
            $priceChange->save();

            DB::commit();

            return redirect()->route('employee.wholesaler.price-change.pending')
                ->with('success', 'Price change approved successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Approve price change error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error approving price change: ' . $e->getMessage());
        }
    }

    /**
     * Reject a price change
     */
    public function rejectWholesalerPriceChange(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'rejection_reason' => 'required|string|max:500',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $priceChange = WholesalerProductPriceChange::findOrFail($id);

            if ($priceChange->status !== 'pending') {
                return redirect()->back()->with('error', 'This price change is not pending.');
            }

            $priceChange->status = 'rejected';
            $priceChange->notes = $priceChange->notes
                ? $priceChange->notes . "\n\nRejection reason: " . $request->rejection_reason
                : 'Rejection reason: ' . $request->rejection_reason;
            $priceChange->save();

            DB::commit();

            return redirect()->route('employee.wholesaler.price-change.pending')
                ->with('success', 'Price change rejected.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reject price change error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error rejecting price change: ' . $e->getMessage());
        }
    }

    /**
     * Show price change history
     */
    public function wholesalerPriceChangeHistory(Request $request)
    {
        try {
            $query = WholesalerProductPriceChange::with(['product', 'wholesaler', 'employee', 'approvedBy']);

            // Apply filters
            if ($request->filled('wholesaler_id') && $request->wholesaler_id != 'all') {
                $query->where('wholesaler_id', $request->wholesaler_id);
            }

            if ($request->filled('employee_id') && $request->employee_id != 'all') {
                $query->where('employee_id', $request->employee_id);
            }

            if ($request->filled('product_id') && $request->product_id != 'all') {
                $query->where('product_id', $request->product_id);
            }

            if ($request->filled('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $priceChanges = $query->orderBy('created_at', 'desc')->paginate(50);

            $wholesalers = Wholesaler::orderBy('business_name')->get();
            $employees = Employee::orderBy('name')->get();
            $products = WholesalerProduct::orderBy('name')->limit(100)->get();

            return view('employee.wholesaler.price-change.history', compact(
                'priceChanges',
                'wholesalers',
                'employees',
                'products'
            ));

        } catch (\Exception $e) {
            Log::error('Price change history error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading price change history: ' . $e->getMessage());
        }
    }

    /**
     * Export price changes to CSV
     */
    public function exportWholesalerPriceChanges(Request $request)
    {
        try {
            $query = WholesalerProductPriceChange::with(['product', 'wholesaler', 'employee']);

            // Apply filters (same as history)
            if ($request->filled('wholesaler_id') && $request->wholesaler_id != 'all') {
                $query->where('wholesaler_id', $request->wholesaler_id);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $priceChanges = $query->orderBy('created_at', 'desc')->get();

            $filename = 'price-changes-' . date('Y-m-d') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}",
            ];

            $callback = function() use ($priceChanges) {
                $file = fopen('php://output', 'w');

                // Headers
                fputcsv($file, [
                    'Date',
                    'Product Code',
                    'Product Name',
                    'Wholesaler',
                    'Employee',
                    'Old Price',
                    'New Price',
                    'Difference',
                    'Percentage',
                    'Change Type',
                    'Status',
                    'Reason'
                ]);

                // Data
                foreach ($priceChanges as $change) {
                    fputcsv($file, [
                        $change->created_at->format('Y-m-d H:i'),
                        $change->product->code ?? 'N/A',
                        $change->product->name ?? 'N/A',
                        $change->wholesaler->business_name ?? 'N/A',
                        $change->employee->name ?? 'N/A',
                        'Rs. ' . number_format($change->old_final_price, 2),
                        'Rs. ' . number_format($change->new_final_price, 2),
                        ($change->price_difference > 0 ? '+' : '') . number_format($change->price_difference, 2),
                        number_format($change->percentage_change, 2) . '%',
                        ucfirst($change->change_type),
                        ucfirst($change->status),
                        $change->change_reason ?? ''
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Export price changes error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting price changes: ' . $e->getMessage());
        }
    }

    // ========== WHOLESALER EXCHANGE MANAGEMENT ==========

    /**
     * Display all exchanges
     */
    public function wholesalerExchanges(Request $request)
    {
        try {
            $employee = Auth::guard('employee')->user();

            $query = WholesalerProductExchange::with([
                'sourceProduct',
                'sourceWholesaler',
                'destinationProduct',
                'destinationWholesaler',
                'employee',
                'approvedBy'
            ]);

            // Filters
            if ($request->filled('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            if ($request->filled('source_wholesaler_id') && $request->source_wholesaler_id != 'all') {
                $query->where('source_wholesaler_id', $request->source_wholesaler_id);
            }

            if ($request->filled('destination_wholesaler_id') && $request->destination_wholesaler_id != 'all') {
                $query->where('destination_wholesaler_id', $request->destination_wholesaler_id);
            }

            if ($request->filled('employee_id') && $request->employee_id != 'all') {
                $query->where('employee_id', $request->employee_id);
            }

            if ($request->filled('exchange_type') && $request->exchange_type != 'all') {
                $query->where('exchange_type', $request->exchange_type);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->whereHas('sourceProduct', function($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    })
                        ->orWhereHas('destinationProduct', function($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%")
                                ->orWhere('code', 'like', "%{$search}%");
                        })
                        ->orWhereHas('sourceWholesaler', function($q2) use ($search) {
                            $q2->where('business_name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('destinationWholesaler', function($q2) use ($search) {
                            $q2->where('business_name', 'like', "%{$search}%");
                        });
                });
            }

            $exchanges = $query->orderBy('created_at', 'desc')->paginate(20);

            // Get filter data
            $wholesalers = Wholesaler::orderBy('business_name')->get(['id', 'business_name as name']);
            $employees = Employee::orderBy('name')->get(['id', 'name']);

            // Stats
            $stats = [
                'total_exchanges' => WholesalerProductExchange::count(),
                'pending_exchanges' => WholesalerProductExchange::where('status', 'pending')->count(),
                'completed_exchanges' => WholesalerProductExchange::where('status', 'completed')->count(),
                'total_value_transferred' => WholesalerProductExchange::sum('destination_price_at_time'),
                'total_adjustments' => WholesalerProductExchange::sum('adjustment_amount'),
                'my_exchanges' => WholesalerProductExchange::where('employee_id', $employee->id)->count(),
            ];

            return view('employee.wholesaler.exchange.index', compact(
                'exchanges',
                'wholesalers',
                'employees',
                'stats',
                'employee'
            ));

        } catch (\Exception $e) {
            Log::error('Exchanges index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading exchanges: ' . $e->getMessage());
        }
    }


    /**
     * Show form to create exchange
     */
    public function createWholesalerExchange(Request $request)
    {
        try {
            $wholesalers = Wholesaler::orderBy('business_name')->get(['id', 'business_name as name']);

            $sourceProduct = null;
            if ($request->filled('source_product_id')) {
                $sourceProduct = WholesalerProduct::with('wholesaler')
                    ->find($request->source_product_id);
            }

            return view('employee.wholesaler.exchange.create', compact('wholesalers', 'sourceProduct'));

        } catch (\Exception $e) {
            Log::error('Create exchange form error: ' . $e->getMessage());
            return redirect()->route('employee.wholesaler.exchange.index')
                ->with('error', 'Error loading exchange form: ' . $e->getMessage());
        }
    }

    /**
     * Store new exchange
     */
    public function storeWholesalerExchange(Request $request)
    {
        DB::beginTransaction();

        try {
            $employee = Auth::guard('employee')->user();

            $validator = Validator::make($request->all(), [
                'source_product_id' => 'required|exists:wholesaler_products,id',
                'destination_product_id' => 'required|exists:wholesaler_products,id|different:source_product_id',
                'source_wholesaler_id' => 'required|exists:wholesalers,id',
                'destination_wholesaler_id' => 'required|exists:wholesalers,id|different:source_wholesaler_id',
                'exchange_type' => 'required|in:full,partial,credit',
                'exchange_quantity' => 'required|numeric|min:0.01',
                'adjustment_amount' => 'nullable|numeric',
                'payment_method' => 'nullable|string|in:cash,credit,bank_transfer',
                'payment_status' => 'nullable|string|in:pending,completed,failed',
                'notes' => 'nullable|string|max:1000',
                'require_approval' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Get products
            $sourceProduct = WholesalerProduct::findOrFail($request->source_product_id);
            $destinationProduct = WholesalerProduct::findOrFail($request->destination_product_id);

            // Verify wholesalers match
            if ($sourceProduct->wholesaler_id != $request->source_wholesaler_id) {
                return redirect()->back()
                    ->with('error', 'Source product does not belong to selected source wholesaler.')
                    ->withInput();
            }

            if ($destinationProduct->wholesaler_id != $request->destination_wholesaler_id) {
                return redirect()->back()
                    ->with('error', 'Destination product does not belong to selected destination wholesaler.')
                    ->withInput();
            }

            // Check stock for source product
            if ($sourceProduct->qty < $request->exchange_quantity) {
                return redirect()->back()
                    ->with('error', 'Source product does not have enough quantity. Available: ' . $sourceProduct->qty)
                    ->withInput();
            }

            // Calculate prices
            $sourcePrice = $sourceProduct->final_price * $request->exchange_quantity;
            $destinationPrice = $destinationProduct->final_price * $request->exchange_quantity;
            $priceDifference = $destinationPrice - $sourcePrice;

            $status = $request->boolean('require_approval') ? 'pending' : 'approved';

            // Create exchange record
            $exchange = WholesalerProductExchange::create([
                'source_product_id' => $sourceProduct->id,
                'source_wholesaler_id' => $sourceProduct->wholesaler_id,
                'destination_product_id' => $destinationProduct->id,
                'destination_wholesaler_id' => $destinationProduct->wholesaler_id,
                'exchange_type' => $request->exchange_type,
                'exchange_quantity' => $request->exchange_quantity,
                'source_price_at_time' => $sourcePrice,
                'destination_price_at_time' => $destinationPrice,
                'price_difference' => $priceDifference,
                'adjustment_amount' => $request->adjustment_amount ?? 0,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status ?? 'pending',
                'employee_id' => $employee->id,
                'status' => $status,
                'notes' => $request->notes,
                'exchange_data' => [
                    'source_product_name' => $sourceProduct->name,
                    'source_product_code' => $sourceProduct->code,
                    'destination_product_name' => $destinationProduct->name,
                    'destination_product_code' => $destinationProduct->code,
                    'source_price_per_unit' => $sourceProduct->final_price,
                    'destination_price_per_unit' => $destinationProduct->final_price,
                ]
            ]);

            // If approved immediately, process the exchange
            if ($status === 'approved') {
                $this->processExchange($exchange, $employee->id);
            }

            DB::commit();

            $message = $status === 'pending'
                ? 'Exchange submitted for approval.'
                : 'Exchange completed successfully.';

            return redirect()->route('employee.wholesaler.exchange.show', $exchange->id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store exchange error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error creating exchange: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Process the actual exchange (transfer products)
     */
    private function processExchange(WholesalerProductExchange $exchange, $employeeId)
    {
        // Update quantities
        $sourceProduct = $exchange->sourceProduct;
        $destinationProduct = $exchange->destinationProduct;

        // Reduce source product quantity
        $sourceProduct->qty -= $exchange->exchange_quantity;
        $sourceProduct->save();

        // Increase destination product quantity
        $destinationProduct->qty += $exchange->exchange_quantity;

        // Update destination product's wholesaler if this is a transfer to new owner
        if ($exchange->destination_wholesaler_id != $destinationProduct->wholesaler_id) {
            $destinationProduct->wholesaler_id = $exchange->destination_wholesaler_id;
        }

        $destinationProduct->save();

        // Update exchange stats on both products
        $sourceProduct->exchange_count = ($sourceProduct->exchange_count ?? 0) + 1;
        $sourceProduct->last_exchanged_at = now();
        $sourceProduct->last_exchanged_by = $employeeId;
        $sourceProduct->save();

        $destinationProduct->exchange_count = ($destinationProduct->exchange_count ?? 0) + 1;
        $destinationProduct->last_exchanged_at = now();
        $destinationProduct->last_exchanged_by = $employeeId;
        $destinationProduct->save();

        // Update exchange record
        $exchange->status = 'completed';
        $exchange->approved_by = $employeeId;
        $exchange->approved_at = now();
        $exchange->payment_status = 'completed';
        $exchange->payment_date = now();
        $exchange->save();

        // Log the exchange
        Log::info('Product exchange completed', [
            'exchange_id' => $exchange->id,
            'source_product' => $sourceProduct->id,
            'destination_product' => $destinationProduct->id,
            'quantity' => $exchange->exchange_quantity,
            'employee_id' => $employeeId
        ]);
    }

    /**
     * Show exchange details
     */
    public function showWholesalerExchange($id)
    {
        try {
            $exchange = WholesalerProductExchange::with([
                'sourceProduct',
                'sourceWholesaler',
                'destinationProduct',
                'destinationWholesaler',
                'employee',
                'approvedBy'
            ])->findOrFail($id);

            return view('employee.wholesaler.exchange.show', compact('exchange'));

        } catch (\Exception $e) {
            Log::error('Show exchange error: ' . $e->getMessage());
            return redirect()->route('employee.wholesaler.exchange.index')
                ->with('error', 'Exchange not found: ' . $e->getMessage());
        }
    }

    /**
     * Approve pending exchange
     */
    public function approveWholesalerExchange($id)
    {
        DB::beginTransaction();

        try {
            $employee = Auth::guard('employee')->user();

            $exchange = WholesalerProductExchange::findOrFail($id);

            if ($exchange->status !== 'pending') {
                return redirect()->back()->with('error', 'This exchange is not pending.');
            }

            $this->processExchange($exchange, $employee->id);

            DB::commit();

            return redirect()->route('employee.wholesaler.exchange.show', $exchange->id)
                ->with('success', 'Exchange approved and completed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Approve exchange error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error approving exchange: ' . $e->getMessage());
        }
    }

    /**
     * Reject pending exchange
     */
    public function rejectWholesalerExchange(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'rejection_reason' => 'required|string|max:500',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $exchange = WholesalerProductExchange::findOrFail($id);

            if ($exchange->status !== 'pending') {
                return redirect()->back()->with('error', 'This exchange is not pending.');
            }

            $exchange->status = 'rejected';
            $exchange->reason = $request->rejection_reason;
            $exchange->save();

            DB::commit();

            return redirect()->route('employee.wholesaler.exchange.index')
                ->with('success', 'Exchange rejected.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reject exchange error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error rejecting exchange: ' . $e->getMessage());
        }
    }

    /**
     * Cancel exchange
     */
    public function cancelWholesalerExchange(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $exchange = WholesalerProductExchange::findOrFail($id);

            if (!in_array($exchange->status, ['pending', 'approved'])) {
                return redirect()->back()->with('error', 'This exchange cannot be cancelled.');
            }

            $exchange->status = 'cancelled';
            $exchange->reason = $request->reason ?? 'Cancelled by employee';
            $exchange->save();

            DB::commit();

            return redirect()->route('employee.wholesaler.exchange.index')
                ->with('success', 'Exchange cancelled.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cancel exchange error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error cancelling exchange: ' . $e->getMessage());
        }
    }

    /**
     * Get products for a wholesaler (AJAX)
     */
    public function getWholesalerProducts(Request $request)
    {
        try {
            $wholesalerId = $request->get('wholesaler_id');

            if (!$wholesalerId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wholesaler ID required'
                ], 400);
            }

            $products = WholesalerProduct::where('wholesaler_id', $wholesalerId)
                ->where('status', 'active')
                ->where('qty', '>', 0)
                ->orderBy('name')
                ->get(['id', 'code', 'name', 'qty', 'selling_price', 'display_price']);

            return response()->json([
                'success' => true,
                'products' => $products
            ]);

        } catch (\Exception $e) {
            Log::error('Get wholesaler products error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error loading products: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product details (AJAX)
     */
    public function getWholesalerProductDetails(Request $request)
    {
        try {
            $productId = $request->get('product_id');

            if (!$productId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product ID required'
                ], 400);
            }

            $product = WholesalerProduct::with('wholesaler')
                ->find($productId);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'code' => $product->code,
                    'name' => $product->name,
                    'wholesaler_id' => $product->wholesaler_id,
                    'wholesaler_name' => $product->wholesaler->business_name ?? 'N/A',
                    'qty' => $product->qty,
                    'price' => $product->final_price,
                    'display_price' => $product->display_price,
                    'selling_price' => $product->selling_price,
                    'discount' => $product->discount ?? 0,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Get product details error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error loading product details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export exchanges to CSV
     */
    public function exportWholesalerExchanges(Request $request)
    {
        try {
            $query = WholesalerProductExchange::with([
                'sourceProduct',
                'sourceWholesaler',
                'destinationProduct',
                'destinationWholesaler',
                'employee'
            ]);

            // Apply filters (same as index)
            if ($request->filled('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $exchanges = $query->orderBy('created_at', 'desc')->get();

            $filename = 'product-exchanges-' . date('Y-m-d') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}",
            ];

            $callback = function() use ($exchanges) {
                $file = fopen('php://output', 'w');

                // Headers
                fputcsv($file, [
                    'Date',
                    'Exchange ID',
                    'Source Product',
                    'Source Wholesaler',
                    'Destination Product',
                    'Destination Wholesaler',
                    'Quantity',
                    'Source Value',
                    'Destination Value',
                    'Difference',
                    'Adjustment',
                    'Type',
                    'Status',
                    'Employee',
                    'Notes'
                ]);

                // Data
                foreach ($exchanges as $exchange) {
                    fputcsv($file, [
                        $exchange->created_at->format('Y-m-d H:i'),
                        $exchange->id,
                        ($exchange->sourceProduct->name ?? 'Unknown') . ' (' . ($exchange->sourceProduct->code ?? '') . ')',
                        $exchange->sourceWholesaler->business_name ?? 'N/A',
                        ($exchange->destinationProduct->name ?? 'Unknown') . ' (' . ($exchange->destinationProduct->code ?? '') . ')',
                        $exchange->destinationWholesaler->business_name ?? 'N/A',
                        $exchange->exchange_quantity,
                        'Rs. ' . number_format($exchange->source_price_at_time, 2),
                        'Rs. ' . number_format($exchange->destination_price_at_time, 2),
                        'Rs. ' . number_format($exchange->price_difference, 2),
                        'Rs. ' . number_format($exchange->adjustment_amount, 2),
                        ucfirst($exchange->exchange_type),
                        ucfirst($exchange->status),
                        $exchange->employee->name ?? 'N/A',
                        $exchange->notes ?? ''
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Export exchanges error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting exchanges: ' . $e->getMessage());
        }
    }

    /**
     * Get exchange statistics
     */
    public function wholesalerExchangeStats(Request $request)
    {
        try {
            $employee = Auth::guard('employee')->user();

            $stats = [
                'total_exchanges' => WholesalerProductExchange::count(),
                'total_products_exchanged' => WholesalerProductExchange::distinct('source_product_id')->count('source_product_id'),
                'total_wholesalers_involved' => WholesalerProductExchange::distinct('source_wholesaler_id')
                        ->count('source_wholesaler_id') + WholesalerProductExchange::distinct('destination_wholesaler_id')
                        ->count('destination_wholesaler_id'),
                'total_value_transferred' => WholesalerProductExchange::sum('destination_price_at_time'),
                'my_exchanges' => WholesalerProductExchange::where('employee_id', $employee->id)->count(),
            ];

            // Monthly trends
            $monthlyTrends = WholesalerProductExchange::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(destination_price_at_time) as total_value'),
                DB::raw('SUM(price_difference) as net_difference')
            )
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'monthly_trends' => $monthlyTrends
            ]);

        } catch (\Exception $e) {
            Log::error('Exchange stats error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error loading statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show exchange history with filters
     */
    public function wholesalerExchangeHistory(Request $request)
    {
        try {
            $query = WholesalerProductExchange::with([
                'sourceProduct',
                'sourceWholesaler',
                'destinationProduct',
                'destinationWholesaler',
                'employee',
                'approvedBy'
            ]);

            // Apply filters
            if ($request->filled('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            if ($request->filled('source_wholesaler_id') && $request->source_wholesaler_id != 'all') {
                $query->where('source_wholesaler_id', $request->source_wholesaler_id);
            }

            if ($request->filled('destination_wholesaler_id') && $request->destination_wholesaler_id != 'all') {
                $query->where('destination_wholesaler_id', $request->destination_wholesaler_id);
            }

            if ($request->filled('employee_id') && $request->employee_id != 'all') {
                $query->where('employee_id', $request->employee_id);
            }

            if ($request->filled('exchange_type') && $request->exchange_type != 'all') {
                $query->where('exchange_type', $request->exchange_type);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->whereHas('sourceProduct', function($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    })
                        ->orWhereHas('destinationProduct', function($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%")
                                ->orWhere('code', 'like', "%{$search}%");
                        });
                });
            }

            $exchanges = $query->orderBy('created_at', 'desc')->paginate(20);

            // Get filter data
            $wholesalers = Wholesaler::orderBy('business_name')->get(['id', 'business_name as name']);
            $employees = Employee::orderBy('name')->get(['id', 'name']);

            // Calculate statistics
            $totalExchanges = WholesalerProductExchange::count();
            $completedExchanges = WholesalerProductExchange::where('status', 'completed')->count();
            $pendingExchanges = WholesalerProductExchange::where('status', 'pending')->count();
            $totalValueTransferred = WholesalerProductExchange::sum('destination_price_at_time');

            return view('employee.wholesaler.exchange.history', compact(
                'exchanges',
                'wholesalers',
                'employees',
                'totalExchanges',
                'completedExchanges',
                'pendingExchanges',
                'totalValueTransferred'
            ));

        } catch (\Exception $e) {
            Log::error('Exchange history error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading exchange history: ' . $e->getMessage());
        }
    }

    // ========== WHOLESALER ORDERS MANAGEMENT ==========

    /**
     * Display wholesaler orders
     */
    public function wholesalerOrders(Request $request)
    {
        try {
            $query = WholesalerOrder::with(['wholesaler'])
                ->latest();

            if ($request->has('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%")
                        ->orWhereHas('wholesaler', function($q2) use ($search) {
                            $q2->where('business_name', 'like', "%{$search}%");
                        });
                });
            }

            $orders = $query->paginate(20);

            $statuses = [
                'all' => 'All Orders',
                'pending' => 'Pending',
                'confirmed' => 'Confirmed',
                'processing' => 'Processing',
                'shipped' => 'Shipped',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            ];

            return view('employee.wholesaler.orders', compact('orders', 'statuses'));

        } catch (\Exception $e) {
            Log::error('Wholesaler orders error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading orders: ' . $e->getMessage());
        }
    }

    /**
     * View wholesaler order
     */
    public function viewWholesalerOrder($id)
    {
        try {
            $order = WholesalerOrder::with(['wholesaler', 'orderItems.product'])
                ->findOrFail($id);

            return view('employee.wholesaler.orders.show', compact('order'));

        } catch (\Exception $e) {
            Log::error('View wholesaler order error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Order not found: ' . $e->getMessage());
        }
    }

    // ========== SELLER PRODUCT MANAGEMENT METHODS ==========

    /**
     * Display all sellers with their products
     */
    public function sellerProducts(Request $request)
    {
        $controller = new \App\Http\Controllers\Employee\EmployeeSellerProductController();
        return $controller->index($request);
    }

    /**
     * Display products of a specific seller
     */
    public function sellerProductsBySeller(Request $request, $sellerId)
    {
        $controller = new \App\Http\Controllers\Employee\EmployeeSellerProductController();
        return $controller->sellerProducts($request, $sellerId);
    }

    /**
     * View seller product details
     */
    public function viewSellerProduct($productId)
    {
        $controller = new \App\Http\Controllers\Employee\EmployeeSellerProductController();
        return $controller->showProduct($productId);
    }

    /**
     * Show price update form for seller product
     */
    public function showSellerPriceUpdateForm($productId)
    {
        $controller = new \App\Http\Controllers\Employee\EmployeeSellerProductController();
        return $controller->showPriceUpdateForm($productId);
    }

    /**
     * Update seller product price
     */
    public function updateSellerProductPrice(Request $request)
    {
        $controller = new \App\Http\Controllers\Employee\EmployeeSellerProductController();
        return $controller->updatePrice($request);
    }

    /**
     * Bulk update seller product prices
     */
    public function bulkSellerPriceUpdate(Request $request)
    {
        $controller = new \App\Http\Controllers\Employee\EmployeeSellerProductController();
        return $controller->bulkPriceUpdate($request);
    }

    /**
     * Assign seller product to shop
     */
    public function assignSellerProductToShop(Request $request)
    {
        $controller = new \App\Http\Controllers\Employee\EmployeeSellerProductController();
        return $controller->assignToShop($request);
    }

    /**
     * Bulk assign seller products to shop
     */
    public function bulkAssignSellerProductsToShop(Request $request)
    {
        $controller = new \App\Http\Controllers\Employee\EmployeeSellerProductController();
        return $controller->bulkAssignToShop($request);
    }

    /**
     * Print seller product details
     */
    public function printSellerProduct($productId)
    {
        $controller = new \App\Http\Controllers\Employee\EmployeeSellerProductController();
        return $controller->printProduct($productId);
    }

    /**
     * Print seller product label
     */
    public function printSellerProductLabel($productId)
    {
        $controller = new \App\Http\Controllers\Employee\EmployeeSellerProductController();
        return $controller->printProductLabel($productId);
    }

    /**
     * Get seller product details for AJAX
     */
    public function getSellerProductDetails($productId)
    {
        $controller = new \App\Http\Controllers\Employee\EmployeeSellerProductController();
        return $controller->getProductDetails($productId);
    }

    /**
     * Get all shops for dropdown
     */
    public function getSellerShops()
    {
        $controller = new \App\Http\Controllers\Employee\EmployeeSellerProductController();
        return $controller->getShops();
    }

    /**
     * Seller product price change history
     */
    public function sellerPriceChangeHistory(Request $request)
    {
        $controller = new \App\Http\Controllers\Employee\EmployeeSellerProductController();
        return $controller->priceChangeHistory($request);
    }

    /**
     * Export seller products to CSV
     */
    public function exportSellerProducts(Request $request, $sellerId = null)
    {
        $controller = new \App\Http\Controllers\Employee\EmployeeSellerProductController();
        return $controller->exportProducts($request, $sellerId);
    }

    // ========== SELLER ORDER MANAGEMENT ==========

    /**
     * Display seller orders
     */
    public function sellerOrders(Request $request)
    {
        try {
            $query = Order::with(['seller', 'customer'])
                ->latest();

            if ($request->has('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('order_no', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%")
                        ->orWhereHas('seller', function($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%");
                        });
                });
            }

            $orders = $query->paginate(20);

            $statuses = [
                'all' => 'All Orders',
                'pending' => 'Pending',
                'confirmed' => 'Confirmed',
                'processing' => 'Processing',
                'shipped' => 'Shipped',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            ];

            return view('employee.seller.orders', compact('orders', 'statuses'));

        } catch (\Exception $e) {
            Log::error('Seller orders error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading orders: ' . $e->getMessage());
        }
    }

    /**
     * Update seller order status
     */
    public function updateSellerOrderStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,confirmed,processing,shipped,completed,cancelled',
            ]);

            $order = Order::findOrFail($id);
            $order->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Update seller order status error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View seller order
     */
    public function viewSellerOrder($id)
    {
        try {
            $order = Order::with(['seller', 'orderItems.product'])
                ->findOrFail($id);

            return view('employee.seller.order-view', compact('order'));

        } catch (\Exception $e) {
            Log::error('View seller order error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Order not found: ' . $e->getMessage());
        }
    }

    // ========== EXPORT METHODS ==========

    /**
     * Export reports
     */
    public function exportReport(Request $request)
    {
        try {
            $type = $request->get('type', 'orders');
            $format = $request->get('format', 'csv');

            switch ($type) {
                case 'wholesaler_orders':
                    $fileName = 'wholesaler-orders-' . date('Y-m-d') . '.' . $format;
                    $headers = $this->getExportHeaders($fileName, $format);

                    $orders = WholesalerOrder::with('wholesaler')->get();

                    return response()->streamDownload(function () use ($orders) {
                        $file = fopen('php://output', 'w');

                        fputcsv($file, ['Order Number', 'Wholesaler', 'Date', 'Customer', 'Total', 'Status']);

                        foreach ($orders as $order) {
                            fputcsv($file, [
                                $order->order_number,
                                $order->wholesaler->business_name ?? 'N/A',
                                $order->created_at->format('Y-m-d'),
                                $order->customer_name,
                                $order->total_amount,
                                $order->status
                            ]);
                        }

                        fclose($file);
                    }, $fileName, $headers);
                    break;

                case 'seller_orders':
                    $fileName = 'seller-orders-' . date('Y-m-d') . '.' . $format;
                    $headers = $this->getExportHeaders($fileName, $format);

                    $orders = Order::with('seller')->get();

                    return response()->streamDownload(function () use ($orders) {
                        $file = fopen('php://output', 'w');

                        fputcsv($file, ['Order Number', 'Seller', 'Date', 'Customer', 'Total', 'Status']);

                        foreach ($orders as $order) {
                            fputcsv($file, [
                                $order->order_no,
                                $order->seller->name ?? 'N/A',
                                $order->created_at->format('Y-m-d'),
                                $order->customer_name,
                                $order->total_amount,
                                $order->status
                            ]);
                        }

                        fclose($file);
                    }, $fileName, $headers);
                    break;

                default:
                    return redirect()->back()->with('error', 'Invalid export type');
            }

        } catch (\Exception $e) {
            Log::error('Export report error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting report: ' . $e->getMessage());
        }
    }

    /**
     * Get export headers
     */
    private function getExportHeaders($fileName, $format)
    {
        $contentType = $format == 'csv' ? 'text/csv' : 'application/vnd.ms-excel';

        return [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
    }

    /**
     * Delivery label print
     */
    public function deliveryLabelPrint($id)
    {
        try {
            $order = Order::with(['seller', 'customer'])
                ->findOrFail($id);

            return view('employee.print.delivery-label', compact('order'));

        } catch (\Exception $e) {
            Log::error('Delivery label print error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error printing label: ' . $e->getMessage());
        }
    }

    // ========== EMPLOYEE PROFILE METHODS ==========

    /**
     * Display employee profile
     */
    public function profile()
    {
        try {
            $employee = Auth::guard('employee')->user();

            // Add profile_image_url attribute
            if (!isset($employee->profile_image_url)) {
                $employee->profile_image_url = $this->getProfileImageUrl($employee);
            }

            // Get employee statistics
            $stats = [
                'total_price_changes' => WholesalerProductPriceChange::where('employee_id', $employee->id)->count(),
                'this_month_changes' => WholesalerProductPriceChange::where('employee_id', $employee->id)
                    ->whereMonth('created_at', now()->month)
                    ->count(),
                'products_updated' => WholesalerProductPriceChange::where('employee_id', $employee->id)
                    ->distinct('product_id')
                    ->count('product_id'),
                'total_exchanges' => WholesalerProductExchange::where('employee_id', $employee->id)->count(),
                'total_orders_processed' => Order::count() + WholesalerOrder::count(),
            ];

            return view('employee.profile.profile', compact('employee', 'stats'));

        } catch (\Exception $e) {
            Log::error('Profile error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading profile: ' . $e->getMessage());
        }
    }

    /**
     * Update employee profile
     */
    public function updateProfile(Request $request)
    {
        DB::beginTransaction();

        try {
            $employee = Auth::guard('employee')->user();
            $employeeId = $employee->id;

            Log::info('Updating employee profile', [
                'employee_id' => $employeeId,
                'request_data' => $request->except(['password', 'password_confirmation', 'img'])
            ]);

            // Define validation rules
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:employees,email,' . $employeeId,
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:500',
                'district' => 'required|string|max:100',
                'post_code' => 'required|string|max:20',
            ];

            // Add password rules conditionally
            if ($request->filled('password')) {
                $rules['password'] = 'required|string|min:8|confirmed';
                $rules['current_password'] = 'required|string|min:8';
            }

            $validator = Validator::make($request->all(), $rules, [
                'email.unique' => 'This email is already registered.',
                'current_password.required' => 'Current password is required to change password.',
            ]);

            if ($validator->fails()) {
                Log::warning('Profile update validation failed', [
                    'errors' => $validator->errors()->all()
                ]);

                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $validated = $validator->validated();

            // Verify current password if trying to change password
            if ($request->filled('password')) {
                if (!Hash::check($request->current_password, $employee->password)) {
                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Current password is incorrect.',
                            'errors' => ['current_password' => ['Current password is incorrect.']]
                        ], 422);
                    }
                    return redirect()->back()
                        ->withErrors(['current_password' => 'Current password is incorrect.'])
                        ->withInput();
                }

                $validated['password'] = Hash::make($request->password);

                // Add view_password if column exists
                if (Schema::hasColumn('employees', 'view_password')) {
                    $validated['view_password'] = $request->password;
                }
            } else {
                // Remove password fields if not changing password
                unset($validated['password']);
                unset($validated['password_confirmation']);
                unset($validated['current_password']);
            }

            // Update employee data
            $employee->update($validated);

            // Refresh employee data
            $employee->refresh();

            DB::commit();

            Log::info('Employee profile updated successfully', [
                'employee_id' => $employeeId,
                'updated_fields' => array_keys($validated)
            ]);

            // Prepare response
            $response = [
                'success' => true,
                'message' => 'Profile updated successfully!',
                'employee' => [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'email' => $employee->email,
                    'phone' => $employee->phone,
                    'profile_image_url' => $this->getProfileImageUrl($employee),
                    'role' => $employee->role,
                    'status' => $employee->status,
                    'code' => $employee->code,
                    'address' => $employee->address,
                    'district' => $employee->district,
                    'post_code' => $employee->post_code,
                ]
            ];

            if ($request->ajax()) {
                return response()->json($response);
            }

            return redirect()->route('employee.profile')
                ->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Profile update failed: ' . $e->getMessage(), [
                'employee_id' => $employeeId ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);

            $errorMessage = 'Error updating profile. ' . $e->getMessage();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }

            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
        }
    }

    /**
     * Get employee profile data (for AJAX requests)
     */
    public function getProfileData(Request $request)
    {
        try {
            $employee = Auth::guard('employee')->user();

            // Get statistics
            $totalPriceChanges = WholesalerProductPriceChange::where('employee_id', $employee->id)->count();
            $thisMonthChanges = WholesalerProductPriceChange::where('employee_id', $employee->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            $productsUpdated = WholesalerProductPriceChange::where('employee_id', $employee->id)
                ->distinct('product_id')
                ->count('product_id');
            $totalExchanges = WholesalerProductExchange::where('employee_id', $employee->id)->count();

            return response()->json([
                'success' => true,
                'employee' => [
                    'id' => $employee->id,
                    'code' => $employee->code,
                    'name' => $employee->name,
                    'email' => $employee->email,
                    'phone' => $employee->phone,
                    'address' => $employee->address,
                    'district' => $employee->district,
                    'post_code' => $employee->post_code,
                    'username' => $employee->username,
                    'role' => $employee->role,
                    'status' => $employee->status,
                    'profile_image_url' => $this->getProfileImageUrl($employee),
                    'created_at' => $employee->created_at ? $employee->created_at->format('Y-m-d H:i:s') : null,
                    'last_login_at' => $employee->last_login_at ? $employee->last_login_at->format('Y-m-d H:i:s') : null,
                    'stats' => [
                        'total_price_changes' => $totalPriceChanges,
                        'this_month_changes' => $thisMonthChanges,
                        'products_updated' => $productsUpdated,
                        'total_exchanges' => $totalExchanges,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting profile data: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error loading profile data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload profile image only
     */
    public function uploadProfileImage(Request $request)
    {
        try {
            $employee = Auth::guard('employee')->user();

            Log::info('Profile image upload request', [
                'employee_id' => $employee->id,
                'has_file' => $request->hasFile('img')
            ]);

            $validator = Validator::make($request->all(), [
                'img' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Delete old image if exists
            $this->deleteOldImage($employee);

            // Upload new image
            $path = 'employee/profiles';
            $filename = 'profile_' . $employee->id . '_' . time() . '.' . $request->file('img')->getClientOriginalExtension();
            $imagePath = $request->file('img')->storeAs($path, $filename, 'public');

            if (!$imagePath) {
                throw new \Exception('Failed to upload image.');
            }

            Log::info('Image uploaded successfully', [
                'path' => $imagePath,
                'employee_id' => $employee->id
            ]);

            // Get the correct image column name
            $imageColumn = $this->getImageColumnName();

            // Update employee record
            $employee->$imageColumn = $imagePath;
            $employee->save();

            Log::info('Employee record updated', [
                'column' => $imageColumn,
                'path' => $imagePath
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile image updated successfully!',
                'image_url' => asset('storage/' . $imagePath),
                'image_path' => $imagePath
            ]);

        } catch (\Exception $e) {
            Log::error('Profile image upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error uploading image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete profile image
     */
    public function deleteProfileImage(Request $request)
    {
        try {
            $employee = Auth::guard('employee')->user();

            Log::info('Delete profile image request', [
                'employee_id' => $employee->id
            ]);

            // Delete the image file
            $this->deleteOldImage($employee);

            // Get the correct image column name
            $imageColumn = $this->getImageColumnName();

            // Update employee record
            $employee->$imageColumn = null;
            $employee->save();

            Log::info('Profile image deleted successfully', [
                'employee_id' => $employee->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile image removed successfully!',
                'default_image' => asset('asset/img/default-avatar.png')
            ]);

        } catch (\Exception $e) {
            Log::error('Delete profile image failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error deleting image: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========== EMPLOYEE MANAGEMENT METHODS (ADMIN ONLY) ==========

    /**
     * Display all employees (Admin only)
     */
    public function index()
    {
        try {
            $employees = Employee::all();
            return view('admin.employee.index', compact('employees'));
        } catch (\Exception $e) {
            Log::error('Employee index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading employees: ' . $e->getMessage());
        }
    }

    /**
     * Show create employee form (Admin only)
     */
    public function create()
    {
        return view('admin.employee.create');
    }

    /**
     * Store new employee (Admin only)
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:employees',
                'phone' => 'required|string|max:20',
                'password' => 'required|string|min:8',
                'address' => 'nullable|string',
                'role' => 'required|in:admin,manager,staff',
            ]);

            $validated['password'] = bcrypt($validated['password']);
            $validated['code'] = 'EMP' . time() . rand(100, 999);
            $validated['status'] = 'active';

            Employee::create($validated);

            return redirect()->route('employee.list')->with('success', 'Employee created successfully!');

        } catch (\Exception $e) {
            Log::error('Create employee error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error creating employee: ' . $e->getMessage());
        }
    }

    /**
     * Display specific employee
     */
    public function show($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            return view('admin.employee.show', compact('employee'));
        } catch (\Exception $e) {
            Log::error('Show employee error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading employee: ' . $e->getMessage());
        }
    }

    /**
     * Edit employee (Admin only)
     */
    public function edit($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            return view('admin.employee.edit', compact('employee'));
        } catch (\Exception $e) {
            Log::error('Edit employee error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading employee: ' . $e->getMessage());
        }
    }

    /**
     * Update employee (Admin only)
     */
    public function update(Request $request, $id)
    {
        try {
            $employee = Employee::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:employees,email,' . $employee->id,
                'phone' => 'required|string|max:20',
                'address' => 'nullable|string',
                'role' => 'required|in:admin,manager,staff',
                'status' => 'required|in:active,inactive',
            ]);

            // Update password if provided
            if ($request->filled('password')) {
                $validated['password'] = bcrypt($request->password);
            }

            $employee->update($validated);

            return redirect()->route('employee.list')->with('success', 'Employee updated successfully!');

        } catch (\Exception $e) {
            Log::error('Update employee error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating employee: ' . $e->getMessage());
        }
    }

    /**
     * Delete employee (Admin only)
     */
    public function destroy($id)
    {
        try {
            $employee = Employee::findOrFail($id);

            // Check if employee has made any price changes
            $priceChanges = WholesalerProductPriceChange::where('employee_id', $employee->id)->count();
            $exchanges = WholesalerProductExchange::where('employee_id', $employee->id)->count();

            if ($priceChanges > 0 || $exchanges > 0) {
                return redirect()->back()->with('error', 'Cannot delete employee. They have made ' . $priceChanges . ' price changes and ' . $exchanges . ' exchanges.');
            }

            $employee->delete();

            return redirect()->route('employee.list')->with('success', 'Employee deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Delete employee error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting employee: ' . $e->getMessage());
        }
    }
}
