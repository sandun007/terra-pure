<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SelfSellerDeliveryCall;
use App\Models\SellerCurfoxBusiness;
use App\Models\Saler;
use App\Services\Curfoxapiservice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SelfSellerDeliveryCallController extends Controller
{
    private const FLOW = [
        'Pending'    => 'packing',
        'packing'    => 'packed',
        'packed'     => 'givedriver',
        'givedriver' => null,
    ];

    public function __construct(private Curfoxapiservice $curfox) {}

    // ── Auth helper ────────────────────────────────────────────────────────

    private function seller(): Saler
    {
        return Auth::guard('seller')->user();
    }

    private function assertSelfSeller(): void
    {
        if ($this->seller()->type !== 'self_seller') {
            abort(403, 'Only self-sellers can access this section.');
        }
    }

    // ── Index / redirect ───────────────────────────────────────────────────

    public function index()
    {
        return redirect()->route('seller.delivery_calls.pending');
    }

    // ── Status tab pages ───────────────────────────────────────────────────

    public function pending(Request $request)    { return $this->statusPage($request, 'Pending'); }
    public function packing(Request $request)    { return $this->statusPage($request, 'packing'); }
    public function packed(Request $request)     { return $this->statusPage($request, 'packed'); }
    public function givedriver(Request $request) { return $this->statusPage($request, 'givedriver'); }

    private function statusPage(Request $request, string $status)
    {
        $this->assertSelfSeller();

        $seller = $this->seller();
        $q      = trim((string) $request->get('q', ''));

        $callsQuery = SelfSellerDeliveryCall::where('seller_id', $seller->id)
            ->where('status', $status)
            ->latest('id');

        if ($q !== '') {
            $callsQuery->where(function ($qq) use ($q) {
                $qq->where('order_code',         'like', "%{$q}%")
                    ->orWhere('call_number',       'like', "%{$q}%")
                    ->orWhere('customer_name',     'like', "%{$q}%")
                    ->orWhere('customer_phone_01', 'like', "%{$q}%")
                    ->orWhere('item_name',         'like', "%{$q}%")
                    ->orWhere('city',              'like', "%{$q}%");
            });
        }

        $calls      = $callsQuery->paginate(20)->withQueryString();
        $nextStatus = self::FLOW[$status] ?? null;

        // Curfox cities for origin picker
        $curfoxCities = [];
        try {
            $curfoxCities = $this->curfox->getAllCities();
        } catch (\Exception $e) {
            Log::warning('Could not load Curfox cities: ' . $e->getMessage());
        }

        // This seller's own businesses
        $myBusinesses     = SellerCurfoxBusiness::where('seller_id', $seller->id)->get();
        $activeBusinessId = $this->getActiveBusinessId($seller, $myBusinesses);

        $activeBusiness = $myBusinesses->firstWhere('curfox_id', $activeBusinessId);

        return view('seller.delivery_calls.index', compact(
            'calls', 'status', 'nextStatus',
            'curfoxCities', 'myBusinesses', 'activeBusinessId', 'activeBusiness', 'seller'
        ));
    }

    // ── Show single ────────────────────────────────────────────────────────

    public function show(int $id)
    {
        $this->assertSelfSeller();

        $call         = SelfSellerDeliveryCall::where('seller_id', $this->seller()->id)->findOrFail($id);
        $myBusinesses = SellerCurfoxBusiness::where('seller_id', $this->seller()->id)->get();

        return view('seller.delivery_calls.view', compact('call', 'myBusinesses'));
    }

    // ── Create / Store manual call ─────────────────────────────────────────

    public function create()
    {
        $this->assertSelfSeller();

        $seller       = $this->seller();
        $curfoxCities = [];
        try {
            $curfoxCities = $this->curfox->getAllCities();
        } catch (\Exception $e) {}

        $myBusinesses = SellerCurfoxBusiness::where('seller_id', $seller->id)->get();

        return view('seller.delivery_calls.create', compact('curfoxCities', 'myBusinesses', 'seller'));
    }

    public function store(Request $request)
    {
        $this->assertSelfSeller();

        $v = $request->validate([
            'customer_name'         => 'required|string|max:255',
            'customer_phone_01'     => 'required|string|max:20',
            'customer_phone_02'     => 'nullable|string|max:20',
            'customer_address'      => 'nullable|string',
            'city'                  => 'nullable|string|max:100',
            'district'              => 'nullable|string|max:100',
            'destination_city_id'   => 'nullable|integer',
            'destination_state_name'=> 'nullable|string|max:150',
            'item_name'             => 'required|string|max:255',
            'item_code'             => 'nullable|string|max:100',
            'qty'                   => 'required|integer|min:1',
            'price'                 => 'required|numeric|min:0',
            'platform'              => 'nullable|string|max:50',
            'remark'                => 'nullable|string',
            'date'                  => 'nullable|date',
        ]);

        $seller = $this->seller();

        $call = SelfSellerDeliveryCall::create(array_merge($v, [
            'seller_id'   => $seller->id,
            'call_number' => SelfSellerDeliveryCall::generateCallNumber($seller->id),
            'total'       => $v['price'] * $v['qty'],
            'status'      => 'Pending',
            'date'        => $v['date'] ?? now()->toDateString(),
        ]));

        return redirect()
            ->route('seller.delivery_calls.show', $call->id)
            ->with('success', "Delivery call {$call->call_number} created.");
    }

    // ── Business Management ────────────────────────────────────────────────

    public function businessIndex()
    {
        $this->assertSelfSeller();

        $seller       = $this->seller();
        $myBusinesses = SellerCurfoxBusiness::where('seller_id', $seller->id)->latest()->get();

        $curfoxCities = [];
        try {
            $curfoxCities = $this->curfox->getAllCities();
        } catch (\Exception $e) {}

        return view('seller.delivery_calls.businesses', compact('myBusinesses', 'seller', 'curfoxCities'));
    }

    /**
     * Create a new Curfox business for this self-seller.
     * POST /seller/delivery-calls/business/create
     */
    public function businessCreate(Request $request)
    {
        $this->assertSelfSeller();

        $v = $request->validate([
            'business_name'       => 'required|string|max:255',
            'address_line_1'      => 'required|string|max:255',
            'address_line_2'      => 'nullable|string|max:255',
            'address_line_3'      => 'nullable|string|max:255',
            'city_id'             => 'required|integer',
            'phone'               => 'required|string|max:20',
            'postal_code'         => 'nullable|string|max:20',
            'bank_account_name'   => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:50',
            'bank_name'           => 'required|string|max:100',
            'bank_branch'         => 'nullable|string|max:100',
        ]);

        $seller = $this->seller();

        // Build Curfox API payload per API spec
        $payload = [
            'business_name'        => $v['business_name'],
            'business_bank_detail' => [
                'account_name'   => $v['bank_account_name'],
                'account_number' => $v['bank_account_number'],
                'bank_name'      => $v['bank_name'],
                'branch_name'    => $v['bank_branch'] ?? '',
            ],
            'business_address'     => [
                'address_line_1' => $v['address_line_1'],
                'address_line_2' => $v['address_line_2'] ?? '',
                'address_line_3' => $v['address_line_3'] ?? '',
                'city_id'        => (int) $v['city_id'],
                'phone'          => $v['phone'],
                'postal_code'    => $v['postal_code'] ?? '',
                'type'           => 'general',
                'is_default'     => true,
            ],
        ];

        try {
            $response = $this->curfox->createBusiness($payload);
            $data     = $response['data'] ?? $response;

            // Resolve city name from our cache
            $cityInfo = $this->curfox->findCityById((int) $v['city_id']);

            SellerCurfoxBusiness::create([
                'seller_id'           => $seller->id,
                'curfox_id'           => (string) ($data['id'] ?? ''),       // row id — used as merchant_business_id in dispatch
                'ref_no'              => $data['ref_no'] ?? null,
                'business_name'       => $data['business_name'] ?? $v['business_name'],
                'merchant_id'         => (string) ($data['merchant_id'] ?? ''),
                'is_default'          => SellerCurfoxBusiness::where('seller_id', $seller->id)->count() === 0,
                'address_line_1'      => $v['address_line_1'],
                'address_line_2'      => $v['address_line_2'] ?? null,
                'address_line_3'      => $v['address_line_3'] ?? null,
                'city_id'             => (int) $v['city_id'],
                'city_name'           => $cityInfo['city_name'] ?? null,
                'phone'               => $v['phone'],
                'postal_code'         => $v['postal_code'] ?? null,
                'bank_account_name'   => $v['bank_account_name'],
                'bank_account_number' => $v['bank_account_number'],
                'bank_name'           => $v['bank_name'],
                'bank_branch'         => $v['bank_branch'] ?? null,
                'raw_response'        => $data,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success'       => true,
                    'message'       => "Business '{$v['business_name']}' created.",
                    'data'          => $data,
                    'curfox_id'     => $data['id'] ?? null,
                    'business_name' => $data['business_name'] ?? $v['business_name'],
                ]);
            }

            return redirect()
                ->route('seller.delivery_calls.businesses')
                ->with('success', "Business '{$v['business_name']}' created successfully in Curfox.");

        } catch (\Exception $e) {
            Log::error('Curfox business creation failed', [
                'seller_id' => $seller->id,
                'error'     => $e->getMessage(),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return back()->with('error', 'Failed: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Set which business is active for dispatch.
     * POST /seller/delivery-calls/business/set-active
     */
    public function businessSetActive(Request $request)
    {
        $this->assertSelfSeller();

        $v      = $request->validate(['business_id' => 'required|string']);
        $seller = $this->seller();

        // Verify this business belongs to the seller
        $biz = SellerCurfoxBusiness::where('seller_id', $seller->id)
            ->where('curfox_id', $v['business_id'])
            ->firstOrFail();

        session(['ss_curfox_biz_' . $seller->id => $v['business_id']]);

        if ($request->expectsJson()) {
            return response()->json([
                'success'       => true,
                'business_id'   => $v['business_id'],
                'business_name' => $biz->business_name,
                'message'       => "Active business set to: {$biz->business_name}",
            ]);
        }

        return back()->with('success', "Active business: {$biz->business_name}");
    }

    /**
     * Delete a local business record (does not delete from Curfox).
     */
    public function businessDelete(int $id)
    {
        $this->assertSelfSeller();

        $biz = SellerCurfoxBusiness::where('seller_id', $this->seller()->id)->findOrFail($id);
        $biz->delete();

        return back()->with('success', 'Business removed.');
    }

    // ── Origin updates ─────────────────────────────────────────────────────

    public function updateOrigin(Request $request, int $id)
    {
        $this->assertSelfSeller();

        $v = $request->validate([
            'origin_city_id'    => 'required|integer',
            'origin_city_name'  => 'required|string|max:150',
            'origin_state_name' => 'required|string|max:150',
        ]);

        $call = SelfSellerDeliveryCall::where('seller_id', $this->seller()->id)->findOrFail($id);

        $call->update([
            'origin_city_id'    => $v['origin_city_id'],
            'origin_city_name'  => $v['origin_city_name'],
            'origin_state_name' => $v['origin_state_name'],
            'curfox_error'      => null,
        ]);

        return response()->json([
            'success'           => true,
            'message'           => "Origin → {$v['origin_city_name']} ({$v['origin_state_name']})",
            'origin_city_id'    => $call->origin_city_id,
            'origin_city_name'  => $call->origin_city_name,
            'origin_state_name' => $call->origin_state_name,
        ]);
    }

    public function bulkUpdateOrigin(Request $request)
    {
        $this->assertSelfSeller();

        $v = $request->validate([
            'ids'               => 'required|array|min:1',
            'ids.*'             => 'integer',
            'origin_city_id'    => 'required|integer',
            'origin_city_name'  => 'required|string|max:150',
            'origin_state_name' => 'required|string|max:150',
        ]);

        $updated = SelfSellerDeliveryCall::whereIn('id', $v['ids'])
            ->where('seller_id', $this->seller()->id)
            ->update([
                'origin_city_id'    => $v['origin_city_id'],
                'origin_city_name'  => $v['origin_city_name'],
                'origin_state_name' => $v['origin_state_name'],
                'curfox_error'      => null,
            ]);

        return response()->json([
            'success' => true,
            'message' => "Origin updated for {$updated} call(s).",
            'updated' => $updated,
        ]);
    }

    // ── Status transitions ─────────────────────────────────────────────────

    public function updateStatus(Request $request, int $id)
    {
        $this->assertSelfSeller();

        $seller = $this->seller();
        $call   = SelfSellerDeliveryCall::where('seller_id', $seller->id)->findOrFail($id);

        $from = $call->status;
        $next = self::FLOW[$from] ?? null;

        if (! $next) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Already at final status.'], 400);
            }
            return back()->with('error', 'Already at final status.');
        }

        if ($next === 'givedriver') {
            try {
                $waybill = $this->dispatchToCurfox($call, $seller);
                $call->update([
                    'status'               => 'givedriver',
                    'curfox_waybill'       => $waybill,
                    'curfox_dispatched_at' => now(),
                    'curfox_error'         => null,
                    'status_date'          => now(),
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => "Dispatched! Waybill: {$waybill}",
                        'waybill' => $waybill,
                    ]);
                }
                return back()->with('success', "Dispatched! Waybill: {$waybill}");

            } catch (\RuntimeException $e) {
                $call->update(['curfox_error' => $e->getMessage()]);

                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
                }
                return back()->with('error', 'Dispatch failed: ' . $e->getMessage());
            }
        }

        $call->update(['status' => $next, 'status_date' => now()]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => "{$from} → {$next}"]);
        }
        return back()->with('success', "Status: {$from} → {$next}");
    }

    public function bulkUpdateStatus(Request $request)
    {
        $this->assertSelfSeller();

        $data   = $request->validate(['ids' => 'required|array|min:1', 'ids.*' => 'integer']);
        $seller = $this->seller();
        $ids    = $data['ids'];

        $statuses = SelfSellerDeliveryCall::whereIn('id', $ids)
            ->where('seller_id', $seller->id)
            ->distinct()->pluck('status')->toArray();

        if (count($statuses) !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'All selected rows must share the same current status.',
            ], 422);
        }

        $from = $statuses[0];
        $next = self::FLOW[$from] ?? null;

        if (! $next) {
            return response()->json(['success' => false, 'message' => 'No next status.'], 400);
        }

        if ($next === 'givedriver') {
            $calls = SelfSellerDeliveryCall::whereIn('id', $ids)
                ->where('seller_id', $seller->id)
                ->where('status', $from)
                ->get();

            $ok = 0; $fail = 0;
            foreach ($calls as $call) {
                try {
                    $waybill = $this->dispatchToCurfox($call, $seller);
                    $call->update([
                        'status'               => 'givedriver',
                        'curfox_waybill'       => $waybill,
                        'curfox_dispatched_at' => now(),
                        'curfox_error'         => null,
                        'status_date'          => now(),
                    ]);
                    $ok++;
                } catch (\RuntimeException $e) {
                    $call->update(['curfox_error' => $e->getMessage()]);
                    $fail++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Dispatched {$ok}. Failed {$fail}.",
                'ok'      => $ok,
                'fail'    => $fail,
            ]);
        }

        $ok = SelfSellerDeliveryCall::whereIn('id', $ids)
            ->where('seller_id', $seller->id)
            ->where('status', $from)
            ->update(['status' => $next, 'status_date' => now()]);

        return response()->json([
            'success' => true,
            'message' => "Updated {$ok} row(s): {$from} → {$next}",
            'ok'      => $ok,
        ]);
    }

    // ── Curfox dispatch ────────────────────────────────────────────────────

    private function dispatchToCurfox(SelfSellerDeliveryCall $call, Saler $seller): string
    {
        // Resolve active business id from session → DB default → error
        $businessId = $this->getActiveBusinessId(
            $seller,
            SellerCurfoxBusiness::where('seller_id', $seller->id)->get()
        );

        if (! $businessId) {
            throw new \RuntimeException(
                'No Curfox business configured. Please go to Delivery → My Businesses and create one first.'
            );
        }

        // Resolve destination city
        $destCityId    = $call->destination_city_id ?? null;
        $destCityName  = null;
        $destStateName = $call->destination_state_name ?? null;

        if (! $destCityId) {
            $searchTerm = $call->city ?: $call->district;
            if ($searchTerm) {
                $info = $this->curfox->findCityByName($searchTerm);
                if ($info) {
                    $destCityId    = (int) $info['city_id'];
                    $destCityName  = $info['city_name'];
                    $destStateName = $info['state_name'];
                }
            }
        }

        if (! $destCityId && ! $destCityName) {
            throw new \RuntimeException(
                "Cannot determine destination city for call #{$call->id}. Please set a valid city."
            );
        }

        $originCityId    = $call->effectiveOriginCityId();
        $originCityName  = $call->effectiveOriginCityName();
        $originStateName = $call->effectiveOriginStateName();

        $orderData = [
            'order_no'         => $call->order_code ?? $call->call_number,
            'customer_name'    => $call->customer_name,
            'customer_address' => $call->customer_address ?? '',
            'customer_phone'   => $call->customer_phone_01,
            'cod'              => (int) round(($call->price ?? 0) * ($call->qty ?? 1)),
            'weight'           => 1,
            'description'      => $call->item_name ?? 'Parcel',
            'remark'           => $call->remark ?? '',
        ];

        if ($call->customer_phone_02) {
            $orderData['customer_secondary_phone'] = $call->customer_phone_02;
        }

        if ($destCityId) {
            $orderData['destination_city_id'] = $destCityId;
        } else {
            $orderData['destination_city_name']  = $destCityName;
            $orderData['destination_state_name'] = $destStateName;
        }

        $generalData = ['merchant_business_id' => (string) $businessId];

        if ($originCityId) {
            $generalData['origin_city_id'] = $originCityId;
        } else {
            $generalData['origin_city_name']  = $originCityName;
            $generalData['origin_state_name'] = $originStateName;
        }

        $response = $this->curfox->createOrders($generalData, [$orderData]);

        $waybills = $response['data'] ?? [];
        $waybill  = is_array($waybills) ? ($waybills[0] ?? null) : ($waybills ?: null);

        if (! $waybill) {
            throw new \RuntimeException('Curfox returned no waybill. Response: ' . json_encode($response));
        }

        // Save which business was used
        $biz = SellerCurfoxBusiness::where('seller_id', $seller->id)
            ->where('curfox_id', $businessId)->first();

        $call->curfox_business_id   = (string) $businessId;
        $call->curfox_business_name = $biz?->business_name;
        $call->save();

        return (string) $waybill;
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    private function getActiveBusinessId(Saler $seller, $businesses): ?string
    {
        $sessionKey = 'ss_curfox_biz_' . $seller->id;
        $id         = session($sessionKey);

        if ($id) {
            return $id;
        }

        // Fall back to DB default
        $default = $businesses instanceof \Illuminate\Database\Eloquent\Collection
            ? $businesses->firstWhere('is_default', true) ?? $businesses->first()
            : SellerCurfoxBusiness::where('seller_id', $seller->id)
                ->orderByDesc('is_default')->first();

        return $default?->curfox_id;
    }
}
