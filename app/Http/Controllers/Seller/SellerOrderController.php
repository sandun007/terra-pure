<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerOrder;
use App\Models\SellerProduct;
use App\Models\Saler;
use App\Models\SelfSellerDeliveryCall;
use App\Models\EmployeeDeliveryCall;
use App\Services\Curfoxapiservice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Exception;
use Throwable;

class SellerOrderController extends Controller
{
    public function __construct(private Curfoxapiservice $curfox) {}

    // Order ID Generator
    public static function generateOrderId(): string
    {
        $prefix = 'ORD/';
        if (!Schema::hasTable('seller_orders')) return $prefix . '00001';

        try {
            $last = DB::table('seller_orders')
                ->select('order_id', 'order_number', 'id')
                ->whereNotNull('order_id')
                ->orWhereNotNull('order_number')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            $next = 1;
            if ($last) {
                $source = $last->order_id ?: $last->order_number;
                if ($source && preg_match('/(\d+)$/', $source, $m)) {
                    $next = ((int) $m[1]) + 1;
                } else {
                    $next = ((int) $last->id) + 1;
                }
            }
            return $prefix . str_pad((string) $next, 5, '0', STR_PAD_LEFT);
        } catch (Throwable $e) {
            Log::error('Order ID generation failed: ' . $e->getMessage());
            return $prefix . date('ymdHis');
        }
    }

    // City Resolution
    private function resolveCityByName(string $nameInput): ?array
    {
        if (empty(trim($nameInput))) return null;

        try {
            $all = $this->curfox->getStatesWithCities();
            $needle = strtolower(trim($nameInput));
            foreach ($all as $state) {
                foreach ($state['cities'] ?? [] as $city) {
                    if (!($city['is_active'] ?? true)) continue;
                    if (strtolower($city['name']) === $needle) {
                        return ['id' => $city['id'], 'city_name' => $city['name'], 'state_name' => $state['name']];
                    }
                }
            }
            return null;
        } catch (Exception $e) {
            Log::warning('City resolution failed: ' . $e->getMessage());
            return null;
        }
    }

    private function resolveCity(?int $cityId, ?string $cityName, ?string $stateName): ?array
    {
        if ($cityId && $cityName && $stateName) {
            return ['id' => $cityId, 'city_name' => $cityName, 'state_name' => $stateName];
        }
        if ($cityId) {
            try {
                $info = $this->curfox->findCityById($cityId);
                if ($info) return $info;
            } catch (Exception $e) {
                Log::warning('City lookup by ID failed: ' . $e->getMessage());
            }
        }
        if ($cityName) return $this->resolveCityByName($cityName);
        return null;
    }

    // New Order Page
    public function index(Request $request)
    {
        $user = Auth::user();
        $code = Schema::hasTable('seller_orders') ? self::generateOrderId() : 'ORD/00001';
        $statesWithCities = [];
        $apiError = null;

        try {
            $statesWithCities = $this->curfox->getStatesWithCities();
        } catch (Exception $e) {
            $apiError = 'Unable to load cities from delivery API.';
            Log::warning('City load failed: ' . $e->getMessage());
        }

        // Get product details from URL parameters
        $productData = [
            'product_code' => $request->get('product_code'),
            'product_name' => $request->get('product_name'),
            'product_price' => $request->get('product_price'),
            'product_stock' => $request->get('product_stock'),
            'product_id' => $request->get('product_id'),
            'product_image' => $request->get('product_image'),
            'quantity' => $request->get('quantity', 1),
        ];

        return view('seller.create.new-order', compact('user', 'code', 'statesWithCities', 'apiError') + $productData);
    }

    // Store Single Order
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'product_id' => 'required|exists:seller_products,id',
            'product_code' => 'required|string',
            'product_name' => 'required|string',
            'product_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_phone_2' => 'nullable|string|max:20',
            'customer_address' => 'required|string|max:500', // Make address required
            'destination_city_id' => 'required|integer', // Make city required
            'destination_city_name' => 'required|string|max:150',
            'destination_state_name' => 'required|string|max:150',
            'district' => 'required|string|max:150',
            'remark' => 'nullable|string',
            'platform' => 'nullable|string',
            'payment_method' => 'nullable|string',
        ], [
            'customer_address.required' => 'Customer address is required for delivery.',
            'destination_city_id.required' => 'Please select a valid delivery city from the dropdown.',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed'
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        if (!$user) {
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Please login.'], 401)
                : redirect()->route('login')->with('error', 'Please login.');
        }

        $availableDeposit = $user->cal_deposit ?? $user->deposit ?? 0;
        $cost = $user->cost ?? 0;

        if ($availableDeposit < $cost) {
            $msg = 'Insufficient deposit. Need at least Rs. ' . $cost;
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $msg], 400)
                : redirect()->route('seller.create.new-order')->with('error', $msg);
        }

        try {
            DB::beginTransaction();
            $result = $this->handleSingleOrder($request, $user);
            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order #' . $result['order']->order_number . ' created successfully!',
                    'order' => $result['order'],
                    'redirect_url' => route('seller.order.pending'),
                ]);
            }
            return redirect()->route('seller.order.pending')
                ->with('success', 'Order #' . $result['order']->order_number . ' created successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500)
                : redirect()->route('seller.create.new-order')->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    private function handleSingleOrder(Request $request, $user): array
    {
        $product = SellerProduct::findOrFail($request->product_id);
        $quantity = (int) $request->quantity;

        if ((int)$product->qty < $quantity) {
            throw new Exception("Insufficient stock. Available: {$product->qty}");
        }

        // Validate address is not empty
        if (empty($request->customer_address)) {
            throw new Exception('Customer address is required for delivery.');
        }

        $orderId = self::generateOrderId();
        $productPrice = (float) $request->product_price;
        $totalAmount = $productPrice * $quantity;

        $resolved = $this->resolveCity(
            $request->destination_city_id ? (int) $request->destination_city_id : null,
            $request->destination_city_name ?? $request->district,
            $request->destination_state_name
        );

        $cityId = $resolved['id'] ?? null;
        $cityName = $resolved['city_name'] ?? ($request->destination_city_name ?? $request->district ?? '');
        $stateName = $resolved['state_name'] ?? ($request->destination_state_name ?? '');

        if (empty($cityName)) {
            throw new Exception('Delivery city is required.');
        }

        $order = SellerOrder::create([
            'seller_id' => $user->id,
            'order_id' => $orderId,
            'order_number' => $orderId,
            'product_id' => $request->product_id,
            'product_name' => $request->product_name,
            'product_code' => $request->product_code,
            'product_price' => $productPrice,
            'product_image' => $request->product_image,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_phone_2' => $request->customer_phone_2 ?? null,
            'customer_address' => $request->customer_address, // Now properly saved
            'district' => $cityName,
            'destination_city_id' => $cityId,
            'destination_city_name' => $cityName,
            'destination_state_name' => $stateName,
            'platform' => $request->platform ?? 'manual',
            'call_status' => 'Pending Call',
            'remark' => $request->remark,
            'order_date' => $request->date,
            'status' => 'pending',
            'quantity' => $quantity,
            'subtotal' => $totalAmount,
            'total_amount' => $totalAmount,
            'payment_method' => $request->payment_method ?? 'cash',
            'payment_status' => 'pending',
        ]);

        $this->deductDeposit($user);
        $product->decrement('qty', $quantity);

        Log::info('Order created successfully', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'customer_address' => $order->customer_address
        ]);

        return ['success' => true, 'order' => $order];
    }

    // Bulk Store
    public function bulkStore(Request $request)
    {
        if (is_string($request->input('leads'))) {
            $decoded = json_decode($request->input('leads'), true);
            $request->merge(['leads' => is_array($decoded) ? $decoded : []]);
        }

        $validator = Validator::make($request->all(), [
            'product_code' => 'required|string',
            'leads' => 'required|array|min:1',
            'leads.*.customer_name' => 'required|string|max:255',
            'leads.*.customer_phone' => 'required|string|max:20',
            'leads.*.customer_address' => 'required|string|max:500', // Make address required
            'leads.*.district' => 'required|string|max:150',
            'leads.*.destination_city_name' => 'required|string|max:150',
            'leads.*.platform' => 'nullable|string',
            'leads.*.remark' => 'nullable|string',
            'default_platform' => 'nullable|string',
            'date' => 'nullable|date',
            'allow_non_curfox' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);

        $product = SellerProduct::where('seller_id', $user->id)
            ->where('code', $request->product_code)
            ->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $leads = $request->leads;
        $count = count($leads);
        $costPerOrder = (float) ($user->cost ?? 0);
        $need = $costPerOrder * $count;
        $availableDeposit = $user->cal_deposit ?? $user->deposit ?? 0;

        if ($costPerOrder > 0 && $availableDeposit < $need) {
            return response()->json(['success' => false, 'message' => "Insufficient deposit. Need Rs. {$need}."], 400);
        }

        if ((int)$product->qty < $count) {
            return response()->json(['success' => false, 'message' => "Insufficient stock. Available: {$product->qty}"], 400);
        }

        // Build city lookup
        $cityLookupMap = [];
        try {
            $statesWithCities = $this->curfox->getStatesWithCities();
            foreach ($statesWithCities as $state) {
                foreach ($state['cities'] ?? [] as $city) {
                    if (!($city['is_active'] ?? true)) continue;
                    $cityLookupMap[strtolower(trim($city['name']))] = [
                        'id' => $city['id'],
                        'city_name' => $city['name'],
                        'state_name' => $state['name'],
                    ];
                }
            }
        } catch (Exception $e) {
            Log::warning('City load failed for bulk: ' . $e->getMessage());
        }

        $quickResolve = function (array $lead) use ($cityLookupMap): ?array {
            $cityName = $lead['destination_city_name'] ?? ($lead['district'] ?? null);
            if (!$cityName) return null;
            $needle = strtolower(trim($cityName));
            return $cityLookupMap[$needle] ?? null;
        };

        $date = $request->date ?? now()->toDateString();
        $defaultPlatform = $request->default_platform ?? 'fb';
        $allowNonCurfox = $request->allow_non_curfox === '1';

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        try {
            DB::beginTransaction();
            $product = SellerProduct::where('id', $product->id)->lockForUpdate()->first();

            foreach ($leads as $i => $lead) {
                try {
                    if ((int)$product->qty < 1) throw new Exception('Insufficient stock');

                    // Validate address
                    if (empty($lead['customer_address'])) {
                        throw new Exception('Customer address is required');
                    }

                    $orderId = self::generateOrderId();
                    $resolved = $quickResolve($lead);
                    $rawCity = $lead['destination_city_name'] ?? ($lead['district'] ?? '');

                    if ($resolved) {
                        $finalCityId = $resolved['id'];
                        $finalCityName = $resolved['city_name'];
                        $finalStateName = $resolved['state_name'];
                    } else {
                        if (!$allowNonCurfox) {
                            throw new Exception("City '{$rawCity}' not found in Curfox network. Enable 'Allow non-Curfox cities' to import anyway.");
                        }
                        $finalCityId = null;
                        $finalCityName = trim($rawCity);
                        $finalStateName = '';
                    }

                    if (empty($finalCityName)) {
                        throw new Exception('Delivery city is required');
                    }

                    SellerOrder::create([
                        'seller_id' => $user->id,
                        'order_id' => $orderId,
                        'order_number' => $orderId,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_code' => $product->code,
                        'product_price' => (float) $product->display_price,
                        'product_image' => $product->image,
                        'customer_name' => $lead['customer_name'],
                        'customer_phone' => $lead['customer_phone'],
                        'customer_phone_2' => $lead['customer_phone_2'] ?? null,
                        'customer_address' => $lead['customer_address'], // Now properly saved
                        'district' => $finalCityName,
                        'destination_city_id' => $finalCityId,
                        'destination_city_name' => $finalCityName,
                        'destination_state_name' => $finalStateName,
                        'platform' => $lead['platform'] ?? $defaultPlatform,
                        'call_status' => 'Pending Call',
                        'remark' => $lead['remark'] ?? null,
                        'order_date' => $date,
                        'status' => 'pending',
                        'quantity' => 1,
                        'subtotal' => (float) $product->display_price,
                        'total_amount' => (float) $product->display_price,
                        'payment_method' => $lead['payment_method'] ?? 'cash',
                        'payment_status' => 'pending',
                    ]);

                    $product->decrement('qty', 1);
                    $this->deductDeposit($user);
                    $successCount++;

                } catch (Exception $e) {
                    $errorCount++;
                    $errors[] = 'Row ' . ($i + 1) . ': ' . $e->getMessage();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Bulk import done. {$successCount} created, {$errorCount} failed.",
                'successCount' => $successCount,
                'errorCount' => $errorCount,
                'errors' => $errors,
                'redirect_url' => route('seller.order.pending'),
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Bulk import error: ' . $e->getMessage()], 500);
        }
    }

    // Bulk Validate
    public function bulkValidate(Request $request)
    {
        if (is_string($request->input('leads'))) {
            $decoded = json_decode($request->input('leads'), true);
            $request->merge(['leads' => is_array($decoded) ? $decoded : []]);
        }

        $request->validate(['leads' => 'required|array|min:1']);

        try {
            $statesWithCities = $this->curfox->getStatesWithCities();
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Could not load city list'], 500);
        }

        $cityLookupMap = [];
        foreach ($statesWithCities as $state) {
            foreach ($state['cities'] ?? [] as $city) {
                if (!($city['is_active'] ?? true)) continue;
                $cityLookupMap[strtolower(trim($city['name']))] = [
                    'id' => $city['id'],
                    'city_name' => $city['name'],
                    'state_name' => $state['name'],
                ];
            }
        }

        $results = [];
        $addressMissing = [];

        foreach ($request->leads as $i => $lead) {
            // Check if address is provided
            if (empty($lead['customer_address'])) {
                $addressMissing[] = $i + 1;
            }

            $rawCity = $lead['destination_city_name'] ?? ($lead['district'] ?? '');
            $needle = strtolower(trim($rawCity));

            if (empty($needle)) {
                $results[] = ['row' => $i + 1, 'input' => '', 'status' => 'missing', 'resolved' => null];
                continue;
            }

            $resolved = $cityLookupMap[$needle] ?? null;
            $results[] = [
                'row' => $i + 1,
                'input' => $rawCity,
                'status' => $resolved ? 'ok' : 'invalid',
                'resolved' => $resolved,
            ];
        }

        $invalid = array_filter($results, fn($r) => $r['status'] === 'invalid');
        $missing = array_filter($results, fn($r) => $r['status'] === 'missing');

        return response()->json([
            'success' => true,
            'results' => $results,
            'invalid_count' => count($invalid),
            'missing_count' => count($missing),
            'address_missing' => $addressMissing,
            'address_missing_count' => count($addressMissing),
            'all_valid' => count($invalid) === 0 && count($missing) === 0 && count($addressMissing) === 0,
        ]);
    }

    // Cities JSON
    public function citiesJson()
    {
        try {
            $cities = $this->curfox->getAllCities();
            return response()->json(['success' => true, 'data' => $cities]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Product Details
    public function getProductDetails($code)
    {
        try {
            $seller = Auth::user();
            if (!$seller) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            // Decode the URL-encoded code
            $decodedCode = urldecode($code);

            Log::info('Searching for product with code: ' . $decodedCode . ' for seller: ' . $seller->id);

            $product = SellerProduct::where('seller_id', $seller->id)
                ->where('code', $decodedCode)
                ->first();

            if (!$product) {
                Log::warning('Product not found with code: ' . $decodedCode);
                return response()->json(['success' => false, 'message' => 'Product not found'], 404);
            }

            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code,
                    'display_price' => (float) $product->display_price,
                    'qty' => (int) $product->qty,
                    'min_qty' => (int) ($product->min_qty ?? 1),
                    'image' => $product->image ? asset('storage/' . $product->image) : null,
                ],
            ]);
        } catch (Exception $e) {
            Log::error('Product details error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    // Order List Pages
    public function pendingOrder()
    {
        $user = Auth::user();
        $orders = collect();
        if (Schema::hasTable('seller_orders')) {
            $orders = SellerOrder::where('seller_id', $user->id)
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();
        }
        return view('seller.order.pending', compact('orders'))->with('count', $orders->count());
    }

    public function confirmedOrders()
    {
        $user = Auth::user();
        $orders = SellerOrder::where('seller_id', $user->id)
            ->where('call_status', 'Confirmed')
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('seller.order.confirmed', compact('orders'))->with('count', $orders->count());
    }

    public function rescheduledOrders()
    {
        $user = Auth::user();
        $orders = SellerOrder::where('seller_id', $user->id)
            ->where('status', 'rescheduling')
            ->orderBy('rescheduling_date', 'desc')
            ->get();
        return view('seller.order.rescheduling_order_list', compact('orders'))->with('count', $orders->count());
    }

    public function completedOrders()
    {
        $user = Auth::user();
        $orders = SellerOrder::where('seller_id', $user->id)
            ->where('status', 'complete')
            ->orderBy('delivery_date', 'desc')
            ->get();
        return view('seller.order.complete_order_list', compact('orders'))->with('count', $orders->count());
    }

    public function returnedOrders()
    {
        $user = Auth::user();
        $orders = SellerOrder::where('seller_id', $user->id)
            ->where('status', 'return')
            ->orderBy('return_date', 'desc')
            ->get();
        return view('seller.order.return_order_list', compact('orders'))->with('count', $orders->count());
    }

    // Call Management Pages
    public function pendingCalls()
    {
        $user = Auth::user();
        $calls = SellerOrder::where('seller_id', $user->id)
            ->where('call_status', 'Pending Call')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
        return view('seller.call.pending', compact('calls'))->with('count', $calls->count());
    }

    public function calledCalls()
    {
        $user = Auth::user();
        $calls = SellerOrder::where('seller_id', $user->id)
            ->where('call_status', 'Called')
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('seller.call.called', compact('calls'))->with('count', $calls->count());
    }

    public function confirmedCalls()
    {
        $user = Auth::user();
        $calls = SellerOrder::where('seller_id', $user->id)
            ->where('call_status', 'Confirmed')
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('seller.call.confirmed', compact('calls'))->with('count', $calls->count());
    }

    public function notAnsweredCalls()
    {
        $user = Auth::user();
        $calls = SellerOrder::where('seller_id', $user->id)
            ->where('call_status', 'Not Answered')
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('seller.call.not-answered', compact('calls'))->with('count', $calls->count());
    }

    public function cancelledCalls()
    {
        $user = Auth::user();
        $calls = SellerOrder::where('seller_id', $user->id)
            ->where('call_status', 'Cancelled')
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('seller.call.cancelled', compact('calls'))->with('count', $calls->count());
    }

    public function show($id)
    {
        $order = SellerOrder::where('seller_id', Auth::id())->findOrFail($id);
        return view('seller.order.order-show', compact('order'));
    }

    // Update Order (includes auto-creation of delivery calls)
    // Update Order (separate order status from call status)
    public function update(Request $request, $id)
    {
        $order = SellerOrder::where('seller_id', Auth::id())->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'nullable|in:pending,complete,return,rescheduling,cancelled',
            'call_status' => 'nullable|string|in:Pending Call,Called,Confirmed,Not Answered,Cancelled',
            'delivery_date' => 'nullable|date',
            'rescheduling_date' => 'nullable|date',
            'return_date' => 'nullable|date',
            'remark' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [];

        // Handle ORDER STATUS changes (only from Order Management)
        if ($request->has('status')) {
            // Validate that order status can only be changed from pending
            if ($order->status !== 'pending' && $request->status !== $order->status) {
                $errorMsg = 'Only pending orders can have their status changed.';
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $errorMsg], 422);
                }
                return redirect()->back()->with('error', $errorMsg);
            }

            $data['status'] = $request->status;

            // Set status-specific fields
            if ($request->status === 'complete') {
                $data['delivery_date'] = $request->delivery_date ?? now();
                $data['payment_status'] = 'completed';
            } elseif ($request->status === 'return') {
                $data['return_date'] = $request->return_date ?? now();
                $data['payment_status'] = 'refunded';
            } elseif ($request->status === 'rescheduling') {
                $data['rescheduling_date'] = $request->rescheduling_date ?? now();
            } elseif ($request->status === 'cancelled') {
                $data['payment_status'] = 'cancelled';
            }
        }

        // Handle CALL STATUS changes (from Call Management)
        if ($request->has('call_status')) {
            // If trying to confirm the order, ensure address exists
            if ($request->call_status === 'Confirmed' && empty($order->customer_address)) {
                $errorMsg = 'Customer address is required before confirming order.';
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $errorMsg], 422);
                }
                return redirect()->back()->with('error', $errorMsg);
            }

            $data['call_status'] = $request->call_status;

            // If call status becomes Confirmed, auto-create delivery call
            // but DON'T change order status (order remains pending)
            if ($request->call_status === 'Confirmed' && $order->status === 'pending') {
                $this->createDeliveryCall($order);
            }
        }

        // Handle remark separately
        if ($request->has('remark')) {
            $data['remark'] = $request->remark;
        }

        // Only update if there are changes
        if (!empty($data)) {
            $order->update($data);

            Log::info('Order updated', [
                'order_id' => $order->id,
                'changes' => $data
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Order updated successfully']);
        }

        return redirect()->back()->with('success', 'Order updated successfully!');
    }

    private function createDeliveryCall(SellerOrder $order)
    {
        $seller = Saler::find($order->seller_id);

        // Log the order data for debugging
        Log::info('Creating delivery call for order:', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'customer_address' => $order->customer_address,
            'customer_name' => $order->customer_name,
            'customer_phone' => $order->customer_phone,
        ]);

        // Ensure address is not empty
        $customerAddress = $order->customer_address;
        if (empty($customerAddress)) {
            $customerAddress = 'No address provided - ' . ($order->district ?? 'Unknown location');
            Log::warning('Empty address detected, using fallback: ' . $customerAddress);
        }

        $deliveryData = [
            'seller_id' => $order->seller_id,
            'product_id' => $order->product_id,
            'order_code' => $order->order_number,
            'date' => now()->toDateString(),
            'status_date' => now(),
            'customer_name' => $order->customer_name,
            'customer_phone_01' => $order->customer_phone,
            'customer_phone_02' => $order->customer_phone_2 ?? null,
            'customer_address' => $customerAddress, // Now properly saved with fallback
            'city' => $order->destination_city_name,
            'district' => $order->district,
            'destination_city_id' => $order->destination_city_id,
            'destination_state_name' => $order->destination_state_name,
            'item_code' => $order->product_code,
            'item_name' => $order->product_name,
            'qty' => $order->quantity,
            'price' => $order->product_price,
            'total' => $order->total_amount,
            'status' => 'Pending',
            'platform' => $order->platform ?? 'manual',
            'remark' => $order->remark,
            'download_status' => false,
        ];

        try {
            if ($seller && $seller->type === 'self_seller') {
                $deliveryData['call_number'] = SelfSellerDeliveryCall::generateCallNumber($seller->id);
                $deliveryCall = SelfSellerDeliveryCall::firstOrCreate(
                    ['order_code' => $order->order_number],
                    $deliveryData
                );
            } else {
                $deliveryData['call_number'] = $order->order_number;
                $deliveryCall = EmployeeDeliveryCall::firstOrCreate(
                    ['order_code' => $order->order_number],
                    $deliveryData
                );
            }

            Log::info('Delivery call created/updated successfully:', [
                'id' => $deliveryCall->id,
                'order_code' => $deliveryCall->order_code,
                'customer_address' => $deliveryCall->customer_address,
            ]);

        } catch (Exception $e) {
            Log::error('Failed to create delivery call: ' . $e->getMessage(), [
                'order_number' => $order->order_number,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function destroy($id)
    {
        $user = Auth::user();
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
                SellerProduct::where('id', $order->product_id)->increment('qty', $order->quantity ?? 1);
            }
            $order->delete();
            DB::commit();

            return redirect()->route('seller.order.pending')
                ->with('success', 'Order deleted! Amount refunded and stock restored.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    private function deductDeposit($user): void
    {
        $cost = $user->cost ?? 0;
        if ($cost <= 0) return;

        try {
            if (isset($user->cal_deposit)) {
                $user->decrement('cal_deposit', $cost);
            } elseif (isset($user->deposit)) {
                $user->decrement('deposit', $cost);
            }
        } catch (Exception $e) {
            Log::warning('Deposit decrement failed', ['user_id' => $user->id]);
        }
    }

    // Dispatch to Curfox
    public function dispatchToCurfox(Request $request, $id)
    {
        $order = SellerOrder::where('seller_id', Auth::id())->findOrFail($id);

        // Validate address exists
        if (empty($order->customer_address)) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot dispatch to Curfox: Customer address is missing. Please update the order with a valid address.'
            ], 422);
        }

        // ... rest of your dispatch logic
    }
}
