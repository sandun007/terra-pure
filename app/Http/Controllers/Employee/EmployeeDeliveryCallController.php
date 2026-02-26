<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDeliveryCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeDeliveryCallController extends Controller
{
    private const FLOW = [
        'Pending'    => 'packing',
        'packing'    => 'packed',
        'packed'     => 'givedriver',
        'givedriver' => null,
    ];

    public function index(Request $request)
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
                $qq->where('order_code', 'like', "%{$q}%")
                    ->orWhere('call_number', 'like', "%{$q}%")
                    ->orWhere('customer_name', 'like', "%{$q}%")
                    ->orWhere('customer_phone_01', 'like', "%{$q}%")
                    ->orWhere('customer_phone_02', 'like', "%{$q}%")
                    ->orWhere('item_name', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%")
                    ->orWhere('district', 'like', "%{$q}%");
            });
        }

        $calls = $callsQuery->paginate(20)->withQueryString();
        $nextStatus = self::FLOW[$status] ?? null;

        return view('employee.delivery_calls.index', compact('calls', 'status', 'nextStatus'));
    }

    public function show($id)
    {
        $call = EmployeeDeliveryCall::findOrFail($id);
        return view('employee.delivery_calls.view', compact('call'));
    }

    // ✅ SINGLE UPDATE
    public function updateStatus(Request $request, $id)
    {
        $call = EmployeeDeliveryCall::findOrFail($id);

        $from = $call->status;
        $next = self::FLOW[$from] ?? null;

        if (!$next) {
            return back()->with('error', 'This record is already in final status.');
        }

        $call->update(['status' => $next]);

        return back()->with('success', "Status updated: {$from} → {$next}");
    }

    // ✅ BULK UPDATE (ARRAY WISE)
    public function bulkUpdateStatus(Request $request)
    {
        $data = $request->validate([
            'from_status' => 'required|in:Pending,packing,packed,givedriver',
            'ids'         => 'required|array|min:1',
            'ids.*'       => 'integer',
        ]);

        $from = $data['from_status'];
        $next = self::FLOW[$from] ?? null;

        if (!$next) {
            return back()->with('error', 'Final status has no next step.');
        }

        $ids = $data['ids'];

        $updated = 0;

        DB::transaction(function () use ($ids, $from, $next, &$updated) {
            // update only records that are still in the same "from" status
            $updated = EmployeeDeliveryCall::whereIn('id', $ids)
                ->where('status', $from)
                ->update([
                    'status' => $next,
                    // your model boot() will set status_date on status change
                ]);
        });

        if ($updated === 0) {
            return back()->with('error', 'No rows updated. Maybe status already changed.');
        }

        return back()->with('success', "Bulk updated {$updated} row(s): {$from} → {$next}");
    }
}
