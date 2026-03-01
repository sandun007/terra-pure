<?php
// app/Http/Controllers/Employee/EmployeeSellerProductController.php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Saler;
use App\Models\EmployeePriceChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeSellerProductController extends Controller
{
    protected $productModel;
    protected $productTable;
    protected $sellerForeignKey;

    public function __construct()
    {
        $this->middleware('auth:employee');

        // Detect which product model exists
        $this->detectProductModel();
    }

    /**
     * Detect the correct product model
     */
    protected function detectProductModel()
    {
        $possibleModels = [
            'App\Models\SellerProduct',
            'App\Models\SalerProduct',
            'App\Models\Product',
        ];

        foreach ($possibleModels as $model) {
            if (class_exists($model)) {
                $this->productModel = $model;
                $this->productTable = (new $model)->getTable();

                // Determine the foreign key based on model
                if (str_contains($model, 'Saler')) {
                    $this->sellerForeignKey = 'saler_id';
                } elseif (str_contains($model, 'Seller')) {
                    $this->sellerForeignKey = 'seller_id';
                } else {
                    $this->sellerForeignKey = 'seller_id'; // default
                }

                return;
            }
        }

        // If no model found, we'll use DB facade
        $this->productModel = null;

        // Try to detect table
        $tables = ['seller_products', 'saler_products', 'products'];
        foreach ($tables as $table) {
            try {
                DB::table($table)->first();
                $this->productTable = $table;

                // Guess foreign key based on table name
                $this->sellerForeignKey = str_contains($table, 'saler') ? 'saler_id' : 'seller_id';
                break;
            } catch (\Exception $e) {
                continue;
            }
        }
    }

    /**
     * Display all seller products
     */
    public function index(Request $request)
    {
        try {
            // If we have a model, use it
            if ($this->productModel) {
                return $this->indexWithModel($request);
            }

            // Otherwise use DB facade
            return $this->indexWithDB($request);

        } catch (\Exception $e) {
            Log::error('Error loading seller products: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load products: ' . $e->getMessage());
        }
    }

    /**
     * Index using Eloquent Model
     */
    protected function indexWithModel(Request $request)
    {
        $model = $this->productModel;
        $foreignKey = $this->sellerForeignKey;

        $query = $model::with([str_replace('_id', '', $foreignKey), 'category', 'brand'])
            ->orderBy('created_at', 'desc');

        // Filter by seller
        if ($request->filled('seller_id')) {
            $query->where($foreignKey, $request->seller_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by approval status (if column exists)
        if ($request->filled('approval')) {
            try {
                if ($request->approval === 'pending') {
                    $query->whereNull('approved_by')->where('status', 'active');
                } elseif ($request->approval === 'approved') {
                    $query->whereNotNull('approved_by');
                } elseif ($request->approval === 'rejected') {
                    $query->where('status', 'rejected');
                }
            } catch (\Exception $e) {
                // Skip if approved_by column doesn't exist
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search, $foreignKey) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");

                try {
                    $q->orWhereHas(str_replace('_id', '', $foreignKey), function($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    });
                } catch (\Exception $e) {
                    // Skip if relationship doesn't exist
                }
            });
        }

        $products = $query->paginate(20)->withQueryString();
        $sellers = Saler::orderBy('name')->get(['id', 'name', 'code']);

        return view('employee.seller.products.index', compact('products', 'sellers'));
    }

    /**
     * Index using DB Facade (fallback)
     */
    protected function indexWithDB(Request $request)
    {
        $table = $this->productTable;
        $foreignKey = $this->sellerForeignKey;

        $query = DB::table($table . ' as p')
            ->leftJoin('sellers as s', 'p.' . $foreignKey, '=', 's.id')
            ->leftJoin('seller_categories as sc', 'p.category_id', '=', 'sc.id')
            ->leftJoin('seller_brands as sb', 'p.brand_id', '=', 'sb.id')
            ->select(
                'p.*',
                's.name as seller_name',
                's.code as seller_code',
                'sc.name as category_name',
                'sb.name as brand_name'
            )
            ->orderBy('p.created_at', 'desc');

        // Apply filters
        if ($request->filled('seller_id')) {
            $query->where('p.' . $foreignKey, $request->seller_id);
        }

        if ($request->filled('status')) {
            $query->where('p.status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('p.name', 'like', "%{$search}%")
                    ->orWhere('p.code', 'like', "%{$search}%")
                    ->orWhere('s.name', 'like', "%{$search}%")
                    ->orWhere('s.code', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(20)->withQueryString();
        $sellers = Saler::orderBy('name')->get(['id', 'name', 'code']);

        return view('employee.seller.products.index', compact('products', 'sellers'));
    }

    /**
     * Show product details
     */
    public function show($id)
    {
        try {
            if ($this->productModel) {
                $model = $this->productModel;
                $foreignKey = $this->sellerForeignKey;

                $product = $model::with([str_replace('_id', '', $foreignKey), 'category', 'brand'])
                    ->findOrFail($id);
            } else {
                $product = DB::table($this->productTable)->where('id', $id)->first();
                if (!$product) {
                    throw new \Exception('Product not found');
                }

                // Get seller info
                $foreignKey = $this->sellerForeignKey;
                $seller = DB::table('sellers')->where('id', $product->$foreignKey)->first();
                $product->seller_name = $seller->name ?? 'N/A';
                $product->seller_code = $seller->code ?? 'N/A';
            }

            $priceHistory = EmployeePriceChange::where('product_id', $id)
                ->where('product_type', 'seller')
                ->with('employee')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return view('employee.seller.products.show', compact('product', 'priceHistory'));

        } catch (\Exception $e) {
            Log::error('Error showing product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Product not found');
        }
    }

    /**
     * Show price update form
     */
    public function editPrice($id)
    {
        try {
            if ($this->productModel) {
                $model = $this->productModel;
                $foreignKey = $this->sellerForeignKey;

                $product = $model::with(str_replace('_id', '', $foreignKey))->findOrFail($id);
            } else {
                $product = DB::table($this->productTable)->where('id', $id)->first();
                if (!$product) {
                    throw new \Exception('Product not found');
                }
            }

            return view('employee.seller.products.price-edit', compact('product'));

        } catch (\Exception $e) {
            Log::error('Error loading price edit form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Product not found');
        }
    }

    /**
     * Update product price (requires approval)
     */
    public function updatePrice(Request $request, $id)
    {
        $request->validate([
            'display_price' => 'required|numeric|min:0',
            'stock_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'packaging_cost' => 'nullable|numeric|min:0',
            'change_reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            if (!$this->productModel) {
                throw new \Exception('Product model not found');
            }

            $model = $this->productModel;
            $product = $model::findOrFail($id);
            $employee = Auth::guard('employee')->user();

            // Store old prices
            $oldDisplayPrice = $product->display_price;
            $oldStockPrice = $product->stock_price;
            $oldDiscount = $product->discount ?? 0;
            $oldPackagingCost = $product->packaging_cost ?? 0;

            // Calculate new final price
            $newDisplayPrice = $request->display_price;
            $newDiscount = $request->discount ?? 0;
            $newFinalPrice = $newDisplayPrice * (1 - $newDiscount / 100);

            // Create price change request (pending approval)
            EmployeePriceChange::create([
                'product_id' => $product->id,
                'product_type' => 'seller',
                'employee_id' => $employee->id,
                'old_display_price' => $oldDisplayPrice,
                'new_display_price' => $newDisplayPrice,
                'old_stock_price' => $oldStockPrice,
                'new_stock_price' => $request->stock_price,
                'old_discount' => $oldDiscount,
                'new_discount' => $newDiscount,
                'old_packaging_cost' => $oldPackagingCost,
                'new_packaging_cost' => $request->packaging_cost ?? 0,
                'old_final_price' => $product->final_price ?? 0,
                'new_final_price' => $newFinalPrice,
                'price_difference' => $newFinalPrice - ($product->final_price ?? 0),
                'percentage_change' => ($product->final_price ?? 0) > 0
                    ? (($newFinalPrice - $product->final_price) / $product->final_price) * 100
                    : 0,
                'change_reason' => $request->change_reason,
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('employee.seller.products.show', $id)
                ->with('success', 'Price update request submitted for approval.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating price: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update price: ' . $e->getMessage())->withInput();
        }
    }

    // ... rest of the methods (approveProduct, rejectProduct, etc.)
    // ... would need similar modifications
}
