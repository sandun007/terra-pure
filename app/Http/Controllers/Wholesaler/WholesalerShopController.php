<?php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use App\Models\WholesalerShop;
use App\Models\WholesalerProduct;
use App\Models\ProductReview;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlaced;

class WholesalerShopController extends Controller
{
    /**
     * Display the shop page with products
     */
    public function index(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $shop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();

            if (!$shop) {
                return redirect()->route('wholesaler.dashboard')->with('error', 'Please create your shop first!');
            }

            // Get all products for this wholesaler
            $query = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->with(['category', 'brand'])
                ->where('status', 'active');

            // Calculate average rating for each product
            $query->withCount(['reviews as average_rating' => function($query) {
                $query->select(DB::raw('COALESCE(AVG(rating), 0)'));
            }])
                ->withCount(['reviews as total_reviews' => function($query) {
                    $query->where('status', 'active');
                }]);

            // Search functionality
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('features', 'like', "%{$search}%");
                });
            }

            // Category filter
            if ($request->has('category') && $request->category != '') {
                $query->where('category_id', $request->category);
            }

            // Brand filter
            if ($request->has('brand') && $request->brand != '') {
                $query->where('brand_id', $request->brand);
            }

            // Price range filter
            if ($request->has('min_price') && $request->min_price != '') {
                $query->where('display_price', '>=', $request->min_price);
            }
            if ($request->has('max_price') && $request->max_price != '') {
                $query->where('display_price', '<=', $request->max_price);
            }

            // Stock filter
            if ($request->has('stock')) {
                switch ($request->stock) {
                    case 'in_stock':
                        $query->where('qty', '>', 50);
                        break;
                    case 'low_stock':
                        $query->whereBetween('qty', [10, 50]);
                        break;
                    case 'out_of_stock':
                        $query->where('qty', '<', 10);
                        break;
                }
            }

            // View type
            $viewType = $request->get('view', 'grid');

            // Sort options
            $sortOptions = [
                'newest' => ['created_at', 'desc'],
                'oldest' => ['created_at', 'asc'],
                'price_low' => ['display_price', 'asc'],
                'price_high' => ['display_price', 'desc'],
                'name_asc' => ['name', 'asc'],
                'name_desc' => ['name', 'desc'],
                'rating' => ['average_rating', 'desc'],
                'popular' => ['total_views', 'desc'],
            ];

            $sort = $request->get('sort', 'newest');
            if (array_key_exists($sort, $sortOptions)) {
                $query->orderBy($sortOptions[$sort][0], $sortOptions[$sort][1]);
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $products = $query->paginate($viewType === 'list' ? 9 : 12);

            // Get available categories and brands for filters
            $categories = Category::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'active')
                ->withCount(['products' => function($query) use ($wholesaler) {
                    $query->where('wholesaler_id', $wholesaler->id)
                        ->where('status', 'active');
                }])
                ->get();

            $brands = Brand::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'active')
                ->withCount(['products' => function($query) use ($wholesaler) {
                    $query->where('wholesaler_id', $wholesaler->id)
                        ->where('status', 'active');
                }])
                ->get();

            return view('wholesaler.shop.shop', compact(
                'shop',
                'products',
                'categories',
                'brands',
                'viewType'
            ));

        } catch (\Exception $e) {
            \Log::error('Shop index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading shop: ' . $e->getMessage());
        }
    }

    /**
     * Display the checkout page
     */
    public function checkout()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $shop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();

            if (!$shop) {
                return redirect()->route('wholesaler.dashboard')->with('error', 'Please create your shop first!');
            }

            $cartKey = "wholesaler_cart_{$wholesaler->id}";
            $cart = Session::get($cartKey, []);

            if (empty($cart)) {
                return redirect()->route('wholesaler.shop.cart')->with('error', 'Your cart is empty!');
            }

            $cartItems = [];
            $subtotal = 0;

            foreach ($cart as $productId => $item) {
                $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                    ->where('id', $productId)
                    ->with(['category', 'brand'])
                    ->first();

                if ($product) {
                    $quantity = $item['quantity'];
                    $itemSubtotal = $product->display_price * $quantity;
                    $cartItems[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'image' => $product->image,
                        'price' => $product->display_price,
                        'wholesale_price' => $product->wholesale_price,
                        'quantity' => $quantity,
                        'subtotal' => $itemSubtotal,
                        'max_order_quantity' => $product->qty,
                    ];
                    $subtotal += $itemSubtotal;
                }
            }

            // Calculate shipping and tax
            $shipping = $this->calculateShipping($subtotal);
            $taxRate = $shop->tax_rate ?? 5; // Default 5% tax
            $tax = $subtotal * ($taxRate / 100);
            $total = $subtotal + $shipping + $tax;

            return view('wholesaler.shop.checkout', compact(
                'shop',
                'cartItems',
                'subtotal',
                'shipping',
                'tax',
                'total',
                'taxRate'
            ));

        } catch (\Exception $e) {
            \Log::error('Checkout page error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading checkout page: ' . $e->getMessage());
        }
    }

    /**
     * Calculate shipping cost
     */
    private function calculateShipping($subtotal)
    {
        // Free shipping for orders over 5000
        if ($subtotal > 5000) {
            return 0;
        }

        // Calculate shipping based on subtotal
        if ($subtotal <= 1000) {
            return 200;
        } elseif ($subtotal <= 3000) {
            return 150;
        } else {
            return 100;
        }
    }

    /**
     * Display a single product with rating
     */
    public function showProduct($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->with(['category', 'brand'])
                ->withCount(['reviews as average_rating' => function($query) {
                    $query->select(DB::raw('COALESCE(AVG(rating), 0)'));
                }])
                ->withCount(['reviews as total_reviews' => function($query) {
                    $query->where('status', 'active');
                }])
                ->findOrFail($id);

            $shop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();

            // Get related products
            $relatedProducts = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('id', '!=', $id)
                ->where('status', 'active')
                ->withCount(['reviews as average_rating' => function($query) {
                    $query->select(DB::raw('COALESCE(AVG(rating), 0)'));
                }])
                ->limit(4)
                ->get();

            // Get product reviews
            $reviews = ProductReview::where('product_id', $id)
                ->where('status', 'active')
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // Calculate rating distribution
            $ratingDistribution = ProductReview::where('product_id', $id)
                ->where('status', 'active')
                ->select('rating', DB::raw('COUNT(*) as count'))
                ->groupBy('rating')
                ->orderBy('rating', 'desc')
                ->get();

            return view('wholesaler.shop.product-details', compact(
                'shop',
                'product',
                'relatedProducts',
                'reviews',
                'ratingDistribution'
            ));

        } catch (\Exception $e) {
            \Log::error('Show product error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Product not found: ' . $e->getMessage());
        }
    }

    /**
     * Quick view product modal
     */
    public function quickView($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->with(['category', 'brand'])
                ->withCount(['reviews as average_rating' => function($query) {
                    $query->select(DB::raw('COALESCE(AVG(rating), 0)'));
                }])
                ->withCount(['reviews as total_reviews' => function($query) {
                    $query->where('status', 'active');
                }])
                ->findOrFail($id);

            $html = view('wholesaler.shop.partials.quick-view', compact('product'))->render();

            return response()->json([
                'success' => true,
                'html' => $html
            ]);

        } catch (\Exception $e) {
            \Log::error('Quick view error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading product details'
            ], 500);
        }
    }

    /**
     * Add product review
     */
    public function addReview(Request $request, $id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'review' => 'required|string|max:1000',
            ]);

            $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('id', $id)
                ->firstOrFail();

            // Check if user has purchased this product
            $hasPurchased = Order::where('wholesaler_id', $wholesaler->id)
                ->whereHas('orderItems', function($query) use ($id) {
                    $query->where('product_id', $id);
                })
                ->where('status', 'completed')
                ->exists();

            if (!$hasPurchased) {
                return redirect()->back()->with('error', 'You can only review products you have purchased.');
            }

            // Check if user already reviewed
            $existingReview = ProductReview::where('product_id', $id)
                ->where('user_id', $wholesaler->id)
                ->where('user_type', 'wholesaler')
                ->first();

            if ($existingReview) {
                $existingReview->update([
                    'rating' => $request->rating,
                    'review' => $request->review,
                    'status' => 'pending'
                ]);

                $message = 'Review updated successfully! It will be visible after approval.';
            } else {
                ProductReview::create([
                    'product_id' => $id,
                    'user_id' => $wholesaler->id,
                    'user_type' => 'wholesaler',
                    'rating' => $request->rating,
                    'review' => $request->review,
                    'status' => 'pending'
                ]);

                $message = 'Review submitted successfully! It will be visible after approval.';
            }

            // Recalculate product average rating
            $this->calculateProductRating($id);

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Add review error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error submitting review: ' . $e->getMessage());
        }
    }

    /**
     * Calculate product average rating
     */
    private function calculateProductRating($productId)
    {
        $reviews = ProductReview::where('product_id', $productId)
            ->where('status', 'active')
            ->get();

        $totalRatings = $reviews->sum('rating');
        $totalReviews = $reviews->count();

        $averageRating = $totalReviews > 0 ? $totalRatings / $totalReviews : 0;

        WholesalerProduct::where('id', $productId)->update([
            'average_rating' => round($averageRating, 2),
            'total_reviews' => $totalReviews,
            'total_ratings' => $totalRatings
        ]);
    }

    /**
     * View cart with proper data structure
     */
    public function viewCart()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $shop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();

            if (!$shop) {
                return redirect()->route('wholesaler.dashboard')->with('error', 'Please create your shop first!');
            }

            $cartKey = "wholesaler_cart_{$wholesaler->id}";
            $cart = Session::get($cartKey, []);

            $cartItems = [];
            $subtotal = 0;
            $cartCount = 0;

            foreach ($cart as $productId => $item) {
                $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                    ->where('id', $productId)
                    ->with(['category', 'brand'])
                    ->first();

                if ($product) {
                    $quantity = $item['quantity'] ?? 1;
                    $totalPrice = $product->display_price * $quantity;

                    $cartItems[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'total_price' => $totalPrice,
                        'image_url' => $product->image ? asset('storage/' . $product->image) : asset('images/default-product.png')
                    ];

                    $subtotal += $totalPrice;
                    $cartCount += $quantity;
                }
            }

            return view('wholesaler.shop.cart', compact('shop', 'cartItems', 'subtotal', 'cartCount'));

        } catch (\Exception $e) {
            \Log::error('View cart error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading cart: ' . $e->getMessage());
        }
    }

    /**
     * Enhanced add to cart with proper validation
     */
    public function addToCart(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $request->validate([
                'product_id' => 'required|exists:wholesaler_products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('id', $request->product_id)
                ->where('status', 'active')
                ->firstOrFail();

            // Check stock availability
            if ($product->qty < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$product->qty} items available in stock"
                ], 400);
            }

            $cartKey = "wholesaler_cart_{$wholesaler->id}";
            $cart = Session::get($cartKey, []);

            if (isset($cart[$product->id])) {
                $newQuantity = $cart[$product->id]['quantity'] + $request->quantity;

                if ($product->qty < $newQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => "Cannot add more items. Available: {$product->qty}"
                    ], 400);
                }

                $cart[$product->id]['quantity'] = $newQuantity;
                $cart[$product->id]['updated_at'] = now();
            } else {
                $cart[$product->id] = [
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'added_at' => now(),
                    'updated_at' => now()
                ];
            }

            Session::put($cartKey, $cart);

            // Calculate cart count and subtotal
            $cartCount = 0;
            $subtotal = 0;

            foreach ($cart as $item) {
                $p = WholesalerProduct::find($item['product_id']);
                if ($p) {
                    $cartCount += $item['quantity'];
                    $subtotal += $p->display_price * $item['quantity'];
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'cart_count' => $cartCount,
                'subtotal' => number_format($subtotal, 2),
                'cart_items' => count($cart)
            ]);

        } catch (\Exception $e) {
            \Log::error('Add to cart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding to cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     */
    public function updateCart(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $request->validate([
                'product_id' => 'required|exists:wholesaler_products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('id', $request->product_id)
                ->where('status', 'active')
                ->firstOrFail();

            // Check stock availability
            if ($product->qty < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$product->qty} items available in stock"
                ], 400);
            }

            $cartKey = "wholesaler_cart_{$wholesaler->id}";
            $cart = Session::get($cartKey, []);

            if (isset($cart[$product->id])) {
                $cart[$product->id]['quantity'] = $request->quantity;
                $cart[$product->id]['updated_at'] = now();

                Session::put($cartKey, $cart);

                // Calculate cart summary
                $cartCount = 0;
                $subtotal = 0;
                $itemSubtotal = 0;

                foreach ($cart as $item) {
                    $p = WholesalerProduct::find($item['product_id']);
                    if ($p) {
                        $cartCount += $item['quantity'];
                        $itemTotal = $p->display_price * $item['quantity'];
                        $subtotal += $itemTotal;

                        if ($p->id == $product->id) {
                            $itemSubtotal = $itemTotal;
                        }
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated successfully!',
                    'cart_count' => $cartCount,
                    'subtotal' => number_format($subtotal, 2),
                    'item_subtotal' => number_format($itemSubtotal, 2),
                    'cart_items' => count($cart)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Product not found in cart'
            ], 404);

        } catch (\Exception $e) {
            \Log::error('Update cart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $request->validate([
                'product_id' => 'required|exists:wholesaler_products,id',
            ]);

            $cartKey = "wholesaler_cart_{$wholesaler->id}";
            $cart = Session::get($cartKey, []);

            if (isset($cart[$request->product_id])) {
                unset($cart[$request->product_id]);
                Session::put($cartKey, $cart);

                // Calculate cart summary
                $cartCount = 0;
                $subtotal = 0;

                foreach ($cart as $item) {
                    $p = WholesalerProduct::find($item['product_id']);
                    if ($p) {
                        $cartCount += $item['quantity'];
                        $subtotal += $p->display_price * $item['quantity'];
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart',
                    'cart_count' => $cartCount,
                    'subtotal' => number_format($subtotal, 2),
                    'cart_items' => count($cart)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart'
            ], 404);

        } catch (\Exception $e) {
            \Log::error('Remove from cart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error removing item from cart'
            ], 500);
        }
    }

    /**
     * Clear entire cart
     */
    public function clearCart()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $cartKey = "wholesaler_cart_{$wholesaler->id}";

            Session::forget($cartKey);

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully',
                'cart_count' => 0,
                'subtotal' => 0,
                'cart_items' => 0
            ]);

        } catch (\Exception $e) {
            \Log::error('Clear cart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error clearing cart'
            ], 500);
        }
    }

    /**
     * Get cart count only
     */
    public function getCartCount()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $cartKey = "wholesaler_cart_{$wholesaler->id}";
            $cart = Session::get($cartKey, []);

            $cartCount = 0;
            foreach ($cart as $item) {
                $cartCount += $item['quantity'];
            }

            return response()->json([
                'success' => true,
                'count' => $cartCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'count' => 0
            ]);
        }
    }

    /**
     * Process checkout and create order
     * @throws \Throwable
     */
    public function processCheckout(Request $request)
    {
        DB::beginTransaction();

        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            // Validate request
            $validator = \Validator::make($request->all(), [
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'required|string|max:20',
                'shipping_address' => 'required|string|max:500',
                'payment_method' => 'required|in:cash_on_delivery,credit_card,bank_transfer',
                'notes' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $cartKey = "wholesaler_cart_{$wholesaler->id}";
            $cart = Session::get($cartKey, []);

            if (empty($cart)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty'
                ], 400);
            }

            // Calculate order total
            $subtotal = 0;
            $orderItems = [];

            // Validate stock and calculate totals
            foreach ($cart as $productId => $item) {
                $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                    ->where('id', $productId)
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw new \Exception("Product not found: {$productId}");
                }

                $quantity = $item['quantity'] ?? 1;

                if ($product->qty < $quantity) {
                    throw new \Exception("Insufficient stock for {$product->name}. Available: {$product->qty}, Requested: {$quantity}");
                }

                $itemTotal = $product->display_price * $quantity;
                $subtotal += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'unit_price' => $product->display_price,
                    'total_price' => $itemTotal,
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                // Update product stock
                $product->decrement('qty', $quantity);
            }

            // Get shop for tax calculation
            $shop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();

            // Calculate taxes and shipping
            $taxRate = $shop->tax_rate ?? 5;
            $tax = $subtotal * ($taxRate / 100);
            $shipping = $this->calculateShipping($subtotal);
            $total = $subtotal + $tax + $shipping;

            // Generate unique order number
            $orderNumber = $this->generateOrderNumber();

            // Check if order number already exists
            while (Order::where('order_no', $orderNumber)->exists()) {
                $orderNumber = $this->generateOrderNumber();
            }

            // Create order
            $order = Order::create([
                'wholesaler_id' => $wholesaler->id,
                'order_no' => $orderNumber,
                'order_number' => $orderNumber, // Add both fields if needed
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total_amount' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method == 'cash_on_delivery' ? 'pending' : 'paid',
                'status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'notes' => $request->notes,
                'order_date' => now(),
            ]);

            // Create order items
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                ]);
            }

            // Clear cart
            Session::forget($cartKey);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_id' => $order->id,
                'order_number' => $order->order_no,
                'redirect' => route('wholesaler.orders.show', $order->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout Process Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error processing checkout: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = date('Ymd');
        $random = strtoupper(Str::random(6));

        return $prefix . '-' . $date . '-' . $random;
    }

    /**
     * Calculate shipping cost
     */

    /**
     * Buy now direct checkout
     */
    public function buyNow(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $request->validate([
                'product_id' => 'required|exists:wholesaler_products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            // Create a temporary cart with just this product
            $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('id', $request->product_id)
                ->where('status', 'active')
                ->firstOrFail();

            // Check stock availability
            if ($product->qty < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$product->qty} items available in stock"
                ], 400);
            }

            // Store in session for buy now
            $buyNowKey = "wholesaler_buy_now_{$wholesaler->id}";
            Session::put($buyNowKey, [
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);

            return response()->json([
                'success' => true,
                'redirect' => route('wholesaler.shop.checkout')
            ]);

        } catch (\Exception $e) {
            \Log::error('Buy now error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing buy now: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send order notification
     */
    private function sendOrderNotification($order, $wholesaler)
    {
        try {
            \Log::info("Order #{$order->order_number} placed by {$wholesaler->name}");

            // Send email notification
            if (class_exists('App\Mail\OrderPlaced')) {
                Mail::to($wholesaler->email)->send(new OrderPlaced($order));
            }

        } catch (\Exception $e) {
            \Log::error('Order notification error: ' . $e->getMessage());
        }
    }

    /**
     * Search products with AJAX support
     */
    public function search(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $shop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();

            $query = $request->get('query', '');

            $products = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'active')
                ->with(['category', 'brand'])
                ->withCount(['reviews as average_rating' => function($query) {
                    $query->select(DB::raw('COALESCE(AVG(rating), 0)'));
                }])
                ->withCount(['reviews as total_reviews' => function($query) {
                    $query->where('status', 'active');
                }])
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhere('code', 'like', "%{$query}%")
                        ->orWhere('features', 'like', "%{$query}%");
                })
                ->paginate(12);

            // Get categories and brands for filters
            $categories = Category::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'active')
                ->get();

            $brands = Brand::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'active')
                ->get();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'html' => view('wholesaler.shop.partials.product-grid', compact('products'))->render()
                ]);
            }

            return view('wholesaler.shop.shop', compact(
                'shop',
                'products',
                'categories',
                'brands'
            ))->with('searchQuery', $query);

        } catch (\Exception $e) {
            \Log::error('Search error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search error: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Search error: ' . $e->getMessage());
        }
    }

    /**
     * Apply coupon code
     */
    public function applyCoupon(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $request->validate([
                'coupon_code' => 'required|string|max:50',
            ]);

            // Here you would typically validate the coupon code against your database
            $couponCode = strtoupper($request->coupon_code);
            $validCoupons = ['WELCOME10', 'SAVE20', 'WHOLESALE15'];

            if (!in_array($couponCode, $validCoupons)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid coupon code'
                ], 400);
            }

            $discount = 0;
            $cartKey = "wholesaler_cart_{$wholesaler->id}";
            $cart = Session::get($cartKey, []);

            $subtotal = 0;
            foreach ($cart as $item) {
                $product = WholesalerProduct::find($item['product_id']);
                if ($product) {
                    $subtotal += $product->display_price * $item['quantity'];
                }
            }

            if ($couponCode == 'WELCOME10') {
                $discount = $subtotal * 0.10;
            } elseif ($couponCode == 'SAVE20') {
                $discount = $subtotal * 0.20;
            } elseif ($couponCode == 'WHOLESALE15') {
                $discount = $subtotal * 0.15;
            }

            // Store coupon in session
            Session::put("wholesaler_coupon_{$wholesaler->id}", [
                'code' => $couponCode,
                'discount' => $discount,
                'applied_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Coupon applied successfully!',
                'discount' => number_format($discount, 2),
                'coupon_code' => $couponCode
            ]);

        } catch (\Exception $e) {
            \Log::error('Apply coupon error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error applying coupon: ' . $e->getMessage()
            ], 500);
        }
    }
}
