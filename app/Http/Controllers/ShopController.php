<?php

namespace App\Http\Controllers;

use App\Models\WholesalerProduct;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    /**
     * Get categories with product count
     */
    private function getCategoriesWithCount()
    {
        return Category::select('categories.id', 'categories.name')
            ->leftJoin('wholesaler_products', 'categories.id', '=', 'wholesaler_products.category_id')
            ->where(function($query) {
                $query->where('wholesaler_products.status', 'active')
                    ->orWhereNull('wholesaler_products.id');
            })
            ->groupBy('categories.id', 'categories.name')
            ->selectRaw('COUNT(wholesaler_products.id) as products_count')
            ->get();
    }

    /**
     * Get brands with product count
     */
    private function getBrandsWithCount()
    {
        return Brand::select('brands.id', 'brands.name')
            ->leftJoin('wholesaler_products', 'brands.id', '=', 'wholesaler_products.brand_id')
            ->where(function($query) {
                $query->where('wholesaler_products.status', 'active')
                    ->orWhereNull('wholesaler_products.id');
            })
            ->groupBy('brands.id', 'brands.name')
            ->selectRaw('COUNT(wholesaler_products.id) as products_count')
            ->get();
    }

    /**
     * Display shop products with pagination and filters
     */
    public function index(Request $request)
    {
        $query = WholesalerProduct::with(['category', 'brand', 'wholesaler'])
            ->where('status', 'active')
            ->latest();

        // Apply filters
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand_id', $request->brand);
        }

        if ($request->has('price_min') && $request->has('price_max')) {
            $query->whereBetween('display_price', [$request->price_min, $request->price_max]);
        }

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('display_price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('display_price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'newest':
                    $query->latest();
                    break;
            }
        }

        $products = $query->paginate(12);

        // Get categories and brands with product count
        $categories = $this->getCategoriesWithCount();
        $brands = $this->getBrandsWithCount();

        // Featured products (products with high stock or specific criteria)
        $featuredProducts = WholesalerProduct::where('status', 'active')
            ->where('qty', '>', 10)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('wholesaler.shop.index', compact('products', 'categories', 'brands', 'featuredProducts'));
    }

    /**
     * Display single product details
     */
    public function show($id)
    {
        $product = WholesalerProduct::with(['category', 'brand', 'wholesaler.shop'])
            ->where('status', 'active')
            ->findOrFail($id);

        // Related products from same category
        $relatedProducts = WholesalerProduct::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->take(4)
            ->get();

        return view('shop.product-details', compact('product', 'relatedProducts'));
    }

    /**
     * Display products by category
     */
    public function category($id)
    {
        $category = Category::findOrFail($id);

        $products = WholesalerProduct::with(['category', 'brand', 'wholesaler'])
            ->where('category_id', $category->id)
            ->where('status', 'active')
            ->latest()
            ->paginate(12);

        $categories = $this->getCategoriesWithCount();
        $brands = $this->getBrandsWithCount();

        return view('shop.category', compact('category', 'products', 'categories', 'brands'));
    }

    /**
     * Display products by brand
     */
    public function brand($id)
    {
        $brand = Brand::findOrFail($id);

        $products = WholesalerProduct::with(['category', 'brand', 'wholesaler'])
            ->where('brand_id', $brand->id)
            ->where('status', 'active')
            ->latest()
            ->paginate(12);

        $categories = $this->getCategoriesWithCount();
        $brands = $this->getBrandsWithCount();

        return view('shop.brand', compact('brand', 'products', 'categories', 'brands'));
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        $query = $request->get('query');

        $products = WholesalerProduct::with(['category', 'brand', 'wholesaler'])
            ->where('status', 'active')
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    ->orWhere('code', 'LIKE', "%{$query}%")
                    ->orWhere('barcode', 'LIKE', "%{$query}%");
            })
            ->paginate(12);

        $categories = $this->getCategoriesWithCount();
        $brands = $this->getBrandsWithCount();

        return view('shop.search', compact('products', 'query', 'categories', 'brands'));
    }

    /**
     * Filter products via AJAX
     */
    public function filter(Request $request)
    {
        $query = WholesalerProduct::with(['category', 'brand', 'wholesaler'])
            ->where('status', 'active');

        if ($request->categories) {
            $query->whereIn('category_id', $request->categories);
        }

        if ($request->brands) {
            $query->whereIn('brand_id', $request->brands);
        }

        if ($request->price_min && $request->price_max) {
            $query->whereBetween('display_price', [$request->price_min, $request->price_max]);
        }

        if ($request->wholesaler_id) {
            $query->where('wholesaler_id', $request->wholesaler_id);
        }

        $products = $query->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Display cart
     */
    public function cart()
    {
        $cart = Session::get('cart', []);
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $tax = $subtotal * 0.1; // 10% tax
        $shipping = 500; // Flat rate shipping
        $total = $subtotal + $tax + $shipping;

        return view('shop.cart', compact('cart', 'subtotal', 'tax', 'shipping', 'total'));
    }

    /**
     * Add product to cart
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:wholesaler_products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = WholesalerProduct::with('wholesaler')->findOrFail($request->product_id);

        // Check if quantity meets minimum order requirement
        if ($request->quantity < $product->min_order_quantity) {
            return response()->json([
                'success' => false,
                'message' => "Minimum order quantity is {$product->min_order_quantity} units."
            ], 400);
        }

        // Check stock availability
        if ($request->quantity > $product->qty) {
            return response()->json([
                'success' => false,
                'message' => "Only {$product->qty} units available in stock."
            ], 400);
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$product->id])) {
            $newQuantity = $cart[$product->id]['quantity'] + $request->quantity;

            // Validate total quantity
            if ($newQuantity > $product->qty) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot add more. Stock limit reached."
                ], 400);
            }

            $cart[$product->id]['quantity'] = $newQuantity;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'code' => $product->code,
                'price' => $product->wholesale_price,
                'display_price' => $product->display_price,
                'image' => $product->image,
                'quantity' => $request->quantity,
                'min_order_quantity' => $product->min_order_quantity,
                'wholesaler_id' => $product->wholesaler_id,
                'wholesaler_name' => $product->wholesaler->name ?? 'Unknown'
            ];
        }

        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => count($cart)
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:0'
        ]);

        $cart = Session::get('cart', []);

        if (isset($cart[$request->product_id])) {
            $product = WholesalerProduct::find($request->product_id);

            if ($request->quantity > 0) {
                if ($request->quantity < $product->min_order_quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => "Minimum order quantity is {$product->min_order_quantity} units."
                    ], 400);
                }

                if ($request->quantity > $product->qty) {
                    return response()->json([
                        'success' => false,
                        'message' => "Only {$product->qty} units available."
                    ], 400);
                }

                $cart[$request->product_id]['quantity'] = $request->quantity;
            } else {
                unset($cart[$request->product_id]);
            }

            Session::put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully!',
                'cart_count' => count($cart)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart'
        ], 404);
    }

    /**
     * Remove product from cart
     */
    public function removeFromCart($id)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart!',
                'cart_count' => count($cart)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart'
        ], 404);
    }

    /**
     * Clear entire cart
     */
    public function clearCart()
    {
        Session::forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully!'
        ]);
    }

    /**
     * Display checkout page
     */
    public function checkout()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty!');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $tax = $subtotal * 0.1;
        $shipping = 500;
        $total = $subtotal + $tax + $shipping;

        return view('shop.checkout', compact('cart', 'subtotal', 'tax', 'shipping', 'total'));
    }

    /**
     * Get cart count for header
     */
    public function getCartCount()
    {
        $cart = Session::get('cart', []);

        return response()->json([
            'count' => count($cart)
        ]);
    }
}
