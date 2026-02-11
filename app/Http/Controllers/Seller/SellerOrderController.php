<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // Add this import
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SellerOrdersImport;
use Illuminate\Support\Str;

class SellerOrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        try {
            // Check if table exists before generating order ID
            if (Schema::hasTable('seller_orders')) {
                $code = SellerOrder::generateOrderId();
            } else {
                $code = 'ORD/00001'; // Default value if table doesn't exist
            }

        } catch (\Exception $e) {
            $code = 'ORD/00001'; // Fallback
            \Log::error('Error in order index: ' . $e->getMessage());
        }

        // Calculate deposit - assuming you have a deposit system
        $import = $user->deposit ?? null;

        return view('seller.order.new_order', compact('user', 'code', 'import'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'excel' => 'required|file|mimes:xlsx,xls,csv',
            'date' => 'required|date',
            'platform' => 'nullable|string'
        ]);

        $user = Auth::user();

        // Check if user has deposit
        if ($user->deposit <= 0) {
            return redirect()->route('seller.new-order')
                ->with('error', 'You do not have sufficient deposit to upload orders.');
        }

        try {
            DB::beginTransaction();

            $file = $request->file('excel');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('seller-orders', $filename, 'public');

            // Import Excel file
            $import = new SellerOrdersImport($user->id, $request->date, $request->platform);
            Excel::import($import, $file);

            // Update last row count
            $user->update([
                'last_row_count' => $import->getRowCount()
            ]);

            // Update user's deposit (deduct cost per lead)
            $totalCost = $import->getRowCount() * $user->cost;
            $user->decrement('deposit', $totalCost);

            DB::commit();

            return redirect()->route('seller.pending-order')
                ->with('success', 'Order uploaded successfully! ' . $import->getRowCount() . ' records imported.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('seller.new-order')
                ->with('error', 'Error uploading file: ' . $e->getMessage());
        }
    }

    public function pendingOrder()
    {
        $user = Auth::user();

        // Check if table exists before querying
        if (!Schema::hasTable('seller_orders')) {
            $orders = collect();
            $count = 0;
        } else {
            $orders = SellerOrder::where('seller_id', $user->id)
                ->pending()
                ->orderBy('created_at', 'desc')
                ->get();

            $count = $orders->count();
        }

        return view('seller.order.pending_order_list', compact('orders', 'count'));
    }

    public function completeOrder()
    {
        $user = Auth::user();

        // Check if table exists before querying
        if (!Schema::hasTable('seller_orders')) {
            $orders = collect();
            $count = 0;
        } else {
            $orders = SellerOrder::where('seller_id', $user->id)
                ->complete()
                ->orderBy('delivery_date', 'desc')
                ->get();

            $count = $orders->count();
        }

        return view('seller.order.complete_order_list', compact('orders', 'count'));
    }

    public function returnOrder()
    {
        $user = Auth::user();

        // Check if table exists before querying
        if (!Schema::hasTable('seller_orders')) {
            $orders = collect();
            $count = 0;
        } else {
            $orders = SellerOrder::where('seller_id', $user->id)
                ->status('return')
                ->orderBy('return_date', 'desc')
                ->get();

            $count = $orders->count();
        }

        return view('seller.order.return_order_list', compact('orders', 'count'));
    }

    public function reschedulingOrder()
    {
        $user = Auth::user();

        // Check if table exists before querying
        if (!Schema::hasTable('seller_orders')) {
            $orders = collect();
            $count = 0;
        } else {
            $orders = SellerOrder::where('seller_id', $user->id)
                ->status('rescheduling')
                ->orderBy('rescheduling_date', 'desc')
                ->get();

            $count = $orders->count();
        }

        return view('seller.order.rescheduling_order_list', compact('orders', 'count'));
    }

    public function show($id)
    {
        if (!Schema::hasTable('seller_orders')) {
            abort(404, 'Orders table not found');
        }

        $order = SellerOrder::where('seller_id', Auth::id())
            ->findOrFail($id);

        return view('seller.order.order-show', compact('order'));
    }

    public function update(Request $request, $id)
    {
        if (!Schema::hasTable('seller_orders')) {
            return redirect()->back()->with('error', 'Orders table not found');
        }

        $order = SellerOrder::where('seller_id', Auth::id())
            ->findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,complete,return,rescheduling',
            'delivery_date' => 'nullable|date',
            'rescheduling_date' => 'nullable|date',
            'return_date' => 'nullable|date',
            'remark' => 'nullable|string'
        ]);

        $data = [
            'status' => $request->status,
            'remark' => $request->remark
        ];

        // Add date based on status
        switch ($request->status) {
            case 'complete':
                $data['delivery_date'] = $request->delivery_date ?? now();
                break;
            case 'return':
                $data['return_date'] = $request->return_date ?? now();
                break;
            case 'rescheduling':
                $data['rescheduling_date'] = $request->rescheduling_date ?? now();
                break;
        }

        $order->update($data);

        return redirect()->back()->with('success', 'Order updated successfully!');
    }

    public function destroy($id)
    {
        if (!Schema::hasTable('seller_orders')) {
            return redirect()->back()->with('error', 'Orders table not found');
        }

        $order = SellerOrder::where('seller_id', Auth::id())
            ->findOrFail($id);

        // Only allow deletion if order is still pending
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending orders can be deleted.');
        }

        $order->delete();

        return redirect()->route('seller.pending-order')->with('success', 'Order deleted successfully!');
    }
}
