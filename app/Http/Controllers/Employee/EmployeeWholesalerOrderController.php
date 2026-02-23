<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\WholesalerOrder;
use App\Models\Wholesaler;
use App\Models\OrderPrintLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class EmployeeWholesalerOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
    }

    /**
     * Display all wholesaler orders for employee
     */
    public function index(Request $request)
    {
        try {
            $query = WholesalerOrder::with(['wholesaler', 'items'])
                ->latest();

            // Filter by status
            if ($request->has('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            // Filter by wholesaler
            if ($request->has('wholesaler_id') && $request->wholesaler_id) {
                $query->where('wholesaler_id', $request->wholesaler_id);
            }

            // Filter by date
            if ($request->has('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->has('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Filter for today's orders
            if ($request->has('filter') && $request->filter == 'today') {
                $query->whereDate('created_at', Carbon::today());
            }

            // Filter for this week's orders
            if ($request->has('filter') && $request->filter == 'week') {
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
            }

            // Filter for last 7 days
            if ($request->has('filter') && $request->filter == 'last7days') {
                $query->where('created_at', '>=', Carbon::now()->subDays(7));
            }

            // Filter for this month
            if ($request->has('filter') && $request->filter == 'month') {
                $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
            }

            // Search
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%")
                        ->orWhereHas('wholesaler', function($q2) use ($search) {
                            $q2->where('business_name', 'like', "%{$search}%")
                                ->orWhere('name', 'like', "%{$search}%");
                        });
                });
            }

            // Handle bulk print selection
            if ($request->has('selected_orders')) {
                $selectedOrders = $request->selected_orders;
                session(['selected_orders_for_print' => $selectedOrders]);
            }

            $orders = $query->paginate(20)->withQueryString();

            // Get counts for status badges
            $statusCounts = [
                'all' => WholesalerOrder::count(),
                'today' => WholesalerOrder::whereDate('created_at', Carbon::today())->count(),
                'week' => WholesalerOrder::whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ])->count(),
                'pending' => WholesalerOrder::where('status', 'pending')->count(),
                'confirmed' => WholesalerOrder::where('status', 'confirmed')->count(),
                'processing' => WholesalerOrder::where('status', 'processing')->count(),
                'shipped' => WholesalerOrder::where('status', 'shipped')->count(),
                'completed' => WholesalerOrder::where('status', 'completed')->count(),
                'cancelled' => WholesalerOrder::where('status', 'cancelled')->count(),
            ];

            // Calculate total revenue
            $totalRevenue = WholesalerOrder::where('status', 'completed')
                ->sum('total_amount');

            // Calculate today's revenue
            $todayRevenue = WholesalerOrder::where('status', 'completed')
                ->whereDate('created_at', Carbon::today())
                ->sum('total_amount');

            // Calculate weekly revenue
            $weeklyRevenue = WholesalerOrder::where('status', 'completed')
                ->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ])
                ->sum('total_amount');

            // Get all wholesalers for filter
            $wholesalers = Wholesaler::all();

            // Get selected orders for print from session
            $selectedOrders = session('selected_orders_for_print', []);

            return view('employee.wholesaler.orders.index', compact(
                'orders',
                'statusCounts',
                'totalRevenue',
                'todayRevenue',
                'weeklyRevenue',
                'wholesalers',
                'selectedOrders'
            ));

        } catch (\Exception $e) {
            \Log::error('Employee Wholesaler Orders index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading orders: ' . $e->getMessage());
        }
    }

    /**
     * Display order details for employee
     */
    public function show($id)
    {
        try {
            $order = WholesalerOrder::with(['wholesaler', 'items.product'])
                ->findOrFail($id);

            return view('employee.wholesaler.orders.show', compact('order'));

        } catch (\Exception $e) {
            \Log::error('Employee Show wholesaler order error: ' . $e->getMessage());
            return redirect()->route('employee.wholesaler_orders.index')->with('error', 'Order not found');
        }
    }

    /**
     * Update order status from employee panel
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,confirmed,processing,shipped,delivered,completed,cancelled,refunded',
                'notes' => 'nullable|string|max:500'
            ]);

            $order = WholesalerOrder::findOrFail($id);

            $oldStatus = $order->status;
            $order->status = $request->status;

            if ($request->has('notes') && $request->notes) {
                $order->notes = $request->notes;
            }

            // Log the status change by employee
            $order->status_updated_by = Auth::guard('employee')->id();
            $order->status_updated_at = now();

            $order->save();

            \Log::info("Employee updated Order #{$order->order_number} status from {$oldStatus} to {$request->status}");

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully',
                'status' => $order->status,
                'status_text' => ucfirst($order->status)
            ]);

        } catch (\Exception $e) {
            \Log::error('Employee Update status error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update order status
     */
    public function bulkUpdateStatus(Request $request)
    {
        try {
            $request->validate([
                'order_ids' => 'required|array',
                'order_ids.*' => 'exists:wholesaler_orders,id',
                'status' => 'required|in:pending,confirmed,processing,shipped,delivered,completed,cancelled',
                'notes' => 'nullable|string|max:500'
            ]);

            $updatedCount = 0;
            $failedCount = 0;
            $failedOrders = [];

            foreach ($request->order_ids as $orderId) {
                try {
                    $order = WholesalerOrder::findOrFail($orderId);

                    $oldStatus = $order->status;
                    $order->status = $request->status;

                    if ($request->has('notes') && $request->notes) {
                        $order->notes = $request->notes;
                    }

                    $order->status_updated_by = Auth::guard('employee')->id();
                    $order->status_updated_at = now();
                    $order->save();

                    $updatedCount++;

                    \Log::info("Employee bulk updated Order #{$order->order_number} status from {$oldStatus} to {$request->status}");

                } catch (\Exception $e) {
                    $failedCount++;
                    $failedOrders[] = $orderId;
                    \Log::error("Failed to update order {$orderId}: " . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully updated {$updatedCount} orders" .
                    ($failedCount > 0 ? ", failed to update {$failedCount} orders" : ""),
                'updated_count' => $updatedCount,
                'failed_count' => $failedCount,
                'failed_orders' => $failedOrders
            ]);

        } catch (\Exception $e) {
            \Log::error('Employee Bulk update status error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating orders: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process refund for cancelled order
     */
    public function processRefund(Request $request, $id)
    {
        try {
            $order = WholesalerOrder::findOrFail($id);

            // Check if order is cancelled
            if ($order->status !== 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only cancelled orders can be refunded'
                ], 400);
            }

            // Check if already refunded
            if ($order->refund_status === 'refunded') {
                return response()->json([
                    'success' => false,
                    'message' => 'Order already refunded'
                ], 400);
            }

            // Check if payment was made
            if ($order->payment_status !== 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Order was not paid, no refund required'
                ], 400);
            }

            // Update order refund status
            $order->refund_status = 'refunded';
            $order->refund_amount = $order->total_amount;
            $order->refunded_at = now();
            $order->refunded_by = Auth::guard('employee')->id();
            $order->save();

            \Log::info("Employee processed refund for Order #{$order->order_number} - Amount: Rs. {$order->refund_amount}");

            return response()->json([
                'success' => true,
                'message' => 'Refund processed successfully',
                'refund_amount' => $order->refund_amount,
                'refund_status' => $order->refund_status
            ]);

        } catch (\Exception $e) {
            \Log::error('Employee Process refund error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing refund: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display print selection page
     */
    public function printSelection()
    {
        try {
            $selectedOrders = session('selected_orders_for_print', []);

            if (empty($selectedOrders)) {
                return redirect()->route('employee.wholesaler_orders.index')
                    ->with('warning', 'No orders selected for printing');
            }

            $orders = WholesalerOrder::with(['wholesaler', 'items'])
                ->whereIn('id', $selectedOrders)
                ->get();

            return view('employee.wholesaler.orders.print-selection', compact('orders'));

        } catch (\Exception $e) {
            \Log::error('Employee Print selection error: ' . $e->getMessage());
            return redirect()->route('employee.wholesaler_orders.index')
                ->with('error', 'Error loading print selection: ' . $e->getMessage());
        }
    }

    /**
     * Print selected orders
     */
    public function printSelectedOrders(Request $request)
    {
        try {
            $selectedOrders = session('selected_orders_for_print', []);

            if (empty($selectedOrders)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No orders selected for printing'
                ], 400);
            }

            $orders = WholesalerOrder::with(['wholesaler', 'items.product'])
                ->whereIn('id', $selectedOrders)
                ->get();

            // Log the print action
            foreach ($orders as $order) {
                OrderPrintLog::create([
                    'order_id' => $order->id,
                    'employee_id' => Auth::guard('employee')->id(),
                    'order_type' => 'wholesaler',
                    'printed_at' => now(),
                    'print_count' => $order->print_count + 1
                ]);

                $order->increment('print_count');
            }

            // Clear the selected orders from session
            session()->forget('selected_orders_for_print');

            return response()->json([
                'success' => true,
                'message' => 'Orders printed successfully',
                'count' => $orders->count(),
                'orders' => $orders->pluck('order_number')
            ]);

        } catch (\Exception $e) {
            \Log::error('Employee Print selected orders error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error printing orders: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear selected orders for print
     */
    public function clearSelectedOrders()
    {
        try {
            session()->forget('selected_orders_for_print');

            return response()->json([
                'success' => true,
                'message' => 'Selected orders cleared successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Employee Clear selected orders error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error clearing selected orders: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save selected orders to session
     */
    public function saveSelectedOrders(Request $request)
    {
        try {
            $request->validate([
                'selected_orders' => 'nullable|array',
                'selected_orders.*' => 'exists:wholesaler_orders,id'
            ]);

            session(['selected_orders_for_print' => $request->selected_orders ?? []]);

            return response()->json([
                'success' => true,
                'message' => 'Selection saved successfully',
                'count' => count($request->selected_orders ?? [])
            ]);

        } catch (\Exception $e) {
            \Log::error('Employee Save selected orders error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving selection: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get today's orders
     */
    public function todayOrders(Request $request)
    {
        try {
            $query = WholesalerOrder::whereDate('created_at', Carbon::today())
                ->with(['wholesaler', 'items'])
                ->latest();

            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%");
                });
            }

            $orders = $query->paginate(20)->withQueryString();

            $todayCount = WholesalerOrder::whereDate('created_at', Carbon::today())->count();
            $todayRevenue = WholesalerOrder::whereDate('created_at', Carbon::today())
                ->where('status', 'completed')
                ->sum('total_amount');

            return view('employee.wholesaler.orders.today', compact('orders', 'todayCount', 'todayRevenue'));

        } catch (\Exception $e) {
            \Log::error('Employee Today orders error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading today\'s orders');
        }
    }

    /**
     * Get weekly orders
     */
    public function weeklyOrders(Request $request)
    {
        try {
            $query = WholesalerOrder::whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
                ->with(['wholesaler', 'items'])
                ->latest();

            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%");
                });
            }

            $orders = $query->paginate(20)->withQueryString();

            $weeklyCount = WholesalerOrder::whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count();

            $weeklyRevenue = WholesalerOrder::whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->where('status', 'completed')
                ->sum('total_amount');

            return view('employee.wholesaler.orders.weekly', compact('orders', 'weeklyCount', 'weeklyRevenue'));

        } catch (\Exception $e) {
            \Log::error('Employee Weekly orders error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading weekly orders');
        }
    }

    /**
     * Download invoice as PDF
     */
    public function downloadInvoice($id)
    {
        try {
            $order = WholesalerOrder::with(['wholesaler', 'items.product'])
                ->findOrFail($id);

            // Generate PDF
            $pdf = Pdf::loadView('employee.wholesaler.orders.invoice-pdf', compact('order'));

            // Log the download
            \Log::info("Employee downloaded invoice for Order #{$order->order_number}");

            // Return PDF download with proper filename
            return $pdf->download("invoice-{$order->order_number}.pdf");

        } catch (\Exception $e) {
            \Log::error('Employee Download invoice error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error generating invoice: ' . $e->getMessage());
        }
    }

    /**
     * View invoice in browser
     */
    public function viewInvoice($id)
    {
        try {
            $order = WholesalerOrder::with(['wholesaler', 'items.product'])
                ->findOrFail($id);

            return view('employee.wholesaler.orders.invoice', compact('order'));

        } catch (\Exception $e) {
            \Log::error('Employee View invoice error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading invoice: ' . $e->getMessage());
        }
    }

    /**
     * Print delivery label
     */
    public function printDeliveryLabel($id)
    {
        try {
            $order = WholesalerOrder::with(['wholesaler', 'items'])
                ->findOrFail($id);

            // Log the print
            OrderPrintLog::create([
                'order_id' => $order->id,
                'employee_id' => Auth::guard('employee')->id(),
                'order_type' => 'wholesaler',
                'printed_at' => now(),
                'print_type' => 'delivery_label'
            ]);

            return view('employee.wholesaler.orders.delivery-label', compact('order'));

        } catch (\Exception $e) {
            \Log::error('Employee Print delivery label error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error generating delivery label: ' . $e->getMessage());
        }
    }

    /**
     * Export orders to CSV for employee
     */
    public function export(Request $request)
    {
        try {
            $query = WholesalerOrder::with(['wholesaler', 'items'])
                ->latest();

            if ($request->has('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            $orders = $query->get();

            $fileName = 'wholesaler-orders-' . date('Y-m-d') . '.csv';
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
                    'Wholesaler',
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
                        $order->wholesaler->business_name ?? 'N/A',
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
            \Log::error('Employee Export wholesaler orders error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting orders');
        }
    }
}
