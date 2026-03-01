<?php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use App\Models\WholesalerOrder;
use App\Models\WholesalerProduct;
use App\Models\WholesalerCall;
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
            'call_status' => 'nullable|string|max:50',
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

        if ($product->qty < $quantity) {
            throw new Exception("Insufficient stock. Available: {$product->qty}, Requested: {$quantity}");
        }

        $orderId = WholesalerOrder::generateOrderId();
        $productPrice = floatval($request->product_price);
        $totalAmount = $productPrice * $quantity;

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
            'quantity' => $quantity,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_phone_2' => $request->customer_phone_2 ?? null,
            'customer_address' => $request->customer_address,
            'district' => $request->district,
            'platform' => $request->platform ?? 'manual',
            'call_status' => $request->call_status ?? 'Pending Call',
            'remark' => $request->remark,
            'order_date' => $request->date,
            'status' => 'pending',
            'subtotal' => $totalAmount,
            'total_amount' => $totalAmount,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending'
        ];

        $order = WholesalerOrder::create($orderData);
        $product->decrement('qty', $quantity);

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

    public function csvUploadPage()
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        return view('wholesaler.order.csv_upload', compact('wholesaler'));
    }

    /**
     * CRITICAL FIX: CSV Insert - Now creates BOTH orders AND calls
     * This ensures data appears in both pending orders AND pending calls
     */
    /**
     * FIXED: CSV Insert - Creates BOTH orders AND calls
     * Now properly handles all fields and redirects to pending calls
     */
    public function csvInsert(Request $request)
    {
        $request->validate([
            'leads'                    => 'required|array',
            'leads.*.customer_name'    => 'required|string',
            'leads.*.customer_phone'   => 'required|string',
            'leads.*.customer_address' => 'nullable|string',
            'leads.*.city'             => 'nullable|string',
            'leads.*.platform'         => 'nullable|string',
            'leads.*.ad_name'          => 'nullable|string',
            'leads.*.campaign_name'    => 'nullable|string',
            'leads.*.created_time'     => 'nullable|string',
            'leads.*.lead_status'      => 'nullable|string',
            'leads.*.call_status'      => 'nullable|string',
            'product_code'             => 'required|string',
            'call_status'              => 'nullable|string',
        ]);

        $wholesaler = Auth::guard('wholesaler')->user();

        try {
            DB::beginTransaction();

            $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('code', $request->product_code)
                ->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product "' . $request->product_code . '" not found in your inventory.'
                ], 422);
            }

            $successCount = 0;
            $errorCount = 0;
            $errors = [];
            $defaultCallStatus = $request->call_status ?? 'Pending';
            $tableColumns = Schema::getColumnListing('wholesaler_orders');

            // Track status counts for redirect
            $statusCounts = [];

            foreach ($request->leads as $index => $lead) {
                try {
                    // Check stock availability
                    if ($product->qty < 1) {
                        $errors[] = "Row " . ($index + 1) . ": Insufficient stock";
                        $errorCount++;
                        continue;
                    }

                    // Generate order ID
                    $orderId = WholesalerOrder::generateOrderId();

                    // Build remark from CSV data
                    $remarkParts = [];
                    if (!empty($lead['ad_name'])) $remarkParts[] = 'Ad: ' . $lead['ad_name'];
                    if (!empty($lead['campaign_name'])) $remarkParts[] = 'Campaign: ' . $lead['campaign_name'];
                    if (!empty($lead['created_time'])) $remarkParts[] = 'Lead Date: ' . $lead['created_time'];
                    if (!empty($lead['lead_status'])) $remarkParts[] = 'FB Status: ' . $lead['lead_status'];

                    $customerAddress = $lead['customer_address'] ?? null;
                    $callStatus = $lead['call_status'] ?? $defaultCallStatus;

                    // Track status for redirect
                    $statusCounts[$callStatus] = ($statusCounts[$callStatus] ?? 0) + 1;

                    // ===== CREATE ORDER =====
                    $orderData = [
                        'wholesaler_id'    => $wholesaler->id,
                        'order_number'     => $orderId,
                        'product_id'       => $product->id,
                        'product_code'     => $product->code,
                        'product_name'     => $product->name,
                        'product_price'    => $product->display_price,
                        'product_image'    => $product->image,
                        'customer_name'    => $lead['customer_name'],
                        'customer_phone'   => $lead['customer_phone'],
                        'customer_phone_2' => $lead['customer_phone_02'] ?? null,
                        'customer_address' => $customerAddress,
                        'district'         => $lead['city'] ?? null,
                        'platform'         => $lead['platform'] ?? 'fb',
                        'call_status'      => $callStatus,
                        'remark'           => !empty($remarkParts) ? implode(' | ', $remarkParts) : null,
                        'order_date'       => now(),
                        'status'           => 'pending',
                        'quantity'         => 1,
                        'subtotal'         => $product->display_price,
                        'total_amount'     => $product->display_price,
                        'payment_method'   => 'cash',
                        'payment_status'   => 'pending',
                    ];

                    $order = WholesalerOrder::create($orderData);

                    // ===== CREATE CALL RECORD =====
                    $callData = [
                        'wholesaler_id'     => $wholesaler->id,
                        'product_id'        => $product->id,
                        'order_code'        => $orderId,
                        'call_number'       => WholesalerCall::generateCallNumber(),
                        'date'              => now()->toDateString(),
                        'customer_name'     => $lead['customer_name'],
                        'customer_phone_01' => $lead['customer_phone'],
                        'customer_phone_02' => $lead['customer_phone_02'] ?? null,
                        'customer_address'  => $customerAddress,
                        'city'              => $lead['city'] ?? null,
                        'item_code'         => $product->code,
                        'item_name'         => $product->name,
                        'qty'               => 1,
                        'price'             => $product->display_price,
                        'total'             => $product->display_price,
                        'status'            => $callStatus,
                        'status_date'       => now(),
                        'platform'          => $lead['platform'] ?? 'fb',
                        'ad_name'           => $lead['ad_name'] ?? null,
                        'campaign_name'     => $lead['campaign_name'] ?? null,
                        'lead_status'       => $lead['lead_status'] ?? null,
                        'remark'            => !empty($remarkParts) ? implode(' | ', $remarkParts) : null,
                        'download_status'   => false,
                    ];

                    WholesalerCall::create($callData);

                    // Decrement stock
                    $product->decrement('qty', 1);
                    $successCount++;

                } catch (Exception $e) {
                    $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
                    $errorCount++;
                    Log::error('CSV row error: ' . $e->getMessage());
                }
            }

            DB::commit();

            // Determine redirect based on most common status
            $redirectStatus = 'Pending';
            if (!empty($statusCounts)) {
                arsort($statusCounts);
                $redirectStatus = key($statusCounts);
            }

            $message = "Successfully created {$successCount} orders and calls.";
            if ($errorCount > 0) $message .= " {$errorCount} failed.";

            return response()->json([
                'success'      => true,
                'message'      => $message,
                'successCount' => $successCount,
                'errorCount'   => $errorCount,
                'errors'       => $errors,
                'redirect_url' => route('wholesaler.call.' . $this->getStatusRoute($redirectStatus)),
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('CSV bulk insert error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error processing CSV data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Helper to get route based on status
     */
    private function getStatusRoute($status)
    {
        $routeMap = [
            'Pending'    => 'index',
            'Conform'    => 'conform',
            'Reject'     => 'reject',
            'Not Answer' => 'not_answer_call_list',
            'Other'      => 'other_call_list',
        ];
        return $routeMap[$status] ?? 'index';
    }

    public function pendingOrder()
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        if (!Schema::hasTable('wholesaler_orders')) {
            $orders = collect(); $count = 0;
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
            $orders = collect(); $count = 0;
        } else {
            $query = WholesalerOrder::where('wholesaler_id', $wholesaler->id)->where('status', 'complete');
            $col   = Schema::hasColumn('wholesaler_orders', 'delivery_date') ? 'delivery_date' : 'updated_at';
            $orders = $query->orderBy($col, 'desc')->get();
            $count  = $orders->count();
        }
        return view('wholesaler.order.complete_order_list', compact('orders', 'count'));
    }

    public function returnOrder()
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        if (!Schema::hasTable('wholesaler_orders')) {
            $orders = collect(); $count = 0;
        } else {
            $query = WholesalerOrder::where('wholesaler_id', $wholesaler->id)->where('status', 'return');
            $col   = Schema::hasColumn('wholesaler_orders', 'return_date') ? 'return_date' : 'updated_at';
            $orders = $query->orderBy($col, 'desc')->get();
            $count  = $orders->count();
        }
        return view('wholesaler.order.return_order_list', compact('orders', 'count'));
    }

    public function reschedulingOrder()
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        if (!Schema::hasTable('wholesaler_orders')) {
            $orders = collect(); $count = 0;
        } else {
            $query = WholesalerOrder::where('wholesaler_id', $wholesaler->id)->where('status', 'rescheduling');
            $col   = Schema::hasColumn('wholesaler_orders', 'rescheduling_date') ? 'rescheduling_date' : 'updated_at';
            $orders = $query->orderBy($col, 'desc')->get();
            $count  = $orders->count();
        }

        $upcomingCount = 0;
        if ($count > 0 && Schema::hasColumn('wholesaler_orders', 'rescheduling_date')) {
            $upcomingCount = $orders->filter(
                fn($o) => $o->rescheduling_date && \Carbon\Carbon::parse($o->rescheduling_date)->isFuture()
            )->count();
        }

        return view('wholesaler.order.rescheduling_order_list', compact('orders', 'count', 'upcomingCount'));
    }

    public function show($id)
    {
        if (!Schema::hasTable('wholesaler_orders')) abort(404, 'Orders table not found');
        $order = WholesalerOrder::where('wholesaler_id', Auth::guard('wholesaler')->id())->findOrFail($id);
        return view('wholesaler.order.order-show', compact('order'));
    }

    /**
     * FIXED: Update method with proper redirect based on status
     */
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
            'call_status'       => 'nullable|string',
        ]);

        $data = [
            'status'      => $request->status,
            'remark'      => $request->remark,
            'call_status' => $request->call_status ?? $order->call_status,
        ];

        switch ($request->status) {
            case 'complete':
                if ($request->delivery_date) $data['delivery_date'] = $request->delivery_date;
                $data['payment_status'] = 'paid';
                break;
            case 'return':
                if ($request->return_date) $data['return_date'] = $request->return_date;
                $data['payment_status'] = 'refunded';
                break;
            case 'rescheduling':
                if ($request->rescheduling_date) $data['rescheduling_date'] = $request->rescheduling_date;
                break;
        }

        $order->update($data);

        // Also update corresponding call if exists
        $call = WholesalerCall::where('wholesaler_id', $order->wholesaler_id)
            ->where('order_code', $order->order_number)
            ->first();
        if ($call) {
            $call->update([
                'status' => $request->call_status ?? $call->status,
                'remark' => $request->remark ?? $call->remark,
                'status_date' => now(),
            ]);
        }

        // Redirect based on new status
        $routeMap = [
            'pending' => 'wholesaler.order.pending-order',
            'complete' => 'wholesaler.order.complete-order',
            'return' => 'wholesaler.order.return-order',
            'rescheduling' => 'wholesaler.order.rescheduling-order',
        ];

        return redirect()->route($routeMap[$request->status])
            ->with('success', 'Order #' . $order->order_number . ' updated successfully!');
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

            // Delete associated call
            WholesalerCall::where('wholesaler_id', $wholesaler->id)
                ->where('order_code', $order->order_number)
                ->delete();

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
