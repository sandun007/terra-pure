<?php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\WholesalerShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryryh\Pdf\Facades\Pdf;

class WholesalerOrderController extends Controller
{
    /**
     * Display all orders
     */
    public function index(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $query = Order::where('wholesaler_id', $wholesaler->id)
                ->with(['orderItems.product'])
                ->orderBy('created_at', 'desc');

            // Filter by status
            if ($request->has('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            // Filter by payment status
            if ($request->has('payment_status') && $request->payment_status != 'all') {
                $query->where('payment_status', $request->payment_status);
            }

            // Filter by date range
            if ($request->has('date_from') && $request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $orders = $query->paginate(10);

            // Statistics
            $totalOrders = Order::where('wholesaler_id', $wholesaler->id)->count();
            $pendingOrders = Order::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'pending')
                ->count();
            $completedOrders = Order::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'completed')
                ->count();
            $totalRevenue = Order::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'completed')
                ->sum('total_amount');

            return view('wholesaler.orders.index', compact(
                'orders',
                'totalOrders',
                'pendingOrders',
                'completedOrders',
                'totalRevenue'
            ));

        } catch (\Exception $e) {
            \Log::error('Order index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading orders: ' . $e->getMessage());
        }
    }

    /**
     * Display pending orders
     */
    public function pending()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $orders = Order::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'pending')
                ->with(['orderItems.product'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('wholesaler.orders.pending', compact('orders'));

        } catch (\Exception $e) {
            \Log::error('Pending orders error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading pending orders: ' . $e->getMessage());
        }
    }

    /**
     * Display confirmed orders
     */
    public function confirmed()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $orders = Order::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'confirmed')
                ->with(['orderItems.product'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('wholesaler.orders.confirmed', compact('orders'));

        } catch (\Exception $e) {
            \Log::error('Confirmed orders error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading confirmed orders: ' . $e->getMessage());
        }
    }

    /**
     * Display completed orders
     */
    public function completed()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $orders = Order::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'completed')
                ->with(['orderItems.product'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('wholesaler.orders.completed', compact('orders'));

        } catch (\Exception $e) {
            \Log::error('Completed orders error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading completed orders: ' . $e->getMessage());
        }
    }

    /**
     * Display processing orders
     */
    public function processing()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $orders = Order::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'processing')
                ->with(['orderItems.product'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('wholesaler.orders.processing', compact('orders'));

        } catch (\Exception $e) {
            \Log::error('Processing orders error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading processing orders: ' . $e->getMessage());
        }
    }

    /**
     * Display shipped orders
     */
    public function shipped()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $orders = Order::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'shipped')
                ->with(['orderItems.product'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('wholesaler.orders.shipped', compact('orders'));

        } catch (\Exception $e) {
            \Log::error('Shipped orders error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading shipped orders: ' . $e->getMessage());
        }
    }

    /**
     * Display cancelled orders
     */
    public function cancelled()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $orders = Order::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'cancelled')
                ->with(['orderItems.product'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('wholesaler.orders.cancelled', compact('orders'));

        } catch (\Exception $e) {
            \Log::error('Cancelled orders error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading cancelled orders: ' . $e->getMessage());
        }
    }

    /**
     * Display order details
     */
    public function show($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $order = Order::where('wholesaler_id', $wholesaler->id)
                ->with(['orderItems.product'])
                ->findOrFail($id);

            $shop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();

            return view('wholesaler.orders.show', compact('order', 'shop'));

        } catch (\Exception $e) {
            \Log::error('Order details error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Order not found: ' . $e->getMessage());
        }
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,confirmed,processing,shipped,completed,cancelled',
                'notes' => 'nullable|string|max:500',
            ]);

            $wholesaler = Auth::guard('wholesaler')->user();

            $order = Order::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            $oldStatus = $order->status;
            $order->update([
                'status' => $request->status,
                'status_notes' => $request->notes,
                'status_updated_at' => now(),
            ]);

            // If order is cancelled, restore stock
            if ($request->status == 'cancelled' && $oldStatus != 'cancelled') {
                foreach ($order->orderItems as $item) {
                    if ($item->product) {
                        $item->product->increment('qty', $item->quantity);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully!',
                'status' => $order->status
            ]);

        } catch (\Exception $e) {
            \Log::error('Update order status error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating order status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download invoice
     */
    public function downloadInvoice($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $order = Order::where('wholesaler_id', $wholesaler->id)
                ->with(['orderItems.product'])
                ->findOrFail($id);

            $shop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();

            $pdf = Pdf::loadView('wholesaler.orders.invoice', compact('order', 'shop'));

            return $pdf->download("invoice-{$order->order_no}.pdf");

        } catch (\Exception $e) {
            \Log::error('Download invoice error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error generating invoice: ' . $e->getMessage());
        }
    }

    /**
     * View invoice
     */
    public function viewInvoice($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $order = Order::where('wholesaler_id', $wholesaler->id)
                ->with(['orderItems.product'])
                ->findOrFail($id);

            $shop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();

            return view('wholesaler.orders.invoice', compact('order', 'shop'));

        } catch (\Exception $e) {
            \Log::error('View invoice error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading invoice: ' . $e->getMessage());
        }
    }

    /**
     * Export orders to CSV
     */
    public function export(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $orders = Order::where('wholesaler_id', $wholesaler->id)
                ->with(['orderItems.product'])
                ->orderBy('created_at', 'desc')
                ->get();

            $fileName = 'orders_' . date('Y-m-d_H-i-s') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=$fileName",
            ];

            $callback = function () use ($orders) {
                $file = fopen('php://output', 'w');

                // Add CSV headers
                fputcsv($file, [
                    'Order Number',
                    'Customer',
                    'Total Amount',
                    'Status',
                    'Payment Status',
                    'Order Date',
                    'Items Count'
                ]);

                foreach ($orders as $order) {
                    fputcsv($file, [
                        $order->order_no,
                        $order->customer_name,
                        $order->total_amount,
                        $order->status,
                        $order->payment_status,
                        $order->created_at->format('Y-m-d H:i:s'),
                        $order->orderItems->count()
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            \Log::error('Export orders error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting orders: ' . $e->getMessage());
        }
    }
}
