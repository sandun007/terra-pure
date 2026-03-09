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
use Throwable;

class SellerOrderController extends Controller
{

    // Add this method to SellerOrderController
    private function createPendingCallFromOrder($order, $user, $product)
    {
        try {
            // Generate call number
            $callNumber = SellerCall::generateCallNumber();

            // Create the pending call
            $call = SellerCall::create([
                'seller_id'              => $user->id,
                'product_id'             => $product->id,
                'order_code'             => $order->order_number,
                'call_number'            => $callNumber,
                'date'                   => now()->toDateString(),
                'customer_name'          => $order->customer_name,
                'customer_phone_01'      => $order->customer_phone,
                'customer_phone_02'      => null, // You might want to add this to orders table
                'customer_address'       => $order->customer_address,
                'city'                   => $order->destination_city_name,
                'district'               => $order->district,
                'destination_city_id'    => $order->destination_city_id,
                'destination_state_name' => $order->destination_state_name,
                'item_code'              => $product->code,
                'item_name'              => $product->name,
                'qty'                    => $order->quantity,
                'price'                  => $product->display_price,
                'total'                  => $order->total_amount,
                'status'                 => 'Pending', // Default status
                'status_date'            => now(),
                'remark'                 => $order->remark ?? 'Auto-generated from order',
                'platform'               => $order->platform ?? 'manual',
                'download_status'        => false,
            ]);

            Log::info('Pending call auto-created from order', [
                'order_id' => $order->id,
                'call_id' => $call->id,
                'order_number' => $order->order_number
            ]);

            return $call;

        } catch (Exception $e) {
            Log::error('Failed to create pending call from order', [
                'order_id' => $order->id ?? null,
                'error' => $e->getMessage()
            ]);
            // Don't throw - we don't want to fail the order if call creation fails
            return null;
        }
    }

    public function __construct(private Curfoxapiservice $curfox) {}

    // ──────────────────────────────────────────────────────────────────────
    // ORDER ID GENERATOR (controller-level)
    // ──────────────────────────────────────────────────────────────────────
    public static function generateOrderId(): string
    {
        $prefix = 'ORD/';

        if (!Schema::hasTable('seller_orders')) {
            return $prefix . '00001';
        }

        try {
            $last = DB::table('seller_orders')
                ->select('order_id', 'order_number', 'id')
                ->where(function ($q) {
                    $q->whereNotNull('order_id')->orWhereNotNull('order_number');
                })
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            $next = 1;

            if ($last) {
                $source = $last->order_id ?: ($last->order_number ?: '');
                if ($source && preg_match('/(\d+)$/', $source, $m)) {
                    $next = ((int) $m[1]) + 1;
                } else {
                    $next = ((int) $last->id) + 1;
                }
            }

            return $prefix . str_pad((string) $next, 5, '0', STR_PAD_LEFT);

        } catch (Throwable $e) {
            Log::error('SellerOrderController generateOrderId failed: ' . $e->getMessage());
            return $prefix . date('ymdHis');
        }
    }

    // ──────────────────────────────────────────────────────────────────────
    // CITY RESOLVER — match city name string → {id, city_name, state_name}
    // Used by bulk import to auto-resolve typed city names.
    // ──────────────────────────────────────────────────────────────────────
    private function resolveCityByName(string $nameInput): ?array
    {
        if (empty(trim($nameInput))) return null;

        try {
            $all = $this->curfox->getStatesWithCities();
            $needle = strtolower(trim($nameInput));

            $exact   = null;
            $partial = null;

            foreach ($all as $state) {
                foreach ($state['cities'] ?? [] as $city) {
                    if (!($city['is_active'] ?? true)) continue;

                    $cityLower = strtolower($city['name']);

                    if ($cityLower === $needle) {
                        $exact = [
                            'id'         => $city['id'],
                            'city_name'  => $city['name'],
                            'state_name' => $state['name'],
                        ];
                        break 2;
                    }

                    if ($partial === null && str_contains($cityLower, $needle)) {
                        $partial = [
                            'id'         => $city['id'],
                            'city_name'  => $city['name'],
                            'state_name' => $state['name'],
                        ];
                    }
                }
            }

            return $exact ?? $partial;

        } catch (Exception $e) {
            Log::warning('resolveCityByName failed: ' . $e->getMessage());
            return null;
        }
    }

    // ──────────────────────────────────────────────────────────────────────
    // RESOLVE CITY (id or name) → canonical city info
    // Returns ['id','city_name','state_name'] or null
    // ──────────────────────────────────────────────────────────────────────
    private function resolveCity(?int $cityId, ?string $cityName, ?string $stateName): ?array
    {
        // If we already have complete valid data, skip API
        if ($cityId && $cityName && $stateName) {
            return [
                'id'         => $cityId,
                'city_name'  => $cityName,
                'state_name' => $stateName,
            ];
        }

        // Lookup by ID first
        if ($cityId) {
            try {
                $info = $this->curfox->findCityById($cityId);
                if ($info) return $info;
            } catch (Exception $e) {
                Log::warning('Curfox city lookup by ID failed: ' . $e->getMessage());
            }
        }

        // Fallback: resolve by name
        if ($cityName) {
            return $this->resolveCityByName($cityName);
        }

        return null;
    }

    // ──────────────────────────────────────────────────────────────────────
    // NEW ORDER PAGE
    // ──────────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $user = Auth::user();

        try {
            $code = Schema::hasTable('seller_orders')
                ? self::generateOrderId()
                : 'ORD/00001';
        } catch (Exception $e) {
            $code = 'ORD/00001';
            Log::error('Error generating order ID: ' . $e->getMessage());
        }

        $import = $user->cal_deposit ?? $user->deposit ?? 0;

        $statesWithCities = [];
        $apiError         = null;
        try {
            $statesWithCities = $this->curfox->getStatesWithCities();
        } catch (Exception $e) {
            $apiError = 'Unable to load cities from delivery API. Please try again.';
            Log::warning('Curfox city load failed on new-order page: ' . $e->getMessage());
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

        return view('seller.order.new_order', compact(
            'user', 'code', 'import',
            'productCode', 'productName', 'productPrice', 'productStock',
            'quantity', 'productImage', 'productDescription', 'productCategory', 'productId',
            'statesWithCities', 'apiError'
        ));
    }

    // ──────────────────────────────────────────────────────────────────────
    // API: cities JSON for JS autocomplete
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

    // ──────────────────────────────────────────────────────────────────────
    // STORE SINGLE ORDER
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
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Please login to continue.'], 401)
                : redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $availableDeposit = $user->cal_deposit ?? $user->deposit ?? 0;
        $cost             = $user->cost ?? 0;

        if ($availableDeposit < $cost) {
            $msg = 'Insufficient deposit. You need at least Rs. ' . $cost . ' to create an order.';
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $msg], 400)
                : redirect()->route('seller.new-order')->with('error', $msg);
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
                'message'    => $e->getMessage(),
            ]);

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Error creating order: ' . $e->getMessage()], 500)
                : redirect()->route('seller.new-order')->withInput()->with('error', 'Error creating order: ' . $e->getMessage());
        }
    }

    private function handleSingleOrder(Request $request, $user): array
    {
        $product  = SellerProduct::findOrFail($request->product_id);
        $quantity = (int) ($request->quantity ?? 1);

        if ((int)$product->qty < $quantity) {
            throw new Exception("Insufficient stock. Available: {$product->qty}, Requested: {$quantity}");
        }

        $orderId      = self::generateOrderId();
        $productPrice = (float) $request->product_price;
        $totalAmount  = $productPrice * $quantity;

        // ── Resolve city ──
        $resolved  = $this->resolveCity(
            $request->destination_city_id ? (int) $request->destination_city_id : null,
            $request->destination_city_name ?? $request->district,
            $request->destination_state_name
        );

        $cityId    = $resolved['id']         ?? null;
        $cityName  = $resolved['city_name']  ?? ($request->destination_city_name ?? $request->district ?? '');
        $stateName = $resolved['state_name'] ?? ($request->destination_state_name ?? '');

        if (empty($cityName)) {
            throw new Exception('Delivery city is required and could not be resolved. Please select a valid city from the dropdown.');
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
        $product->decrement('qty', $quantity);

        // ── NEW: Auto-create pending call ──
        $this->createPendingCallFromOrder($order, $user, $product);
        // ───────────────────────────────────

        return ['success' => true, 'order' => $order];
    }

    // ──────────────────────────────────────────────────────────────────────
    // ✅ BULK STORE — CSV Import with city auto-resolution + validation
    // ──────────────────────────────────────────────────────────────────────
    public function bulkStore(Request $request)
    {
        // Accept leads as JSON string (FormData)
        if (is_string($request->input('leads'))) {
            $decoded = json_decode($request->input('leads'), true);
            $request->merge(['leads' => is_array($decoded) ? $decoded : []]);
        }

        $request->validate([
            'product_code'                    => 'required|string',
            'leads'                           => 'required|array|min:1',
            'leads.*.customer_name'           => 'required|string|max:255',
            'leads.*.customer_phone'          => 'required|string|max:20',
            'leads.*.customer_address'        => 'nullable|string',
            'leads.*.district'                => 'nullable|string|max:150',
            'leads.*.destination_city_id'     => 'nullable|integer',
            'leads.*.destination_city_name'   => 'nullable|string|max:150',
            'leads.*.destination_state_name'  => 'nullable|string|max:150',
            'leads.*.platform'                => 'nullable|string|max:50',
            'leads.*.payment_method'          => 'nullable|string|max:50',
            'leads.*.remark'                  => 'nullable|string',
            'default_platform'                => 'nullable|string|max:50',
            'default_payment_method'          => 'nullable|string|max:50',
            'default_call_status'             => 'nullable|string|max:50',
            'date'                            => 'nullable|date',
        ]);

        $user = Auth::user();
        if (! $user) return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);

        $product = SellerProduct::where('seller_id', $user->id)
            ->where('code', $request->product_code)
            ->first();

        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found for code: ' . $request->product_code,
            ], 404);
        }

        $leads = $request->leads;
        $count = count($leads);

        // ── Deposit check ──────────────────────────────────────────────
        $costPerOrder     = (float) ($user->cost ?? 0);
        $need             = $costPerOrder * $count;
        $availableDeposit = $user->cal_deposit ?? $user->deposit ?? 0;

        if ($costPerOrder > 0 && $availableDeposit < $need) {
            return response()->json([
                'success' => false,
                'message' => "Insufficient deposit. Need Rs. {$need} for {$count} orders (Rs. {$costPerOrder} each).",
            ], 400);
        }

        // ── Stock check ────────────────────────────────────────────────
        if ((int)$product->qty < $count) {
            return response()->json([
                'success' => false,
                'message' => "Insufficient stock. Available: {$product->qty}, Required: {$count}",
            ], 400);
        }

        // ── Pre-resolve all city names (bulk cache, avoids N API calls) ─
        // Build city lookup map from Curfox once
        $cityLookupMap = [];   // lowercase_name => {id, city_name, state_name}
        $idLookupMap   = [];   // id => {id, city_name, state_name}

        try {
            $statesWithCities = $this->curfox->getStatesWithCities();
            foreach ($statesWithCities as $state) {
                foreach ($state['cities'] ?? [] as $city) {
                    if (!($city['is_active'] ?? true)) continue;
                    $entry = [
                        'id'         => $city['id'],
                        'city_name'  => $city['name'],
                        'state_name' => $state['name'],
                    ];
                    $cityLookupMap[strtolower(trim($city['name']))] = $entry;
                    $idLookupMap[$city['id']] = $entry;
                }
            }
        } catch (Exception $e) {
            Log::warning('Bulk: Curfox city list load failed — city validation will be skipped: ' . $e->getMessage());
        }

        /**
         * Resolve a single lead's city fields using the pre-loaded map.
         * Returns ['id','city_name','state_name'] or null.
         */
        $quickResolve = function (array $lead) use ($cityLookupMap, $idLookupMap): ?array {
            $cityId    = isset($lead['destination_city_id'])   ? (int) $lead['destination_city_id']   : null;
            $cityName  = $lead['destination_city_name'] ?? ($lead['district'] ?? null);
            $stateName = $lead['destination_state_name'] ?? null;

            // All three already provided
            if ($cityId && $cityName && $stateName) {
                // Verify ID exists in lookup
                if (isset($idLookupMap[$cityId])) {
                    return $idLookupMap[$cityId];
                }
                // Fallback: accept as-is (when API list not loaded)
                return ['id' => $cityId, 'city_name' => $cityName, 'state_name' => $stateName];
            }

            // Resolve by ID
            if ($cityId && isset($idLookupMap[$cityId])) {
                return $idLookupMap[$cityId];
            }

            // Resolve by name (exact)
            if ($cityName) {
                $needle = strtolower(trim($cityName));
                if (isset($cityLookupMap[$needle])) {
                    return $cityLookupMap[$needle];
                }
                // Partial match
                foreach ($cityLookupMap as $key => $entry) {
                    if (str_contains($key, $needle) || str_contains($needle, $key)) {
                        return $entry;
                    }
                }
            }

            return null;
        };

        // ── Validate all rows upfront and collect city errors ───────────
        $validationErrors = [];
        foreach ($leads as $i => $lead) {
            $resolved = $quickResolve($lead);
            $cityName = $lead['destination_city_name'] ?? ($lead['district'] ?? null);

            if (!empty($cityName) && !$resolved) {
                $validationErrors[] = [
                    'row'     => $i + 1,
                    'name'    => $lead['customer_name'] ?? '-',
                    'city'    => $cityName,
                    'message' => "City \"{$cityName}\" not found in Curfox delivery network.",
                ];
            }
        }

        if (!empty($validationErrors)) {
            return response()->json([
                'success'           => false,
                'message'           => count($validationErrors) . ' rows have invalid city names. Please fix and re-upload.',
                'city_errors'       => $validationErrors,
                'city_errors_count' => count($validationErrors),
            ], 422);
        }

        // ── Process imports ────────────────────────────────────────────
        $date              = $request->date ?? now()->toDateString();
        $defaultPlatform   = $request->default_platform ?? 'fb';
        $defaultPayment    = $request->default_payment_method ?? 'cash';
        $defaultCallStatus = $request->default_call_status ?? 'Pending Call';

        $successCount = 0;
        $errorCount   = 0;
        $errors       = [];

        try {
            DB::beginTransaction();

            // Lock product row
            $product = SellerProduct::where('id', $product->id)->lockForUpdate()->first();

            foreach ($leads as $i => $lead) {
                try {
                    if ((int)$product->qty < 1) throw new Exception('Insufficient stock');

                    $orderId  = self::generateOrderId();
                    $resolved = $quickResolve($lead);

                    // City is required — if still null after resolve, use fallback text or fail
                    $rawCity = $lead['destination_city_name'] ?? ($lead['district'] ?? null);

                    if ($resolved) {
                        $finalCityId    = $resolved['id'];
                        $finalCityName  = $resolved['city_name'];
                        $finalStateName = $resolved['state_name'];
                    } elseif ($rawCity) {
                        // Non-Curfox city or API list not loaded — store raw value (no city_id)
                        $finalCityId    = null;
                        $finalCityName  = trim($rawCity);
                        $finalStateName = $lead['destination_state_name'] ?? '';
                    } else {
                        // No city at all — skip row
                        throw new Exception('Delivery city is required but was not provided.');
                    }

                    SellerOrder::create([
                        'seller_id'   => $user->id,
                        'order_id'    => $orderId,
                        'order_number' => $orderId,

                        'product_id'    => $product->id,
                        'product_name'  => $product->name,
                        'product_code'  => $product->code,
                        'product_price' => (float) $product->display_price,
                        'product_image' => $product->image,

                        'customer_name'    => $lead['customer_name'],
                        'customer_phone'   => $lead['customer_phone'],
                        'customer_address' => $lead['customer_address'] ?? null,

                        // ✅ All three are guaranteed non-null
                        'district'               => $finalCityName,
                        'destination_city_id'    => $finalCityId,
                        'destination_city_name'  => $finalCityName,
                        'destination_state_name' => $finalStateName,

                        'platform'    => $lead['platform']        ?? $defaultPlatform,
                        'call_status' => $defaultCallStatus,
                        'remark'      => $lead['remark']          ?? null,

                        'order_date' => $date,
                        'status'     => 'pending',

                        'quantity'     => 1,
                        'subtotal'     => (float) $product->display_price,
                        'total_amount' => (float) $product->display_price,

                        'payment_method' => $lead['payment_method'] ?? $defaultPayment,
                        'payment_status' => 'pending',
                    ]);

                    $product->decrement('qty', 1);
                    $this->deductDeposit($user);
                    $successCount++;

                } catch (Exception $e) {
                    $errorCount++;
                    $errors[] = 'Row ' . ($i + 1) . ' (' . ($lead['customer_name'] ?? '-') . '): ' . $e->getMessage();
                }
            }

            DB::commit();

            return response()->json([
                'success'      => true,
                'message'      => "Bulk import done. {$successCount} created, {$errorCount} failed.",
                'successCount' => $successCount,
                'errorCount'   => $errorCount,
                'errors'       => $errors,
                'redirect_url' => route('seller.pending-order'),
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Bulk store error: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ──────────────────────────────────────────────────────────────────────
    // ✅ BULK VALIDATE (AJAX) — pre-check cities before import
    // Called by JS after CSV parse; returns per-row city status
    // ──────────────────────────────────────────────────────────────────────
    public function bulkValidate(Request $request)
    {
        if (is_string($request->input('leads'))) {
            $decoded = json_decode($request->input('leads'), true);
            $request->merge(['leads' => is_array($decoded) ? $decoded : []]);
        }

        $request->validate([
            'leads'            => 'required|array|min:1',
            'leads.*.district' => 'nullable|string',
        ]);

        try {
            $statesWithCities = $this->curfox->getStatesWithCities();
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not load city list from Curfox: ' . $e->getMessage(),
            ], 500);
        }

        // Build lookup
        $cityLookupMap = [];
        foreach ($statesWithCities as $state) {
            foreach ($state['cities'] ?? [] as $city) {
                if (!($city['is_active'] ?? true)) continue;
                $cityLookupMap[strtolower(trim($city['name']))] = [
                    'id'         => $city['id'],
                    'city_name'  => $city['name'],
                    'state_name' => $state['name'],
                ];
            }
        }

        $results = [];
        foreach ($request->leads as $i => $lead) {
            $rawCity = $lead['destination_city_name'] ?? ($lead['district'] ?? '');
            $needle  = strtolower(trim($rawCity));

            if (empty($needle)) {
                $results[] = [
                    'row'    => $i + 1,
                    'input'  => '',
                    'status' => 'missing',
                    'resolved' => null,
                ];
                continue;
            }

            $resolved = null;

            // Exact
            if (isset($cityLookupMap[$needle])) {
                $resolved = $cityLookupMap[$needle];
            } else {
                // Partial
                foreach ($cityLookupMap as $key => $entry) {
                    if (str_contains($key, $needle) || str_contains($needle, $key)) {
                        $resolved = $entry;
                        break;
                    }
                }
            }

            $results[] = [
                'row'      => $i + 1,
                'input'    => $rawCity,
                'status'   => $resolved ? 'ok' : 'invalid',
                'resolved' => $resolved,
            ];
        }

        $invalid = array_filter($results, fn($r) => $r['status'] === 'invalid');
        $missing = array_filter($results, fn($r) => $r['status'] === 'missing');

        return response()->json([
            'success'       => true,
            'results'       => $results,
            'invalid_count' => count($invalid),
            'missing_count' => count($missing),
            'total'         => count($results),
            'all_valid'     => count($invalid) === 0 && count($missing) === 0,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────
    // ✅ CURFOX DISPATCH — push a pending order to Curfox API
    // Matches: POST /api/public/merchant/order/single
    // ──────────────────────────────────────────────────────────────────────
    public function dispatchToCurfox(Request $request, $id)
    {
        if (! Schema::hasTable('seller_orders')) abort(404);

        $user  = Auth::user();
        $order = SellerOrder::where('seller_id', $user->id)->findOrFail($id);

        // Must have city info
        if (empty($order->destination_city_name) || empty($order->destination_state_name)) {
            return response()->json([
                'success' => false,
                'message' => 'Order is missing destination city or state. Please update the order before dispatching.',
            ], 422);
        }

        // Build Curfox payload matching the API spec
        try {
            $merchantBusinessId = $user->curfox_business_id
                ?? config('services.curfox.merchant_business_id');

            // Origin — from seller profile or config
            $originCity  = $user->origin_city_name  ?? config('services.curfox.origin_city_name',  'Colombo');
            $originState = $user->origin_state_name ?? config('services.curfox.origin_state_name', 'Colombo');

            $payload = [
                'general_data' => [
                    'merchant_business_id' => (string) $merchantBusinessId,
                    'origin_city_name'     => $originCity,
                    'origin_state_name'    => $originState,
                ],
                'order_data' => [
                    [
                        'order_no'                 => $order->order_number,
                        'customer_name'            => $order->customer_name,
                        'customer_address'         => $order->customer_address ?? $order->district ?? '',
                        'customer_phone'           => $order->customer_phone,
                        'customer_secondary_phone' => $order->customer_phone_2 ?? null,
                        'destination_city_name'    => $order->destination_city_name,
                        'destination_state_name'   => $order->destination_state_name,
                        'cod'                      => (int) $order->total_amount,
                        'description'              => $order->product_name ?? '',
                        'weight'                   => (float) ($order->weight ?? 1),
                        'remark'                   => $order->remark ?? '',
                    ],
                ],
            ];

            // Call Curfox service
            $response = $this->curfox->createOrder($payload);

            // Store waybill
            $waybill = $response['data'][0] ?? null;
            if ($waybill) {
                $order->update([
                    'curfox_waybill'       => $waybill,
                    'curfox_dispatched_at' => now(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Order dispatched to Curfox successfully.',
                'waybill' => $waybill,
                'data'    => $response,
            ]);

        } catch (Exception $e) {
            Log::error('Curfox dispatch failed', [
                'order_id' => $id,
                'message'  => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Curfox dispatch failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ──────────────────────────────────────────────────────────────────────
    // PRODUCT DETAILS (AJAX)
    // ──────────────────────────────────────────────────────────────────────
    public function getProductDetails($code)
    {
        try {
            $seller = Auth::user();
            if (! $seller) return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);

            $decodedCode = urldecode($code);

            $product = SellerProduct::where('seller_id', $seller->id)
                ->where('code', $decodedCode)
                ->with('category')
                ->first();

            if (! $product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found with code: ' . $decodedCode,
                ], 404);
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
    // LIST PAGES
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

        // ── Auto-create EmployeeDeliveryCall when Complete + Confirmed ──
        if ($request->status === 'complete' && $request->call_status === 'Confirmed') {
            $product = SellerProduct::find($order->product_id);
            $this->createEmployeeDeliveryCall($order, $product);
        }
        // ───────────────────────────────────────────────────────────────

        return redirect()->back()->with('success', 'Order updated successfully!');
    }

    private function createEmployeeDeliveryCall(SellerOrder $order, ?SellerProduct $product): void
    {
        try {
            // Avoid duplicate delivery calls for the same order
            $exists = \App\Models\EmployeeDeliveryCall::where('order_code', $order->order_number)->exists();
            if ($exists) {
                Log::info('EmployeeDeliveryCall already exists for order', ['order_number' => $order->order_number]);
                return;
            }

            \App\Models\EmployeeDeliveryCall::create([
                'seller_id'              => $order->seller_id,
                'product_id'             => $order->product_id,
                'order_code'             => $order->order_number,
                'call_number'            => $order->order_number, // reuse order number as call ref
                'date'                   => now()->toDateString(),
                'status_date'            => now(),
                'customer_name'          => $order->customer_name,
                'customer_phone_01'      => $order->customer_phone,
                'customer_phone_02'      => null,
                'customer_address'       => $order->customer_address,
                'city'                   => $order->destination_city_name,
                'district'               => $order->district,
                'destination_city_id'    => $order->destination_city_id,
                'destination_state_name' => $order->destination_state_name,
                'item_code'              => $order->product_code,
                'item_name'              => $order->product_name,
                'qty'                    => $order->quantity,
                'price'                  => $order->product_price,
                'total'                  => $order->total_amount,
                'status'                 => 'Pending',   // starts at Pending in delivery flow
                'remark'                 => $order->remark ?? 'Auto-created from confirmed order',
                'platform'               => $order->platform ?? 'manual',
                'download_status'        => false,
            ]);

            Log::info('EmployeeDeliveryCall auto-created from confirmed order', [
                'order_id'     => $order->id,
                'order_number' => $order->order_number,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to auto-create EmployeeDeliveryCall', [
                'order_id' => $order->id ?? null,
                'error'    => $e->getMessage(),
            ]);
            // Non-fatal — order update already succeeded
        }
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
    // HELPERS
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
            try {
                $user->decrement('cal_deposit', $cost);
            } catch (Exception $e) {
                try {
                    $user->decrement('deposit', $cost);
                } catch (Exception $e2) {
                    Log::warning('Could not decrement deposit', ['user_id' => $user->id]);
                }
            }
        }
    }
}
