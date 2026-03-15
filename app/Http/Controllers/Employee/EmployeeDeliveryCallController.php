<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDeliveryCall;
use App\Models\Saler;
use App\Services\Curfoxapiservice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeDeliveryCallController extends Controller
{
    // ── Local Pipeline Flow ────────────────────────────────────────────────
    private const FLOW = [
        'Pending'    => 'packing',
        'packing'    => 'packed',
        'packed'     => 'givedriver',
        'givedriver' => null,
    ];

    private const DEFAULT_MERCHANT_BUSINESS_ID = '7809';

    // ── Curfox Delivery Status → Local Category Map ────────────────────────
    private const CURFOX_CATEGORY_MAP = [
        'DRAFT'                                            => 'confirmed',
        'CONFIRMED'                                        => 'confirmed',
        'DEFAULT WAREHOUSE CHANGE'                         => 'confirmed',
        'PICKUP RIDER ASSIGNED'                            => 'confirmed',
        'PICKED UP'                                        => 'confirmed',
        'DELIVERED BY PICKUP RIDER'                        => 'completed',
        'DISPATCH TO ORIGIN WAREHOUSE'                     => 'confirmed',
        'RECEIVED TO ORIGIN WAREHOUSE'                     => 'confirmed',
        'DISPATCHED FROM ORIGIN WAREHOUSE'                 => 'confirmed',
        'RECEIVED AT DESTINATION WAREHOUSE'                => 'confirmed',
        'ASSIGNED TO DESTINATION RIDER'                    => 'confirmed',
        'DELIVERED'                                        => 'completed',
        'PARTIALLY DELIVERED'                              => 'completed',
        'RETURN TO DESTINATION WAREHOUSE'                  => 'rescheduled',
        'RESCHEDULED'                                      => 'rescheduled',
        'FAILED TO DELIVER'                                => 'cancelled',
        'RETURN TO ORIGIN WAREHOUSE'                       => 'cancelled',
        'RECEIVED TO ORIGIN WAREHOUSE (FAILED TO DELIVER)' => 'cancelled',
        'RETURN TO CLIENT'                                 => 'cancelled',
        'RECEIVED FAILED ORDER'                            => 'cancelled',
        'CANCELLED'                                        => 'cancelled',
    ];

    private const TRACKING_CACHE_MINUTES = 30;

    public function __construct(private Curfoxapiservice $curfox) {}

    // ═══════════════════════════════════════════════════════════════════════
    // ── LOCAL PIPELINE STATUS PAGES ────────────────────────────────────────
    // ═══════════════════════════════════════════════════════════════════════

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
        $systemSellerIds = Saler::where('type', 'system_seller')->pluck('id')->toArray();

        $callsQuery = EmployeeDeliveryCall::query()
            ->where('status', $status)
            ->whereIn('seller_id', $systemSellerIds)
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

        $pipelineCounts = [
            'Pending'    => EmployeeDeliveryCall::whereIn('seller_id', $systemSellerIds)->where('status', 'Pending')->count(),
            'packing'    => EmployeeDeliveryCall::whereIn('seller_id', $systemSellerIds)->where('status', 'packing')->count(),
            'packed'     => EmployeeDeliveryCall::whereIn('seller_id', $systemSellerIds)->where('status', 'packed')->count(),
            'givedriver' => EmployeeDeliveryCall::whereIn('seller_id', $systemSellerIds)->where('status', 'givedriver')->count(),
        ];

        $trackingCounts = $this->getTrackingCounts($systemSellerIds);

        $curfoxCities = [];
        try { $curfoxCities = $this->curfox->getAllCities(); } catch (\Exception $e) {}

        $curfoxBusinesses      = [];
        $curfoxDefaultBusiness = null;
        try {
            $curfoxBusinesses      = $this->curfox->getBusinesses();
            $curfoxDefaultBusiness = $this->curfox->getDefaultBusiness();
        } catch (\Exception $e) {}

        $selectedBusinessId = session(
            'curfox_merchant_business_id',
            $curfoxDefaultBusiness['id'] ?? self::DEFAULT_MERCHANT_BUSINESS_ID
        );

        return view('employee.delivery_calls.index', compact(
            'calls', 'status', 'nextStatus', 'curfoxCities', 'curfoxBusinesses',
            'curfoxDefaultBusiness', 'selectedBusinessId', 'pipelineCounts', 'trackingCounts'
        ));
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ── CURFOX TRACKING STATUS PAGES ──────────────────────────────────────
    // ═══════════════════════════════════════════════════════════════════════

    public function trackingIndex(Request $request)
    {
        return redirect()->route('employee.delivery_calls.tracking.confirmed');
    }

    public function trackingConfirmed(Request $request)  { return $this->trackingPage($request, 'confirmed'); }
    public function trackingCompleted(Request $request)   { return $this->trackingPage($request, 'completed'); }
    public function trackingRescheduled(Request $request) { return $this->trackingPage($request, 'rescheduled'); }
    public function trackingCancelled(Request $request)   { return $this->trackingPage($request, 'cancelled'); }

    private function trackingPage(Request $request, string $category)
    {
        $q = trim((string) $request->get('q', ''));
        $systemSellerIds = Saler::where('type', 'system_seller')->pluck('id')->toArray();

        $dispatchedCalls = EmployeeDeliveryCall::query()
            ->whereIn('seller_id', $systemSellerIds)
            ->whereNotNull('curfox_waybill')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('order_code',      'like', "%{$q}%")
                        ->orWhere('call_number',    'like', "%{$q}%")
                        ->orWhere('customer_name',  'like', "%{$q}%")
                        ->orWhere('curfox_waybill', 'like', "%{$q}%")
                        ->orWhere('item_name',      'like', "%{$q}%");
                });
            })
            ->latest('id')
            ->get();

        $categorized = [
            'confirmed'  => collect(),
            'completed'  => collect(),
            'rescheduled' => collect(),
            'cancelled'  => collect(),
        ];

        foreach ($dispatchedCalls as $call) {
            $info = $this->getCachedWaybillInfo($call->curfox_waybill);
            $call->tracking_category    = $info['category'];
            $call->tracking_status_name = $info['status_name'];
            $call->tracking_checked_at  = $info['checked_at'];

            // Attach seller name
            $call->seller_name = Saler::find($call->seller_id)?->name ?? 'Unknown';

            if (isset($categorized[$info['category']])) {
                $categorized[$info['category']]->push($call);
            }
        }

        $calls = $categorized[$category] ?? collect();

        $trackingCounts = [
            'confirmed'  => $categorized['confirmed']->count(),
            'completed'  => $categorized['completed']->count(),
            'rescheduled' => $categorized['rescheduled']->count(),
            'cancelled'  => $categorized['cancelled']->count(),
        ];

        $pipelineCounts = [
            'Pending'    => EmployeeDeliveryCall::whereIn('seller_id', $systemSellerIds)->where('status', 'Pending')->count(),
            'packing'    => EmployeeDeliveryCall::whereIn('seller_id', $systemSellerIds)->where('status', 'packing')->count(),
            'packed'     => EmployeeDeliveryCall::whereIn('seller_id', $systemSellerIds)->where('status', 'packed')->count(),
            'givedriver' => EmployeeDeliveryCall::whereIn('seller_id', $systemSellerIds)->where('status', 'givedriver')->count(),
        ];

        return view('employee.delivery_calls.tracking', compact(
            'calls', 'category', 'trackingCounts', 'pipelineCounts', 'q'
        ));
    }

    // ── Sync single waybill (AJAX) ──────────────────────────────────────────
    public function syncWaybill(Request $request, int $id)
    {
        $systemSellerIds = Saler::where('type', 'system_seller')->pluck('id')->toArray();
        $call = EmployeeDeliveryCall::whereIn('seller_id', $systemSellerIds)->findOrFail($id);

        if (! $call->curfox_waybill) {
            return response()->json(['success' => false, 'message' => 'No waybill assigned.'], 400);
        }

        $info = $this->refreshWaybillInfo($call->curfox_waybill);

        return response()->json([
            'success'     => true,
            'category'    => $info['category'],
            'status_name' => $info['status_name'],
            'checked_at'  => $info['checked_at'],
        ]);
    }

    // ── Sync all dispatched waybills (AJAX) ────────────────────────────────
    public function syncAllWaybills(Request $request)
    {
        $systemSellerIds = Saler::where('type', 'system_seller')->pluck('id')->toArray();

        $calls = EmployeeDeliveryCall::whereIn('seller_id', $systemSellerIds)
            ->whereNotNull('curfox_waybill')
            ->get(['id', 'curfox_waybill']);

        $results = [];
        foreach ($calls as $call) {
            $info = $this->refreshWaybillInfo($call->curfox_waybill);
            $results[] = [
                'id'          => $call->id,
                'waybill'     => $call->curfox_waybill,
                'category'    => $info['category'],
                'status_name' => $info['status_name'],
            ];
        }

        return response()->json([
            'success' => true,
            'synced'  => count($results),
            'results' => $results,
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ── WAYBILL TRACKING HELPERS ───────────────────────────────────────────
    // ═══════════════════════════════════════════════════════════════════════

    private function getCachedWaybillInfo(string $waybill): array
    {
        $cacheKey = 'curfox_waybill_info_' . $waybill;

        return Cache::remember($cacheKey, now()->addMinutes(self::TRACKING_CACHE_MINUTES), function () use ($waybill) {
            return $this->fetchWaybillInfo($waybill);
        });
    }

    private function refreshWaybillInfo(string $waybill): array
    {
        $cacheKey = 'curfox_waybill_info_' . $waybill;
        Cache::forget($cacheKey);
        $info = $this->fetchWaybillInfo($waybill);
        Cache::put($cacheKey, $info, now()->addMinutes(self::TRACKING_CACHE_MINUTES));
        return $info;
    }

    private function fetchWaybillInfo(string $waybill): array
    {
        try {
            $response  = $this->curfox->getTrackingInfo($waybill);
            $trackings = $response['data'] ?? [];

            if (empty($trackings)) {
                return ['status_name' => 'Unknown', 'category' => 'confirmed', 'checked_at' => now()->toDateTimeString(), 'error' => null];
            }

            $latestStatus = $trackings[0]['status']['name'] ?? null;
            $category     = self::CURFOX_CATEGORY_MAP[$latestStatus] ?? 'confirmed';

            return [
                'status_name' => $latestStatus ?? 'Unknown',
                'category'    => $category,
                'checked_at'  => now()->toDateTimeString(),
                'error'       => null,
            ];
        } catch (\Exception $e) {
            Log::warning("Tracking error for waybill {$waybill}: " . $e->getMessage());
            return ['status_name' => 'Error', 'category' => 'confirmed', 'checked_at' => now()->toDateTimeString(), 'error' => $e->getMessage()];
        }
    }

    private function getTrackingCounts(array $systemSellerIds): array
    {
        $waybills = EmployeeDeliveryCall::whereIn('seller_id', $systemSellerIds)
            ->whereNotNull('curfox_waybill')
            ->pluck('curfox_waybill');

        $counts = ['confirmed' => 0, 'completed' => 0, 'rescheduled' => 0, 'cancelled' => 0];

        foreach ($waybills as $waybill) {
            $cacheKey = 'curfox_waybill_info_' . $waybill;
            if (Cache::has($cacheKey)) {
                $info = Cache::get($cacheKey);
                $cat  = $info['category'] ?? 'confirmed';
                if (isset($counts[$cat])) {
                    $counts[$cat]++;
                }
            }
        }

        return $counts;
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ── SHOW ───────────────────────────────────────────────────────────────
    // ═══════════════════════════════════════════════════════════════════════

    public function show($id)
    {
        $call   = EmployeeDeliveryCall::findOrFail($id);
        $seller = Saler::find($call->seller_id);

        if (! $seller || $seller->type !== 'system_seller') {
            abort(403, 'Unauthorized access to non-system seller order');
        }

        if ($call->curfox_waybill) {
            $info = $this->getCachedWaybillInfo($call->curfox_waybill);
            $call->tracking_status_name = $info['status_name'];
            $call->tracking_category    = $info['category'];
            $call->tracking_checked_at  = $info['checked_at'];
        }

        return view('employee.delivery_calls.view', compact('call'));
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ── BUSINESS SELECTOR ─────────────────────────────────────────────────
    // ═══════════════════════════════════════════════════════════════════════

    public function setBusiness(Request $request)
    {
        $v = $request->validate(['merchant_business_id' => 'required|string|max:50']);
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

    // ═══════════════════════════════════════════════════════════════════════
    // ── ORIGIN UPDATES ─────────────────────────────────────────────────────
    // ═══════════════════════════════════════════════════════════════════════

    public function updateOrigin(Request $request, int $id)
    {
        $v = $request->validate([
            'origin_city_id'    => 'required|integer',
            'origin_city_name'  => 'required|string|max:150',
            'origin_state_name' => 'required|string|max:150',
        ]);

        $call   = EmployeeDeliveryCall::findOrFail($id);
        $seller = Saler::find($call->seller_id);

        if (! $seller || $seller->type !== 'system_seller') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
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

        $systemSellerIds = Saler::where('type', 'system_seller')->pluck('id')->toArray();

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
            'message' => "Origin updated for {$updated} call(s)." . ($skipped > 0 ? " {$skipped} skipped." : ''),
            'updated' => $updated,
            'skipped' => $skipped,
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ── STATUS TRANSITIONS ─────────────────────────────────────────────────
    // ═══════════════════════════════════════════════════════════════════════

    public function updateStatus(Request $request, $id)
    {
        $call   = EmployeeDeliveryCall::findOrFail($id);
        $seller = Saler::find($call->seller_id);

        if (! $seller || $seller->type !== 'system_seller') {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            return back()->with('error', 'Unauthorized: Not a system seller order');
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
                $this->getCachedWaybillInfo($waybill);

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
                    return response()->json(['success' => false, 'message' => 'Dispatch failed: ' . $e->getMessage()], 422);
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
        $data = $request->validate(['ids' => 'required|array|min:1', 'ids.*' => 'integer']);
        $ids  = $data['ids'];

        $systemSellerIds = Saler::where('type', 'system_seller')->pluck('id')->toArray();

        $nonSystemCalls = EmployeeDeliveryCall::whereIn('id', $ids)
            ->whereNotIn('seller_id', $systemSellerIds)
            ->count();

        if ($nonSystemCalls > 0) {
            return response()->json([
                'success' => false,
                'message' => "{$nonSystemCalls} selected call(s) do not belong to system sellers.",
            ], 403);
        }

        $statuses = EmployeeDeliveryCall::whereIn('id', $ids)
            ->whereIn('seller_id', $systemSellerIds)
            ->distinct()
            ->pluck('status')
            ->toArray();

        if (count($statuses) !== 1) {
            return response()->json(['success' => false, 'message' => 'All selected rows must share the same status.'], 422);
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
                    $this->getCachedWaybillInfo($waybill);
                    $ok++;
                    $details[] = ['id' => $call->id, 'waybill' => $waybill];
                } catch (\RuntimeException $e) {
                    $call->update(['curfox_error' => $e->getMessage()]);
                    $fail++;
                    $details[] = ['id' => $call->id, 'error' => $e->getMessage()];
                    Log::error('Bulk dispatch failed for call ' . $call->id);
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Dispatched {$ok}. {$fail} failed.",
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
            'message' => "Updated {$ok} row(s): {$from} → {$next}",
            'ok'      => $ok,
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ── CURFOX DISPATCH ────────────────────────────────────────────────────
    // ═══════════════════════════════════════════════════════════════════════

    private function dispatchToCurfox(EmployeeDeliveryCall $call): string
    {
        $merchantBusinessId = session('curfox_merchant_business_id');

        if (! $merchantBusinessId) {
            try {
                $default            = $this->curfox->getDefaultBusiness();
                $merchantBusinessId = $default ? (string) $default['id'] : self::DEFAULT_MERCHANT_BUSINESS_ID;
            } catch (\Exception $e) {
                $merchantBusinessId = self::DEFAULT_MERCHANT_BUSINESS_ID;
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
                "Cannot determine destination city for call #{$call->id}."
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

    // ═══════════════════════════════════════════════════════════════════════
    // ── TRACKING PROXY ─────────────────────────────────────────────────────
    // ═══════════════════════════════════════════════════════════════════════

    public function tracking(Request $request)
    {
        $v = $request->validate(['waybill_number' => 'required|string|max:100']);

        try {
            $response = $this->curfox->getTrackingInfo($v['waybill_number']);
            return response()->json($response);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    private function isSystemSellerCall(EmployeeDeliveryCall $call): bool
    {
        $seller = Saler::find($call->seller_id);
        return $seller && $seller->type === 'system_seller';
    }
}
