<?php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use App\Models\WholesalerOrder;
use App\Models\WholesalerOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class WholesalerOrderController extends Controller
{
    /**
     * Display all orders
     */
    public function index(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $query = WholesalerOrder::where('wholesaler_id', $wholesaler->id)
                ->with(['items'])
                ->latest();

            // Filter by status
            if ($request->has('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            // Filter by date
            if ($request->has('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->has('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Search
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%");
                });
            }

            $orders = $query->paginate(20)->withQueryString();

            // Get counts for status badges
            $statusCounts = [
                'all' => WholesalerOrder::where('wholesaler_id', $wholesaler->id)->count(),
                'pending' => WholesalerOrder::where('wholesaler_id', $wholesaler->id)->where('status', 'pending')->count(),
                'confirmed' => WholesalerOrder::where('wholesaler_id', $wholesaler->id)->where('status', 'confirmed')->count(),
                'processing' => WholesalerOrder::where('wholesaler_id', $wholesaler->id)->where('status', 'processing')->count(),
                'shipped' => WholesalerOrder::where('wholesaler_id', $wholesaler->id)->where('status', 'shipped')->count(),
                'completed' => WholesalerOrder::where('wholesaler_id', $wholesaler->id)->where('status', 'completed')->count(),
                'cancelled' => WholesalerOrder::where('wholesaler_id', $wholesaler->id)->where('status', 'cancelled')->count(),
            ];

            // Calculate total revenue
            $totalRevenue = WholesalerOrder::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'completed')
                ->sum('total_amount');

            return view('wholesaler.orders.index', compact('orders', 'statusCounts', 'totalRevenue'));

        } catch (\Exception $e) {
            \Log::error('Orders index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading orders: ' . $e->getMessage());
        }
    }

    /**
     * Display order details
     */
    public function show($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $order = WholesalerOrder::where('wholesaler_id', $wholesaler->id)
                ->with(['items.product'])
                ->findOrFail($id);

            return view('wholesaler.orders.show', compact('order'));

        } catch (\Exception $e) {
            \Log::error('Show order error: ' . $e->getMessage());
            return redirect()->route('wholesaler.orders.index')->with('error', 'Order not found');
        }
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $request->validate([
                'status' => 'required|in:pending,confirmed,processing,shipped,delivered,completed,cancelled,refunded',
                'notes' => 'nullable|string|max:500'
            ]);

            $order = WholesalerOrder::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            $oldStatus = $order->status;
            $order->status = $request->status;

            if ($request->has('notes') && $request->notes) {
                $order->notes = $request->notes;
            }

            $order->save();

            \Log::info("Order #{$order->order_number} status changed from {$oldStatus} to {$request->status}");

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully',
                'status' => $order->status,
                'status_text' => ucfirst($order->status)
            ]);

        } catch (\Exception $e) {
            \Log::error('Update status error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display pending orders
     */
    public function pending()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $orders = WholesalerOrder::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'pending')
                ->with(['items'])
                ->latest()
                ->paginate(20);

            return view('wholesaler.orders.pending', compact('orders'));

        } catch (\Exception $e) {
            \Log::error('Pending orders error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading pending orders');
        }
    }

    /**
     * Display confirmed orders
     */
    public function confirmed()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $orders = WholesalerOrder::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'confirmed')
                ->with(['items'])
                ->latest()
                ->paginate(20);

            return view('wholesaler.orders.confirmed', compact('orders'));

        } catch (\Exception $e) {
            \Log::error('Confirmed orders error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading confirmed orders');
        }
    }

    /**
     * Display processing orders
     */
    public function processing()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $orders = WholesalerOrder::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'processing')
                ->with(['items'])
                ->latest()
                ->paginate(20);

            return view('wholesaler.orders.processing', compact('orders'));

        } catch (\Exception $e) {
            \Log::error('Processing orders error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading processing orders');
        }
    }

    /**
     * Display shipped orders
     */
    public function shipped()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $orders = WholesalerOrder::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'shipped')
                ->with(['items'])
                ->latest()
                ->paginate(20);

            return view('wholesaler.orders.shipped', compact('orders'));

        } catch (\Exception $e) {
            \Log::error('Shipped orders error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading shipped orders');
        }
    }

    /**
     * Display completed orders
     */
    public function completed()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $orders = WholesalerOrder::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'completed')
                ->with(['items'])
                ->latest()
                ->paginate(20);

            $totalRevenue = WholesalerOrder::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'completed')
                ->sum('total_amount');

            return view('wholesaler.orders.completed', compact('orders', 'totalRevenue'));

        } catch (\Exception $e) {
            \Log::error('Completed orders error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading completed orders');
        }
    }

    /**
     * Display cancelled orders
     */
    public function cancelled()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $orders = WholesalerOrder::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'cancelled')
                ->with(['items'])
                ->latest()
                ->paginate(20);

            return view('wholesaler.orders.cancelled', compact('orders'));

        } catch (\Exception $e) {
            \Log::error('Cancelled orders error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading cancelled orders');
        }
    }

    /**
     * Download invoice
     */
    public function downloadInvoice($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $order = WholesalerOrder::where('wholesaler_id', $wholesaler->id)
                ->with(['items.product'])
                ->findOrFail($id);

            return Pdf::loadView('wholesaler.orders.invoice', compact('order'))->download("invoice-{$order->order_number}.pdf");

        } catch (\Exception $e) {
            \Log::error('Download invoice error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error generating invoice');
        }
    }

    /**
     * View invoice
     */
    public function viewInvoice($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $order = WholesalerOrder::where('wholesaler_id', $wholesaler->id)
                ->with(['items.product'])
                ->findOrFail($id);

            return view('wholesaler.orders.invoice-view', compact('order'));

        } catch (\Exception $e) {
            \Log::error('View invoice error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading invoice');
        }
    }

    /**
     * Export orders to CSV
     */
    public function export(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $orders = WholesalerOrder::where('wholesaler_id', $wholesaler->id)
                ->with(['items'])
                ->latest()
                ->get();

            $fileName = 'orders-' . date('Y-m-d') . '.csv';
            $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $callback = function() use ($orders) {
                $file = fopen('php://output', 'w');

                fputcsv($file, [
                    'Order Number',
                    'Date',
                    'Customer Name',
                    'Customer Email',
                    'Customer Phone',
                    'Total Amount',
                    'Status',
                    'Payment Method',
                    'Payment Status'
                ]);

                foreach ($orders as $order) {
                    fputcsv($file, [
                        $order->order_number,
                        $order->created_at->format('Y-m-d H:i:s'),
                        $order->customer_name,
                        $order->customer_email,
                        $order->customer_phone,
                        $order->total_amount,
                        ucfirst($order->status),
                        ucwords(str_replace('_', ' ', $order->payment_method)),
                        ucfirst($order->payment_status)
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            \Log::error('Export orders error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting orders');
        }
    }
}
