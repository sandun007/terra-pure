<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDeliveryCall;
use App\Services\Curfoxapiservice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeDeliveryCallController extends Controller
{
    /**
     * Status flow: each key advances to its value.
     * null = final stage.
     */
    private const FLOW = [
        'Pending'    => 'packing',
        'packing'    => 'packed',
        'packed'     => 'givedriver',
        'givedriver' => null,
    ];

    /**
     * Merchant business ID for the Curfox API.
     * Adjust to match your actual business_id from the merchant portal.
     */
    private const MERCHANT_BUSINESS_ID = '7809';

    /**
     * Origin warehouse city (must be a valid city name in Curfox /state API).
     */
    private const ORIGIN_CITY_NAME  = 'Colombo 01';
    private const ORIGIN_STATE_NAME = 'Colombo';

    public function __construct(private Curfoxapiservice $curfox) {}

    // ──────────────────────────────────────────────────────────────────────
    //  PAGES
    // ──────────────────────────────────────────────────────────────────────

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

        $callsQuery = EmployeeDeliveryCall::query()
            ->where('status', $status)
            ->latest('id');

        if ($q !== '') {
            $callsQuery->where(function ($qq) use ($q) {
                $qq->where('order_code',        'like', "%{$q}%")
                    ->orWhere('call_number',      'like', "%{$q}%")
                    ->orWhere('customer_name',    'like', "%{$q}%")
                    ->orWhere('customer_phone_01','like', "%{$q}%")
                    ->orWhere('item_name',        'like', "%{$q}%")
                    ->orWhere('city',             'like', "%{$q}%")
                    ->orWhere('district',         'like', "%{$q}%");
            });
        }

        $calls      = $callsQuery->paginate(20)->withQueryString();
        $nextStatus = self::FLOW[$status] ?? null;

        return view('employee.delivery_calls.index', compact('calls', 'status', 'nextStatus'));
    }

    public function show($id)
    {
        $call = EmployeeDeliveryCall::findOrFail($id);
        return view('employee.delivery_calls.view', compact('call'));
    }

    // ──────────────────────────────────────────────────────────────────────
    //  SINGLE STATUS UPDATE
    // ──────────────────────────────────────────────────────────────────────

    public function updateStatus(Request $request, $id)
    {
        $call = EmployeeDeliveryCall::findOrFail($id);
        $from = $call->status;
        $next = self::FLOW[$from] ?? null;

        if (! $next) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Already at final status.'], 400);
            }
            return back()->with('error', 'This record is already in final status.');
        }

        // ── If advancing TO givedriver, dispatch to Curfox ────────────────
        if ($next === 'givedriver') {
            try {
                $waybill = $this->dispatchToCurfox($call);
                $call->update([
                    'status'               => $next,
                    'curfox_waybill'       => $waybill,
                    'curfox_dispatched_at' => now(),
                    'curfox_error'         => null,
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
                // Save the error but do NOT change the status so they can retry
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

        // ── Normal status advance ─────────────────────────────────────────
        $call->update(['status' => $next]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => "{$from} → {$next}"]);
        }
        return back()->with('success', "Status updated: {$from} → {$next}");
    }

    // ──────────────────────────────────────────────────────────────────────
    //  BULK STATUS UPDATE
    // ──────────────────────────────────────────────────────────────────────

    public function bulkUpdateStatus(Request $request)
    {
        $data = $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        $ids = $data['ids'];

        // Determine the common "from" status for the selected rows
        $statuses = EmployeeDeliveryCall::whereIn('id', $ids)
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

        $ok      = 0;
        $fail    = 0;
        $details = [];

        // ── Bulk dispatch to Curfox when advancing to givedriver ──────────
        if ($next === 'givedriver') {
            $calls = EmployeeDeliveryCall::whereIn('id', $ids)
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

        // ── Normal bulk advance ───────────────────────────────────────────
        DB::transaction(function () use ($ids, $from, $next, &$ok) {
            $ok = EmployeeDeliveryCall::whereIn('id', $ids)
                ->where('status', $from)
                ->update(['status' => $next]);
        });

        return response()->json([
            'success' => true,
            'message' => "Updated {$ok} row(s): {$from} → {$next}",
            'ok'      => $ok,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────
    //  CURFOX DISPATCH
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Push a single delivery call to Curfox and return the waybill number.
     *
     * @throws \RuntimeException if the API call fails
     */
    private function dispatchToCurfox(EmployeeDeliveryCall $call): string
    {
        // Resolve destination city – prefer stored city_id, fall back to name lookup
        $cityId    = $call->destination_city_id ?? null;
        $cityName  = null;
        $stateName = $call->destination_state_name ?? null;

        if (! $cityId) {
            $searchTerm = $call->city ?: $call->district;
            if ($searchTerm) {
                $info = $this->curfox->findCityByName($searchTerm);
                if ($info) {
                    $cityId    = $info['city_id'];
                    $cityName  = $info['city_name'];
                    $stateName = $info['state_name'];
                }
            }
        }

        if (! $cityId && ! $cityName) {
            throw new \RuntimeException(
                "Cannot determine destination city for call #{$call->id}. " .
                "Please set a valid city before dispatching."
            );
        }

        // Build order data
        $orderData = [
            'order_no'          => $call->order_code ?? $call->call_number,
            'customer_name'     => $call->customer_name,
            'customer_address'  => $call->customer_address ?? '',
            'customer_phone'    => $call->customer_phone_01,
            'cod'               => (int) round(($call->price ?? 0) * ($call->qty ?? 1)),
            'weight'            => 1,
            'description'       => $call->item_name ?? 'Parcel',
            'remark'            => $call->remark ?? '',
        ];

        if ($call->customer_phone_02) {
            $orderData['customer_secondary_phone'] = $call->customer_phone_02;
        }

        if ($cityId) {
            $orderData['destination_city_id'] = $cityId;
        } else {
            $orderData['destination_city_name']  = $cityName;
            $orderData['destination_state_name'] = $stateName;
        }

        $generalData = [
            'merchant_business_id' => self::MERCHANT_BUSINESS_ID,
            'origin_city_name'     => self::ORIGIN_CITY_NAME,
            'origin_state_name'    => self::ORIGIN_STATE_NAME,
        ];

        $response = $this->curfox->createOrders($generalData, [$orderData]);

        // Extract waybill from response data array
        $waybills = $response['data'] ?? [];
        $waybill  = is_array($waybills) ? ($waybills[0] ?? null) : $waybills;

        if (! $waybill) {
            throw new \RuntimeException('Curfox returned no waybill. Response: ' . json_encode($response));
        }

        return (string) $waybill;
    }
}
