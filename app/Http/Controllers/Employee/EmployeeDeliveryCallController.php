<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDeliveryCall;
use App\Models\Saler;
use App\Services\Curfoxapiservice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeDeliveryCallController extends Controller
{
    private const FLOW = [
        'Pending'    => 'packing',
        'packing'    => 'packed',
        'packed'     => 'givedriver',
        'givedriver' => null,
    ];

    private const DEFAULT_MERCHANT_BUSINESS_ID = '7809';

    public function __construct(private Curfoxapiservice $curfox) {}

    // ── Status pages ──────────────────────────────────────────────────────

    public function index()
    {
        return redirect()->route('employee.delivery_calls.pending');
    }

    public function pending(Request $request)    { return $this->statusPage($request, 'Pending'); }
    public function packing(Request $request)    { return $this->statusPage($request, 'packing'); }
    public function packed(Request $request)     { return $this->statusPage($request, 'packed'); }
    public function givedriver(Request $request) { return $this->statusPage($request, 'givedriver'); }

    private function statusPage(Request $request, string $status)
    {
        $q = trim((string) $request->get('q', ''));

        // Get system seller IDs first
        $systemSellerIds = Saler::where('type', 'system_seller')->pluck('id')->toArray();

        $callsQuery = EmployeeDeliveryCall::query()
            ->where('status', $status)
            ->whereIn('seller_id', $systemSellerIds) // Only system sellers
            ->latest('id');

        if ($q !== '') {
            $callsQuery->where(function ($qq) use ($q) {
                $qq->where('order_code',         'like', "%{$q}%")
                    ->orWhere('call_number',       'like', "%{$q}%")
                    ->orWhere('customer_name',     'like', "%{$q}%")
                    ->orWhere('customer_phone_01', 'like', "%{$q}%")
                    ->orWhere('item_name',         'like', "%{$q}%")
                    ->orWhere('city',              'like', "%{$q}%")
                    ->orWhere('district',          'like', "%{$q}%");
            });
        }

        $calls      = $callsQuery->paginate(20)->withQueryString();
        $nextStatus = self::FLOW[$status] ?? null;

        $curfoxCities = [];
        try {
            $curfoxCities = $this->curfox->getAllCities();
        } catch (\Exception $e) {
            Log::warning('Could not load Curfox cities for origin picker: ' . $e->getMessage());
        }

        $curfoxBusinesses      = [];
        $curfoxDefaultBusiness = null;
        try {
            $curfoxBusinesses      = $this->curfox->getBusinesses();
            $curfoxDefaultBusiness = $this->curfox->getDefaultBusiness();
        } catch (\Exception $e) {
            Log::warning('Could not load Curfox businesses: ' . $e->getMessage());
        }

        $selectedBusinessId = session(
            'curfox_merchant_business_id',
            $curfoxDefaultBusiness['id'] ?? self::DEFAULT_MERCHANT_BUSINESS_ID
        );

        return view('employee.delivery_calls.index', compact(
            'calls',
            'status',
            'nextStatus',
            'curfoxCities',
            'curfoxBusinesses',
            'curfoxDefaultBusiness',
            'selectedBusinessId'
        ));
    }

    public function show($id)
    {
        $call = EmployeeDeliveryCall::findOrFail($id);

        // Check if this belongs to a system seller
        $seller = Saler::find($call->seller_id);
        if (!$seller || $seller->type !== 'system_seller') {
            abort(403, 'Unauthorized access to non-system seller order');
        }

        return view('employee.delivery_calls.view', compact('call'));
    }

    // ── Business selector ─────────────────────────────────────────────────

    public function setBusiness(Request $request)
    {
        $v = $request->validate([
            'merchant_business_id' => 'required|string|max:50',
        ]);

        session(['curfox_merchant_business_id' => $v['merchant_business_id']]);

        if ($request->expectsJson()) {
            return response()->json([
                'success'              => true,
                'merchant_business_id' => $v['merchant_business_id'],
                'message'              => 'Active business updated.',
            ]);
        }

        return back()->with('success', 'Active Curfox business updated.');
    }

    // ── Origin city update ────────────────────────────────────────────────

    public function updateOrigin(Request $request, int $id)
    {
        $v = $request->validate([
            'origin_city_id'    => 'required|integer',
            'origin_city_name'  => 'required|string|max:150',
            'origin_state_name' => 'required|string|max:150',
        ]);

        $call = EmployeeDeliveryCall::findOrFail($id);

        // Check if this belongs to a system seller
        $seller = Saler::find($call->seller_id);
        if (!$seller || $seller->type !== 'system_seller') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: This order does not belong to a system seller'
            ], 403);
        }

        $call->update([
            'origin_city_id'    => $v['origin_city_id'],
            'origin_city_name'  => $v['origin_city_name'],
            'origin_state_name' => $v['origin_state_name'],
            'curfox_error'      => null,
        ]);

        return response()->json([
            'success'           => true,
            'message'           => "Origin set to {$v['origin_city_name']} ({$v['origin_state_name']}).",
            'origin_city_id'    => $call->origin_city_id,
            'origin_city_name'  => $call->origin_city_name,
            'origin_state_name' => $call->origin_state_name,
        ]);
    }

    public function bulkUpdateOrigin(Request $request)
    {
        $v = $request->validate([
            'ids'               => 'required|array|min:1',
            'ids.*'             => 'integer',
            'origin_city_id'    => 'required|integer',
            'origin_city_name'  => 'required|string|max:150',
            'origin_state_name' => 'required|string|max:150',
        ]);

        // Get system seller IDs
        $systemSellerIds = Saler::where('type', 'system_seller')->pluck('id')->toArray();

        // Only update calls that belong to system sellers
        $updated = EmployeeDeliveryCall::whereIn('id', $v['ids'])
            ->whereIn('seller_id', $systemSellerIds)
            ->update([
                'origin_city_id'    => $v['origin_city_id'],
                'origin_city_name'  => $v['origin_city_name'],
                'origin_state_name' => $v['origin_state_name'],
                'curfox_error'      => null,
            ]);

        $skipped = count($v['ids']) - $updated;

        return response()->json([
            'success' => true,
            'message' => "Origin updated to {$v['origin_city_name']} for {$updated} system seller call(s)." .
                ($skipped > 0 ? " {$skipped} non-system seller call(s) skipped." : ""),
            'updated' => $updated,
            'skipped' => $skipped,
        ]);
    }

    // ── Status transitions ────────────────────────────────────────────────

    public function updateStatus(Request $request, $id)
    {
        $call = EmployeeDeliveryCall::findOrFail($id);

        // Check if this belongs to a system seller
        $seller = Saler::find($call->seller_id);
        if (!$seller || $seller->type !== 'system_seller') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: This order does not belong to a system seller'
                ], 403);
            }
            return back()->with('error', 'Unauthorized: This order does not belong to a system seller');
        }

        $from = $call->status;
        $next = self::FLOW[$from] ?? null;

        if (! $next) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Already at final status.'], 400);
            }
            return back()->with('error', 'This record is already in final status.');
        }

        if ($next === 'givedriver') {
            try {
                $waybill = $this->dispatchToCurfox($call);
                $call->update([
                    'status'               => $next,
                    'curfox_waybill'       => $waybill,
                    'curfox_dispatched_at' => now(),
                    'curfox_error'         => null,
                    'status_date'          => now(),
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => "Dispatched to courier. Waybill: {$waybill}",
                        'waybill' => $waybill,
                    ]);
                }
                return back()->with('success', "Order dispatched! Waybill: {$waybill}");

            } catch (\RuntimeException $e) {
                $call->update(['curfox_error' => $e->getMessage()]);
                Log::error('Curfox dispatch failed for call ' . $id, ['error' => $e->getMessage()]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Courier dispatch failed: ' . $e->getMessage(),
                    ], 422);
                }
                return back()->with('error', 'Courier dispatch failed: ' . $e->getMessage());
            }
        }

        $call->update(['status' => $next, 'status_date' => now()]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => "{$from} → {$next}"]);
        }
        return back()->with('success', "Status updated: {$from} → {$next}");
    }

    public function bulkUpdateStatus(Request $request)
    {
        $data = $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        $ids = $data['ids'];

        // Get system seller IDs
        $systemSellerIds = Saler::where('type', 'system_seller')->pluck('id')->toArray();

        // First, verify all selected calls belong to system sellers
        $nonSystemCalls = EmployeeDeliveryCall::whereIn('id', $ids)
            ->whereNotIn('seller_id', $systemSellerIds)
            ->count();

        if ($nonSystemCalls > 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot process: {$nonSystemCalls} selected call(s) do not belong to system sellers.",
            ], 403);
        }

        $statuses = EmployeeDeliveryCall::whereIn('id', $ids)
            ->whereIn('seller_id', $systemSellerIds)
            ->distinct()
            ->pluck('status')
            ->toArray();

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

        $ok = 0; $fail = 0; $details = [];

        if ($next === 'givedriver') {
            $calls = EmployeeDeliveryCall::whereIn('id', $ids)
                ->whereIn('seller_id', $systemSellerIds)
                ->where('status', $from)
                ->get();

            foreach ($calls as $call) {
                try {
                    $waybill = $this->dispatchToCurfox($call);
                    $call->update([
                        'status'               => 'givedriver',
                        'curfox_waybill'       => $waybill,
                        'curfox_dispatched_at' => now(),
                        'curfox_error'         => null,
                        'status_date'          => now(),
                    ]);
                    $ok++;
                    $details[] = ['id' => $call->id, 'waybill' => $waybill];
                } catch (\RuntimeException $e) {
                    $call->update(['curfox_error' => $e->getMessage()]);
                    $fail++;
                    $details[] = ['id' => $call->id, 'error' => $e->getMessage()];
                    Log::error('Bulk Curfox dispatch failed for call ' . $call->id, ['error' => $e->getMessage()]);
                }
            }
            return response()->json([
                'success' => true,
                'message' => "Dispatched {$ok} orders. {$fail} failed.",
                'ok'      => $ok,
                'fail'    => $fail,
                'details' => $details,
            ]);
        }

        DB::transaction(function () use ($ids, $from, $next, $systemSellerIds, &$ok) {
            $ok = EmployeeDeliveryCall::whereIn('id', $ids)
                ->whereIn('seller_id', $systemSellerIds)
                ->where('status', $from)
                ->update(['status' => $next, 'status_date' => now()]);
        });

        return response()->json([
            'success' => true,
            'message' => "Updated {$ok} system seller row(s): {$from} → {$next}",
            'ok'      => $ok,
        ]);
    }

    // ── Curfox dispatch ───────────────────────────────────────────────────

    private function dispatchToCurfox(EmployeeDeliveryCall $call): string
    {
        $merchantBusinessId = session('curfox_merchant_business_id');

        if (! $merchantBusinessId) {
            try {
                $default            = $this->curfox->getDefaultBusiness();
                $merchantBusinessId = $default ? (string) $default['id'] : self::DEFAULT_MERCHANT_BUSINESS_ID;
            } catch (\Exception $e) {
                $merchantBusinessId = self::DEFAULT_MERCHANT_BUSINESS_ID;
                Log::warning('Could not fetch Curfox default business, using fallback: ' . $e->getMessage());
            }
        }

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
                "Cannot determine destination city for call #{$call->id}. Please set a valid city before dispatching."
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

        $generalData = ['merchant_business_id' => (string) $merchantBusinessId];

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

        return (string) $waybill;
    }

    // ── Tracking proxy ────────────────────────────────────────────────────

    /**
     * POST /employee/delivery-calls/tracking
     * Proxies the Curfox GET tracking-info endpoint.
     */
    public function tracking(Request $request)
    {
        $v = $request->validate([
            'waybill_number' => 'required|string|max:100',
        ]);

        try {
            $response = $this->curfox->getTrackingInfo($v['waybill_number']);
            return response()->json($response);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Helper method to check if a call belongs to a system seller
     */
    private function isSystemSellerCall(EmployeeDeliveryCall $call): bool
    {
        $seller = Saler::find($call->seller_id);
        return $seller && $seller->type === 'system_seller';
    }
}
