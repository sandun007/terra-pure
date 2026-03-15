<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SelfSellerDeliveryCall;
use App\Models\SellerProduct;
use App\Services\Curfoxapiservice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SelfSellerDeliveryCallController extends Controller
{
    // ── Internal Pipeline Flow ─────────────────────────────────────────────
    private const FLOW = [
        'pending'    => 'packing',
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

    public function __construct(private Curfoxapiservice $curfox)
    {
        $this->middleware('auth:seller');
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ── LOCAL PIPELINE PAGES (pending → packing → packed → givedriver) ────
    // ═══════════════════════════════════════════════════════════════════════

    public function index(Request $request)
    {
        return redirect()->route('seller.delivery_calls.pending');
    }

    public function pending(Request $request)    { return $this->statusPage($request, 'pending'); }
    public function packing(Request $request)    { return $this->statusPage($request, 'packing'); }
    public function packed(Request $request)     { return $this->statusPage($request, 'packed'); }
    public function givedriver(Request $request) { return $this->statusPage($request, 'givedriver'); }

    private function statusPage(Request $request, string $status)
    {
        $seller = Auth::user();
        $q = trim((string) $request->get('q', ''));

        $callsQuery = SelfSellerDeliveryCall::query()
            ->where('seller_id', $seller->id)
            ->where('status', $status)
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

        $counts = [
            'pending'    => SelfSellerDeliveryCall::where('seller_id', $seller->id)->where('status', 'pending')->count(),
            'packing'    => SelfSellerDeliveryCall::where('seller_id', $seller->id)->where('status', 'packing')->count(),
            'packed'     => SelfSellerDeliveryCall::where('seller_id', $seller->id)->where('status', 'packed')->count(),
            'givedriver' => SelfSellerDeliveryCall::where('seller_id', $seller->id)->where('status', 'givedriver')->count(),
        ];

        // Tracking page counts
        $trackingCounts = $this->getTrackingCounts($seller->id);

        $curfoxCities = [];
        try { $curfoxCities = $this->curfox->getAllCities(); } catch (\Exception $e) {}

        $curfoxBusinesses      = [];
        $curfoxDefaultBusiness = null;
        try {
            $curfoxBusinesses      = $this->curfox->getBusinesses();
            $curfoxDefaultBusiness = $this->curfox->getDefaultBusiness();
        } catch (\Exception $e) {}

        $selectedBusinessId = session(
            'seller_curfox_business_id_' . $seller->id,
            $curfoxDefaultBusiness['id'] ?? self::DEFAULT_MERCHANT_BUSINESS_ID
        );

        return view('seller.delivery_calls.index', compact(
            'calls', 'status', 'nextStatus', 'curfoxCities', 'curfoxBusinesses',
            'curfoxDefaultBusiness', 'selectedBusinessId', 'counts', 'trackingCounts'
        ));
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ── CURFOX TRACKING STATUS PAGES (post-dispatch) ───────────────────────
    // ═══════════════════════════════════════════════════════════════════════

    public function trackingIndex(Request $request)
    {
        return redirect()->route('seller.delivery_calls.tracking.confirmed');
    }

    public function trackingConfirmed(Request $request)
    {
        return $this->trackingPage($request, 'confirmed');
    }

    public function trackingCompleted(Request $request)
    {
        return $this->trackingPage($request, 'completed');
    }

    public function trackingRescheduled(Request $request)
    {
        return $this->trackingPage($request, 'rescheduled');
    }

    public function trackingCancelled(Request $request)
    {
        return $this->trackingPage($request, 'cancelled');
    }

    private function trackingPage(Request $request, string $category)
    {
        $seller = Auth::user();
        $q = trim((string) $request->get('q', ''));

        // Get all dispatched calls (have a waybill)
        $dispatchedCalls = SelfSellerDeliveryCall::query()
            ->where('seller_id', $seller->id)
            ->whereNotNull('curfox_waybill')
            ->when($q !== '', function($query) use ($q) {
                $query->where(function($qq) use ($q) {
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

        // Categorize by Curfox tracking status (cached)
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

        // Pipeline counts for nav
        $pipelineCounts = [
            'pending'    => SelfSellerDeliveryCall::where('seller_id', $seller->id)->where('status', 'pending')->count(),
            'packing'    => SelfSellerDeliveryCall::where('seller_id', $seller->id)->where('status', 'packing')->count(),
            'packed'     => SelfSellerDeliveryCall::where('seller_id', $seller->id)->where('status', 'packed')->count(),
            'givedriver' => SelfSellerDeliveryCall::where('seller_id', $seller->id)->where('status', 'givedriver')->count(),
        ];

        return view('seller.delivery_calls.tracking', compact(
            'calls', 'category', 'trackingCounts', 'pipelineCounts', 'q'
        ));
    }

    // ── Sync individual waybill (AJAX) ──────────────────────────────────────
    public function syncWaybill(Request $request, int $id)
    {
        $seller = Auth::user();
        $call   = SelfSellerDeliveryCall::where('seller_id', $seller->id)->findOrFail($id);

        if (! $call->curfox_waybill) {
            return response()->json(['success' => false, 'message' => 'No waybill assigned.'], 400);
        }

        // Force refresh cache
        $info = $this->refreshWaybillInfo($call->curfox_waybill);

        return response()->json([
            'success'      => true,
            'category'     => $info['category'],
            'status_name'  => $info['status_name'],
            'checked_at'   => $info['checked_at'],
        ]);
    }

    // ── Sync all dispatched waybills for this seller (AJAX) ────────────────
    public function syncAllWaybills(Request $request)
    {
        $seller = Auth::user();

        $calls = SelfSellerDeliveryCall::where('seller_id', $seller->id)
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
            $response = $this->curfox->getTrackingInfo($waybill);
            $trackings = $response['data'] ?? [];

            if (empty($trackings)) {
                return $this->unknownWaybillInfo($waybill);
            }

            // Latest status is first in the array
            $latestStatus = $trackings[0]['status']['name'] ?? null;
            $category     = self::CURFOX_CATEGORY_MAP[$latestStatus] ?? 'confirmed';

            return [
                'status_name' => $latestStatus ?? 'Unknown',
                'category'    => $category,
                'checked_at'  => now()->toDateTimeString(),
                'error'       => null,
            ];
        } catch (\Exception $e) {
            Log::warning("Could not fetch Curfox tracking for waybill {$waybill}: " . $e->getMessage());
            return $this->unknownWaybillInfo($waybill, $e->getMessage());
        }
    }

    private function unknownWaybillInfo(string $waybill, ?string $error = null): array
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
        // Quick count from cache if available, otherwise return 0 (lazy load)
        $dispatchedWaybills = SelfSellerDeliveryCall::where('seller_id', $sellerId)
            ->whereNotNull('curfox_waybill')
            ->pluck('curfox_waybill');

        $counts = ['confirmed' => 0, 'completed' => 0, 'rescheduled' => 0, 'cancelled' => 0];

        foreach ($dispatchedWaybills as $waybill) {
            $cacheKey = 'curfox_waybill_info_' . $waybill;
            if (Cache::has($cacheKey)) {
                $info = Cache::get($cacheKey);
                $cat = $info['category'] ?? 'confirmed';
                if (isset($counts[$cat])) {
                    $counts[$cat]++;
                }
            }
        }

        return $counts;
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ── SHOW / CREATE / STORE ──────────────────────────────────────────────
    // ═══════════════════════════════════════════════════════════════════════

    public function show($id)
    {
        $seller = Auth::user();
        $call   = SelfSellerDeliveryCall::where('seller_id', $seller->id)->findOrFail($id);

        // Attach tracking info if dispatched
        if ($call->curfox_waybill) {
            $info = $this->getCachedWaybillInfo($call->curfox_waybill);
            $call->tracking_status_name = $info['status_name'];
            $call->tracking_category    = $info['category'];
            $call->tracking_checked_at  = $info['checked_at'];
        }

        return view('seller.delivery_calls.show', compact('call'));
    }

    public function create()
    {
        $seller = Auth::user();

        $products = SellerProduct::where('seller_id', $seller->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $nextCallNumber = SelfSellerDeliveryCall::generateCallNumber($seller->id);

        $curfoxCities = [];
        try { $curfoxCities = $this->curfox->getAllCities(); } catch (\Exception $e) {}

        return view('seller.delivery-calls.create', compact('products', 'nextCallNumber', 'curfoxCities'));
    }

    public function store(Request $request)
    {
        $seller = Auth::user();

        $validator = Validator::make($request->all(), [
            'product_id'        => 'required|exists:seller_products,id',
            'order_code'        => 'nullable|string|max:100',
            'customer_name'     => 'required|string|max:255',
            'customer_phone_01' => 'required|string|max:20',
            'customer_phone_02' => 'nullable|string|max:20',
            'customer_address'  => 'required|string',
            'city'              => 'required|string|max:150',
            'district'          => 'required|string|max:150',
            'item_code'         => 'required|string|max:100',
            'item_name'         => 'required|string|max:255',
            'qty'               => 'required|integer|min:1',
            'price'             => 'required|numeric|min:0',
            'total'             => 'required|numeric|min:0',
            'platform'          => 'nullable|string|max:50',
            'ad_name'           => 'nullable|string|max:255',
            'campaign_name'     => 'nullable|string|max:255',
            'remark'            => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $product = SellerProduct::findOrFail($request->product_id);

            if ($product->qty < $request->qty) {
                throw new \Exception("Insufficient stock. Available: {$product->qty}");
            }

            $callData = $request->except('_token');
            $callData['seller_id']       = $seller->id;
            $callData['call_number']     = SelfSellerDeliveryCall::generateCallNumber($seller->id);
            $callData['status']          = 'pending';
            $callData['date']            = now()->toDateString();
            $callData['status_date']     = now();
            $callData['download_status'] = false;

            $call = SelfSellerDeliveryCall::create($callData);
            $product->decrement('qty', $request->qty);

            DB::commit();

            return redirect()->route('seller.delivery_calls.pending')
                ->with('success', "Delivery call #{$call->call_number} created successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create delivery call: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create delivery call: ' . $e->getMessage())
                ->withInput();
        }
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ── BUSINESS SELECTOR ─────────────────────────────────────────────────
    // ═══════════════════════════════════════════════════════════════════════

    public function setBusiness(Request $request)
    {
        $seller = Auth::user();

        $v = $request->validate(['merchant_business_id' => 'required|string|max:50']);
        session(['seller_curfox_business_id_' . $seller->id => $v['merchant_business_id']]);

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
    // ── ORIGIN CITY UPDATES ────────────────────────────────────────────────
    // ═══════════════════════════════════════════════════════════════════════

    public function updateOrigin(Request $request, int $id)
    {
        $seller = Auth::user();
        $v = $request->validate([
            'origin_city_id'    => 'required|integer',
            'origin_city_name'  => 'required|string|max:150',
            'origin_state_name' => 'required|string|max:150',
        ]);

        $call = SelfSellerDeliveryCall::where('seller_id', $seller->id)->findOrFail($id);
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
        $seller = Auth::user();
        $v = $request->validate([
            'ids'               => 'required|array|min:1',
            'ids.*'             => 'integer',
            'origin_city_id'    => 'required|integer',
            'origin_city_name'  => 'required|string|max:150',
            'origin_state_name' => 'required|string|max:150',
        ]);

        $updated = SelfSellerDeliveryCall::whereIn('id', $v['ids'])
            ->where('seller_id', $seller->id)
            ->update([
                'origin_city_id'    => $v['origin_city_id'],
                'origin_city_name'  => $v['origin_city_name'],
                'origin_state_name' => $v['origin_state_name'],
                'curfox_error'      => null,
            ]);

        return response()->json([
            'success' => true,
            'message' => "Origin updated to {$v['origin_city_name']} for {$updated} call(s).",
            'updated' => $updated,
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ── STATUS TRANSITIONS (local pipeline) ────────────────────────────────
    // ═══════════════════════════════════════════════════════════════════════

    public function updateStatus(Request $request, $id)
    {
        $seller = Auth::user();
        $call   = SelfSellerDeliveryCall::where('seller_id', $seller->id)->findOrFail($id);

        $currentStatus = strtolower($call->status);
        $next          = self::FLOW[$currentStatus] ?? null;

        Log::info('Status update requested', [
            'call_id'        => $id,
            'current_status' => $call->status,
            'normalized'     => $currentStatus,
            'next_status'    => $next,
        ]);

        if (! $next) {
            $errorMsg = "Cannot progress from status '{$call->status}'. No next status defined.";
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 400);
            }
            return back()->with('error', $errorMsg);
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

                // Pre-cache initial tracking status
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
            return response()->json([
                'success' => true,
                'message' => "Status updated: {$call->status} → {$next}",
            ]);
        }

        return back()->with('success', "Status updated: {$call->status} → {$next}");
    }

    public function bulkUpdateStatus(Request $request)
    {
        $seller = Auth::user();
        $data = $request->validate(['ids' => 'required|array|min:1', 'ids.*' => 'integer']);
        $ids  = $data['ids'];

        $statuses = SelfSellerDeliveryCall::whereIn('id', $ids)
            ->where('seller_id', $seller->id)
            ->select('status')
            ->distinct()
            ->get()
            ->pluck('status')
            ->map(fn($s) => strtolower($s))
            ->toArray();

        if (count($statuses) !== 1) {
            return response()->json([
                'success'        => false,
                'message'        => 'All selected rows must share the same current status.',
                'statuses_found' => $statuses,
            ], 422);
        }

        $from = $statuses[0];
        $next = self::FLOW[$from] ?? null;

        if (! $next) {
            return response()->json(['success' => false, 'message' => 'No next status defined.'], 400);
        }

        $ok = 0; $fail = 0; $details = [];

        if ($next === 'givedriver') {
            $calls = SelfSellerDeliveryCall::whereIn('id', $ids)
                ->where('seller_id', $seller->id)
                ->whereRaw('LOWER(status) = ?', [$from])
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

        DB::transaction(function () use ($ids, $from, $next, $seller, &$ok) {
            $ok = SelfSellerDeliveryCall::whereIn('id', $ids)
                ->where('seller_id', $seller->id)
                ->whereRaw('LOWER(status) = ?', [$from])
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

    private function dispatchToCurfox(SelfSellerDeliveryCall $call): string
    {
        $seller = Auth::user();
        $merchantBusinessId = session('seller_curfox_business_id_' . $seller->id);

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

    public function getNextStatus($currentStatus)
    {
        return self::FLOW[strtolower($currentStatus)] ?? null;
    }
}
