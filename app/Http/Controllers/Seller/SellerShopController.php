<?php
// app/Http/Controllers/Seller/SellerShopController.php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerShop;
use App\Models\WholesalerProduct;
use App\Models\Wholesaler;
use App\Models\SellerOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SellerShopController extends Controller
{
    /**
     * Display the shop page with products from ALL wholesalers
     * Only shows products that are approved by employee
     */
    public function index(Request $request)
    {
        try {
            $seller = Auth::guard('seller')->user();

            // Check if the new columns exist
            $hasApprovalColumns = Schema::hasColumns('wholesaler_products', [
                'employee_approval_status',
                'display_in_seller_shop'
            ]);

            // Get products from ALL wholesalers
            $query = WholesalerProduct::with(['category', 'brand', 'wholesaler'])
                ->where('status', 'active'); // Always require active status

            // Only apply approval filters if columns exist
            if ($hasApprovalColumns) {
                $query->where('employee_approval_status', 'approved')
                    ->where('display_in_seller_shop', true);
            } else {
                // Log warning that columns are missing
                Log::warning('Employee approval columns missing from wholesaler_products table');
            }

            // Search by CODE, name
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Filter by wholesaler
            if ($request->has('wholesaler_id') && $request->wholesaler_id != '') {
                $query->where('wholesaler_id', $request->wholesaler_id);
            }

            // Filter by stock status
            if ($request->has('filter') && $request->filter != 'all') {
                switch ($request->filter) {
                    case 'in_stock':
                        $query->where('qty', '>', 0);
                        break;
                    case 'low_stock':
                        $query->where('qty', '>', 0)
                            ->whereRaw('qty <= min_stock_level');
                        break;
                    case 'out_of_stock':
                        $query->where('qty', '<=', 0);
                        break;
                }
            }

            // Sort options
            $sort = $request->get('sort', 'newest');
            switch ($sort) {
                case 'price_low':
                    $query->orderBy('selling_price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('selling_price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }

            $products = $query->paginate(12)->withQueryString();
            $shop = SellerShop::where('seller_id', $seller->id)->first();

            // Get all wholesalers for filter dropdown
            $wholesalersQuery = Wholesaler::whereHas('products', function($q) use ($hasApprovalColumns) {
                $q->where('status', 'active');

                if ($hasApprovalColumns) {
                    $q->where('employee_approval_status', 'approved')
                        ->where('display_in_seller_shop', true);
                }
            });

            $wholesalers = $wholesalersQuery->get(['id', 'business_name as name']);

            // Show a warning if columns are missing (for admin users)
            $warning = null;
            if (!$hasApprovalColumns && Auth::guard('seller')->user()->is_admin ?? false) {
                $warning = 'Employee approval system not fully set up. Please run migrations.';
            }

            return view('seller.shop.shop', compact('shop', 'products', 'wholesalers', 'warning'));

        } catch (\Exception $e) {
            Log::error('Seller Shop index error: ' . $e->getMessage());

            // Fallback query without approval columns
            try {
                $seller = Auth::guard('seller')->user();
                $products = WholesalerProduct::with(['category', 'brand', 'wholesaler'])
                    ->where('status', 'active')
                    ->paginate(12);

                $shop = SellerShop::where('seller_id', $seller->id)->first();
                $wholesalers = Wholesaler::whereHas('products', function($q) {
                    $q->where('status', 'active');
                })->get(['id', 'business_name as name']);

                return view('seller.shop.shop', compact('shop', 'products', 'wholesalers'))
                    ->with('warning', 'Using fallback mode: Product approvals not configured.');

            } catch (\Exception $fallbackError) {
                return redirect()->back()->with('error', 'Error loading products: ' . $e->getMessage());
            }
        }
    }

    /**
     * Display a single product by code
     */
    public function showProduct($code)
    {
        try {
            $seller = Auth::guard('seller')->user();

            $decodedCode = urldecode($code);

            $hasApprovalColumns = Schema::hasColumns('wholesaler_products', [
                'employee_approval_status',
                'display_in_seller_shop'
            ]);

            // Build query based on available columns
            $query = WholesalerProduct::with(['category', 'brand', 'wholesaler'])
                ->where('code', $decodedCode)
                ->where('status', 'active');

            if ($hasApprovalColumns) {
                $query->where('employee_approval_status', 'approved')
                    ->where('display_in_seller_shop', true);
            }

            $product = $query->first();

            if (!$product) {
                Log::warning('Product not found with code: ' . $decodedCode);
                return redirect()->route('seller.shop.index')
                    ->with('error', 'Product not found or not available in shop.');
            }

            $shop = SellerShop::where('seller_id', $seller->id)->first();

            // Get related products
            $relatedQuery = WholesalerProduct::with(['category', 'brand', 'wholesaler'])
                ->where('status', 'active')
                ->where('id', '!=', $product->id);

            if ($hasApprovalColumns) {
                $relatedQuery->where('employee_approval_status', 'approved')
                    ->where('display_in_seller_shop', true);
            }

            $relatedProducts = $relatedQuery
                ->where(function($q) use ($product) {
                    if ($product->category_id) {
                        $q->orWhere('category_id', $product->category_id);
                    }
                    if ($product->wholesaler_id) {
                        $q->orWhere('wholesaler_id', $product->wholesaler_id);
                    }
                })
                ->latest()
                ->limit(4)
                ->get();

            return view('seller.shop.product-details', compact('shop', 'product', 'relatedProducts'));

        } catch (\Exception $e) {
            Log::error('Seller showProduct error: ' . $e->getMessage());
            return redirect()->route('seller.shop.index')
                ->with('error', 'Error loading product details: ' . $e->getMessage());
        }
    }

    /**
     * Find product by code - AJAX endpoint for order page
     */
    public function findByCode($code)
    {
        try {
            $seller = Auth::guard('seller')->user();

            $hasApprovalColumns = Schema::hasColumns('wholesaler_products', [
                'employee_approval_status',
                'display_in_seller_shop'
            ]);

            $query = WholesalerProduct::with(['category', 'brand', 'wholesaler'])
                ->where('code', $code)
                ->where('status', 'active');

            if ($hasApprovalColumns) {
                $query->where('employee_approval_status', 'approved')
                    ->where('display_in_seller_shop', true);
            }

            $product = $query->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found or not available: ' . $code
                ], 404);
            }

            // Calculate final price with discount
            $finalPrice = $product->selling_price;
            if ($product->discount > 0) {
                $finalPrice = $product->selling_price * (1 - $product->discount / 100);
            }

            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'code' => $product->code,
                    'name' => $product->name,
                    'description' => $product->description ?? 'No description available',
                    'selling_price' => (float) $finalPrice,
                    'display_price' => (float) $product->selling_price,
                    'discount' => (float) ($product->discount ?? 0),
                    'qty' => (int) $product->qty,
                    'min_qty' => (int) ($product->min_stock_level ?? 1),
                    'category' => $product->category->name ?? 'Uncategorized',
                    'brand' => $product->brand->name ?? null,
                    'wholesaler' => $product->wholesaler->business_name ?? 'Unknown',
                    'wholesaler_id' => $product->wholesaler_id,
                    'image' => $product->image ? asset('storage/' . $product->image) : null,
                    'stock_status' => $product->qty > 0
                        ? ($product->qty <= ($product->min_stock_level ?? 1) ? 'low' : 'available')
                        : 'out'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Seller Find product by code error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error finding product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product details via AJAX
     */
    public function getProductDetails($code)
    {
        try {
            $seller = Auth::guard('seller')->user();

            $decodedCode = urldecode($code);

            $hasApprovalColumns = Schema::hasColumns('wholesaler_products', [
                'employee_approval_status',
                'display_in_seller_shop'
            ]);

            $query = WholesalerProduct::with(['category', 'brand', 'wholesaler'])
                ->where('code', $decodedCode)
                ->where('status', 'active');

            if ($hasApprovalColumns) {
                $query->where('employee_approval_status', 'approved')
                    ->where('display_in_seller_shop', true);
            }

            $product = $query->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found with code: ' . $decodedCode
                ], 404);
            }

            $finalPrice = $product->selling_price;
            if ($product->discount > 0) {
                $finalPrice = $product->selling_price * (1 - $product->discount / 100);
            }

            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'code' => $product->code,
                    'name' => $product->name,
                    'description' => $product->description ?? '',
                    'selling_price' => (float) $finalPrice,
                    'display_price' => (float) $product->selling_price,
                    'discount' => (float) ($product->discount ?? 0),
                    'qty' => (int) $product->qty,
                    'min_qty' => (int) ($product->min_stock_level ?? 1),
                    'category' => $product->category->name ?? 'Uncategorized',
                    'brand' => $product->brand->name ?? null,
                    'wholesaler' => $product->wholesaler->business_name ?? 'Unknown',
                    'wholesaler_id' => $product->wholesaler_id,
                    'image' => $product->image ? asset('storage/' . $product->image) : null,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Seller Get product details error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Product not found with code: ' . $code
            ], 404);
        }
    }

    /**
     * Quick order from shop page
     */
    public function quickOrder(Request $request)
    {
        try {
            $seller = Auth::guard('seller')->user();

            $validator = Validator::make($request->all(), [
                'product_code' => 'required|string',
                'quantity' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $hasApprovalColumns = Schema::hasColumns('wholesaler_products', [
                'employee_approval_status',
                'display_in_seller_shop'
            ]);

            $query = WholesalerProduct::where('code', $request->product_code)
                ->where('status', 'active');

            if ($hasApprovalColumns) {
                $query->where('employee_approval_status', 'approved')
                    ->where('display_in_seller_shop', true);
            }

            $product = $query->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found or not available'
                ], 404);
            }

            // Check stock
            if ($product->qty < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock. Available: {$product->qty}"
                ], 400);
            }

            $finalPrice = $product->selling_price;
            if ($product->discount > 0) {
                $finalPrice = $product->selling_price * (1 - $product->discount / 100);
            }

            // Redirect to order page with product details
            $redirectUrl = route('seller.create.new-order') . '?' . http_build_query([
                    'product_code' => $product->code,
                    'product_name' => $product->name,
                    'product_price' => $finalPrice,
                    'product_stock' => $product->qty,
                    'quantity' => $request->quantity,
                    'product_image' => $product->image ? asset('storage/' . $product->image) : null,
                    'product_description' => $product->description,
                    'product_category' => $product->category->name ?? 'Uncategorized',
                    'product_id' => $product->id,
                    'wholesaler_id' => $product->wholesaler_id,
                ]);

            return response()->json([
                'success' => true,
                'redirect_url' => $redirectUrl
            ]);

        } catch (\Exception $e) {
            Log::error('Seller quickOrder error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        try {
            $seller = Auth::guard('seller')->user();
            $query = $request->get('query', '');

            $hasApprovalColumns = Schema::hasColumns('wholesaler_products', [
                'employee_approval_status',
                'display_in_seller_shop'
            ]);

            $productsQuery = WholesalerProduct::with(['category', 'brand', 'wholesaler'])
                ->where('status', 'active')
                ->where(function ($q) use ($query) {
                    $q->where('code', 'like', "%{$query}%")
                        ->orWhere('name', 'like', "%{$query}%");
                });

            if ($hasApprovalColumns) {
                $productsQuery->where('employee_approval_status', 'approved')
                    ->where('display_in_seller_shop', true);
            }

            $products = $productsQuery->paginate(12);

            $shop = SellerShop::where('seller_id', $seller->id)->first();

            // Get wholesalers for filter
            $wholesalersQuery = Wholesaler::whereHas('products', function($q) use ($hasApprovalColumns) {
                $q->where('status', 'active');

                if ($hasApprovalColumns) {
                    $q->where('employee_approval_status', 'approved')
                        ->where('display_in_seller_shop', true);
                }
            });

            $wholesalers = $wholesalersQuery->get(['id', 'business_name as name']);

            return view('seller.shop.shop', compact('shop', 'products', 'wholesalers'));

        } catch (\Exception $e) {
            Log::error('Seller search error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Search error occurred');
        }
    }

    /**
     * Generate next available product code (for seller's own products)
     */
    private function generateNextProductCode()
    {
        $prefix = 'SLR/ITM/';
        $lastProduct = \App\Models\SellerProduct::where('code', 'LIKE', $prefix . '%')
            ->orderBy('code', 'desc')
            ->first();

        if ($lastProduct) {
            $lastNumber = (int) str_replace($prefix, '', $lastProduct->code);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
