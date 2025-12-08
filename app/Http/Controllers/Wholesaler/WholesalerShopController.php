<?php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use App\Models\WholesalerProduct;
use App\Models\Category;
use App\Models\Brand;
use App\Models\WholesalerShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class WholesalerShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:wholesaler');
    }

    /**
     * Display shop homepage - MODIFIED TO AUTO-CREATE SHOP
     */
    public function index(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            // Auto-create shop if doesn't exist
            $shop = $this->getOrCreateShop($wholesaler);

            // Base query for active products
            $query = WholesalerProduct::with(['category', 'brand'])
                ->where('wholesaler_id', $wholesaler->id)
                ->where('status', 'active');

            // Apply filters
            if ($request->has('category') && $request->category != '') {
                $query->where('category_id', $request->category);
            }

            if ($request->has('brand') && $request->brand != '') {
                $query->where('brand_id', $request->brand);
            }

            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%")
                        ->orWhere('code', 'LIKE', "%{$search}%")
                        ->orWhere('sku', 'LIKE', "%{$search}%");
                });
            }

            // Price filter - Handle both single price or range
            if ($request->has('price')) {
                if (strpos($request->price, '-') !== false) {
                    list($minPrice, $maxPrice) = explode('-', $request->price);
                    $query->whereBetween('wholesale_price', [floatval($minPrice), floatval($maxPrice)]);
                }
            }

            // Alternative price filter using min_price and max_price
            if ($request->has('min_price') && $request->min_price != '') {
                $query->where('wholesale_price', '>=', floatval($request->min_price));
            }

            if ($request->has('max_price') && $request->max_price != '') {
                $query->where('wholesale_price', '<=', floatval($request->max_price));
            }

            // Stock filter
            if ($request->has('stock')) {
                switch ($request->stock) {
                    case 'in_stock':
                        $query->where('qty', '>', 0);
                        break;
                    case 'low_stock':
                        $query->where('qty', '>', 0)->where('qty', '<=', 10);
                        break;
                    case 'out_of_stock':
                        $query->where('qty', '<=', 0);
                        break;
                }
            }

            // Discount filter
            if ($request->has('discount')) {
                $query->whereHas('discount', function($q) {
                    $q->where('status', 'active');
                });
            }

            // Featured filter
            if ($request->has('featured')) {
                $query->where('is_featured', true);
            }

            // Sorting
            $sort = $request->get('sort', 'newest');
            switch ($sort) {
                case 'price_low':
                    $query->orderBy('wholesale_price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('wholesale_price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'popular':
                    $query->orderBy('total_sold', 'desc');
                    break;
                case 'most_viewed':
                    $query->orderBy('views', 'desc');
                    break;
                case 'top_rated':
                    $query->orderBy('average_rating', 'desc');
                    break;
                case 'stock_low':
                    $query->orderBy('qty', 'asc');
                    break;
                case 'stock_high':
                    $query->orderBy('qty', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }

            // Get paginated products
            $products = $query->paginate($request->get('per_page', 12));

            // Append all query parameters to pagination links
            $products->appends($request->except('page'));

            // Get categories for filters with product counts
            $categories = Category::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'active')
                ->withCount(['products' => function($q) use ($wholesaler) {
                    $q->where('wholesaler_id', $wholesaler->id)
                        ->where('status', 'active');
                }])
                ->orderBy('name')
                ->get();

            // Get brands for filters with product counts
            $brands = Brand::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'active')
                ->withCount(['products' => function($q) use ($wholesaler) {
                    $q->where('wholesaler_id', $wholesaler->id)
                        ->where('status', 'active');
                }])
                ->orderBy('name')
                ->get();

            // Featured products
            $featuredProducts = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'active')
                ->where('is_featured', true)
                ->where('qty', '>', 0)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Top selling products (if you have sales tracking)
            $topSelling = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'active')
                ->where('qty', '>', 0)
                ->orderBy('total_sold', 'desc')
                ->limit(8)
                ->get();

            // Recently added products
            $recentProducts = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'active')
                ->where('qty', '>', 0)
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get();

            // Get price range for filter
            $priceRange = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'active')
                ->selectRaw('MIN(wholesale_price) as min_price, MAX(wholesale_price) as max_price')
                ->first();

            // Calculate discount percentages if needed
            foreach ($products as $product) {
                if ($product->display_price > 0 && $product->wholesale_price < $product->display_price) {
                    $product->discount_percentage = round((($product->display_price - $product->wholesale_price) / $product->display_price) * 100);
                } else {
                    $product->discount_percentage = 0;
                }
            }

            // Get available sort options
            $sortOptions = [
                'newest' => 'Newest Arrivals',
                'price_low' => 'Price: Low to High',
                'price_high' => 'Price: High to Low',
                'name_asc' => 'Name: A to Z',
                'name_desc' => 'Name: Z to A',
                'popular' => 'Most Popular',
                'most_viewed' => 'Most Viewed',
                'top_rated' => 'Top Rated',
                'stock_low' => 'Low Stock First',
                'stock_high' => 'High Stock First'
            ];

            // Get current filter values for form preservation
            $filterValues = [
                'search' => $request->search,
                'category' => $request->category,
                'brand' => $request->brand,
                'min_price' => $request->min_price,
                'max_price' => $request->max_price,
                'stock' => $request->stock,
                'discount' => $request->discount,
                'featured' => $request->featured,
                'sort' => $sort,
            ];

            // Get statistics for dashboard
            $stats = [
                'total_products' => WholesalerProduct::where('wholesaler_id', $wholesaler->id)->count(),
                'active_products' => WholesalerProduct::where('wholesaler_id', $wholesaler->id)->where('status', 'active')->count(),
                'out_of_stock' => WholesalerProduct::where('wholesaler_id', $wholesaler->id)->where('qty', '<=', 0)->count(),
                'featured_products' => WholesalerProduct::where('wholesaler_id', $wholesaler->id)->where('is_featured', true)->count(),
            ];

            return view('wholesaler.shop.index', compact(
                'shop',
                'products',
                'categories',
                'brands',
                'featuredProducts',
                'topSelling',
                'recentProducts',
                'priceRange',
                'sortOptions',
                'filterValues',
                'stats'
            ));

        } catch (\Exception $e) {
            \Log::error('Shop Index Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'wholesaler_id' => Auth::guard('wholesaler')->id()
            ]);

            return redirect()->route('wholesaler.dashboard')
                ->with('error', 'Error loading shop. Please try again later.');
        }
    }

// Helper function to auto-create shop
    private function getOrCreateShop($wholesaler)
    {
        try {
            // Check if shop exists
            $shop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();

            if (!$shop) {
                // Create default shop
                $shop = WholesalerShop::create([
                    'wholesaler_id' => $wholesaler->id,
                    'shop_name' => $wholesaler->business_name . "'s Shop",
                    'slug' => Str::slug($wholesaler->business_name . '-shop'),
                    'description' => 'Welcome to our wholesale store. We offer premium quality products at competitive prices.',
                    'status' => 'active',
                    'contact_email' => $wholesaler->email,
                    'contact_phone' => $wholesaler->phone,
                    'address' => $wholesaler->address,
                    'theme_color' => '#3a86ff',
                    'logo' => null,
                    'banner' => null,
                    'meta_title' => $wholesaler->business_name . ' - Wholesale Shop',
                    'meta_description' => 'Premium wholesale products from ' . $wholesaler->business_name,
                    'social_facebook' => null,
                    'social_instagram' => null,
                    'social_twitter' => null,
                    'seo_keywords' => 'wholesale, ' . $wholesaler->business_name . ', bulk products',
                    'analytics_code' => null,
                    'currency' => 'Rs',
                    'currency_symbol' => 'Rs',
                    'tax_rate' => 13,
                    'shipping_enabled' => true,
                    'return_policy' => '30 days return policy',
                    'refund_policy' => 'Full refund within 30 days',
                    'terms_conditions' => 'Standard terms and conditions apply',
                ]);

                \Log::info('Auto-created shop for wholesaler: ' . $wholesaler->id);
            }

            return $shop;

        } catch (\Exception $e) {
            \Log::error('Error creating shop: ' . $e->getMessage());

            // Return a fallback shop object
            return (object)[
                'id' => 0,
                'shop_name' => $wholesaler->business_name . "'s Shop",
                'slug' => 'default-shop',
                'description' => 'Wholesale Shop',
                'status' => 'active'
            ];
        }
    }

    /**
     * Auto-create shop for wholesaler
     */


    /**
     * Display product details
     */
    public function showProduct($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            // Auto-create shop if doesn't exist
            $this->getOrCreateShop($wholesaler);

            $product = WholesalerProduct::with(['category', 'brand', 'wholesaler.shop'])
                ->where('wholesaler_id', $wholesaler->id)
                ->where('id', $id)
                ->where('status', 'active')
                ->firstOrFail();

            // Related products
            $relatedProducts = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->where('status', 'active')
                ->limit(4)
                ->get();

            return view('wholesaler.shop.product-details', compact('product', 'relatedProducts'));

        } catch (\Exception $e) {
            return redirect()->route('wholesaler.shop.index')
                ->with('error', 'Product not found or not available');
        }
    }

    /**
     * Add product to cart
     */
    public function addToCart(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:wholesaler_products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $wholesaler = Auth::guard('wholesaler')->user();

            $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('id', $request->product_id)
                ->where('status', 'active')
                ->firstOrFail();

            // Check stock
            if ($product->qty < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock. Only ' . $product->qty . ' items available.'
                ], 400);
            }

            // Check minimum order quantity
            if ($request->quantity < $product->min_order_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Minimum order quantity is ' . $product->min_order_quantity
                ], 400);
            }

            // Get cart from session
            $cart = Session::get('wholesaler_cart', []);

            // Check if product already in cart
            $cartItemKey = 'product_' . $product->id;

            if (isset($cart[$cartItemKey])) {
                // Update quantity
                $newQuantity = $cart[$cartItemKey]['quantity'] + $request->quantity;

                if ($newQuantity > $product->qty) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot add more. Maximum available: ' . $product->qty
                    ], 400);
                }

                $cart[$cartItemKey]['quantity'] = $newQuantity;
            } else {
                // Add new item
                $cart[$cartItemKey] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->wholesale_price,
                    'display_price' => $product->display_price,
                    'quantity' => $request->quantity,
                    'image' => $product->image,
                    'code' => $product->code,
                    'min_order_quantity' => $product->min_order_quantity,
                    'max_quantity' => $product->qty,
                    'category' => $product->category->name ?? 'N/A',
                    'brand' => $product->brand->name ?? 'N/A'
                ];
            }

            // Save cart to session
            Session::put('wholesaler_cart', $cart);
            Session::save();

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'cart_count' => count($cart),
                'cart_total' => $this->calculateCartTotal($cart)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding to cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Quick buy now (add to cart and redirect to checkout)
     */
    public function buyNow(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:wholesaler_products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $wholesaler = Auth::guard('wholesaler')->user();

            $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('id', $request->product_id)
                ->where('status', 'active')
                ->firstOrFail();

            // Check stock
            if ($product->qty < $request->quantity) {
                return redirect()->back()->with('error', 'Insufficient stock. Only ' . $product->qty . ' items available.');
            }

            // Check minimum order quantity
            if ($request->quantity < $product->min_order_quantity) {
                return redirect()->back()->with('error', 'Minimum order quantity is ' . $product->min_order_quantity);
            }

            // Clear cart and add single item
            $cart = [];
            $cart['product_' . $product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->wholesale_price,
                'display_price' => $product->display_price,
                'quantity' => $request->quantity,
                'image' => $product->image,
                'code' => $product->code,
                'min_order_quantity' => $product->min_order_quantity,
                'max_quantity' => $product->qty,
                'category' => $product->category->name ?? 'N/A',
                'brand' => $product->brand->name ?? 'N/A'
            ];

            Session::put('wholesaler_cart', $cart);
            Session::save();

            return redirect()->route('wholesaler.shop.checkout');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error processing buy now: ' . $e->getMessage());
        }
    }

    /**
     * View cart
     */
    public function viewCart()
    {
        try {
            $cart = Session::get('wholesaler_cart', []);
            $wholesaler = Auth::guard('wholesaler')->user();

            // Auto-create shop if doesn't exist
            $shop = $this->getOrCreateShop($wholesaler);

            $cartTotal = $this->calculateCartTotal($cart);

            return view('wholesaler.shop.cart', compact('cart', 'cartTotal', 'shop'));

        } catch (\Exception $e) {
            return redirect()->route('wholesaler.shop.index')
                ->with('error', 'Error loading cart: ' . $e->getMessage());
        }
    }

    /**
     * Update cart item
     */
    public function updateCart(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required',
                'quantity' => 'required|integer|min:1'
            ]);

            $cart = Session::get('wholesaler_cart', []);
            $cartItemKey = 'product_' . $request->product_id;

            if (isset($cart[$cartItemKey])) {
                $wholesaler = Auth::guard('wholesaler')->user();

                $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                    ->where('id', $request->product_id)
                    ->first();

                if (!$product) {
                    unset($cart[$cartItemKey]);
                    Session::put('wholesaler_cart', $cart);
                    return response()->json([
                        'success' => false,
                        'message' => 'Product not found'
                    ], 404);
                }

                // Check stock
                if ($request->quantity > $product->qty) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Only ' . $product->qty . ' items available'
                    ], 400);
                }

                // Check minimum order
                if ($request->quantity < $product->min_order_quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Minimum order quantity is ' . $product->min_order_quantity
                    ], 400);
                }

                $cart[$cartItemKey]['quantity'] = $request->quantity;
                Session::put('wholesaler_cart', $cart);
                Session::save();

                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated successfully',
                    'cart_count' => count($cart),
                    'cart_total' => $this->calculateCartTotal($cart),
                    'item_total' => $cart[$cartItemKey]['price'] * $cart[$cartItemKey]['quantity']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Product not found in cart'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove from cart
     */
    public function removeFromCart($id)
    {
        try {
            $cart = Session::get('wholesaler_cart', []);
            $cartItemKey = 'product_' . $id;

            if (isset($cart[$cartItemKey])) {
                unset($cart[$cartItemKey]);
                Session::put('wholesaler_cart', $cart);
                Session::save();

                return response()->json([
                    'success' => true,
                    'message' => 'Product removed from cart',
                    'cart_count' => count($cart),
                    'cart_total' => $this->calculateCartTotal($cart)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Product not found in cart'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing from cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear cart
     */
    public function clearCart()
    {
        Session::forget('wholesaler_cart');

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully',
            'cart_count' => 0,
            'cart_total' => 0
        ]);
    }

    /**
     * Get cart count
     */
    public function getCartCount()
    {
        $cart = Session::get('wholesaler_cart', []);

        return response()->json([
            'count' => count($cart),
            'total' => $this->calculateCartTotal($cart)
        ]);
    }

    /**
     * Checkout page
     */
    public function checkout()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $cart = Session::get('wholesaler_cart', []);

            if (empty($cart)) {
                return redirect()->route('wholesaler.shop.cart')
                    ->with('error', 'Your cart is empty');
            }

            // Auto-create shop if doesn't exist
            $shop = $this->getOrCreateShop($wholesaler);
            $cartTotal = $this->calculateCartTotal($cart);

            // Check stock availability before checkout
            foreach ($cart as $item) {
                $product = WholesalerProduct::find($item['id']);
                if (!$product || $product->qty < $item['quantity']) {
                    return redirect()->route('wholesaler.shop.cart')
                        ->with('error', 'Product ' . $item['name'] . ' is out of stock or quantity not available');
                }
            }

            return view('wholesaler.shop.checkout', compact('cart', 'cartTotal', 'shop'));

        } catch (\Exception $e) {
            return redirect()->route('wholesaler.shop.cart')
                ->with('error', 'Error loading checkout: ' . $e->getMessage());
        }
    }

    /**
     * Process checkout
     */
    public function processCheckout(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $cart = Session::get('wholesaler_cart', []);

            if (empty($cart)) {
                return redirect()->route('wholesaler.shop.index')
                    ->with('error', 'Your cart is empty');
            }

            $request->validate([
                'payment_method' => 'required|in:cash,bank_transfer,digital_wallet',
                'shipping_address' => 'required|string|max:500',
                'notes' => 'nullable|string|max:1000'
            ]);

            // Calculate total
            $totalAmount = $this->calculateCartTotal($cart);

            // TODO: Create order logic here
            // This is a simplified version - you should expand this based on your Order model

            // Clear cart after successful order
            Session::forget('wholesaler_cart');

            return redirect()->route('wholesaler.orders.index')
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            return redirect()->route('wholesaler.shop.checkout')
                ->with('error', 'Error processing checkout: ' . $e->getMessage());
        }
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $request->validate([
                'query' => 'required|string|min:2'
            ]);

            // Auto-create shop if doesn't exist
            $shop = $this->getOrCreateShop($wholesaler);

            $products = WholesalerProduct::with(['category', 'brand'])
                ->where('wholesaler_id', $wholesaler->id)
                ->where('status', 'active')
                ->where(function($q) use ($request) {
                    $q->where('name', 'LIKE', "%{$request->query}%")
                        ->orWhere('description', 'LIKE', "%{$request->query}%")
                        ->orWhere('code', 'LIKE', "%{$request->query}%")
                        ->orWhere('barcode', 'LIKE', "%{$request->query}%");
                })
                ->paginate(12);

            return view('wholesaler.shop.search', compact('products', 'shop'));

        } catch (\Exception $e) {
            return redirect()->route('wholesaler.shop.index')
                ->with('error', 'Error searching products: ' . $e->getMessage());
        }
    }

    /**
     * Calculate cart total
     */
    private function calculateCartTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}
