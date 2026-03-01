<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerOrder;
use App\Models\SellerProduct;
use App\Services\Curfoxapiservice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Exception;

class SellerOrderController extends Controller
{
    public function __construct(private Curfoxapiservice $curfox) {}

    // ──────────────────────────────────────────────────────────────────────
    //  NEW ORDER PAGE
    // ──────────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $user = Auth::user();

        try {
            $code = Schema::hasTable('seller_orders')
                ? SellerOrder::generateOrderId()
                : 'ORD/00001';
        } catch (Exception $e) {
            $code = 'ORD/00001';
            Log::error('Error generating order ID: ' . $e->getMessage());
        }

        $import = $user->cal_deposit ?? $user->deposit ?? 0;

        // Load cities/states from Curfox API (cached 24 h)
        $statesWithCities = [];
        $apiError         = null;
        try {
            $statesWithCities = $this->curfox->getStatesWithCities();
        } catch (Exception $e) {
            $apiError = 'Unable to load cities from delivery API. Please try again.';
            Log::warning('Curfox city load failed on new-order page: ' . $e->getMessage());
        }

        // Query-string pre-fill (from product list click)
        $productCode        = $request->get('product_code');
        $productName        = $request->get('product_name');
        $productPrice       = $request->get('product_price');
        $productStock       = $request->get('product_stock');
        $quantity           = $request->get('quantity', 1);
        $productImage       = $request->get('product_image');
        $productDescription = $request->get('product_description');
        $productCategory    = $request->get('product_category');
        $productId          = $request->get('product_id');

        return view('seller.order.new_order', compact(
            'user', 'code', 'import',
            'productCode', 'productName', 'productPrice', 'productStock',
            'quantity', 'productImage', 'productDescription', 'productCategory', 'productId',
            'statesWithCities', 'apiError'
        ));
    }

    // ──────────────────────────────────────────────────────────────────────
    //  API ENDPOINT: cities JSON for JS autocomplete
    // ──────────────────────────────────────────────────────────────────────

    public function citiesJson()
    {
        try {
            $cities = $this->curfox->getAllCities();
            return response()->json(['success' => true, 'data' => $cities]);
        } catch (Exception $e) {
            Log::error('Curfox cities JSON error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function refreshCities()
    {
        try {
            $cities = $this->curfox->refreshStatesWithCities();
            return response()->json(['success' => true, 'message' => 'Cities refreshed', 'count' => count($cities)]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ──────────────────────────────────────────────────────────────────────
    //  STORE ORDER
    // ──────────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date'               => 'required|date',
            'platform'           => 'nullable|string',
            'product_id'         => 'required|exists:seller_products,id',
            'product_code'       => 'required|string',
            'product_name'       => 'required|string',
            'product_price'      => 'required|numeric|min:0',
            'quantity'           => 'required|integer|min:1',
            'customer_name'      => 'required|string|max:255',
            'customer_phone'     => 'required|string|max:20',
            'customer_address'   => 'nullable|string',
            // City / state from Curfox API
            'destination_city_id'    => 'nullable|integer',
            'destination_city_name'  => 'nullable|string|max:150',
            'destination_state_name' => 'nullable|string|max:150',
            'district'               => 'nullable|string|max:150',
            'call_status'            => 'nullable|string|max:50',
            'remark'                 => 'nullable|string',
            'payment_method'         => 'required|string',
        ]);

        $user = Auth::user();

        if (! $user) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Please login to continue.'], 401);
            }
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $availableDeposit = $user->cal_deposit ?? $user->deposit ?? 0;
        $cost             = $user->cost ?? 0;

        if ($availableDeposit < $cost) {
            $msg = 'Insufficient deposit. You need at least Rs. ' . $cost . ' to create an order.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 400);
            }
            return redirect()->route('seller.new-order')->with('error', $msg);
        }

        try {
            DB::beginTransaction();
            $result = $this->handleSingleOrder($request, $user);
            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success'      => true,
                    'message'      => 'Order #' . $result['order']->order_number . ' created successfully!',
                    'order'        => $result['order'],
                    'redirect_url' => route('seller.pending-order'),
                ]);
            }

            return redirect()->route('seller.pending-order')
                ->with('success', 'Order #' . $result['order']->order_number . ' created successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating order', [
                'user_id'    => $user->id ?? null,
                'product_id' => $request->product_id,
                'trace'      => $e->getTraceAsString(),
                'message'    => $e->getMessage(),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Error creating order: ' . $e->getMessage()], 500);
            }

            return redirect()->route('seller.new-order')
                ->withInput()
                ->with('error', 'Error creating order: ' . $e->getMessage());
        }
    }

    // ──────────────────────────────────────────────────────────────────────
    //  PRIVATE: handle single order creation
    // ──────────────────────────────────────────────────────────────────────

    private function handleSingleOrder(Request $request, $user): array
    {
        $product  = SellerProduct::findOrFail($request->product_id);
        $quantity = $request->quantity ?? 1;

        if ($product->qty < $quantity) {
            throw new Exception("Insufficient stock. Available: {$product->qty}, Requested: {$quantity}");
        }

        $orderId      = SellerOrder::generateOrderId();
        $productPrice = floatval($request->product_price);
        $totalAmount  = $productPrice * $quantity;

        // Resolve city/state info
        $cityId    = $request->destination_city_id;
        $cityName  = $request->destination_city_name ?? $request->district;
        $stateName = $request->destination_state_name;

        // If city_id provided but names missing, look them up
        if ($cityId && (! $cityName || ! $stateName)) {
            try {
                $info = $this->curfox->findCityById((int) $cityId);
                if ($info) {
                    $cityName  = $info['city_name'];
                    $stateName = $info['state_name'];
                }
            } catch (Exception $e) {
                Log::warning('Curfox city lookup failed: ' . $e->getMessage());
            }
        }

        $order = SellerOrder::create([
            'seller_id'              => $user->id,
            'order_id'               => $orderId,
            'order_number'           => $orderId,
            'product_id'             => $request->product_id,
            'product_name'           => $request->product_name,
            'product_code'           => $request->product_code,
            'product_price'          => $productPrice,
            'product_image'          => $request->product_image,
            'customer_name'          => $request->customer_name,
            'customer_phone'         => $request->customer_phone,
            'customer_address'       => $request->customer_address,
            'district'               => $cityName,
            // Store API city/state IDs for later courier dispatch
            'destination_city_id'    => $cityId,
            'destination_city_name'  => $cityName,
            'destination_state_name' => $stateName,
            'platform'               => $request->platform ?? 'manual',
            'call_status'            => $request->call_status ?? 'Pending Call',
            'remark'                 => $request->remark,
            'order_date'             => $request->date,
            'status'                 => 'pending',
            'quantity'               => $quantity,
            'subtotal'               => $totalAmount,
            'total_amount'           => $totalAmount,
            'payment_method'         => $request->payment_method,
            'payment_status'         => 'pending',
        ]);

        $this->deductDeposit($user);

        try { $user->update(['last_row_count' => 1]); } catch (Exception $e) {}

        $product->decrement('qty', $quantity);

        Log::info('Order created', ['order_id' => $order->order_id, 'user_id' => $user->id]);

        return ['success' => true, 'order' => $order];
    }

    // ──────────────────────────────────────────────────────────────────────
    //  PRODUCT DETAILS (AJAX)
    // ──────────────────────────────────────────────────────────────────────

    public function getProductDetails($code)
    {
        try {
            $seller = Auth::user();

            if (! $seller) {
                return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
            }

            $decodedCode = urldecode($code);
            $product     = SellerProduct::where('seller_id', $seller->id)
                ->where('code', $decodedCode)
                ->with('category')
                ->first();

            if (! $product) {
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
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // ──────────────────────────────────────────────────────────────────────
    //  CSV UPLOAD
    // ──────────────────────────────────────────────────────────────────────

    public function csvUploadPage()
    {
        $user = Auth::user();
        return view('seller.order.csv_upload', compact('user'));
    }

    public function csvInsert(Request $request)
    {
        $request->validate([
            'leads'                     => 'required|array',
            'leads.*.customer_name'     => 'required|string',
            'leads.*.customer_phone'    => 'required|string',
            'leads.*.customer_address'  => 'nullable|string',
            'leads.*.city'              => 'nullable|string',
            'leads.*.platform'          => 'nullable|string',
            'leads.*.ad_name'           => 'nullable|string',
            'leads.*.call_status'       => 'nullable|string',
            'product_code'              => 'required|string',
            'call_status'               => 'nullable|string',
        ]);

        $user = Auth::user();

        // Pre-load city map for fast lookup
        $cityMap = [];
        try {
            foreach ($this->curfox->getAllCities() as $c) {
                $cityMap[strtolower($c['name'])] = $c;
            }
        } catch (Exception $e) {
            Log::warning('Could not load curfox cities for CSV insert: ' . $e->getMessage());
        }

        try {
            DB::beginTransaction();

            $product = SellerProduct::where('seller_id', $user->id)
                ->where('code', $request->product_code)
                ->firstOrFail();

            $successCount      = 0;
            $errorCount        = 0;
            $errors            = [];
            $defaultCallStatus = $request->call_status ?? 'Pending';

            foreach ($request->leads as $index => $lead) {
                try {
                    if ($product->qty < 1) {
                        $errors[] = "Row " . ($index + 1) . ": Insufficient stock";
                        $errorCount++;
                        continue;
                    }

                    $orderId = SellerOrder::generateOrderId();

                    $remarkParts = [];
                    if (! empty($lead['ad_name']))       $remarkParts[] = 'Ad: ' . $lead['ad_name'];
                    if (! empty($lead['campaign_name'])) $remarkParts[] = 'Campaign: ' . $lead['campaign_name'];
                    if (! empty($lead['created_time']))  $remarkParts[] = 'Lead Date: ' . $lead['created_time'];
                    if (! empty($lead['lead_status']))   $remarkParts[] = 'FB Status: ' . $lead['lead_status'];

                    // Resolve city
                    $cityName  = $lead['city'] ?? null;
                    $cityId    = null;
                    $stateName = null;
                    if ($cityName) {
                        $match = $cityMap[strtolower($cityName)] ?? null;
                        if ($match) {
                            $cityId    = $match['id'];
                            $stateName = $match['state_name'];
                            $cityName  = $match['name'];
                        }
                    }

                    SellerOrder::create([
                        'seller_id'              => $user->id,
                        'order_id'               => $orderId,
                        'order_number'           => $orderId,
                        'product_id'             => $product->id,
                        'product_name'           => $product->name,
                        'product_code'           => $product->code,
                        'product_price'          => $product->display_price,
                        'product_image'          => $product->image,
                        'customer_name'          => $lead['customer_name'],
                        'customer_phone'         => $lead['customer_phone'],
                        'customer_address'       => $lead['customer_address'] ?? null,
                        'district'               => $cityName,
                        'destination_city_id'    => $cityId,
                        'destination_city_name'  => $cityName,
                        'destination_state_name' => $stateName,
                        'platform'               => $lead['platform'] ?? 'fb',
                        'call_status'            => $lead['call_status'] ?? $defaultCallStatus,
                        'remark'                 => ! empty($remarkParts) ? implode(' | ', $remarkParts) : null,
                        'order_date'             => now(),
                        'status'                 => 'pending',
                        'quantity'               => 1,
                        'subtotal'               => $product->display_price,
                        'total_amount'           => $product->display_price,
                        'payment_method'         => 'cash',
                        'payment_status'         => 'pending',
                    ]);

                    $product->decrement('qty', 1);
                    $this->deductDeposit($user);
                    $successCount++;

                } catch (Exception $e) {
                    $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
                    $errorCount++;
                    Log::error("CSV Insert Error row " . ($index + 1), ['error' => $e->getMessage(), 'lead' => $lead]);
                }
            }

            DB::commit();

            return response()->json([
                'success'      => true,
                'message'      => "Successfully created {$successCount} orders." . ($errorCount ? " {$errorCount} failed." : ''),
                'successCount' => $successCount,
                'errorCount'   => $errorCount,
                'errors'       => $errors,
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // ──────────────────────────────────────────────────────────────────────
    //  LIST PAGES
    // ──────────────────────────────────────────────────────────────────────

    public function pendingOrder()
    {
        $user   = Auth::user();
        $orders = collect();
        $count  = 0;

        if (Schema::hasTable('seller_orders')) {
            $orders = SellerOrder::where('seller_id', $user->id)
                ->where('status', 'pending')
                ->with('product')
                ->orderBy('created_at', 'desc')
                ->get();
            $count = $orders->count();
        }

        return view('seller.order.pending_order_list', compact('orders', 'count'));
    }

    public function completeOrder()
    {
        $user   = Auth::user();
        $orders = collect();
        $count  = 0;

        if (Schema::hasTable('seller_orders')) {
            $orders = SellerOrder::where('seller_id', $user->id)
                ->where('status', 'complete')
                ->with('product')
                ->orderBy('delivery_date', 'desc')
                ->get();
            $count = $orders->count();
        }

        return view('seller.order.complete_order_list', compact('orders', 'count'));
    }

    public function returnOrder()
    {
        $user   = Auth::user();
        $orders = collect();
        $count  = 0;

        if (Schema::hasTable('seller_orders')) {
            $orders = SellerOrder::where('seller_id', $user->id)
                ->where('status', 'return')
                ->with('product')
                ->orderBy('return_date', 'desc')
                ->get();
            $count = $orders->count();
        }

        return view('seller.order.return_order_list', compact('orders', 'count'));
    }

    public function reschedulingOrder()
    {
        $user   = Auth::user();
        $orders = collect();
        $count  = 0;

        if (Schema::hasTable('seller_orders')) {
            $orders = SellerOrder::where('seller_id', $user->id)
                ->where('status', 'rescheduling')
                ->with('product')
                ->orderBy('rescheduling_date', 'desc')
                ->get();
            $count = $orders->count();
        }

        return view('seller.order.rescheduling_order_list', compact('orders', 'count'));
    }

    public function show($id)
    {
        if (! Schema::hasTable('seller_orders')) abort(404);

        $order = SellerOrder::where('seller_id', Auth::id())
            ->with('product')
            ->findOrFail($id);

        return view('seller.order.order-show', compact('order'));
    }

    // ──────────────────────────────────────────────────────────────────────
    //  UPDATE & DELETE
    // ──────────────────────────────────────────────────────────────────────

    public function update(Request $request, $id)
    {
        if (! Schema::hasTable('seller_orders')) {
            return redirect()->back()->with('error', 'Orders table not found');
        }

        $order = SellerOrder::where('seller_id', Auth::id())->findOrFail($id);

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
                $data['delivery_date']  = $request->delivery_date ?? now();
                $data['payment_status'] = 'completed';
                break;
            case 'return':
                $data['return_date']    = $request->return_date ?? now();
                $data['payment_status'] = 'refunded';
                break;
            case 'rescheduling':
                $data['rescheduling_date'] = $request->rescheduling_date ?? now();
                break;
        }

        $order->update($data);

        return redirect()->back()->with('success', 'Order updated successfully!');
    }

    public function destroy($id)
    {
        if (! Schema::hasTable('seller_orders')) {
            return redirect()->back()->with('error', 'Orders table not found');
        }

        $user  = Auth::user();
        $order = SellerOrder::where('seller_id', $user->id)->findOrFail($id);

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending orders can be deleted.');
        }

        try {
            DB::beginTransaction();

            $refundAmount = $user->cost ?? 0;
            if (isset($user->cal_deposit)) {
                $user->increment('cal_deposit', $refundAmount);
            } elseif (isset($user->deposit)) {
                $user->increment('deposit', $refundAmount);
            }

            if ($order->product_id) {
                $product = SellerProduct::find($order->product_id);
                if ($product) $product->increment('qty', $order->quantity ?? 1);
            }

            $order->delete();
            DB::commit();

            return redirect()->route('seller.pending-order')
                ->with('success', 'Order deleted! Amount refunded and stock restored.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting order', ['order_id' => $id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error deleting order: ' . $e->getMessage());
        }
    }

    // ──────────────────────────────────────────────────────────────────────
    //  HELPERS
    // ──────────────────────────────────────────────────────────────────────

    private function deductDeposit($user): void
    {
        $cost = $user->cost ?? 0;
        if ($cost <= 0) return;

        if (isset($user->cal_deposit)) {
            $user->decrement('cal_deposit', $cost);
        } elseif (isset($user->deposit)) {
            $user->decrement('deposit', $cost);
        } else {
            try { $user->decrement('cal_deposit', $cost); } catch (Exception $e) {
                try { $user->decrement('deposit', $cost); } catch (Exception $e2) {
                    Log::warning('Could not decrement deposit', ['user_id' => $user->id]);
                }
            }
        }
    }
}
