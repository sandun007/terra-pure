<?php
// app/Http/Controllers/Wholesaler/WholesalerCallController.php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use App\Models\WholesalerCall;
use App\Models\WholesalerProduct;
use App\Models\City;
use App\Models\Wholesaler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class WholesalerCallController extends Controller
{
    /**
     * Display pending calls list
     */
    public function index()
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        $calls = WholesalerCall::where('wholesaler_id', $wholesaler->id)
            ->where('status', 'Pending')
            ->with(['product', 'wholesaler'])
            ->latest()
            ->get();

        return view('wholesaler.calls.pending_call_list', compact('calls'));
    }

    /**
     * Display confirmed calls list
     */
    public function conformCallList()
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        $calls = WholesalerCall::where('wholesaler_id', $wholesaler->id)
            ->where('status', 'Conform')
            ->with(['product', 'wholesaler'])
            ->latest()
            ->get();

        return view('wholesaler.calls.conform_call_list', compact('calls'));
    }

    /**
     * Display rejected calls list
     */
    public function rejectCallList()
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        $calls = WholesalerCall::where('wholesaler_id', $wholesaler->id)
            ->where('status', 'Reject')
            ->with(['product', 'wholesaler'])
            ->latest()
            ->get();

        return view('wholesaler.calls.reject_call_list', compact('calls'));
    }

    /**
     * Display not answered calls list
     */
    public function notAnswerCallList()
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        $calls = WholesalerCall::where('wholesaler_id', $wholesaler->id)
            ->where('status', 'Not Answer')
            ->with(['product', 'wholesaler'])
            ->latest()
            ->get();

        return view('wholesaler.calls.not_answer_call_list', compact('calls'));
    }

    /**
     * Display other status calls list
     */
    public function otherCallList()
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        $calls = WholesalerCall::where('wholesaler_id', $wholesaler->id)
            ->where('status', 'Other')
            ->with(['product', 'wholesaler'])
            ->latest()
            ->get();

        return view('wholesaler.calls.other_call_list', compact('calls'));
    }

    /**
     * Show call details view only (read-only)
     */
    public function show($id)
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        $call = WholesalerCall::where('wholesaler_id', $wholesaler->id)
            ->with(['product', 'wholesaler'])
            ->findOrFail($id);

        return view('wholesaler.calls.show_call', compact('call'));
    }

    /**
     * Show call edit form
     */
    public function edit($id)
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $cities = City::orderBy('city')->get();

        $call = WholesalerCall::where('wholesaler_id', $wholesaler->id)
            ->with(['product', 'wholesaler'])
            ->findOrFail($id);

        return view('wholesaler.calls.edit_call', compact('call', 'cities'));
    }

    /**
     * Update call status and details
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_phone_01'=> 'required|string|max:20',
            'customer_phone_02'=> 'nullable|string|max:20',
            'customer_address' => 'nullable|string',
            'city'             => 'nullable|string|max:100',
            'qty'              => 'required|integer|min:1',
            'price'            => 'required|numeric|min:0',
            'status'           => 'required|in:Pending,Conform,Reject,Not Answer,Other,WhatsApp',
            'remark'           => 'nullable|string',
        ]);

        $wholesaler = Auth::guard('wholesaler')->user();

        try {
            DB::beginTransaction();

            $call = WholesalerCall::where('wholesaler_id', $wholesaler->id)->findOrFail($id);
            $previousStatus = $call->status;
            $total = $validated['qty'] * $validated['price'];

            $call->update([
                'customer_name'    => $validated['customer_name'],
                'customer_phone_01'=> $validated['customer_phone_01'],
                'customer_phone_02'=> $validated['customer_phone_02'] ?? null,
                'customer_address' => $validated['customer_address'] ?? null,
                'city'             => $validated['city'] ?? null,
                'qty'              => $validated['qty'],
                'price'            => $validated['price'],
                'total'            => $total,
                'status'           => $validated['status'],
                'status_date'      => now(),
                'remark'           => $validated['remark'] ?? null,
            ]);

            DB::commit();

            // Redirect back to the appropriate list based on new status
            $route = $this->getStatusRoute($validated['status']);
            return redirect()->route($route)->with('success', 'Call updated successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating call: ' . $e->getMessage(), [
                'call_id'   => $id,
                'wholesaler_id' => $wholesaler->id,
            ]);
            return redirect()->back()->withInput()->with('error', 'Error updating call: ' . $e->getMessage());
        }
    }

    /**
     * Delete a call record
     */
    public function destroy($id)
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        try {
            $call = WholesalerCall::where('wholesaler_id', $wholesaler->id)->findOrFail($id);
            $status = $call->status;
            $call->delete();

            // Redirect back to the appropriate list
            $route = $this->getStatusRoute($status);
            return redirect()->route($route)->with('success', 'Call deleted successfully!');

        } catch (Exception $e) {
            Log::error('Error deleting call: ' . $e->getMessage(), [
                'call_id'   => $id,
                'wholesaler_id' => $wholesaler->id,
            ]);
            return redirect()->back()->with('error', 'Error deleting call: ' . $e->getMessage());
        }
    }

    /**
     * Print call details
     */
    public function print($id)
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        try {
            $call = WholesalerCall::where('wholesaler_id', $wholesaler->id)
                ->with(['product', 'wholesaler'])
                ->findOrFail($id);

            return view('wholesaler.calls.print_call', compact('call'));

        } catch (Exception $e) {
            Log::error('Error printing call: ' . $e->getMessage(), [
                'call_id'   => $id,
                'wholesaler_id' => $wholesaler->id,
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
            'call_ids.*' => 'exists:wholesaler_calls,id',
            'status'     => 'required|in:Pending,Conform,Reject,Not Answer,Other,WhatsApp',
        ]);

        $wholesaler = Auth::guard('wholesaler')->user();

        try {
            DB::beginTransaction();

            WholesalerCall::where('wholesaler_id', $wholesaler->id)
                ->whereIn('id', $validated['call_ids'])
                ->update([
                    'status'      => $validated['status'],
                    'status_date' => now(),
                ]);

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
        $wholesaler = Auth::guard('wholesaler')->user();
        $status = $request->get('status', 'Conform');

        $calls = WholesalerCall::where('wholesaler_id', $wholesaler->id)
            ->where('status', $status)
            ->where('download_status', 0)
            ->with(['product'])
            ->get();

        if ($calls->isEmpty()) {
            return redirect()->back()->with('error', 'No calls to export.');
        }

        WholesalerCall::whereIn('id', $calls->pluck('id'))->update(['download_status' => 1]);

        return redirect()->back()->with('success', 'Calls exported successfully!');
    }

    /**
     * Get call statistics
     */
    public function statistics()
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        $stats = [
            'pending'    => WholesalerCall::where('wholesaler_id', $wholesaler->id)->where('status', 'Pending')->count(),
            'conform'    => WholesalerCall::where('wholesaler_id', $wholesaler->id)->where('status', 'Conform')->count(),
            'reject'     => WholesalerCall::where('wholesaler_id', $wholesaler->id)->where('status', 'Reject')->count(),
            'not_answer' => WholesalerCall::where('wholesaler_id', $wholesaler->id)->where('status', 'Not Answer')->count(),
            'other'      => WholesalerCall::where('wholesaler_id', $wholesaler->id)->where('status', 'Other')->count(),
            'total'      => WholesalerCall::where('wholesaler_id', $wholesaler->id)->count(),
        ];

        return response()->json($stats);
    }

    /**
     * CSV Upload page
     */
    public function csvUploadPage()
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        return view('wholesaler.calls.csv_upload', compact('wholesaler'));
    }

    /**
     * Refresh CSRF token — called by JS every 10 min and on tab focus
     */
    public function csrfRefresh()
    {
        return response()->json(['token' => csrf_token()]);
    }

    /**
     * Return wholesaler's products as JSON for the CSV upload product selector
     */
    public function getWholesalerProducts()
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        try {
            $products = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'active')
                ->where('cancel', false)
                ->orderBy('created_at', 'desc')
                ->get(['id', 'code', 'name', 'display_price', 'qty']);

            return response()->json([
                'success'  => true,
                'products' => $products,
            ]);

        } catch (Exception $e) {
            Log::error('getWholesalerProducts error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Insert CSV leads into wholesaler_calls
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

        $wholesaler = Auth::guard('wholesaler')->user();

        try {
            DB::beginTransaction();

            $product = $this->findProduct($wholesaler->id, $request->product_code);

            if (!$product) {
                $available = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                    ->where('status', 'active')
                    ->pluck('code')
                    ->toArray();

                return response()->json([
                    'success' => false,
                    'message' => 'Product "' . $request->product_code . '" not found. '
                        . 'Available codes: ' . (count($available) ? implode(', ', $available) : 'none') . '.',
                ], 422);
            }

            $successCount = 0;
            $errorCount = 0;
            $errors = [];
            $defaultStatus = $request->call_status ?? 'Pending';
            $createdCalls = [];

            foreach ($request->leads as $index => $lead) {
                try {
                    $remarkParts = [];
                    if (!empty($lead['ad_name'])) $remarkParts[] = 'Ad: ' . $lead['ad_name'];
                    if (!empty($lead['campaign_name'])) $remarkParts[] = 'Campaign: ' . $lead['campaign_name'];
                    if (!empty($lead['created_time'])) $remarkParts[] = 'Lead Date: ' . $lead['created_time'];
                    if (!empty($lead['lead_status'])) $remarkParts[] = 'FB Status: ' . $lead['lead_status'];

                    $remark = !empty($remarkParts) ? implode(' | ', $remarkParts) : null;
                    $callStatus = $lead['call_status'] ?? $defaultStatus;

                    $callData = [
                        'wholesaler_id'     => $wholesaler->id,
                        'product_id'        => $product->id,
                        'date'              => now()->toDateString(),
                        'customer_name'     => $lead['customer_name'],
                        'customer_phone_01' => $lead['customer_phone'],
                        'customer_phone_02' => $lead['customer_phone_02'] ?? null,
                        'customer_address'  => $lead['customer_address'] ?? null,
                        'city'              => $lead['city'] ?? null,
                        'item_code'         => $product->code,
                        'item_name'         => $product->name,
                        'qty'               => 1,
                        'price'             => $product->display_price,
                        'total'             => $product->display_price,
                        'status'            => $callStatus,
                        'status_date'       => now(),
                        'platform'          => $lead['platform'] ?? 'fb',
                        'ad_name'           => $lead['ad_name'] ?? null,
                        'campaign_name'     => $lead['campaign_name'] ?? null,
                        'lead_status'       => $lead['lead_status'] ?? null,
                        'remark'            => $remark,
                        'download_status'   => false,
                    ];

                    $call = WholesalerCall::create($callData);
                    $createdCalls[] = [
                        'id'            => $call->id,
                        'status'        => $callStatus,
                        'customer_name' => $call->customer_name,
                    ];
                    $successCount++;

                } catch (Exception $e) {
                    $errors[] = 'Row ' . ($index + 1) . ': ' . $e->getMessage();
                    $errorCount++;
                }
            }

            DB::commit();

            $redirectStatus = $defaultStatus;
            $statusCounts = array_count_values(array_column($createdCalls, 'status'));
            if (!empty($statusCounts)) {
                arsort($statusCounts);
                $redirectStatus = key($statusCounts);
            }

            $message = "Successfully created {$successCount} calls.";
            if ($errorCount > 0) {
                $message .= " {$errorCount} failed.";
            }

            return response()->json([
                'success'        => true,
                'message'        => $message,
                'successCount'   => $successCount,
                'callCount'      => $successCount,
                'errorCount'     => $errorCount,
                'errors'         => $errors,
                'redirect_url'   => route('wholesaler.call.' . $this->getStatusRouteName($redirectStatus)),
                'created_calls'  => $createdCalls,
                'status_summary' => $statusCounts,
                'csrf_token'     => csrf_token(),
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('CSV bulk call insert error', [
                'error'        => $e->getMessage(),
                'wholesaler_id'    => $wholesaler->id,
                'product_code' => $request->product_code,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error processing CSV data: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Get route name based on status
     */
    private function getStatusRoute($status)
    {
        $routeMap = [
            'Pending'    => 'wholesaler.call.index',
            'Conform'    => 'wholesaler.call.conform',
            'Reject'     => 'wholesaler.call.reject',
            'Not Answer' => 'wholesaler.call.not_answer_call_list',
            'Other'      => 'wholesaler.call.other_call_list',
            'WhatsApp'   => 'wholesaler.call.index',
        ];

        return $routeMap[$status] ?? 'wholesaler.call.index';
    }

    /**
     * Get route name without prefix for JSON response
     */
    private function getStatusRouteName($status)
    {
        $routeMap = [
            'Pending'    => 'index',
            'Conform'    => 'conform',
            'Reject'     => 'reject',
            'Not Answer' => 'not_answer_call_list',
            'Other'      => 'other_call_list',
            'WhatsApp'   => 'index',
        ];

        return $routeMap[$status] ?? 'index';
    }

    /**
     * Multi-strategy product finder
     */
    private function findProduct(int $wholesalerId, string $rawCode): ?WholesalerProduct
    {
        $code = trim($rawCode);

        // 1. Exact match
        $p = WholesalerProduct::where('wholesaler_id', $wholesalerId)->where('code', $code)->first();
        if ($p) return $p;

        // 2. Case-insensitive
        $p = WholesalerProduct::where('wholesaler_id', $wholesalerId)
            ->whereRaw('LOWER(TRIM(code)) = ?', [strtolower($code)])
            ->first();
        if ($p) return $p;

        // 3. Code has no slash — treat as numeric suffix
        if (!str_contains($code, '/')) {
            $p = WholesalerProduct::where('wholesaler_id', $wholesalerId)
                ->where('code', 'LIKE', '%/' . $code)
                ->first();
            if ($p) return $p;
        }

        // 4. Code has slashes — extract last segment as numeric part
        if (str_contains($code, '/')) {
            $parts = explode('/', $code);
            $numPart = end($parts);
            $p = WholesalerProduct::where('wholesaler_id', $wholesalerId)
                ->where('code', 'LIKE', '%' . $numPart)
                ->first();
            if ($p) return $p;
        }

        // 5. Cross-wholesaler fallback (optional)
        $p = WholesalerProduct::where('code', $code)->first();
        if ($p) {
            Log::warning('findProduct: wholesaler_id mismatch — used cross-wholesaler fallback', [
                'requested_wholesaler' => $wholesalerId,
                'actual_wholesaler'    => $p->wholesaler_id,
                'code'                 => $code,
            ]);
            return $p;
        }

        return null;
    }
}
