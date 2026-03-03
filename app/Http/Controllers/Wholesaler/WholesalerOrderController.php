<?php
// app/Http/Controllers/Wholesaler/WholesalerOrderController.php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use App\Models\WholesalerOrder;
use App\Models\WholesalerProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Exception;

class WholesalerOrderController extends Controller
{
    public function index(Request $request)
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        try {
            $code = Schema::hasTable('wholesaler_orders')
                ? WholesalerOrder::generateOrderId()
                : 'ORD/00001';
        } catch (Exception $e) {
            $code = 'ORD/00001';
            Log::error('Error generating order ID: ' . $e->getMessage());
        }

        $productCode        = $request->get('product_code');
        $productName        = $request->get('product_name');
        $productPrice       = $request->get('product_price');
        $productStock       = $request->get('product_stock');
        $quantity           = $request->get('quantity', 1);
        $productImage       = $request->get('product_image');
        $productDescription = $request->get('product_description');
        $productCategory    = $request->get('product_category');
        $productId          = $request->get('product_id');

        return view('wholesaler.order.new_order', compact(
            'wholesaler', 'code',
            'productCode', 'productName', 'productPrice', 'productStock',
            'quantity', 'productImage', 'productDescription', 'productCategory', 'productId'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'platform' => 'nullable|string',
            'product_id' => 'required|exists:wholesaler_products,id',
            'product_code' => 'required|string',
            'product_name' => 'required|string',
            'product_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_phone_2' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string',
            'district' => 'nullable|string|max:100',
            'remark' => 'nullable|string',
            'payment_method' => 'required|string',
            'product_image' => 'nullable|string',
            'product_description' => 'nullable|string',
            'product_category' => 'nullable|string',
        ]);

        $wholesaler = Auth::guard('wholesaler')->user();

        if (!$wholesaler) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Please login to continue.'], 401);
            }
            return redirect()->route('wholesaler.login')->with('error', 'Please login to continue.');
        }

        try {
            DB::beginTransaction();
            $result = $this->handleSingleOrder($request, $wholesaler);
            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order #' . $result['order']->order_number . ' created successfully!',
                    'order' => $result['order'],
                    'redirect_url' => route('wholesaler.order.pending-order')
                ]);
            }

            return redirect()->route('wholesaler.order.pending-order')
                ->with('success', 'Order #' . $result['order']->order_number . ' created successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating order: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Error creating order: ' . $e->getMessage()], 500);
            }

            return redirect()->route('wholesaler.order.new')->withInput()->with('error', 'Error creating order: ' . $e->getMessage());
        }
    }

    private function handleSingleOrder(Request $request, $wholesaler)
    {
        $product = WholesalerProduct::findOrFail($request->product_id);
        $quantity = $request->quantity ?? 1;

        if ((int)$product->qty < (int)$quantity) {
            throw new Exception("Insufficient stock. Available: {$product->qty}, Requested: {$quantity}");
        }

        $orderId = WholesalerOrder::generateOrderId();
        $productPrice = floatval($request->product_price);
        $totalAmount = $productPrice * (int)$quantity;

        $orderData = [
            'wholesaler_id' => $wholesaler->id,
            'order_number' => $orderId,
            'product_id' => $request->product_id,
            'product_code' => $request->product_code,
            'product_name' => $request->product_name,
            'product_description' => $request->product_description ?? null,
            'product_category' => $request->product_category ?? null,
            'product_price' => $productPrice,
            'product_image' => $request->product_image ?? null,
            'quantity' => (int)$quantity,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_phone_2' => $request->customer_phone_2 ?? null,
            'customer_address' => $request->customer_address,
            'district' => $request->district,
            'platform' => $request->platform ?? 'manual',
            'remark' => $request->remark,
            'order_date' => $request->date,
            'status' => 'pending',
            'subtotal' => $totalAmount,
            'total_amount' => $totalAmount,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending'
        ];

        $order = WholesalerOrder::create($orderData);

        // decrement stock
        $product->decrement('qty', (int)$quantity);

        Log::info('Order created successfully', [
            'order_id' => $order->order_number,
            'wholesaler_id' => $wholesaler->id,
            'product_id' => $product->id,
            'quantity' => $quantity
        ]);

        return ['success' => true, 'order' => $order];
    }

    public function getProductDetails($code)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            if (!$wholesaler) {
                return response()->json(['success' => false, 'message' => 'Wholesaler not authenticated'], 401);
            }

            $decodedCode = urldecode($code);

            $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('code', $decodedCode)
                ->with('category')
                ->first();

            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Product not found with code: ' . $decodedCode], 404);
            }

            return response()->json([
                'success' => true,
                'product' => [
                    'id'            => $product->id,
                    'name'          => $product->name,
                    'code'          => $product->code,
                    'display_price' => (float) $product->display_price,
                    'stock_price'   => (float) $product->stock_price,
                    'price'         => (float) $product->display_price,
                    'qty'           => (int) $product->qty,
                    'min_qty'       => (int) ($product->min_qty ?? 1),
                    'category'      => $product->category->name ?? 'Uncategorized',
                    'description'   => $product->description ?? '',
                    'image'         => $product->image ? asset('storage/' . $product->image) : null,
                ],
            ]);
        } catch (Exception $e) {
            Log::error('Get product details error', ['code' => $code, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error fetching product details: ' . $e->getMessage()], 500);
        }
    }

    public function pendingOrder()
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        if (!Schema::hasTable('wholesaler_orders')) {
            $orders = collect();
            $count = 0;
        } else {
            $orders = WholesalerOrder::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();
            $count = $orders->count();
        }

        return view('wholesaler.order.pending_order_list', compact('orders', 'count'));
    }

    public function completeOrder()
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        if (!Schema::hasTable('wholesaler_orders')) {
            $orders = collect();
            $count = 0;
        } else {
            $orders = WholesalerOrder::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'complete')
                ->orderBy('delivery_date', 'desc')
                ->orderBy('updated_at', 'desc')
                ->get();
            $count = $orders->count();
        }

        return view('wholesaler.order.complete_order_list', compact('orders', 'count'));
    }

    public function returnOrder()
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        if (!Schema::hasTable('wholesaler_orders')) {
            $orders = collect();
            $count = 0;
        } else {
            $orders = WholesalerOrder::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'return')
                ->orderBy('return_date', 'desc')
                ->orderBy('updated_at', 'desc')
                ->get();
            $count = $orders->count();
        }

        return view('wholesaler.order.return_order_list', compact('orders', 'count'));
    }

    public function reschedulingOrder()
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        if (!Schema::hasTable('wholesaler_orders')) {
            $orders = collect();
            $count = 0;
        } else {
            $orders = WholesalerOrder::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'rescheduling')
                ->orderBy('rescheduling_date', 'asc')
                ->orderBy('updated_at', 'desc')
                ->get();
            $count = $orders->count();
        }

        $upcomingCount = 0;
        if ($count > 0) {
            $upcomingCount = $orders->filter(
                fn($o) => $o->rescheduling_date && \Carbon\Carbon::parse($o->rescheduling_date)->isFuture()
            )->count();
        }

        return view('wholesaler.order.rescheduling_order_list', compact('orders', 'count', 'upcomingCount'));
    }

    public function show($id)
    {
        if (!Schema::hasTable('wholesaler_orders')) abort(404, 'Orders table not found');

        $order = WholesalerOrder::where('wholesaler_id', Auth::guard('wholesaler')->id())
            ->findOrFail($id);

        return view('wholesaler.order.order-show', compact('order'));
    }

    public function update(Request $request, $id)
    {
        if (!Schema::hasTable('wholesaler_orders')) {
            return redirect()->back()->with('error', 'Orders table not found');
        }

        $order = WholesalerOrder::where('wholesaler_id', Auth::guard('wholesaler')->id())->findOrFail($id);

        $request->validate([
            'status'            => 'required|in:pending,complete,return,rescheduling',
            'delivery_date'     => 'nullable|date',
            'rescheduling_date' => 'nullable|date',
            'return_date'       => 'nullable|date',
            'remark'            => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'status' => $request->status,
                'remark' => $request->remark ?? $order->remark,
                'delivery_date' => null,
                'return_date' => null,
                'rescheduling_date' => null,
            ];

            switch ($request->status) {
                case 'complete':
                    $data['delivery_date'] = $request->delivery_date ?? now();
                    $data['payment_status'] = 'paid';
                    break;

                case 'return':
                    $data['return_date'] = $request->return_date ?? now();
                    $data['payment_status'] = 'refunded';
                    break;

                case 'rescheduling':
                    $data['rescheduling_date'] = $request->rescheduling_date ?? now()->addDays(3);
                    break;

                default:
                    // pending -> keep only cleared dates, do nothing else
                    break;
            }

            $order->update($data);

            DB::commit();

            $redirectUrl = match ($request->status) {
                'complete' => route('wholesaler.order.complete-order'),
                'return' => route('wholesaler.order.return-order'),
                'rescheduling' => route('wholesaler.order.rescheduling-order'),
                default => route('wholesaler.order.pending-order'),
            };

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order updated successfully!',
                    'redirect_url' => $redirectUrl
                ]);
            }

            return redirect($redirectUrl)->with('success', 'Order updated successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating order: ' . $e->getMessage(), [
                'order_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating order: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error updating order: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        if (!Schema::hasTable('wholesaler_orders')) {
            return redirect()->back()->with('error', 'Orders table not found');
        }

        $wholesaler = Auth::guard('wholesaler')->user();
        $order = WholesalerOrder::where('wholesaler_id', $wholesaler->id)->findOrFail($id);

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending orders can be deleted.');
        }

        try {
            DB::beginTransaction();

            if (!empty($order->product_id)) {
                $product = WholesalerProduct::find($order->product_id);
                if ($product) $product->increment('qty', $order->quantity ?? 1);
            }

            $order->delete();

            DB::commit();

            return redirect()->route('wholesaler.order.pending-order')
                ->with('success', 'Order deleted successfully! Stock restored.');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error deleting order: ' . $e->getMessage());
        }
    }
}
