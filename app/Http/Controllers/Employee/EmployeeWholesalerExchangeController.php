<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\WholesalerProduct;
use App\Models\Wholesaler;
use App\Models\WholesalerProductExchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmployeeWholesalerExchangeController extends Controller
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

            $query = WholesalerProductExchange::with([
                'sourceProduct',
                'sourceWholesaler',
                'destinationProduct',
                'destinationWholesaler',
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
                    $q->whereHas('sourceProduct', function($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    })
                        ->orWhereHas('destinationProduct', function($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%")
                                ->orWhere('code', 'like', "%{$search}%");
                        })
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
            $employees = \App\Models\Employee::orderBy('name')->get(['id', 'name']);

            // Stats
            $stats = [
                'total_exchanges' => WholesalerProductExchange::count(),
                'pending_exchanges' => WholesalerProductExchange::where('status', 'pending')->count(),
                'completed_exchanges' => WholesalerProductExchange::where('status', 'completed')->count(),
                'total_value_transferred' => WholesalerProductExchange::sum('destination_price_at_time'),
                'total_adjustments' => WholesalerProductExchange::sum('adjustment_amount'),
                'my_exchanges' => WholesalerProductExchange::where('employee_id', $employee->id)->count(),
            ];

            return view('employee.wholesaler.exchange.index', compact(
                'exchanges',
                'wholesalers',
                'employees',
                'stats',
                'employee'
            ));

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerExchangeController@index error: ' . $e->getMessage());
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

            $sourceProduct = null;
            if ($request->filled('source_product_id')) {
                $sourceProduct = WholesalerProduct::with('wholesaler')
                    ->find($request->source_product_id);
            }

            return view('employee.wholesaler.exchange.create', compact('wholesalers', 'sourceProduct'));

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerExchangeController@create error: ' . $e->getMessage());
            return redirect()->route('employee.wholesaler.exchange.index')
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
                'source_product_id' => 'required|exists:wholesaler_products,id',
                'destination_product_id' => 'required|exists:wholesaler_products,id|different:source_product_id',
                'source_wholesaler_id' => 'required|exists:wholesalers,id',
                'destination_wholesaler_id' => 'required|exists:wholesalers,id|different:source_wholesaler_id',
                'exchange_type' => 'required|in:full,partial,credit',
                'exchange_quantity' => 'required|numeric|min:0.01',
                'adjustment_amount' => 'nullable|numeric',
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
            $sourceProduct = WholesalerProduct::findOrFail($request->source_product_id);
            $destinationProduct = WholesalerProduct::findOrFail($request->destination_product_id);

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
            if ($sourceProduct->qty < $request->exchange_quantity) {
                return redirect()->back()
                    ->with('error', 'Source product does not have enough quantity. Available: ' . $sourceProduct->qty)
                    ->withInput();
            }

            // Calculate prices
            $sourcePrice = $sourceProduct->final_price * $request->exchange_quantity;
            $destinationPrice = $destinationProduct->final_price * $request->exchange_quantity;
            $priceDifference = $destinationPrice - $sourcePrice;

            $status = $request->boolean('require_approval') ? 'pending' : 'approved';

            // Create exchange record
            $exchange = WholesalerProductExchange::create([
                'source_product_id' => $sourceProduct->id,
                'source_wholesaler_id' => $sourceProduct->wholesaler_id,
                'destination_product_id' => $destinationProduct->id,
                'destination_wholesaler_id' => $destinationProduct->wholesaler_id,
                'exchange_type' => $request->exchange_type,
                'exchange_quantity' => $request->exchange_quantity,
                'source_price_at_time' => $sourcePrice,
                'destination_price_at_time' => $destinationPrice,
                'price_difference' => $priceDifference,
                'adjustment_amount' => $request->adjustment_amount ?? 0,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status ?? 'pending',
                'employee_id' => $employee->id,
                'status' => $status,
                'notes' => $request->notes,
                'exchange_data' => [
                    'source_product_name' => $sourceProduct->name,
                    'source_product_code' => $sourceProduct->code,
                    'destination_product_name' => $destinationProduct->name,
                    'destination_product_code' => $destinationProduct->code,
                    'source_price_per_unit' => $sourceProduct->final_price,
                    'destination_price_per_unit' => $destinationProduct->final_price,
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

            return redirect()->route('employee.wholesaler.exchange.show', $exchange->id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmployeeWholesalerExchangeController@store error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error creating exchange: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Process the actual exchange (transfer products)
     */
    private function processExchange(WholesalerProductExchange $exchange, $employeeId)
    {
        // Update quantities
        $sourceProduct = $exchange->sourceProduct;
        $destinationProduct = $exchange->destinationProduct;

        // Reduce source product quantity
        $sourceProduct->qty -= $exchange->exchange_quantity;
        $sourceProduct->save();

        // Increase destination product quantity
        $destinationProduct->qty += $exchange->exchange_quantity;

        // Update destination product's wholesaler if this is a transfer to new owner
        if ($exchange->destination_wholesaler_id != $destinationProduct->wholesaler_id) {
            $destinationProduct->wholesaler_id = $exchange->destination_wholesaler_id;
        }

        $destinationProduct->save();

        // Update exchange stats on both products
        $sourceProduct->updateExchangeStats($exchange);
        $destinationProduct->updateExchangeStats($exchange);

        // Update exchange record
        $exchange->status = 'completed';
        $exchange->approved_by = $employeeId;
        $exchange->approved_at = now();
        $exchange->payment_status = 'completed';
        $exchange->payment_date = now();
        $exchange->save();

        // Log the exchange
        Log::info('Product exchange completed', [
            'exchange_id' => $exchange->id,
            'source_product' => $sourceProduct->id,
            'destination_product' => $destinationProduct->id,
            'quantity' => $exchange->exchange_quantity,
            'employee_id' => $employeeId
        ]);
    }

    /**
     * Show exchange details
     */
    public function show($id)
    {
        try {
            $exchange = WholesalerProductExchange::with([
                'sourceProduct',
                'sourceWholesaler',
                'destinationProduct',
                'destinationWholesaler',
                'employee',
                'approvedBy'
            ])->findOrFail($id);

            return view('employee.wholesaler.exchange.show', compact('exchange'));

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerExchangeController@show error: ' . $e->getMessage());
            return redirect()->route('employee.wholesaler.exchange.index')
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

            $exchange = WholesalerProductExchange::findOrFail($id);

            if ($exchange->status !== 'pending') {
                return redirect()->back()->with('error', 'This exchange is not pending.');
            }

            $this->processExchange($exchange, $employee->id);

            DB::commit();

            return redirect()->route('employee.wholesaler.exchange.show', $exchange->id)
                ->with('success', 'Exchange approved and completed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmployeeWholesalerExchangeController@approve error: ' . $e->getMessage());

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

            $exchange = WholesalerProductExchange::findOrFail($id);

            if ($exchange->status !== 'pending') {
                return redirect()->back()->with('error', 'This exchange is not pending.');
            }

            $exchange->status = 'rejected';
            $exchange->reason = $request->rejection_reason;
            $exchange->save();

            DB::commit();

            return redirect()->route('employee.wholesaler.exchange.index')
                ->with('success', 'Exchange rejected.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmployeeWholesalerExchangeController@reject error: ' . $e->getMessage());

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
            $exchange = WholesalerProductExchange::findOrFail($id);

            if (!in_array($exchange->status, ['pending', 'approved'])) {
                return redirect()->back()->with('error', 'This exchange cannot be cancelled.');
            }

            $exchange->status = 'cancelled';
            $exchange->reason = $request->reason ?? 'Cancelled by employee';
            $exchange->save();

            DB::commit();

            return redirect()->route('employee.wholesaler.exchange.index')
                ->with('success', 'Exchange cancelled.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmployeeWholesalerExchangeController@cancel error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error cancelling exchange: ' . $e->getMessage());
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

            $products = WholesalerProduct::where('wholesaler_id', $wholesalerId)
                ->where('status', 'active')
                ->where('qty', '>', 0)
                ->orderBy('name')
                ->get(['id', 'code', 'name', 'qty', 'selling_price', 'display_price']);

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

            $product = WholesalerProduct::with('wholesaler')
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
                    'qty' => $product->qty,
                    'price' => $product->final_price,
                    'display_price' => $product->display_price,
                    'selling_price' => $product->selling_price,
                    'discount' => $product->discount ?? 0,
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
            $query = WholesalerProductExchange::with([
                'sourceProduct',
                'sourceWholesaler',
                'destinationProduct',
                'destinationWholesaler',
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

            $filename = 'product-exchanges-' . date('Y-m-d') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}",
            ];

            $callback = function() use ($exchanges) {
                $file = fopen('php://output', 'w');

                // Headers
                fputcsv($file, [
                    'Date',
                    'Exchange ID',
                    'Source Product',
                    'Source Wholesaler',
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
                        $exchange->id,
                        $exchange->sourceProduct->name . ' (' . $exchange->sourceProduct->code . ')',
                        $exchange->sourceWholesaler->business_name ?? 'N/A',
                        $exchange->destinationProduct->name . ' (' . $exchange->destinationProduct->code . ')',
                        $exchange->destinationWholesaler->business_name ?? 'N/A',
                        $exchange->exchange_quantity,
                        'Rs. ' . number_format($exchange->source_price_at_time, 2),
                        'Rs. ' . number_format($exchange->destination_price_at_time, 2),
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
            Log::error('EmployeeWholesalerExchangeController@export error: ' . $e->getMessage());
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
                'total_exchanges' => WholesalerProductExchange::count(),
                'total_products_exchanged' => WholesalerProductExchange::distinct('source_product_id')->count('source_product_id'),
                'total_wholesalers_involved' => WholesalerProductExchange::distinct('source_wholesaler_id')
                        ->count('source_wholesaler_id') + WholesalerProductExchange::distinct('destination_wholesaler_id')
                        ->count('destination_wholesaler_id'),
                'total_value_transferred' => WholesalerProductExchange::sum('destination_price_at_time'),
                'my_exchanges' => WholesalerProductExchange::where('employee_id', $employee->id)->count(),
            ];

            // Monthly trends
            $monthlyTrends = WholesalerProductExchange::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(destination_price_at_time) as total_value'),
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
            Log::error('EmployeeWholesalerExchangeController@stats error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error loading statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}
