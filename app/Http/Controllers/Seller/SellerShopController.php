<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerShop;
use App\Models\SellerProduct;
use App\Models\SellerOrder;
use App\Models\SellerOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class SellerShopController extends Controller
{
    /**
     * Display the shop page with products
     */
    public function index(Request $request)
    {
        try {
            $seller = Auth::guard('seller')->user();

            // Get products
            $query = SellerProduct::where('seller_id', $seller->id)
                ->with(['category', 'brand'])
                ->where('status', 'active');

            // Search by CODE, name, description
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Filter by stock status
            if ($request->has('filter') && $request->filter != 'all') {
                switch ($request->filter) {
                    case 'in_stock':
                        $query->where('qty', '>', 0);
                        break;
                    case 'low_stock':
                        $query->where('qty', '>', 0)
                            ->whereRaw('qty <= min_qty');
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
                    $query->orderBy('display_price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('display_price', 'desc');
                    break;
                case 'code_asc':
                    $query->orderBy('code', 'asc');
                    break;
                case 'code_desc':
                    $query->orderBy('code', 'desc');
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
            $nextProductCode = $this->generateNextProductCode();

            return view('seller.shop.shop', compact('shop', 'products', 'nextProductCode'));

        } catch (\Exception $e) {
            \Log::error('Shop index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading products');
        }
    }

    /**
     * Display a single product
     */
    public function showProduct(SellerProduct $product)
    {
        try {
            $seller = Auth::guard('seller')->user();

            // ✅ Must be owner seller
            if ((int)$product->seller_id !== (int)$seller->id) {
                return redirect()->route('seller.shop.seller.index')->with('error', 'Product not found.');
            }

            // Load relations
            $product->load(['category', 'brand']);

            $shop = SellerShop::where('seller_id', $seller->id)->first();

            $relatedProducts = SellerProduct::where('seller_id', $seller->id)
                ->where('status', 'active')
                ->where('id', '!=', $product->id)
                ->latest()
                ->limit(4)
                ->get();

            return view('seller.shop.product-details', compact('shop', 'product', 'relatedProducts'));

        } catch (\Exception $e) {
            \Log::error('showProduct error: ' . $e->getMessage());
            return redirect()->route('seller.shop.seller.index')->with('error', 'Product not found.');
        }
    }
    public function showProductById(SellerProduct $product)
    {
        $seller = Auth::guard('seller')->user();

        // Security: only allow own products
        if ((int) $product->seller_id !== (int) $seller->id) {
            return redirect()->route('seller.shop.seller.index')->with('error', 'Product not found.');
        }

        // Optional: only show active products in shop
        if ($product->status !== 'active') {
            return redirect()->route('seller.shop.seller.index')->with('error', 'Product not available.');
        }

        $product->load(['category', 'brand']);

        $shop = SellerShop::where('seller_id', $seller->id)->first();

        $relatedProducts = SellerProduct::where('seller_id', $seller->id)
            ->where('status', 'active')
            ->where('id', '!=', $product->id)
            ->latest()
            ->limit(4)
            ->get();

        return view('seller.shop.product-details', compact('shop', 'product', 'relatedProducts'));
    }
    /**
     * Find product by code - AJAX endpoint for order page
     */
    public function findByCode($code)
    {
        try {
            $seller = Auth::guard('seller')->user();

            $product = SellerProduct::where('seller_id', $seller->id)
                ->where('code', $code)
                ->where('status', 'active')
                ->with(['category', 'brand'])
                ->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found with code: ' . $code
                ], 404);
            }

            // Calculate final price with discount
            $finalPrice = $product->display_price;
            if ($product->discount > 0) {
                $finalPrice = $product->display_price * (1 - $product->discount / 100);
            }

            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'code' => $product->code,
                    'name' => $product->name,
                    'description' => $product->description ?? 'No description available',
                    'display_price' => (float) $finalPrice,
                    'original_price' => (float) $product->display_price,
                    'stock_price' => (float) $product->stock_price,
                    'discount' => (float) ($product->discount ?? 0),
                    'qty' => (int) $product->qty,
                    'min_qty' => (int) ($product->min_qty ?? 1),
                    'category' => $product->category->name ?? 'Uncategorized',
                    'brand' => $product->brand->name ?? null,
                    'image' => $product->image ? asset('storage/' . $product->image) : null,
                    'stock_status' => $product->qty > 0
                        ? ($product->qty <= $product->min_qty ? 'low' : 'available')
                        : 'out'
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Find product by code error: ' . $e->getMessage());
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

            $product = SellerProduct::where('seller_id', $seller->id)
                ->where('code', $code)
                ->with(['category', 'brand'])
                ->firstOrFail();

            $finalPrice = $product->display_price;
            if ($product->discount > 0) {
                $finalPrice = $product->display_price * (1 - $product->discount / 100);
            }

            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'code' => $product->code,
                    'name' => $product->name,
                    'description' => $product->description ?? '',
                    'display_price' => (float) $finalPrice,
                    'stock_price' => (float) $product->stock_price,
                    'discount' => (float) ($product->discount ?? 0),
                    'qty' => (int) $product->qty,
                    'min_qty' => (int) ($product->min_qty ?? 1),
                    'category' => $product->category->name ?? 'Uncategorized',
                    'brand' => $product->brand->name ?? null,
                    'image' => $product->image ? asset('storage/' . $product->image) : null,
                    'stock_status' => $product->qty > 0 ? ($product->qty <= $product->min_qty ? 'low' : 'available') : 'out',
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found with code: ' . $code
            ], 404);
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

            $products = SellerProduct::where('seller_id', $seller->id)
                ->where('status', 'active')
                ->with(['category', 'brand'])
                ->where(function ($q) use ($query) {
                    $q->where('code', 'like', "%{$query}%")
                        ->orWhere('name', 'like', "%{$query}%");
                })
                ->paginate(12);

            $shop = SellerShop::where('seller_id', $seller->id)->first();
            $nextProductCode = $this->generateNextProductCode();

            return view('seller.shop.shop', compact('shop', 'products', 'nextProductCode'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Search error occurred');
        }
    }

    /**
     * Generate next available product code
     */
    private function generateNextProductCode()
    {
        $prefix = 'DX/ITM/';
        $lastProduct = SellerProduct::where('code', 'LIKE', $prefix . '%')
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
