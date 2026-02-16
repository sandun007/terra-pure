<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerCall;
use App\Models\SellerProduct;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class SellerCallController extends Controller
{
    /**
     * Display pending calls list
     */
    public function index()
    {
        $seller = Auth::user();

        $calls = SellerCall::where('seller_id', $seller->id)
            ->where('status', 'Pending')
            ->with(['product', 'seller'])
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
            ->with(['product', 'seller'])
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
     * Show call details and edit form
     */
    public function show($id)
    {
        $seller = Auth::user();
        $cities = City::orderBy('city')->get();

        $call = SellerCall::where('seller_id', $seller->id)
            ->with(['product', 'seller'])
            ->findOrFail($id);

        return view('seller.calls.call_conform_form', compact('call', 'cities'));
    }

    /**
     * Update call status and details
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone_01' => 'required|string|max:20',
            'customer_phone_02' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'qty' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:Pending,Conform,Reject,Not Answer,Other,WhatsApp',
            'remark' => 'nullable|string',
        ]);

        $seller = Auth::user();

        try {
            DB::beginTransaction();

            $call = SellerCall::where('seller_id', $seller->id)->findOrFail($id);

            // Calculate total
            $total = $validated['qty'] * $validated['price'];

            // Update call record
            $call->update([
                'customer_name' => $validated['customer_name'],
                'customer_phone_01' => $validated['customer_phone_01'],
                'customer_phone_02' => $validated['customer_phone_02'] ?? null,
                'customer_address' => $validated['customer_address'] ?? null,
                'city' => $validated['city'] ?? null,
                'qty' => $validated['qty'],
                'price' => $validated['price'],
                'total' => $total,
                'status' => $validated['status'],
                'status_date' => now(),
                'remark' => $validated['remark'] ?? null,
            ]);

            // Handle deposit deduction for confirmed calls
            if ($validated['status'] === 'Conform' && $call->wasChanged('status')) {
                $this->handleDepositDeduction($seller, $call);
            }

            DB::commit();

            return redirect()->route('seller.call.index')
                ->with('success', 'Call updated successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating call: ' . $e->getMessage(), [
                'call_id' => $id,
                'seller_id' => $seller->id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating call: ' . $e->getMessage());
        }
    }

    /**
     * Delete a call record
     */
    public function destroy($id)
    {
        $seller = Auth::user();

        try {
            $call = SellerCall::where('seller_id', $seller->id)->findOrFail($id);

            // Only allow deletion of pending calls
            if ($call->status !== 'Pending') {
                return redirect()->back()
                    ->with('error', 'Only pending calls can be deleted.');
            }

            $call->delete();

            return redirect()->route('seller.call.index')
                ->with('success', 'Call deleted successfully!');

        } catch (Exception $e) {
            Log::error('Error deleting call: ' . $e->getMessage(), [
                'call_id' => $id,
                'seller_id' => $seller->id
            ]);

            return redirect()->back()
                ->with('error', 'Error deleting call: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update call status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'call_ids' => 'required|array',
            'call_ids.*' => 'exists:seller_calls,id',
            'status' => 'required|in:Pending,Conform,Reject,Not Answer,Other,WhatsApp',
        ]);

        $seller = Auth::user();

        try {
            DB::beginTransaction();

            SellerCall::where('seller_id', $seller->id)
                ->whereIn('id', $validated['call_ids'])
                ->update([
                    'status' => $validated['status'],
                    'status_date' => now(),
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($validated['call_ids']) . ' calls updated successfully!'
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Bulk update error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating calls: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export calls to Excel
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
            return redirect()->back()
                ->with('error', 'No calls to export.');
        }

        // Mark as downloaded
        SellerCall::whereIn('id', $calls->pluck('id'))
            ->update(['download_status' => 1]);

        // Export logic here (using Laravel Excel or similar)
        $fileName = strtolower($status) . '_calls_' . now()->format('Y-m-d') . '.xlsx';

        // Return Excel file (implement with Laravel Excel package)
        return redirect()->back()
            ->with('success', 'Calls exported successfully!');
    }

    /**
     * Get call statistics
     */
    public function statistics()
    {
        $seller = Auth::user();

        $stats = [
            'pending' => SellerCall::where('seller_id', $seller->id)
                ->where('status', 'Pending')->count(),
            'conform' => SellerCall::where('seller_id', $seller->id)
                ->where('status', 'Conform')->count(),
            'reject' => SellerCall::where('seller_id', $seller->id)
                ->where('status', 'Reject')->count(),
            'not_answer' => SellerCall::where('seller_id', $seller->id)
                ->where('status', 'Not Answer')->count(),
            'other' => SellerCall::where('seller_id', $seller->id)
                ->where('status', 'Other')->count(),
            'total' => SellerCall::where('seller_id', $seller->id)->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Handle deposit deduction for confirmed calls
     */
    private function handleDepositDeduction($seller, $call)
    {
        $cost = $seller->cost ?? 0;

        if ($cost <= 0) {
            return;
        }

        // Check deposit availability
        $availableDeposit = $seller->cal_deposit ?? $seller->deposit ?? 0;

        if ($availableDeposit < $cost) {
            throw new Exception('Insufficient deposit balance.');
        }

        // Deduct from appropriate field
        if (isset($seller->cal_deposit)) {
            $seller->decrement('cal_deposit', $cost);
        } elseif (isset($seller->deposit)) {
            $seller->decrement('deposit', $cost);
        }

        Log::info('Deposit deducted for confirmed call', [
            'call_id' => $call->id,
            'seller_id' => $seller->id,
            'amount' => $cost
        ]);
    }
}
