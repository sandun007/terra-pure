<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerCall;
use App\Models\SellerProduct;
use App\Models\City;
use App\Models\EmployeeDeliveryCall;
use App\Services\Curfoxapiservice;          // ← NEW
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;


class SellerCallController extends Controller
{
    // ── NEW: inject Curfox service ────────────────────────────────────────
    public function __construct(private Curfoxapiservice $curfox) {}

    /**
     * Display pending calls list
     */
    public function index()
    {
        $seller = Auth::user();

        $calls = SellerCall::where('seller_id', $seller->id)
            ->where('status', 'Pending')
            ->with(['product', 'seller', 'employeeDeliveryCall'])
            ->latest()
            ->get();

        return view('seller.calls.pending_call_list', compact('calls'));
    }

    /**
     * Display confirmed calls list
     */
    public function conformCallList()
    {
        $seller = Auth::user();

        $calls = SellerCall::where('seller_id', $seller->id)
            ->where('status', 'Conform')
            ->with(['product', 'seller', 'employeeDeliveryCall'])
            ->latest()
            ->get();

        return view('seller.calls.conform_call_list', compact('calls'));
    }

    /**
     * Display rejected calls list
     */
    public function rejectCallList()
    {
        $seller = Auth::user();

        $calls = SellerCall::where('seller_id', $seller->id)
            ->where('status', 'Reject')
            ->with(['product', 'seller'])
            ->latest()
            ->get();

        return view('seller.calls.reject_call_list', compact('calls'));
    }

    /**
     * Display not answered calls list
     */
    public function notAnswerCallList()
    {
        $seller = Auth::user();

        $calls = SellerCall::where('seller_id', $seller->id)
            ->where('status', 'Not Answer')
            ->with(['product', 'seller'])
            ->latest()
            ->get();

        return view('seller.calls.not_answer_call_list', compact('calls'));
    }

    /**
     * Display other status calls list
     */
    public function otherCallList()
    {
        $seller = Auth::user();

        $calls = SellerCall::where('seller_id', $seller->id)
            ->where('status', 'Other')
            ->with(['product', 'seller'])
            ->latest()
            ->get();

        return view('seller.calls.other_call_list', compact('calls'));
    }

    /**
     * Show call details view only (read-only)
     */
    public function show($id)
    {
        $seller = Auth::user();

        $call = SellerCall::where('seller_id', $seller->id)
            ->with(['product', 'seller', 'employeeDeliveryCall'])
            ->findOrFail($id);

        return view('seller.calls.show_call', compact('call'));
    }

    /**
     * Show call edit form
     */
    public function edit($id)
    {
        $seller = Auth::user();
        $cities = City::orderBy('city')->get();

        $call = SellerCall::where('seller_id', $seller->id)
            ->with(['product', 'seller', 'employeeDeliveryCall'])
            ->findOrFail($id);

        return view('seller.calls.edit_call', compact('call', 'cities'));
    }

    /**
     * Update call status and details
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'customer_name'          => 'required|string|max:255',
            'customer_phone_01'      => 'required|string|max:20',
            'customer_phone_02'      => 'nullable|string|max:20',
            'customer_address'       => 'nullable|string',
            'city'                   => 'nullable|string|max:100',
            // ── NEW: Curfox city fields ──
            'destination_city_id'    => 'nullable|integer',
            'destination_state_name' => 'nullable|string|max:150',
            // ────────────────────────────
            'qty'                    => 'required|integer|min:1',
            'price'                  => 'required|numeric|min:0',
            'status'                 => 'required|in:Pending,Conform,Reject,Not Answer,Other,WhatsApp',
            'remark'                 => 'nullable|string',
        ]);

        $seller = Auth::user();

        try {
            DB::beginTransaction();

            $call           = SellerCall::where('seller_id', $seller->id)->findOrFail($id);
            $previousStatus = $call->status;
            $total          = (int) $validated['qty'] * (float) $validated['price'];

            $call->update([
                'customer_name'          => $validated['customer_name'],
                'customer_phone_01'      => $validated['customer_phone_01'],
                'customer_phone_02'      => $validated['customer_phone_02'] ?? null,
                'customer_address'       => $validated['customer_address'] ?? null,
                'city'                   => $validated['city'] ?? null,
                // ── NEW ──
                'destination_city_id'    => $validated['destination_city_id'] ?? null,
                'destination_state_name' => $validated['destination_state_name'] ?? null,
                // ─────────
                'qty'                    => $validated['qty'],
                'price'                  => $validated['price'],
                'total'                  => $total,
                'status'                 => $validated['status'],
                'status_date'            => now(),
                'remark'                 => $validated['remark'] ?? null,
            ]);

            // Deposit deduct only when switching into Conform
            if ($validated['status'] === 'Conform' && $previousStatus !== 'Conform') {
                $this->handleDepositDeduction($seller, $call);
                // Sync to employee_delivery_call is handled by SellerCall model events (fixed).
            }

            DB::commit();

            $route = $this->getStatusRoute($validated['status']);
            return redirect()->route($route)->with('success', 'Call updated successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating call: ' . $e->getMessage(), [
                'call_id'   => $id,
                'seller_id' => $seller->id,
            ]);
            return redirect()->back()->withInput()->with('error', 'Error updating call: ' . $e->getMessage());
        }
    }

    /**
     * Delete a call record
     */
    public function destroy($id)
    {
        $seller = Auth::user();

        try {
            $call   = SellerCall::where('seller_id', $seller->id)->findOrFail($id);
            $status = $call->status;

            // SellerCall model deleted() event will delete employee_delivery_call if needed.
            $call->delete();

            $route = $this->getStatusRoute($status);
            return redirect()->route($route)->with('success', 'Call deleted successfully!');

        } catch (Exception $e) {
            Log::error('Error deleting call: ' . $e->getMessage(), [
                'call_id'   => $id,
                'seller_id' => $seller->id,
            ]);
            return redirect()->back()->with('error', 'Error deleting call: ' . $e->getMessage());
        }
    }

    /**
     * Print call details
     */
    public function print($id)
    {
        $seller = Auth::user();

        try {
            $call = SellerCall::where('seller_id', $seller->id)
                ->with(['product', 'seller'])
                ->findOrFail($id);

            return view('seller.calls.print_call', compact('call'));

        } catch (Exception $e) {
            Log::error('Error printing call: ' . $e->getMessage(), [
                'call_id'   => $id,
                'seller_id' => $seller->id,
            ]);
            return redirect()->back()->with('error', 'Error printing call: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update call status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'call_ids'   => 'required|array',
            'call_ids.*' => 'exists:seller_calls,id',
            'status'     => 'required|in:Pending,Conform,Reject,Not Answer,Other,WhatsApp',
        ]);

        $seller = Auth::user();

        try {
            DB::beginTransaction();

            $calls = SellerCall::where('seller_id', $seller->id)
                ->whereIn('id', $validated['call_ids'])
                ->get();

            foreach ($calls as $call) {
                $previousStatus = $call->status;

                $call->status      = $validated['status'];
                $call->status_date = now();
                $call->save(); // model events handle sync (fixed)

                if ($validated['status'] === 'Conform' && $previousStatus !== 'Conform') {
                    $this->handleDepositDeduction($seller, $call);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($validated['call_ids']) . ' calls updated successfully!',
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Bulk update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error updating calls: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Export calls
     */
    public function export(Request $request)
    {
        $seller = Auth::user();
        $status = $request->get('status', 'Conform');

        $calls = SellerCall::where('seller_id', $seller->id)
            ->where('status', $status)
            ->where('download_status', 0)
            ->with(['product'])
            ->get();

        if ($calls->isEmpty()) {
            return redirect()->back()->with('error', 'No calls to export.');
        }

        SellerCall::whereIn('id', $calls->pluck('id'))->update(['download_status' => 1]);

        if ($status === 'Conform') {
            EmployeeDeliveryCall::whereIn('call_id', $calls->pluck('id'))
                ->update(['download_status' => 1]);
        }

        return redirect()->back()->with('success', 'Calls exported successfully!');
    }

    /**
     * Get call statistics
     */
    public function statistics()
    {
        $seller = Auth::user();

        $stats = [
            'pending'    => SellerCall::where('seller_id', $seller->id)->where('status', 'Pending')->count(),
            'conform'    => SellerCall::where('seller_id', $seller->id)->where('status', 'Conform')->count(),
            'reject'     => SellerCall::where('seller_id', $seller->id)->where('status', 'Reject')->count(),
            'not_answer' => SellerCall::where('seller_id', $seller->id)->where('status', 'Not Answer')->count(),
            'other'      => SellerCall::where('seller_id', $seller->id)->where('status', 'Other')->count(),
            'total'      => SellerCall::where('seller_id', $seller->id)->count(),
            'synced'     => EmployeeDeliveryCall::where('seller_id', $seller->id)->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get employee delivery calls list
     */
    public function employeeDeliveryList()
    {
        $seller = Auth::user();

        $deliveryCalls = EmployeeDeliveryCall::where('seller_id', $seller->id)
            ->with(['product', 'originalCall'])
            ->latest()
            ->paginate(20);

        return view('seller.calls.employee_delivery_list', compact('deliveryCalls'));
    }

    /**
     * Manually sync a call to employee delivery
     */
    public function syncToEmployeeDelivery($id)
    {
        $seller = Auth::user();

        try {
            $call = SellerCall::where('seller_id', $seller->id)
                ->where('status', 'Conform')
                ->findOrFail($id);

            $deliveryCall = $call->syncToEmployeeDelivery();

            return response()->json([
                'success' => true,
                'message' => 'Call synced to employee delivery successfully!',
                'data'    => $deliveryCall,
            ]);

        } catch (Exception $e) {
            Log::error('Error syncing to employee delivery: ' . $e->getMessage(), [
                'call_id'   => $id,
                'seller_id' => $seller->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error syncing call: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * CSV Upload page
     */
    public function csvUploadPage()
    {
        $seller = Auth::user();
        return view('seller.order.csv_upload', compact('seller'));
    }

    /**
     * Refresh CSRF token — called by JS every 10 min and on tab focus
     */
    public function csrfRefresh()
    {
        return response()->json(['token' => csrf_token()]);
    }

    /**
     * Return seller's products as JSON for the CSV upload product selector
     */
    public function getSellerProducts()
    {
        $seller = Auth::user();

        try {
            $products = SellerProduct::where('seller_id', $seller->id)
                ->where('status', 'active')
                ->where('cancel', false)
                ->orderBy('created_at', 'desc')
                ->get(['id', 'code', 'name', 'display_price', 'qty']);

            return response()->json([
                'success'  => true,
                'products' => $products,
            ]);

        } catch (Exception $e) {
            Log::error('getSellerProducts error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Insert CSV leads into seller_calls.
     * ── UPDATED: resolves Curfox city IDs for each lead so dispatch works later ──
     */
    public function csvInsert(Request $request)
    {
        $request->validate([
            'leads'                    => 'required|array',
            'leads.*.customer_name'    => 'required|string',
            'leads.*.customer_phone'   => 'required|string',
            'leads.*.customer_address' => 'nullable|string',
            'leads.*.city'             => 'nullable|string',
            'leads.*.platform'         => 'nullable|string',
            'leads.*.ad_name'          => 'nullable|string',
            'leads.*.call_status'      => 'nullable|string',
            'product_code'             => 'required|string',
            'call_status'              => 'nullable|string',
        ]);

        $seller = Auth::user();

        try {
            DB::beginTransaction();

            $product = $this->findProduct($seller->id, $request->product_code);

            if (! $product) {
                $available = SellerProduct::where('seller_id', $seller->id)
                    ->where('status', 'active')
                    ->pluck('code')
                    ->toArray();

                return response()->json([
                    'success' => false,
                    'message' => 'Product "' . $request->product_code . '" not found. '
                        . 'Available codes: ' . (count($available) ? implode(', ', $available) : 'none') . '.',
                ], 422);
            }

            // ── NEW: pre-load all Curfox cities once for the whole batch ──
            $cityMap = $this->buildCityMap();
            // ──────────────────────────────────────────────────────────────

            $successCount  = 0;
            $errorCount    = 0;
            $errors        = [];
            $defaultStatus = $request->call_status ?? 'Pending';
            $createdCalls  = [];

            foreach ($request->leads as $index => $lead) {
                try {
                    $remarkParts = [];
                    if (! empty($lead['ad_name']))       $remarkParts[] = 'Ad: ' . $lead['ad_name'];
                    if (! empty($lead['campaign_name'])) $remarkParts[] = 'Campaign: ' . $lead['campaign_name'];
                    if (! empty($lead['created_time']))  $remarkParts[] = 'Lead Date: ' . $lead['created_time'];
                    if (! empty($lead['lead_status']))   $remarkParts[] = 'FB Status: ' . $lead['lead_status'];

                    $remark     = ! empty($remarkParts) ? implode(' | ', $remarkParts) : null;
                    $callStatus = $lead['call_status'] ?? $defaultStatus;

                    // ── NEW: resolve city from Curfox API ──
                    $cityId    = null;
                    $stateName = null;
                    $cityName  = $lead['city'] ?? null;

                    if ($cityName && ! empty($cityMap)) {
                        $key   = strtolower(trim($cityName));
                        $match = $cityMap[$key] ?? null;
                        if ($match) {
                            $cityId    = $match['id'];
                            $stateName = $match['state_name'];
                            $cityName  = $match['name']; // canonical name
                        }
                    }
                    // ───────────────────────────────────────

                    $call = SellerCall::create([
                        'seller_id'              => $seller->id,
                        'product_id'             => $product->id,
                        'date'                   => now()->toDateString(),
                        'customer_name'          => $lead['customer_name'],
                        'customer_phone_01'      => $lead['customer_phone'],
                        'customer_phone_02'      => $lead['customer_phone_02'] ?? null,
                        'customer_address'       => $lead['customer_address'] ?? null,
                        'city'                   => $cityName,
                        // ── NEW ──
                        'destination_city_id'    => $cityId,
                        'destination_state_name' => $stateName,
                        // ─────────
                        'item_code'              => $product->code,
                        'item_name'              => $product->name,
                        'qty'                    => 1,
                        'price'                  => $product->display_price,
                        'total'                  => $product->display_price,
                        'status'                 => $callStatus,
                        'status_date'            => now(),
                        'platform'               => $lead['platform'] ?? 'fb',
                        'ad_name'                => $lead['ad_name'] ?? null,
                        'campaign_name'          => $lead['campaign_name'] ?? null,
                        'lead_status'            => $lead['lead_status'] ?? null,
                        'remark'                 => $remark,
                        'download_status'        => false,
                    ]); // model created() handles sync if Conform

                    $createdCalls[] = [
                        'id'            => $call->id,
                        'status'        => $callStatus,
                        'customer_name' => $call->customer_name,
                        'city_resolved' => (bool) $cityId,   // ← NEW flag
                        'synced'        => $callStatus === 'Conform',
                    ];
                    $successCount++;

                } catch (Exception $e) {
                    $errors[] = 'Row ' . ($index + 1) . ': ' . $e->getMessage();
                    $errorCount++;
                }
            }

            DB::commit();

            $redirectStatus = $defaultStatus;
            $statusCounts   = array_count_values(array_column($createdCalls, 'status'));
            if (! empty($statusCounts)) {
                arsort($statusCounts);
                $redirectStatus = key($statusCounts);
            }

            $cityResolved = count(array_filter($createdCalls, fn ($c) => $c['city_resolved']));
            $message = "Successfully created {$successCount} calls.";
            if ($cityResolved > 0) $message .= " {$cityResolved} cities resolved via delivery API.";
            if ($errorCount > 0)   $message .= " {$errorCount} failed.";

            return response()->json([
                'success'        => true,
                'message'        => $message,
                'successCount'   => $successCount,
                'callCount'      => $successCount,
                'errorCount'     => $errorCount,
                'errors'         => $errors,
                'redirect_url'   => route($this->getStatusRoute($redirectStatus)),
                'created_calls'  => $createdCalls,
                'status_summary' => $statusCounts,
                'csrf_token'     => csrf_token(),
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('CSV bulk call insert error', [
                'error'        => $e->getMessage(),
                'seller_id'    => $seller->id,
                'product_code' => $request->product_code,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error processing CSV data: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // NEW: CONFIRM → EMPLOYEE DELIVERY QUEUE
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Confirm a single pending call and push to employee delivery queue.
     * Called via AJAX from the pending call list "Confirm & Send" button.
     */
    public function confirmCall(Request $request, int $id)
    {
        $seller = Auth::user();

        $call = SellerCall::where('seller_id', $seller->id)
            ->where('status', 'Pending')
            ->findOrFail($id);

        try {
            DB::beginTransaction();

            // Resolve Curfox city if not already stored on the call
            [$cityId, $stateName, $cityName] = $this->resolveCityForCall($call);

            $call->update([
                'status'                 => 'Conform',
                'status_date'            => now(),
                'destination_city_id'    => $cityId    ?? $call->destination_city_id,
                'destination_state_name' => $stateName ?? $call->destination_state_name,
                'city'                   => $cityName  ?? $call->city,
            ]);

            $this->handleDepositDeduction($seller, $call);

            // EmployeeDeliveryCall is created via model event OR we force-create here
            // if the model event doesn't handle city fields yet.
            $deliveryCall = $call->employeeDeliveryCall
                ?? $this->createEmployeeDeliveryCall($call, $cityId, $stateName, $cityName);

            // If it already existed but city was missing, patch it
            if ($cityId && ! $deliveryCall->destination_city_id) {
                $deliveryCall->update([
                    'destination_city_id'    => $cityId,
                    'destination_state_name' => $stateName,
                    'city'                   => $cityName ?? $deliveryCall->city,
                ]);
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success'          => true,
                    'message'          => 'Call confirmed and sent to delivery queue.',
                    'delivery_call_id' => $deliveryCall->id,
                    'city_resolved'    => (bool) $cityId,
                ]);
            }

            return redirect()->route('call.conform')->with('success', 'Call confirmed!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('confirmCall failed', ['call_id' => $id, 'error' => $e->getMessage()]);

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Failed: ' . $e->getMessage());
        }
    }

    /**
     * Bulk confirm multiple pending calls → employee delivery queue.
     */
    public function bulkConfirmCalls(Request $request)
    {
        $request->validate([
            'call_ids'   => 'required|array|min:1',
            'call_ids.*' => 'integer',
        ]);

        $seller  = Auth::user();
        $ok      = 0;
        $fail    = 0;
        $errors  = [];

        // Pre-load Curfox cities once for the whole batch
        $cityMap = $this->buildCityMap();

        foreach ($request->call_ids as $id) {
            try {
                $call = SellerCall::where('seller_id', $seller->id)
                    ->where('status', 'Pending')
                    ->findOrFail($id);

                DB::beginTransaction();

                [$cityId, $stateName, $cityName] = $this->resolveCityWithMap($call, $cityMap);

                $call->update([
                    'status'                 => 'Conform',
                    'status_date'            => now(),
                    'destination_city_id'    => $cityId    ?? $call->destination_city_id,
                    'destination_state_name' => $stateName ?? $call->destination_state_name,
                    'city'                   => $cityName  ?? $call->city,
                ]);

                $this->handleDepositDeduction($seller, $call);

                $deliveryCall = $call->employeeDeliveryCall
                    ?? $this->createEmployeeDeliveryCall($call, $cityId, $stateName, $cityName);

                if ($cityId && ! $deliveryCall->destination_city_id) {
                    $deliveryCall->update([
                        'destination_city_id'    => $cityId,
                        'destination_state_name' => $stateName,
                        'city'                   => $cityName ?? $deliveryCall->city,
                    ]);
                }

                DB::commit();
                $ok++;

            } catch (Exception $e) {
                DB::rollBack();
                $fail++;
                $errors[] = "Call #{$id}: " . $e->getMessage();
                Log::error('bulkConfirmCalls failed for ' . $id, ['error' => $e->getMessage()]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Confirmed {$ok} calls." . ($fail ? " {$fail} failed." : ''),
            'ok'      => $ok,
            'fail'    => $fail,
            'errors'  => $errors,
        ]);
    }

    /**
     * Return all Curfox cities as JSON for JS city autocomplete.
     */
    public function curfoxCitiesJson()
    {
        try {
            $cities = $this->curfox->getAllCities();
            return response()->json(['success' => true, 'data' => $cities]);
        } catch (Exception $e) {
            Log::error('curfoxCitiesJson: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────────────────────

    private function getStatusRoute($status)
    {
        $routeMap = [
            'Pending'    => 'call.index',
            'Conform'    => 'call.conform',
            'Reject'     => 'call.reject',
            'Not Answer' => 'call.not_answer_call_list',
            'Other'      => 'call.other_call_list',
            'WhatsApp'   => 'call.index',
        ];

        return $routeMap[$status] ?? 'call.index';
    }

    private function findProduct(int $sellerId, string $rawCode): ?SellerProduct
    {
        $code = trim($rawCode);

        $p = SellerProduct::where('seller_id', $sellerId)->where('code', $code)->first();
        if ($p) return $p;

        $p = SellerProduct::where('seller_id', $sellerId)
            ->whereRaw('LOWER(TRIM(code)) = ?', [strtolower($code)])
            ->first();
        if ($p) return $p;

        if (! str_contains($code, '/')) {
            $p = SellerProduct::where('seller_id', $sellerId)
                ->where('code', 'LIKE', '%/' . $code)
                ->first();
            if ($p) return $p;
        }

        if (str_contains($code, '/')) {
            $parts   = explode('/', $code);
            $numPart = end($parts);
            $p = SellerProduct::where('seller_id', $sellerId)
                ->where('code', 'LIKE', '%' . $numPart)
                ->first();
            if ($p) return $p;
        }

        $p = SellerProduct::where('code', $code)->first();
        if ($p) {
            Log::warning('findProduct: seller_id mismatch — used cross-seller fallback', [
                'requested_seller' => $sellerId,
                'actual_seller'    => $p->seller_id,
                'code'             => $code,
            ]);
            return $p;
        }

        return null;
    }

    private function handleDepositDeduction($seller, $call): void
    {
        $cost = (float) ($seller->cost ?? 0);
        if ($cost <= 0) return;

        $calDeposit = $seller->getAttribute('cal_deposit');
        $deposit    = $seller->getAttribute('deposit');
        $available  = $calDeposit !== null ? (float) $calDeposit : (float) ($deposit ?? 0);

        if ($available < $cost) {
            throw new Exception('Insufficient deposit balance.');
        }

        if ($calDeposit !== null) {
            $seller->decrement('cal_deposit', $cost);
        } else {
            $seller->decrement('deposit', $cost);
        }

        Log::info('Deposit deducted for confirmed call', [
            'call_id'   => $call->id,
            'seller_id' => $seller->id,
            'amount'    => $cost,
        ]);
    }

    // ── NEW helpers ──────────────────────────────────────────────────────────

    /**
     * Build lowercase-keyed city map from Curfox API (cached 24h).
     * Returns empty array silently if API is unreachable.
     *
     * @return array<string, array{id:int, name:string, state_name:string}>
     */
    private function buildCityMap(): array
    {
        $map = [];
        try {
            foreach ($this->curfox->getAllCities() as $city) {
                $map[strtolower(trim($city['name']))] = $city;
            }
        } catch (Exception $e) {
            Log::warning('Curfox buildCityMap failed: ' . $e->getMessage());
        }
        return $map;
    }

    /**
     * Resolve city for a single call (fresh API lookup, no pre-built map).
     *
     * @return array{0:int|null, 1:string|null, 2:string|null}  [cityId, stateName, cityName]
     */
    private function resolveCityForCall(SellerCall $call): array
    {
        // Already resolved
        if ($call->destination_city_id) {
            return [$call->destination_city_id, $call->destination_state_name, $call->city];
        }

        $term = $call->city ?? $call->district ?? null;
        if (! $term) return [null, null, null];

        try {
            $info = $this->curfox->findCityByName($term);
            if ($info) {
                return [(int) $info['city_id'], $info['state_name'], $info['city_name']];
            }
        } catch (Exception $e) {
            Log::warning('resolveCityForCall API error: ' . $e->getMessage());
        }

        return [null, null, $term];
    }

    /**
     * Resolve city using a pre-built map (for batch operations).
     *
     * @return array{0:int|null, 1:string|null, 2:string|null}
     */
    private function resolveCityWithMap(SellerCall $call, array $cityMap): array
    {
        if ($call->destination_city_id) {
            return [$call->destination_city_id, $call->destination_state_name, $call->city];
        }

        $term = strtolower(trim($call->city ?? $call->district ?? ''));
        if ($term && isset($cityMap[$term])) {
            $m = $cityMap[$term];
            return [(int) $m['id'], $m['state_name'], $m['name']];
        }

        return [null, null, $call->city ?? null];
    }

    /**
     * Create an EmployeeDeliveryCall record for a confirmed call with Curfox city fields.
     */
    private function createEmployeeDeliveryCall(
        SellerCall $call,
        ?int       $cityId,
        ?string    $stateName,
        ?string    $cityName
    ): EmployeeDeliveryCall {
        return EmployeeDeliveryCall::create([
            'seller_id'              => $call->seller_id,
            'product_id'             => $call->product_id,
            'call_id'                => $call->id,
            'order_code'             => $call->order_code ?? null,
            'date'                   => $call->date ?? now()->toDateString(),
            'customer_name'          => $call->customer_name,
            'customer_phone_01'      => $call->customer_phone_01,
            'customer_phone_02'      => $call->customer_phone_02,
            'customer_address'       => $call->customer_address,
            'city'                   => $cityName ?? $call->city,
            'district'               => $call->district ?? null,
            'destination_city_id'    => $cityId,
            'destination_state_name' => $stateName,
            'item_code'              => $call->item_code,
            'item_name'              => $call->item_name,
            'qty'                    => $call->qty ?? 1,
            'price'                  => $call->price ?? 0,
            'total'                  => ($call->price ?? 0) * ($call->qty ?? 1),
            'status'                 => 'Pending',
            'remark'                 => $call->remark,
            'platform'               => $call->platform ?? null,
        ]);
    }
}
