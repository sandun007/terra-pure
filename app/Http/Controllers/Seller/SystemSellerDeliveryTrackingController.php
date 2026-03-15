<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDeliveryCall;
use App\Services\Curfoxapiservice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SystemSellerDeliveryTrackingController extends Controller
{
    // ── Curfox Status → Category Map ──────────────────────────────────────
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

    public function show(int $id)
    {
        $seller = Auth::user();

        if ($seller->type !== 'system_seller') {
            abort(403);
        }

        // Load the call — must belong to this seller
        $call = \App\Models\EmployeeDeliveryCall::with('product', 'seller')
            ->where('seller_id', $seller->id)
            ->findOrFail($id);

        return view('seller.system_delivery.show', compact('call'));
    }

    public function __construct(private Curfoxapiservice $curfox)
    {
        $this->middleware('auth:seller');
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ── TRACKING PAGES ─────────────────────────────────────────────────────
    // ═══════════════════════════════════════════════════════════════════════

    public function index(Request $request)
    {
        return redirect()->route('seller.system_delivery.tracking.confirmed');
    }

    public function confirmed(Request $request)   { return $this->trackingPage($request, 'confirmed'); }
    public function completed(Request $request)   { return $this->trackingPage($request, 'completed'); }
    public function rescheduled(Request $request) { return $this->trackingPage($request, 'rescheduled'); }
    public function cancelled(Request $request)   { return $this->trackingPage($request, 'cancelled'); }

    private function trackingPage(Request $request, string $category)
    {
        $seller = Auth::user();

        // Guard: only system_seller
        if ($seller->type !== 'system_seller') {
            abort(403, 'This section is for system sellers only.');
        }

        $q = trim((string) $request->get('q', ''));

        // All dispatched calls belonging to THIS system seller
        $dispatchedCalls = EmployeeDeliveryCall::query()
            ->where('seller_id', $seller->id)
            ->whereNotNull('curfox_waybill')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('order_code',      'like', "%{$q}%")
                        ->orWhere('call_number',    'like', "%{$q}%")
                        ->orWhere('customer_name',  'like', "%{$q}%")
                        ->orWhere('curfox_waybill', 'like', "%{$q}%")
                        ->orWhere('item_name',      'like', "%{$q}%")
                        ->orWhere('city',           'like', "%{$q}%");
                });
            })
            ->latest('id')
            ->get();

        // Categorize by Curfox tracking status
        $categorized = [
            'confirmed'   => collect(),
            'completed'   => collect(),
            'rescheduled' => collect(),
            'cancelled'   => collect(),
        ];

        foreach ($dispatchedCalls as $call) {
            $info = $this->getCachedWaybillInfo($call->curfox_waybill);
            $call->tracking_category    = $info['category'];
            $call->tracking_status_name = $info['status_name'];
            $call->tracking_checked_at  = $info['checked_at'];

            if (isset($categorized[$info['category']])) {
                $categorized[$info['category']]->push($call);
            }
        }

        $calls = $categorized[$category] ?? collect();

        $trackingCounts = [
            'confirmed'   => $categorized['confirmed']->count(),
            'completed'   => $categorized['completed']->count(),
            'rescheduled' => $categorized['rescheduled']->count(),
            'cancelled'   => $categorized['cancelled']->count(),
        ];

        // Pipeline counts (managed by employee, read-only for seller)
        $pipelineCounts = [
            'Pending'    => EmployeeDeliveryCall::where('seller_id', $seller->id)->where('status', 'Pending')->count(),
            'packing'    => EmployeeDeliveryCall::where('seller_id', $seller->id)->where('status', 'packing')->count(),
            'packed'     => EmployeeDeliveryCall::where('seller_id', $seller->id)->where('status', 'packed')->count(),
            'givedriver' => EmployeeDeliveryCall::where('seller_id', $seller->id)->where('status', 'givedriver')->count(),
        ];

        return view('seller.system_delivery.tracking', compact(
            'calls', 'category', 'trackingCounts', 'pipelineCounts', 'q'
        ));
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ── PIPELINE OVERVIEW (read-only for system seller) ────────────────────
    // ═══════════════════════════════════════════════════════════════════════

    public function pipeline(Request $request)
    {
        $seller = Auth::user();

        if ($seller->type !== 'system_seller') {
            abort(403);
        }

        $q = trim((string) $request->get('q', ''));
        $statusFilter = $request->get('status', 'all');

        $query = EmployeeDeliveryCall::query()
            ->where('seller_id', $seller->id)
            ->when($statusFilter !== 'all', fn($q2) => $q2->where('status', $statusFilter))
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('order_code',         'like', "%{$q}%")
                        ->orWhere('call_number',       'like', "%{$q}%")
                        ->orWhere('customer_name',     'like', "%{$q}%")
                        ->orWhere('customer_phone_01', 'like', "%{$q}%")
                        ->orWhere('item_name',         'like', "%{$q}%")
                        ->orWhere('city',              'like', "%{$q}%");
                });
            })
            ->latest('id');

        $calls = $query->paginate(20)->withQueryString();

        $pipelineCounts = [
            'all'        => EmployeeDeliveryCall::where('seller_id', $seller->id)->count(),
            'Pending'    => EmployeeDeliveryCall::where('seller_id', $seller->id)->where('status', 'Pending')->count(),
            'packing'    => EmployeeDeliveryCall::where('seller_id', $seller->id)->where('status', 'packing')->count(),
            'packed'     => EmployeeDeliveryCall::where('seller_id', $seller->id)->where('status', 'packed')->count(),
            'givedriver' => EmployeeDeliveryCall::where('seller_id', $seller->id)->where('status', 'givedriver')->count(),
        ];

        $trackingCounts = $this->getTrackingCounts($seller->id);

        return view('seller.system_delivery.pipeline', compact(
            'calls', 'statusFilter', 'pipelineCounts', 'trackingCounts', 'q'
        ));
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ── WAYBILL SYNC (AJAX) ────────────────────────────────────────────────
    // ═══════════════════════════════════════════════════════════════════════

    public function syncWaybill(Request $request, int $id)
    {
        $seller = Auth::user();

        $call = EmployeeDeliveryCall::where('seller_id', $seller->id)->findOrFail($id);

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

    public function syncAll(Request $request)
    {
        $seller = Auth::user();

        $calls = EmployeeDeliveryCall::where('seller_id', $seller->id)
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

    // ── Tracking proxy ──────────────────────────────────────────────────────
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

    // ═══════════════════════════════════════════════════════════════════════
    // ── CACHE HELPERS ──────────────────────────────────────────────────────
    // ═══════════════════════════════════════════════════════════════════════

    private function getCachedWaybillInfo(string $waybill): array
    {
        $key = 'curfox_waybill_info_' . $waybill;

        return Cache::remember($key, now()->addMinutes(self::TRACKING_CACHE_MINUTES), function () use ($waybill) {
            return $this->fetchWaybillInfo($waybill);
        });
    }

    private function refreshWaybillInfo(string $waybill): array
    {
        $key = 'curfox_waybill_info_' . $waybill;
        Cache::forget($key);
        $info = $this->fetchWaybillInfo($waybill);
        Cache::put($key, $info, now()->addMinutes(self::TRACKING_CACHE_MINUTES));
        return $info;
    }

    private function fetchWaybillInfo(string $waybill): array
    {
        try {
            $response  = $this->curfox->getTrackingInfo($waybill);
            $trackings = $response['data'] ?? [];

            if (empty($trackings)) {
                return $this->unknownInfo();
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
            Log::warning("Waybill tracking error [{$waybill}]: " . $e->getMessage());
            return $this->unknownInfo($e->getMessage());
        }
    }

    private function unknownInfo(?string $error = null): array
    {
        return [
            'status_name' => 'Unknown',
            'category'    => 'confirmed',
            'checked_at'  => now()->toDateTimeString(),
            'error'       => $error,
        ];
    }

    private function getTrackingCounts(int $sellerId): array
    {
        $waybills = EmployeeDeliveryCall::where('seller_id', $sellerId)
            ->whereNotNull('curfox_waybill')
            ->pluck('curfox_waybill');

        $counts = ['confirmed' => 0, 'completed' => 0, 'rescheduled' => 0, 'cancelled' => 0];

        foreach ($waybills as $waybill) {
            $key = 'curfox_waybill_info_' . $waybill;
            if (Cache::has($key)) {
                $cat = Cache::get($key)['category'] ?? 'confirmed';
                if (isset($counts[$cat])) $counts[$cat]++;
            }
        }

        return $counts;
    }
}
