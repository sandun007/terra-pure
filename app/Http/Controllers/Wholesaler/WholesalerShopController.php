<?php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use App\Models\WholesalerShop;
use App\Models\WholesalerProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WholesalerShopController extends Controller
{
    /**
     * Display the shop page with products
     */
    public function index(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            // Get products
            $query = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
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
            $shop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();
            $nextProductCode = $this->generateNextProductCode();

            return view('wholesaler.shop.shop', compact('shop', 'products', 'nextProductCode'));

        } catch (\Exception $e) {
            Log::error('Shop index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading products');
        }
    }

    /**
     * Display a single product by code
     */
    /**
     * Display a single product by code
     */
    public function showProduct($code)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            // Decode URL-encoded code if necessary
            $decodedCode = urldecode($code);

            // Find product by code
            $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('code', $decodedCode)
                ->with(['category', 'brand'])
                ->first();

            if (!$product) {
                Log::warning('Product not found with code: ' . $decodedCode . ' for wholesaler: ' . $wholesaler->id);
                return redirect()->route('wholesaler.shop.index')->with('error', 'Product not found with code: ' . $decodedCode);
            }

            // Check if product belongs to wholesaler (additional safety)
            if ((int)$product->wholesaler_id !== (int)$wholesaler->id) {
                Log::warning('Product access denied: Product belongs to different wholesaler');
                return redirect()->route('wholesaler.shop.index')->with('error', 'Product not found.');
            }

            $shop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();

            $relatedProducts = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'active')
                ->where('id', '!=', $product->id)
                ->latest()
                ->limit(4)
                ->get();

            return view('wholesaler.shop.product-details', compact('shop', 'product', 'relatedProducts'));

        } catch (\Exception $e) {
            Log::error('showProduct error: ' . $e->getMessage());
            return redirect()->route('wholesaler.shop.index')->with('error', 'Error loading product details.');
        }
    }

    /**
     * Show product by ID (for backward compatibility)
     */
    public function showProductById($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            // Find product by ID
            $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->with(['category', 'brand'])
                ->find($id);

            if (!$product) {
                return redirect()->route('wholesaler.shop.index')->with('error', 'Product not found.');
            }

            // Optional: only show active products in shop
            if ($product->status !== 'active') {
                return redirect()->route('wholesaler.shop.index')->with('error', 'Product not available.');
            }

            $shop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();

            $relatedProducts = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'active')
                ->where('id', '!=', $product->id)
                ->latest()
                ->limit(4)
                ->get();

            return view('wholesaler.shop.product-details', compact('shop', 'product', 'relatedProducts'));

        } catch (\Exception $e) {
            Log::error('showProductById error: ' . $e->getMessage());
            return redirect()->route('wholesaler.shop.index')->with('error', 'Product not found.');
        }
    }

    /**
     * Find product by code - AJAX endpoint for order page
     */
    public function findByCode($code)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
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
            Log::error('Find product by code error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error finding product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product details via AJAX - FIXED for the route error
     */
    public function getProductDetails($code)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            // Decode URL-encoded code
            $decodedCode = urldecode($code);

            $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('code', $decodedCode)
                ->with(['category', 'brand'])
                ->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found with code: ' . $decodedCode
                ], 404);
            }

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
                    'original_price' => (float) $product->display_price,
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
            Log::error('Get product details error: ' . $e->getMessage());
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
            $wholesaler = Auth::guard('wholesaler')->user();
            $query = $request->get('query', '');

            $products = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'active')
                ->with(['category', 'brand'])
                ->where(function ($q) use ($query) {
                    $q->where('code', 'like', "%{$query}%")
                        ->orWhere('name', 'like', "%{$query}%");
                })
                ->paginate(12);

            $shop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();
            $nextProductCode = $this->generateNextProductCode();

            return view('wholesaler.shop.shop', compact('shop', 'products', 'nextProductCode'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Search error occurred');
        }
    }

    /**
     * Generate next available product code
     */
    private function generateNextProductCode()
    {
        $prefix = 'WS/ITM/';
        $lastProduct = WholesalerProduct::where('code', 'LIKE', $prefix . '%')
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
