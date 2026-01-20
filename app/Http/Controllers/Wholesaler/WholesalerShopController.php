<?php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use App\Models\WholesalerShop;
use App\Models\WholesalerProduct;
use App\Models\WholesalerOrder;
use App\Models\WholesalerOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

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

            $query = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->with(['category', 'brand'])
                ->where('status', 'active');

            $query->withCount(['reviews as average_rating' => function ($q) {
                $q->select(DB::raw('COALESCE(AVG(rating), 0)'));
            }])
                ->withCount(['reviews as total_reviews' => function ($q) {
                    $q->where('status', 'active');
                }]);

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

            // Sort options - use display_price for sorting
            $sortOptions = [
                'newest' => ['created_at', 'desc'],
                'oldest' => ['created_at', 'asc'],
                'price_low' => ['display_price', 'asc'],
                'price_high' => ['display_price', 'desc'],
                'name_asc' => ['name', 'asc'],
                'name_desc' => ['name', 'desc'],
            ];

            $sort = $request->get('sort', 'newest');
            if (array_key_exists($sort, $sortOptions)) {
                $query->orderBy($sortOptions[$sort][0], $sortOptions[$sort][1]);
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $products = $query->paginate(12)->withQueryString();

            return view('wholesaler.shop.shop', compact('shop', 'products'));

        } catch (\Exception $e) {
            \Log::error('Shop index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading shop: ' . $e->getMessage());
        }
    }

    /**
     * Display a single product
     */
    public function showProduct($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->with(['category', 'brand'])
                ->withCount(['reviews as average_rating' => function ($query) {
                    $query->select(DB::raw('COALESCE(AVG(rating), 0)'));
                }])
                ->withCount(['reviews as total_reviews' => function ($query) {
                    $query->where('status', 'active');
                }])
                ->findOrFail($id);

            $shop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();

            $relatedProducts = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('id', '!=', $id)
                ->where('status', 'active')
                ->limit(4)
                ->get();

            return view('wholesaler.shop.product-details', compact('shop', 'product', 'relatedProducts'));

        } catch (\Exception $e) {
            \Log::error('Show product error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Product not found: ' . $e->getMessage());
        }
    }

    /**
     * Process Order Now - Use display_price for calculations
     */
    public function orderNow(Request $request)
    {
        DB::beginTransaction();

        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            // Validate request
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:wholesaler_products,id',
                'quantity' => 'required|integer|min:1',
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

            $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('id', $request->product_id)
                ->lockForUpdate()
                ->firstOrFail();

            // Check stock availability
            if ($product->qty < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$product->qty} items available in stock"
                ], 400);
            }

            // Get shop for tax calculation
            $shop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();

            // Calculate order total using display_price
            $unitPrice = $product->display_price ?? $product->selling_price;
            $subtotal = $unitPrice * $request->quantity;
            $taxRate = $shop->tax_rate ?? 5;
            $tax = $subtotal * ($taxRate / 100);
            $shipping = $this->calculateShipping($subtotal);
            $total = $subtotal + $tax + $shipping;

            // Generate unique order number
            $orderNumber = $this->generateOrderNumber();
            while (WholesalerOrder::where('order_number', $orderNumber)->exists()) {
                $orderNumber = $this->generateOrderNumber();
            }

            // Create order
            $order = WholesalerOrder::create([
                'wholesaler_id' => $wholesaler->id,
                'order_number' => $orderNumber,
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
            ]);

            // Create order item - use display_price
            WholesalerOrderItem::create([
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
                'redirect' => route('wholesaler.orders.show', $order->id)
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
     * Calculate shipping cost based on subtotal
     */
    private function calculateShipping($subtotal)
    {
        if ($subtotal > 5000) return 0;
        if ($subtotal <= 1000) return 200;
        if ($subtotal <= 3000) return 150;
        return 100;
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
    }

    /**
     * Search products
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
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                })
                ->paginate(12);

            return view('wholesaler.shop.shop', compact('shop', 'products'))->with('searchQuery', $query);

        } catch (\Exception $e) {
            \Log::error('Search error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Search error: ' . $e->getMessage());
        }
    }


// WholesalerOrderController.php - Add missing method
// In WholesalerOrderController, add this after the index() method:

    /**
     * Calculate total revenue for completed orders
     */
    private function calculateTotalRevenue($wholesalerId)
    {
        return WholesalerOrder::where('wholesaler_id', $wholesalerId)
            ->where('status', 'completed')
            ->sum('total_amount');
    }
}
