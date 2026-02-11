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
     * Display the shop page with products - SHOP NOT REQUIRED
     */
    public function index(Request $request)
    {
        try {
            $seller = Auth::guard('seller')->user();

            // Get products even if shop doesn't exist
            $query = SellerProduct::where('seller_id', $seller->id)
                ->with(['category', 'brand'])
                ->where('status', 'active');

            // Search functionality
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('features', 'like', "%{$search}%");
                });
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

            // Get shop if exists, but don't require it
            $shop = SellerShop::where('seller_id', $seller->id)->first();

            return view('seller.shop.shop', compact('shop', 'products'));

        } catch (\Exception $e) {
            \Log::error('Shop index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading products: ' . $e->getMessage());
        }
    }

    /**
     * Display a single product - SHOP NOT REQUIRED
     */
    public function showProduct($id)
    {
        try {
            $seller = Auth::guard('seller')->user();

            $product = SellerProduct::where('seller_id', $seller->id)
                ->with(['category', 'brand'])
                ->findOrFail($id);

            // Get shop if exists, but don't require it
            $shop = SellerShop::where('seller_id', $seller->id)->first();

            $relatedProducts = SellerProduct::where('seller_id', $seller->id)
                ->where('id', '!=', $id)
                ->where('status', 'active')
                ->limit(4)
                ->get();

            return view('seller.shop.product-details', compact('shop', 'product', 'relatedProducts'));

        } catch (\Exception $e) {
            \Log::error('Show product error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Product not found: ' . $e->getMessage());
        }
    }

    /**
     * Process Order Now - SIMPLIFIED VERSION
     */
    public function orderNow(Request $request)
    {
        DB::beginTransaction();

        try {
            $seller = Auth::guard('seller')->user();

            // Validate request
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:seller_products,id',
                'quantity' => 'required|integer|min:1',
                'payment_method' => 'required|in:cash_on_delivery,credit_card,bank_transfer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $product = SellerProduct::where('seller_id', $seller->id)
                ->where('id', $request->product_id)
                ->firstOrFail();

            // Check stock availability
            if ($product->qty < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$product->qty} items available in stock"
                ], 400);
            }

            // Calculate order total
            $unitPrice = $product->display_price;
            $subtotal = $unitPrice * $request->quantity;

            // Fixed shipping cost (you can modify this)
            $shipping = 100;
            $total = $subtotal + $shipping;

            // Generate unique order number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            while (SellerOrder::where('order_number', $orderNumber)->exists()) {
                $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }

            // Create order - simplified
            $order = SellerOrder::create([
                'seller_id' => $seller->id,
                'order_number' => $orderNumber,
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'total_amount' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'status' => 'pending',
                'shipping_address' => 'Address not set',
                'customer_name' => 'Customer Name',
                'customer_email' => 'customer@example.com',
                'customer_phone' => '0000000000',
            ]);

            // Create order item
            SellerOrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $request->quantity,
                'unit_price' => $unitPrice,
                'total_price' => $subtotal,
            ]);

            // Update product stock
            $product->decrement('qty', $request->quantity);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'redirect' => route('seller.orders.show', $order->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order Now Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error processing order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Quick Order - SIMPLIFIED
     */
    public function quickOrder(Request $request)
    {
        try {
            $seller = Auth::guard('seller')->user();

            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:seller_products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please select valid quantity'
                ], 422);
            }

            $product = SellerProduct::findOrFail($request->product_id);

            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->display_price,
                    'quantity' => $request->quantity,
                    'total' => $product->display_price * $request->quantity
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Quick Order Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
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

            // Get shop if exists, but don't require it
            $shop = SellerShop::where('seller_id', $seller->id)->first();

            $query = $request->get('query', '');

            $products = SellerProduct::where('seller_id', $seller->id)
                ->where('status', 'active')
                ->with(['category', 'brand'])
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                })
                ->paginate(12);

            return view('seller.shop.shop', compact('shop', 'products'))->with('searchQuery', $query);

        } catch (\Exception $e) {
            \Log::error('Search error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Search error: ' . $e->getMessage());
        }
    }
}
