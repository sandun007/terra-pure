<?php
// app/Http/Controllers/Employee/EmployeeWholesalerOrderExchangeController.php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\WholesalerOrderExchange;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Wholesaler;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmployeeWholesalerOrderExchangeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
    }

    /**
     * Display all exchanges
     */
    public function index(Request $request)
    {
        try {
            $employee = Auth::guard('employee')->user();

            $query = WholesalerOrderExchange::with([
                'sourceProduct',
                'sourceWholesaler',
                'sourceOrder',
                'destinationProduct',
                'destinationWholesaler',
                'destinationOrder',
                'employee',
                'approvedBy'
            ]);

            // Filters
            if ($request->filled('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            if ($request->filled('source_wholesaler_id') && $request->source_wholesaler_id != 'all') {
                $query->where('source_wholesaler_id', $request->source_wholesaler_id);
            }

            if ($request->filled('destination_wholesaler_id') && $request->destination_wholesaler_id != 'all') {
                $query->where('destination_wholesaler_id', $request->destination_wholesaler_id);
            }

            if ($request->filled('employee_id') && $request->employee_id != 'all') {
                $query->where('employee_id', $request->employee_id);
            }

            if ($request->filled('exchange_type') && $request->exchange_type != 'all') {
                $query->where('exchange_type', $request->exchange_type);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('exchange_number', 'like', "%{$search}%")
                        ->orWhere('source_product_name', 'like', "%{$search}%")
                        ->orWhere('source_product_code', 'like', "%{$search}%")
                        ->orWhere('destination_product_name', 'like', "%{$search}%")
                        ->orWhere('destination_product_code', 'like', "%{$search}%")
                        ->orWhereHas('sourceWholesaler', function($q2) use ($search) {
                            $q2->where('business_name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('destinationWholesaler', function($q2) use ($search) {
                            $q2->where('business_name', 'like', "%{$search}%");
                        });
                });
            }

            $exchanges = $query->orderBy('created_at', 'desc')->paginate(20);

            // Get filter data
            $wholesalers = Wholesaler::orderBy('business_name')->get(['id', 'business_name as name']);
            $employees = Employee::orderBy('name')->get(['id', 'name']);

            // Stats
            $stats = [
                'total_exchanges' => WholesalerOrderExchange::count(),
                'pending_exchanges' => WholesalerOrderExchange::where('status', 'pending')->count(),
                'completed_exchanges' => WholesalerOrderExchange::where('status', 'completed')->count(),
                'total_value_transferred' => WholesalerOrderExchange::sum('destination_total_price'),
                'total_adjustments' => WholesalerOrderExchange::sum('adjustment_amount'),
                'my_exchanges' => WholesalerOrderExchange::where('employee_id', $employee->id)->count(),
            ];

            return view('employee.wholesaler.exchange.index', compact(
                'exchanges',
                'wholesalers',
                'employees',
                'stats',
                'employee'
            ));

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerOrderExchangeController@index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading exchanges: ' . $e->getMessage());
        }
    }

    /**
     * Show form to create exchange
     */
    public function create(Request $request)
    {
        try {
            $wholesalers = Wholesaler::orderBy('business_name')->get(['id', 'business_name as name']);

            $sourceOrder = null;
            $sourceOrderItem = null;

            if ($request->filled('order_id')) {
                $sourceOrder = Order::with(['items.product', 'wholesaler'])
                    ->find($request->order_id);
            }

            if ($request->filled('order_item_id')) {
                $sourceOrderItem = OrderItem::with(['product', 'order.wholesaler'])
                    ->find($request->order_item_id);
            }

            return view('employee.wholesaler.exchange.create', compact('wholesalers', 'sourceOrder', 'sourceOrderItem'));

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerOrderExchangeController@create error: ' . $e->getMessage());
            return redirect()->route('employee.wholesaler.exchanges.index')
                ->with('error', 'Error loading exchange form: ' . $e->getMessage());
        }
    }

    /**
     * Store new exchange
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $employee = Auth::guard('employee')->user();

            $validator = Validator::make($request->all(), [
                'source_order_id' => 'nullable|exists:orders,id',
                'source_order_item_id' => 'nullable|exists:order_items,id',
                'source_product_id' => 'required|exists:products,id',
                'source_wholesaler_id' => 'required|exists:wholesalers,id',
                'destination_order_id' => 'nullable|exists:orders,id',
                'destination_product_id' => 'required|exists:products,id|different:source_product_id',
                'destination_wholesaler_id' => 'required|exists:wholesalers,id|different:source_wholesaler_id',
                'exchange_type' => 'required|in:full,partial,credit',
                'exchange_quantity' => 'required|integer|min:1',
                'adjustment_amount' => 'nullable|numeric|min:0',
                'payment_method' => 'nullable|string|in:cash,credit,bank_transfer',
                'payment_status' => 'nullable|string|in:pending,completed,failed',
                'notes' => 'nullable|string|max:1000',
                'require_approval' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Get products
            $sourceProduct = Product::findOrFail($request->source_product_id);
            $destinationProduct = Product::findOrFail($request->destination_product_id);

            // Verify wholesalers match
            if ($sourceProduct->wholesaler_id != $request->source_wholesaler_id) {
                return redirect()->back()
                    ->with('error', 'Source product does not belong to selected source wholesaler.')
                    ->withInput();
            }

            if ($destinationProduct->wholesaler_id != $request->destination_wholesaler_id) {
                return redirect()->back()
                    ->with('error', 'Destination product does not belong to selected destination wholesaler.')
                    ->withInput();
            }

            // Check stock for source product
            if ($sourceProduct->quantity < $request->exchange_quantity) {
                return redirect()->back()
                    ->with('error', 'Source product does not have enough quantity. Available: ' . $sourceProduct->quantity)
                    ->withInput();
            }

            // Get source order details if provided
            $sourceOrder = null;
            $sourceOrderItem = null;
            if ($request->filled('source_order_id')) {
                $sourceOrder = Order::find($request->source_order_id);
            }
            if ($request->filled('source_order_item_id')) {
                $sourceOrderItem = OrderItem::find($request->source_order_item_id);
            }

            // Calculate prices
            $sourcePricePerUnit = $sourceProduct->selling_price ?? $sourceProduct->price;
            $destPricePerUnit = $destinationProduct->selling_price ?? $destinationProduct->price;

            $sourceTotalPrice = $sourcePricePerUnit * $request->exchange_quantity;
            $destinationTotalPrice = $destPricePerUnit * $request->exchange_quantity;
            $priceDifference = $destinationTotalPrice - $sourceTotalPrice;

            $status = $request->boolean('require_approval') ? 'pending' : 'approved';

            // Create exchange record
            $exchange = WholesalerOrderExchange::create([
                // Source Details
                'source_order_id' => $request->source_order_id,
                'source_order_item_id' => $request->source_order_item_id,
                'source_product_id' => $sourceProduct->id,
                'source_wholesaler_id' => $sourceProduct->wholesaler_id,
                'source_product_name' => $sourceProduct->name,
                'source_product_code' => $sourceProduct->code,
                'source_quantity' => $sourceProduct->quantity,
                'source_price_per_unit' => $sourcePricePerUnit,
                'source_total_price' => $sourceTotalPrice,

                // Destination Details
                'destination_product_id' => $destinationProduct->id,
                'destination_wholesaler_id' => $destinationProduct->wholesaler_id,
                'destination_product_name' => $destinationProduct->name,
                'destination_product_code' => $destinationProduct->code,
                'destination_quantity' => $destinationProduct->quantity,
                'destination_price_per_unit' => $destPricePerUnit,
                'destination_total_price' => $destinationTotalPrice,

                // Exchange Details
                'exchange_type' => $request->exchange_type,
                'exchange_quantity' => $request->exchange_quantity,
                'price_difference' => $priceDifference,
                'adjustment_amount' => $request->adjustment_amount ?? 0,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status ?? 'pending',
                'exchange_number' => WholesalerOrderExchange::generateExchangeNumber(),
                'employee_id' => $employee->id,
                'status' => $status,
                'notes' => $request->notes,
                'exchange_data' => [
                    'source_order_number' => $sourceOrder->order_number ?? null,
                    'source_order_date' => $sourceOrder->created_at ?? null,
                    'source_product_original_price' => $sourceProduct->price,
                    'source_product_selling_price' => $sourceProduct->selling_price,
                    'destination_product_original_price' => $destinationProduct->price,
                    'destination_product_selling_price' => $destinationProduct->selling_price,
                ]
            ]);

            // If approved immediately, process the exchange
            if ($status === 'approved') {
                $this->processExchange($exchange, $employee->id);
            }

            DB::commit();

            $message = $status === 'pending'
                ? 'Exchange submitted for approval.'
                : 'Exchange completed successfully.';

            return redirect()->route('employee.wholesaler.exchanges.show', $exchange->id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmployeeWholesalerOrderExchangeController@store error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error creating exchange: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Process the actual exchange (transfer products)
     */
    private function processExchange(WholesalerOrderExchange $exchange, $employeeId)
    {
        // Update quantities in products
        $exchange->updateQuantities();

        // Create destination order if needed
        if (!$exchange->destination_order_id) {
            $destinationOrder = $this->createDestinationOrder($exchange, $employeeId);
            $exchange->destination_order_id = $destinationOrder->id;
        }

        // Update exchange record
        $exchange->status = 'completed';
        $exchange->approved_by = $employeeId;
        $exchange->approved_at = now();
        $exchange->payment_status = 'completed';
        $exchange->payment_date = now();
        $exchange->save();

        // Log the exchange
        Log::info('Order exchange completed', [
            'exchange_id' => $exchange->id,
            'exchange_number' => $exchange->exchange_number,
            'source_product' => $exchange->source_product_id,
            'destination_product' => $exchange->destination_product_id,
            'quantity' => $exchange->exchange_quantity,
            'employee_id' => $employeeId
        ]);
    }

    /**
     * Create destination order for exchange
     */
    private function createDestinationOrder(WholesalerOrderExchange $exchange, $employeeId)
    {
        $order = Order::create([
            'order_number' => 'EXC-' . $exchange->exchange_number,
            'wholesaler_id' => $exchange->destination_wholesaler_id,
            'employee_id' => $employeeId,
            'order_date' => now(),
            'status' => 'completed',
            'total_amount' => $exchange->destination_total_price,
            'payment_status' => $exchange->payment_status,
            'payment_method' => $exchange->payment_method,
            'notes' => 'Auto-generated from exchange #' . $exchange->exchange_number
        ]);

        // Create order item
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $exchange->destination_product_id,
            'product_name' => $exchange->destination_product_name,
            'product_code' => $exchange->destination_product_code,
            'quantity' => $exchange->exchange_quantity,
            'price' => $exchange->destination_price_per_unit,
            'total' => $exchange->destination_total_price
        ]);

        return $order;
    }

    /**
     * Show exchange details
     */
    public function show($id)
    {
        try {
            $exchange = WholesalerOrderExchange::with([
                'sourceProduct',
                'sourceWholesaler',
                'sourceOrder',
                'sourceOrderItem',
                'destinationProduct',
                'destinationWholesaler',
                'destinationOrder',
                'employee',
                'approvedBy'
            ])->findOrFail($id);

            return view('employee.wholesaler.exchange.show', compact('exchange'));

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerOrderExchangeController@show error: ' . $e->getMessage());
            return redirect()->route('employee.wholesaler.exchanges.index')
                ->with('error', 'Exchange not found: ' . $e->getMessage());
        }
    }

    /**
     * Approve pending exchange
     */
    public function approve($id)
    {
        DB::beginTransaction();

        try {
            $employee = Auth::guard('employee')->user();

            $exchange = WholesalerOrderExchange::findOrFail($id);

            if ($exchange->status !== 'pending') {
                return redirect()->back()->with('error', 'This exchange is not pending.');
            }

            $this->processExchange($exchange, $employee->id);

            DB::commit();

            return redirect()->route('employee.wholesaler.exchanges.show', $exchange->id)
                ->with('success', 'Exchange approved and completed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmployeeWholesalerOrderExchangeController@approve error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error approving exchange: ' . $e->getMessage());
        }
    }

    /**
     * Reject pending exchange
     */
    public function reject(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'rejection_reason' => 'required|string|max:500',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $exchange = WholesalerOrderExchange::findOrFail($id);

            if ($exchange->status !== 'pending') {
                return redirect()->back()->with('error', 'This exchange is not pending.');
            }

            $exchange->status = 'rejected';
            $exchange->reason = $request->rejection_reason;
            $exchange->save();

            DB::commit();

            return redirect()->route('employee.wholesaler.exchanges.index')
                ->with('success', 'Exchange rejected.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmployeeWholesalerOrderExchangeController@reject error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error rejecting exchange: ' . $e->getMessage());
        }
    }

    /**
     * Cancel exchange
     */
    public function cancel(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $exchange = WholesalerOrderExchange::findOrFail($id);

            if (!in_array($exchange->status, ['pending', 'approved'])) {
                return redirect()->back()->with('error', 'This exchange cannot be cancelled.');
            }

            $exchange->status = 'cancelled';
            $exchange->reason = $request->reason ?? 'Cancelled by employee';
            $exchange->save();

            DB::commit();

            return redirect()->route('employee.wholesaler.exchanges.index')
                ->with('success', 'Exchange cancelled.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmployeeWholesalerOrderExchangeController@cancel error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error cancelling exchange: ' . $e->getMessage());
        }
    }

    /**
     * Get wholesaler orders (AJAX)
     */
    public function getWholesalerOrders(Request $request)
    {
        try {
            $wholesalerId = $request->get('wholesaler_id');

            if (!$wholesalerId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wholesaler ID required'
                ], 400);
            }

            $orders = Order::where('wholesaler_id', $wholesalerId)
                ->with(['items.product'])
                ->whereIn('status', ['pending', 'confirmed', 'processing'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'orders' => $orders
            ]);

        } catch (\Exception $e) {
            Log::error('getWholesalerOrders error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error loading orders: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get order details (AJAX)
     */
    public function getOrderDetails(Request $request)
    {
        try {
            $orderId = $request->get('order_id');

            if (!$orderId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order ID required'
                ], 400);
            }

            $order = Order::with(['items.product', 'wholesaler'])
                ->find($orderId);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'order' => $order
            ]);

        } catch (\Exception $e) {
            Log::error('getOrderDetails error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error loading order details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get products for a wholesaler (AJAX)
     */
    public function getWholesalerProducts(Request $request)
    {
        try {
            $wholesalerId = $request->get('wholesaler_id');

            if (!$wholesalerId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wholesaler ID required'
                ], 400);
            }

            $products = Product::where('wholesaler_id', $wholesalerId)
                ->where('status', 'active')
                ->where('quantity', '>', 0)
                ->orderBy('name')
                ->get(['id', 'code', 'name', 'quantity', 'price', 'selling_price']);

            return response()->json([
                'success' => true,
                'products' => $products
            ]);

        } catch (\Exception $e) {
            Log::error('getWholesalerProducts error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error loading products: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product details (AJAX)
     */
    public function getProductDetails(Request $request)
    {
        try {
            $productId = $request->get('product_id');

            if (!$productId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product ID required'
                ], 400);
            }

            $product = Product::with('wholesaler')
                ->find($productId);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'code' => $product->code,
                    'name' => $product->name,
                    'wholesaler_id' => $product->wholesaler_id,
                    'wholesaler_name' => $product->wholesaler->business_name ?? 'N/A',
                    'quantity' => $product->quantity,
                    'price' => $product->selling_price ?? $product->price,
                    'original_price' => $product->price,
                    'selling_price' => $product->selling_price,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('getProductDetails error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error loading product details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export exchanges to CSV
     */
    public function export(Request $request)
    {
        try {
            $query = WholesalerOrderExchange::with([
                'sourceProduct',
                'sourceWholesaler',
                'sourceOrder',
                'destinationProduct',
                'destinationWholesaler',
                'destinationOrder',
                'employee'
            ]);

            // Apply filters (same as index)
            if ($request->filled('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $exchanges = $query->orderBy('created_at', 'desc')->get();

            $filename = 'order-exchanges-' . date('Y-m-d') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}",
            ];

            $callback = function() use ($exchanges) {
                $file = fopen('php://output', 'w');

                // Headers
                fputcsv($file, [
                    'Date',
                    'Exchange Number',
                    'Source Order',
                    'Source Product',
                    'Source Wholesaler',
                    'Destination Order',
                    'Destination Product',
                    'Destination Wholesaler',
                    'Quantity',
                    'Source Value',
                    'Destination Value',
                    'Difference',
                    'Adjustment',
                    'Type',
                    'Status',
                    'Employee',
                    'Notes'
                ]);

                // Data
                foreach ($exchanges as $exchange) {
                    fputcsv($file, [
                        $exchange->created_at->format('Y-m-d H:i'),
                        $exchange->exchange_number,
                        $exchange->sourceOrder->order_number ?? 'N/A',
                        $exchange->source_product_name . ' (' . $exchange->source_product_code . ')',
                        $exchange->sourceWholesaler->business_name ?? 'N/A',
                        $exchange->destinationOrder->order_number ?? 'N/A',
                        $exchange->destination_product_name . ' (' . $exchange->destination_product_code . ')',
                        $exchange->destinationWholesaler->business_name ?? 'N/A',
                        $exchange->exchange_quantity,
                        'Rs. ' . number_format($exchange->source_total_price, 2),
                        'Rs. ' . number_format($exchange->destination_total_price, 2),
                        'Rs. ' . number_format($exchange->price_difference, 2),
                        'Rs. ' . number_format($exchange->adjustment_amount, 2),
                        ucfirst($exchange->exchange_type),
                        ucfirst($exchange->status),
                        $exchange->employee->name ?? 'N/A',
                        $exchange->notes ?? ''
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerOrderExchangeController@export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting exchanges: ' . $e->getMessage());
        }
    }

    /**
     * Get exchange statistics
     */
    public function stats(Request $request)
    {
        try {
            $employee = Auth::guard('employee')->user();

            $stats = [
                'total_exchanges' => WholesalerOrderExchange::count(),
                'total_products_exchanged' => WholesalerOrderExchange::distinct('source_product_id')->count('source_product_id'),
                'total_wholesalers_involved' => WholesalerOrderExchange::distinct('source_wholesaler_id')
                        ->count('source_wholesaler_id') + WholesalerOrderExchange::distinct('destination_wholesaler_id')
                        ->count('destination_wholesaler_id'),
                'total_value_transferred' => WholesalerOrderExchange::sum('destination_total_price'),
                'my_exchanges' => WholesalerOrderExchange::where('employee_id', $employee->id)->count(),
            ];

            // Monthly trends
            $monthlyTrends = WholesalerOrderExchange::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(destination_total_price) as total_value'),
                DB::raw('SUM(price_difference) as net_difference')
            )
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'monthly_trends' => $monthlyTrends
            ]);

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerOrderExchangeController@stats error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error loading statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}
