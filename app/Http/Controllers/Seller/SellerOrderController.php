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

    // ── Order ID Generator ────────────────────────────────────────────────────

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

    // ── City Resolution ───────────────────────────────────────────────────────

    private function resolveCityByName(string $nameInput): ?array
    {
        if (empty(trim($nameInput))) return null;
        try {
            $all    = $this->curfox->getStatesWithCities();
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

    // ── New Order Page ────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $user             = Auth::user();
        $code             = Schema::hasTable('seller_orders') ? self::generateOrderId() : 'ORD/00001';
        $statesWithCities = [];
        $apiError         = null;

        try {
            $statesWithCities = $this->curfox->getStatesWithCities();
        } catch (Exception $e) {
            $apiError = 'Unable to load cities from delivery API.';
            Log::warning('City load failed: ' . $e->getMessage());
        }

        $productData = [
            'product_code'  => $request->get('product_code'),
            'product_name'  => $request->get('product_name'),
            'product_price' => $request->get('product_price'),
            'product_stock' => $request->get('product_stock'),
            'product_id'    => $request->get('product_id'),
            'product_image' => $request->get('product_image'),
            'quantity'      => $request->get('quantity', 1),
        ];

        return view('seller.create.new-order', compact('user', 'code', 'statesWithCities', 'apiError') + $productData);
    }

    // ── Store Single Order — AUTO-CONFIRMS, creates delivery call immediately ─

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date'                   => 'required|date',
            'product_id'             => 'required|exists:seller_products,id',
            'product_code'           => 'required|string',
            'product_name'           => 'required|string',
            'product_price'          => 'required|numeric|min:0',
            'quantity'               => 'required|integer|min:1',
            'customer_name'          => 'required|string|max:255',
            'customer_phone'         => 'required|string|max:20',
            'customer_phone_2'       => 'nullable|string|max:20',
            'customer_address'       => 'required|string|max:500',
            'destination_city_id'    => 'required|integer',
            'destination_city_name'  => 'required|string|max:150',
            'destination_state_name' => 'required|string|max:150',
            'district'               => 'required|string|max:150',
            'remark'                 => 'nullable|string',
            'delivery_remark'        => 'nullable|string',
            'platform'               => 'nullable|string',
            'payment_method'         => 'nullable|string',
            'selling_price'          => 'nullable|numeric|min:0',
        ], [
            'customer_address.required'    => 'Customer address is required for delivery.',
            'destination_city_id.required' => 'Please select a valid delivery city from the dropdown.',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors(),
                    'message' => 'Validation failed',
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
        $cost             = $user->cost ?? 0;

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
                    'success'      => true,
                    'message'      => 'Order #' . $result['order']->order_number . ' created & confirmed!',
                    'order'        => $result['order'],
                    'redirect_url' => route('seller.order.confirmed'),
                ]);
            }
            return redirect()->route('seller.order.confirmed')
                ->with('success', 'Order #' . $result['order']->order_number . ' created & confirmed!');

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
        $product  = SellerProduct::findOrFail($request->product_id);
        $quantity = (int) $request->quantity;

        if ((int)$product->qty < $quantity) {
            throw new Exception("Insufficient stock. Available: {$product->qty}");
        }
        if (empty($request->customer_address)) {
            throw new Exception('Customer address is required for delivery.');
        }

        $orderId      = self::generateOrderId();
        $productPrice = (float) ($request->selling_price ?: $request->product_price);
        $totalAmount  = $productPrice * $quantity;

        $resolved  = $this->resolveCity(
            $request->destination_city_id ? (int) $request->destination_city_id : null,
            $request->destination_city_name ?? $request->district,
            $request->destination_state_name
        );
        $cityId    = $resolved['id']         ?? null;
        $cityName  = $resolved['city_name']  ?? ($request->destination_city_name ?? $request->district ?? '');
        $stateName = $resolved['state_name'] ?? ($request->destination_state_name ?? '');

        if (empty($cityName)) throw new Exception('Delivery city is required.');

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
            'customer_phone_2'       => $request->customer_phone_2 ?? null,
            'customer_address'       => $request->customer_address,
            'district'               => $cityName,
            'destination_city_id'    => $cityId,
            'destination_city_name'  => $cityName,
            'destination_state_name' => $stateName,
            'platform'               => $request->platform ?? 'manual',
            'call_status'            => 'Confirmed',
            'status'                 => 'pending',
            'remark'                 => $request->remark,
            'delivery_remark'        => $request->delivery_remark,
            'order_date'             => $request->date,
            'quantity'               => $quantity,
            'subtotal'               => $totalAmount,
            'total_amount'           => $totalAmount,
            'payment_method'         => $request->payment_method ?? 'cash',
            'payment_status'         => 'pending',
        ]);

        $this->createDeliveryCall($order);
        $this->deductDeposit($user);
        $product->decrement('qty', $quantity);

        Log::info('Single order auto-confirmed', ['order_id' => $order->id]);

        return ['success' => true, 'order' => $order];
    }

    // ── Bulk Store ────────────────────────────────────────────────────────────

    public function bulkStore(Request $request)
    {
        if (is_string($request->input('leads'))) {
            $decoded = json_decode($request->input('leads'), true);
            $request->merge(['leads' => is_array($decoded) ? $decoded : []]);
        }

        $validator = Validator::make($request->all(), [
            'leads'                            => 'required|array|min:1',
            'leads.*.customer_name'            => 'required|string|max:255',
            'leads.*.customer_phone'           => 'required|string|max:20',
            'leads.*.customer_address'         => 'nullable|string|max:500',
            'leads.*.district'                 => 'nullable|string|max:150',
            'leads.*.destination_city_name'    => 'nullable|string|max:150',
            'leads.*.platform'                 => 'nullable|string',
            'leads.*.remark'                   => 'nullable|string',
            'leads.*.delivery_remark'          => 'nullable|string',
            'leads.*.product_code'             => 'nullable|string|max:100',
            'default_product_code'             => 'nullable|string',
            'default_platform'                 => 'nullable|string',
            'date'                             => 'nullable|date',
            'allow_non_curfox'                 => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);

        $defaultProduct = null;
        $defaultCode    = trim((string)($request->default_product_code ?? ''));
        if ($defaultCode) {
            $defaultProduct = SellerProduct::where('seller_id', $user->id)
                ->where('code', $defaultCode)
                ->first();
            if (!$defaultProduct) {
                return response()->json(['success' => false, 'message' => "Default product '{$defaultCode}' not found."], 404);
            }
        }

        $rowCodes = array_filter(array_unique(
            array_map(fn($l) => trim((string)($l['product_code'] ?? '')), $request->leads)
        ));

        $productCache = [];
        if (!empty($rowCodes)) {
            SellerProduct::where('seller_id', $user->id)
                ->whereIn('code', $rowCodes)
                ->get()
                ->each(function ($p) use (&$productCache) {
                    $productCache[$p->code] = $p;
                });
        }

        $cityMap = [];
        try {
            foreach ($this->curfox->getStatesWithCities() as $state) {
                foreach ($state['cities'] ?? [] as $city) {
                    if (!($city['is_active'] ?? true)) continue;
                    $cityMap[strtolower(trim($city['name']))] = [
                        'id'         => $city['id'],
                        'city_name'  => $city['name'],
                        'state_name' => $state['name'],
                    ];
                }
            }
        } catch (Exception $e) {
            Log::warning('City load failed for bulk: ' . $e->getMessage());
        }

        $resolve = function (array $lead) use ($cityMap): ?array {
            $name = $lead['destination_city_name'] ?? ($lead['district'] ?? null);
            if (!$name) return null;
            return $cityMap[strtolower(trim($name))] ?? null;
        };

        $date            = $request->date ?? now()->toDateString();
        $defaultPlatform = $request->default_platform ?? 'fb';
        $allowNonCurfox  = $request->allow_non_curfox === '1';

        $successCount = 0;
        $errorCount   = 0;
        $errors       = [];

        $costPerOrder  = (float)($user->cost ?? 0);
        $totalRows     = count($request->leads);
        $avail         = $user->cal_deposit ?? $user->deposit ?? 0;
        if ($costPerOrder > 0 && $avail < $costPerOrder * $totalRows) {
            return response()->json([
                'success' => false,
                'message' => "Insufficient deposit. Need Rs. " . ($costPerOrder * $totalRows) . ".",
            ], 400);
        }

        try {
            DB::beginTransaction();

            if ($defaultProduct) {
                $defaultProduct = SellerProduct::where('id', $defaultProduct->id)->lockForUpdate()->first();
            }

            $lockedProducts = [];
            foreach ($productCache as $code => $p) {
                $lockedProducts[$code] = SellerProduct::where('id', $p->id)->lockForUpdate()->first();
            }
            foreach ($lockedProducts as $code => $lp) {
                $productCache[$code] = $lp;
            }

            foreach ($request->leads as $i => $lead) {
                try {
                    $rowCode = trim((string)($lead['product_code'] ?? ''));
                    if ($rowCode && isset($productCache[$rowCode])) {
                        $product = $productCache[$rowCode];
                    } elseif ($defaultProduct) {
                        $product = $defaultProduct;
                    } else {
                        throw new Exception("No product found for row. Provide a product_code column or set a default product.");
                    }

                    if ((int)$product->qty < 1) {
                        throw new Exception("Insufficient stock for product '{$product->code}' (Available: {$product->qty})");
                    }

                    $orderId  = self::generateOrderId();
                    $resolved = $resolve($lead);
                    $rawCity  = $lead['destination_city_name'] ?? ($lead['district'] ?? '');

                    if ($resolved) {
                        $finalCityId    = $resolved['id'];
                        $finalCityName  = $resolved['city_name'];
                        $finalStateName = $resolved['state_name'];
                    } else {
                        if (!$allowNonCurfox) {
                            throw new Exception("City '{$rawCity}' not in Curfox network. Enable 'Allow non-Curfox cities'.");
                        }
                        $finalCityId    = null;
                        $finalCityName  = trim($rawCity);
                        $finalStateName = '';
                    }

                    if (empty($finalCityName)) throw new Exception('Delivery city is required');

                    $unitPrice = isset($lead['selling_price']) && is_numeric($lead['selling_price']) && (float)$lead['selling_price'] > 0
                        ? (float)$lead['selling_price']
                        : (float)$product->display_price;

                    SellerOrder::create([
                        'seller_id'              => $user->id,
                        'order_id'               => $orderId,
                        'order_number'           => $orderId,
                        'product_id'             => $product->id,
                        'product_name'           => $product->name,
                        'product_code'           => $product->code,
                        'product_price'          => $unitPrice,
                        'product_image'          => $product->image,
                        'customer_name'          => $lead['customer_name'],
                        'customer_phone'         => $lead['customer_phone'],
                        'customer_phone_2'       => $lead['customer_phone_2'] ?? null,
                        'customer_address'       => $lead['customer_address'] ?? null,
                        'district'               => $finalCityName,
                        'destination_city_id'    => $finalCityId,
                        'destination_city_name'  => $finalCityName,
                        'destination_state_name' => $finalStateName,
                        'platform'               => $lead['platform'] ?? $defaultPlatform,
                        'call_status'            => 'Pending Call',
                        'status'                 => 'pending',
                        'remark'                 => $lead['remark'] ?? null,
                        'delivery_remark'        => $lead['delivery_remark'] ?? null,
                        'order_date'             => $date,
                        'quantity'               => 1,
                        'subtotal'               => $unitPrice,
                        'total_amount'           => $unitPrice,
                        'payment_method'         => $lead['payment_method'] ?? 'cash',
                        'payment_status'         => 'pending',
                    ]);

                    $product->decrement('qty', 1);
                    if ($rowCode && isset($productCache[$rowCode])) {
                        $productCache[$rowCode]->qty -= 1;
                    } elseif ($defaultProduct && $product->id === $defaultProduct->id) {
                        $defaultProduct->qty -= 1;
                    }

                    $this->deductDeposit($user);
                    $successCount++;

                } catch (Exception $e) {
                    $errorCount++;
                    $errors[] = 'Row ' . ($i + 1) . ': ' . $e->getMessage();
                }
            }

            DB::commit();

            return response()->json([
                'success'      => true,
                'message'      => "Bulk import done. {$successCount} created, {$errorCount} failed.",
                'successCount' => $successCount,
                'errorCount'   => $errorCount,
                'errors'       => $errors,
                'redirect_url' => route('seller.order.pending'),
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Bulk import error: ' . $e->getMessage()], 500);
        }
    }

    // ── Bulk Validate ─────────────────────────────────────────────────────────

    public function bulkValidate(Request $request)
    {
        if (is_string($request->input('leads'))) {
            $decoded = json_decode($request->input('leads'), true);
            $request->merge(['leads' => is_array($decoded) ? $decoded : []]);
        }

        $request->validate(['leads' => 'required|array|min:1']);

        $user = Auth::user();

        try {
            $statesWithCities = $this->curfox->getStatesWithCities();
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Could not load city list'], 500);
        }

        $cityMap = [];
        foreach ($statesWithCities as $state) {
            foreach ($state['cities'] ?? [] as $city) {
                if (!($city['is_active'] ?? true)) continue;
                $cityMap[strtolower(trim($city['name']))] = [
                    'id'         => $city['id'],
                    'city_name'  => $city['name'],
                    'state_name' => $state['name'],
                ];
            }
        }

        $rowCodes = array_filter(array_unique(
            array_map(fn($l) => trim((string)($l['product_code'] ?? '')), $request->leads)
        ));
        $productMap = [];
        if (!empty($rowCodes) && $user) {
            SellerProduct::where('seller_id', $user->id)
                ->whereIn('code', array_values($rowCodes))
                ->get()
                ->each(function ($p) use (&$productMap) {
                    $productMap[$p->code] = [
                        'id'    => $p->id,
                        'name'  => $p->name,
                        'qty'   => $p->qty,
                        'price' => $p->display_price,
                    ];
                });
        }

        $results        = [];
        $addressMissing = [];

        foreach ($request->leads as $i => $lead) {
            if (empty($lead['customer_address'])) $addressMissing[] = $i + 1;

            $rawCity = $lead['destination_city_name'] ?? ($lead['district'] ?? '');
            $needle  = strtolower(trim($rawCity));
            $cityStatus   = '';
            $cityResolved = null;

            if (empty($needle)) {
                $cityStatus = 'missing';
            } else {
                $cityResolved = $cityMap[$needle] ?? null;
                $cityStatus   = $cityResolved ? 'ok' : 'invalid';
            }

            $rowCode       = trim((string)($lead['product_code'] ?? ''));
            $productStatus = null;
            $productInfo   = null;
            if ($rowCode) {
                if (isset($productMap[$rowCode])) {
                    $productStatus = 'ok';
                    $productInfo   = $productMap[$rowCode];
                } else {
                    $productStatus = 'invalid';
                }
            }

            $results[] = [
                'row'            => $i + 1,
                'input'          => $rawCity,
                'status'         => $cityStatus,
                'resolved'       => $cityResolved,
                'product_code'   => $rowCode ?: null,
                'product_status' => $productStatus,
                'product_info'   => $productInfo,
            ];
        }

        $invalid        = array_filter($results, fn($r) => $r['status'] === 'invalid');
        $missing        = array_filter($results, fn($r) => $r['status'] === 'missing');
        $productInvalid = array_filter($results, fn($r) => $r['product_status'] === 'invalid');

        return response()->json([
            'success'                => true,
            'results'                => $results,
            'invalid_count'          => count($invalid),
            'missing_count'          => count($missing),
            'address_missing'        => $addressMissing,
            'address_missing_count'  => count($addressMissing),
            'product_invalid_count'  => count($productInvalid),
            'all_valid'              => count($invalid) === 0 && count($missing) === 0 && count($productInvalid) === 0,
        ]);
    }

    // ── Cities JSON ───────────────────────────────────────────────────────────

    public function citiesJson()
    {
        try {
            $cities = $this->curfox->getAllCities();
            return response()->json(['success' => true, 'data' => $cities]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ── Product Details ───────────────────────────────────────────────────────

    public function getProductDetails($code)
    {
        try {
            $seller = Auth::user();
            if (!$seller) return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);

            $decodedCode = urldecode($code);
            $product     = SellerProduct::where('seller_id', $seller->id)
                ->where('code', $decodedCode)
                ->first();

            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Product not found'], 404);
            }

            return response()->json([
                'success' => true,
                'product' => [
                    'id'            => $product->id,
                    'name'          => $product->name,
                    'code'          => $product->code,
                    'display_price' => (float) $product->display_price,
                    'qty'           => (int) $product->qty,
                    'min_qty'       => (int) ($product->min_qty ?? 1),
                    'image'         => $product->image ? asset('storage/' . $product->image) : null,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    // ── Order List Pages ──────────────────────────────────────────────────────

    public function pendingOrder()
    {
        $user   = Auth::user();
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
        $user   = Auth::user();
        $orders = SellerOrder::where('seller_id', $user->id)
            ->where('call_status', 'Confirmed')
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('seller.order.confirmed', compact('orders'))->with('count', $orders->count());
    }

    public function rescheduledOrders()
    {
        $user   = Auth::user();
        $orders = SellerOrder::where('seller_id', $user->id)
            ->where('status', 'rescheduling')
            ->orderBy('rescheduling_date', 'desc')
            ->get();
        return view('seller.order.rescheduling_order_list', compact('orders'))->with('count', $orders->count());
    }

    public function completedOrders()
    {
        $user   = Auth::user();
        $orders = SellerOrder::where('seller_id', $user->id)
            ->where('status', 'complete')
            ->orderBy('delivery_date', 'desc')
            ->get();
        return view('seller.order.complete_order_list', compact('orders'))->with('count', $orders->count());
    }

    public function returnedOrders()
    {
        $user   = Auth::user();
        $orders = SellerOrder::where('seller_id', $user->id)
            ->where('status', 'return')
            ->orderBy('return_date', 'desc')
            ->get();
        return view('seller.order.return_order_list', compact('orders'))->with('count', $orders->count());
    }

    // ── Call Management Pages ─────────────────────────────────────────────────

    public function pendingCalls()
    {
        $user  = Auth::user();
        $calls = SellerOrder::where('seller_id', $user->id)
            ->where('call_status', 'Pending Call')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
        return view('seller.call.pending', compact('calls'))->with('count', $calls->count());
    }

    public function calledCalls()
    {
        $user  = Auth::user();
        $calls = SellerOrder::where('seller_id', $user->id)
            ->where('call_status', 'Called')
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('seller.call.called', compact('calls'))->with('count', $calls->count());
    }

    public function confirmedCalls()
    {
        $user  = Auth::user();
        $calls = SellerOrder::where('seller_id', $user->id)
            ->where('call_status', 'Confirmed')
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('seller.call.confirmed', compact('calls'))->with('count', $calls->count());
    }

    public function notAnsweredCalls()
    {
        $user  = Auth::user();
        $calls = SellerOrder::where('seller_id', $user->id)
            ->where('call_status', 'Not Answered')
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('seller.call.not-answered', compact('calls'))->with('count', $calls->count());
    }

    public function cancelledCalls()
    {
        $user  = Auth::user();
        $calls = SellerOrder::where('seller_id', $user->id)
            ->where('call_status', 'Cancelled')
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('seller.call.cancelled', compact('calls'))->with('count', $calls->count());
    }

    // ── Show ──────────────────────────────────────────────────────────────────

    public function show($id)
    {
        $order = SellerOrder::where('seller_id', Auth::id())->findOrFail($id);
        return view('seller.order.order-show', compact('order'));
    }

    // ── Edit (Full-page edit form) ────────────────────────────────────────────

    public function edit($id)
    {
        $order            = SellerOrder::where('seller_id', Auth::id())->findOrFail($id);
        $statesWithCities = [];
        $apiError         = null;

        try {
            $statesWithCities = $this->curfox->getStatesWithCities();
        } catch (Exception $e) {
            $apiError = 'Unable to load cities from delivery API.';
            Log::warning('City load failed for edit: ' . $e->getMessage());
        }

        return view('seller.order.edit', compact('order', 'statesWithCities', 'apiError'));
    }

    // ── Update ────────────────────────────────────────────────────────────────

    public function update(Request $request, $id)
    {
        $order = SellerOrder::where('seller_id', Auth::id())->findOrFail($id);

        $validator = Validator::make($request->all(), [
            // Status fields
            'status'                 => 'nullable|in:pending,complete,return,rescheduling,cancelled',
            'call_status'            => 'nullable|string|in:Pending Call,Called,Confirmed,Not Answered,Cancelled',
            'delivery_date'          => 'nullable|date',
            'rescheduling_date'      => 'nullable|date',
            'return_date'            => 'nullable|date',
            // Customer fields
            'customer_name'          => 'nullable|string|max:255',
            'customer_phone'         => 'nullable|string|max:20',
            'customer_phone_2'       => 'nullable|string|max:20',
            'customer_address'       => 'nullable|string|max:500',
            // Product / pricing fields
            'quantity'               => 'nullable|integer|min:1',
            'product_price'          => 'nullable|numeric|min:0',
            'total_amount'           => 'nullable|numeric|min:0',
            'order_date'             => 'nullable|date',
            'platform'               => 'nullable|string',
            'payment_method'         => 'nullable|string',
            // Delivery location fields
            'district'               => 'nullable|string|max:150',
            'destination_city_id'    => 'nullable|integer',
            'destination_city_name'  => 'nullable|string|max:150',
            'destination_state_name' => 'nullable|string|max:150',
            // Remarks
            'remark'                 => 'nullable|string',
            'delivery_remark'        => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [];

        // ── Order Status ──────────────────────────────────────────────────────
        if ($request->has('status') && $request->status) {
            if ($order->status !== 'pending' && $request->status !== $order->status) {
                $msg = 'Only pending orders can have their status changed.';
                if ($request->expectsJson()) return response()->json(['success' => false, 'message' => $msg], 422);
                return redirect()->back()->with('error', $msg);
            }
            $data['status'] = $request->status;
            if ($request->status === 'complete') {
                $data['delivery_date']  = $request->delivery_date ?? now()->toDateString();
                $data['payment_status'] = 'completed';
            }
            if ($request->status === 'return') {
                $data['return_date']    = $request->return_date ?? now()->toDateString();
                $data['payment_status'] = 'refunded';
            }
            if ($request->status === 'rescheduling') {
                $data['rescheduling_date'] = $request->rescheduling_date ?? now()->toDateString();
            }
            if ($request->status === 'cancelled') {
                $data['payment_status'] = 'cancelled';
            }
        }

        // ── Call Status ───────────────────────────────────────────────────────
        if ($request->has('call_status') && $request->call_status) {
            $addressForConfirm = $request->customer_address ?? $order->customer_address;
            if ($request->call_status === 'Confirmed' && empty($addressForConfirm)) {
                $msg = 'Customer address is required before confirming order.';
                if ($request->expectsJson()) return response()->json(['success' => false, 'message' => $msg], 422);
                return redirect()->back()->with('error', $msg);
            }
            $data['call_status'] = $request->call_status;
            if ($request->call_status === 'Confirmed' && $order->status === 'pending') {
                $this->createDeliveryCall($order);
            }
        }

        // ── Customer Details ──────────────────────────────────────────────────
        if ($request->filled('customer_name'))    $data['customer_name']    = $request->customer_name;
        if ($request->filled('customer_phone'))   $data['customer_phone']   = $request->customer_phone;
        if ($request->has('customer_phone_2'))    $data['customer_phone_2'] = $request->customer_phone_2;
        if ($request->filled('customer_address')) $data['customer_address'] = $request->customer_address;

        // ── Product / Pricing ─────────────────────────────────────────────────
        if ($request->filled('quantity')) {
            $data['quantity'] = (int) $request->quantity;
        }
        if ($request->filled('product_price')) {
            $data['product_price'] = (float) $request->product_price;
        }
        if ($request->filled('total_amount')) {
            $data['total_amount'] = (float) $request->total_amount;
            $data['subtotal']     = (float) $request->total_amount;
        }

        // ── Delivery Location ─────────────────────────────────────────────────
        if ($request->filled('district'))               $data['district']               = $request->district;
        if ($request->filled('destination_city_id'))    $data['destination_city_id']    = (int) $request->destination_city_id;
        if ($request->filled('destination_city_name'))  $data['destination_city_name']  = $request->destination_city_name;
        if ($request->filled('destination_state_name')) $data['destination_state_name'] = $request->destination_state_name;

        // ── Other Fields ──────────────────────────────────────────────────────
        if ($request->filled('order_date'))     $data['order_date']     = $request->order_date;
        if ($request->filled('platform'))       $data['platform']       = $request->platform;
        if ($request->filled('payment_method')) $data['payment_method'] = $request->payment_method;
        if ($request->has('remark'))            $data['remark']         = $request->remark;
        if ($request->has('delivery_remark'))   $data['delivery_remark']= $request->delivery_remark;

        if (!empty($data)) $order->update($data);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Order updated successfully']);
        }

        // Redirect back to the edit page with success, or to pending list
        return redirect()->route('seller.order.edit', $order->id)
            ->with('success', 'Order #' . $order->order_number . ' updated successfully!');
    }

    // ── Create Delivery Call ──────────────────────────────────────────────────

    private function createDeliveryCall(SellerOrder $order)
    {
        $seller          = Saler::find($order->seller_id);
        $customerAddress = $order->customer_address;

        if (empty($customerAddress)) {
            $customerAddress = 'No address provided - ' . ($order->district ?? 'Unknown location');
        }

        $deliveryRemark = trim(implode(' | ', array_filter([
            $order->remark,
            !empty($order->delivery_remark) ? '[Delivery: ' . $order->delivery_remark . ']' : null,
        ])));

        $deliveryData = [
            'seller_id'              => $order->seller_id,
            'product_id'             => $order->product_id,
            'order_code'             => $order->order_number,
            'date'                   => now()->toDateString(),
            'status_date'            => now(),
            'customer_name'          => $order->customer_name,
            'customer_phone_01'      => $order->customer_phone,
            'customer_phone_02'      => $order->customer_phone_2 ?? null,
            'customer_address'       => $customerAddress,
            'city'                   => $order->destination_city_name,
            'district'               => $order->district,
            'destination_city_id'    => $order->destination_city_id,
            'destination_state_name' => $order->destination_state_name,
            'item_code'              => $order->product_code,
            'item_name'              => $order->product_name,
            'qty'                    => $order->quantity,
            'price'                  => $order->product_price,
            'total'                  => $order->total_amount,
            'status'                 => 'pending',
            'platform'               => $order->platform ?? 'manual',
            'remark'                 => $deliveryRemark,
            'download_status'        => false,
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
            Log::info('Delivery call created', ['id' => $deliveryCall->id, 'order_code' => $deliveryCall->order_code]);
        } catch (Exception $e) {
            Log::error('Failed to create delivery call: ' . $e->getMessage(), ['order_number' => $order->order_number]);
        }
    }

    // ── Delete ────────────────────────────────────────────────────────────────

    public function destroy($id)
    {
        $user  = Auth::user();
        $order = SellerOrder::where('seller_id', $user->id)->findOrFail($id);

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending orders can be deleted.');
        }

        try {
            DB::beginTransaction();
            $refundAmount = $user->cost ?? 0;
            if (isset($user->cal_deposit))  $user->increment('cal_deposit', $refundAmount);
            elseif (isset($user->deposit))  $user->increment('deposit', $refundAmount);
            if ($order->product_id) SellerProduct::where('id', $order->product_id)->increment('qty', $order->quantity ?? 1);
            $order->delete();
            DB::commit();
            return redirect()->route('seller.order.pending')->with('success', 'Order deleted! Stock and deposit restored.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ── Deposit Helper ────────────────────────────────────────────────────────

    private function deductDeposit($user): void
    {
        $cost = $user->cost ?? 0;
        if ($cost <= 0) return;
        try {
            if (isset($user->cal_deposit))  $user->decrement('cal_deposit', $cost);
            elseif (isset($user->deposit))  $user->decrement('deposit', $cost);
        } catch (Exception $e) {
            Log::warning('Deposit decrement failed', ['user_id' => $user->id]);
        }
    }

    // ── Dispatch to Curfox ────────────────────────────────────────────────────

    public function dispatchToCurfox(Request $request, $id)
    {
        $order = SellerOrder::where('seller_id', Auth::id())->findOrFail($id);
        if (empty($order->customer_address)) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot dispatch: Customer address is missing.',
            ], 422);
        }
    }
}
