<?php
// app/Http/Controllers/Employee/EmployeeWholesalerPriceChangeController.php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\WholesalerProduct;
use App\Models\WholesalerProductPriceChange;
use App\Models\Wholesaler;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmployeeWholesalerPriceChangeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
    }

    /**
     * Display price change management dashboard
     */
    public function index(Request $request)
    {
        try {
            $employee = Auth::guard('employee')->user();

            $query = WholesalerProductPriceChange::with([
                'product',
                'wholesaler',
                'employee',
                'approvedBy'
            ]);

            // Filters
            if ($request->filled('wholesaler_id') && $request->wholesaler_id != 'all') {
                $query->where('wholesaler_id', $request->wholesaler_id);
            }

            if ($request->filled('employee_id') && $request->employee_id != 'all') {
                $query->where('employee_id', $request->employee_id);
            }

            if ($request->filled('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            if ($request->filled('change_type') && $request->change_type != 'all') {
                $query->where('change_type', $request->change_type);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('product', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            }

            $priceChanges = $query->orderBy('created_at', 'desc')->paginate(20);

            // Get filter data
            $wholesalers = Wholesaler::orderBy('business_name')->get(['id', 'business_name as name']);
            $employees = Employee::orderBy('name')->get(['id', 'name']);

            // Stats
            $stats = [
                'total_changes' => WholesalerProductPriceChange::count(),
                'today_changes' => WholesalerProductPriceChange::whereDate('created_at', today())->count(),
                'pending_changes' => WholesalerProductPriceChange::where('status', 'pending')->count(),
                'approved_changes' => WholesalerProductPriceChange::where('status', 'approved')->count(),
                'rejected_changes' => WholesalerProductPriceChange::where('status', 'rejected')->count(),
                'total_increase' => WholesalerProductPriceChange::where('price_difference', '>', 0)->sum('price_difference'),
                'total_decrease' => WholesalerProductPriceChange::where('price_difference', '<', 0)->sum('price_difference'),
                'my_changes' => WholesalerProductPriceChange::where('employee_id', $employee->id)->count(),
                'products_affected' => WholesalerProductPriceChange::distinct('product_id')->count('product_id'),
            ];

            return view('employee.wholesaler.price-change.index', compact(
                'priceChanges',
                'wholesalers',
                'employees',
                'stats',
                'employee'
            ));

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerPriceChangeController@index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading price changes: ' . $e->getMessage());
        }
    }

    /**
     * Show form to create price change (only for approved products)
     */
    public function create(Request $request)
    {
        try {
            $productId = $request->get('product_id');
            $product = null;

            if ($productId) {
                $product = WholesalerProduct::with('wholesaler')
                    ->where('employee_approval_status', 'approved')
                    ->find($productId);

                if (!$product) {
                    return redirect()->route('employee.wholesaler.price-changes.index')
                        ->with('error', 'Product not found or not approved yet.');
                }
            }

            $wholesalers = Wholesaler::orderBy('business_name')->get(['id', 'business_name as name']);

            return view('employee.wholesaler.price-change.create', compact('product', 'wholesalers'));

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerPriceChangeController@create error: ' . $e->getMessage());
            return redirect()->route('employee.wholesaler.price-changes.index')
                ->with('error', 'Error loading form: ' . $e->getMessage());
        }
    }

    /**
     * Store a new price change (single product)
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $employee = Auth::guard('employee')->user();

            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:wholesaler_products,id',
                'new_selling_price' => 'required|numeric|min:0',
                'new_display_price' => 'nullable|numeric|min:0',
                'new_discount' => 'nullable|numeric|min:0|max:100',
                'change_reason' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:1000',
                'require_approval' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $product = WholesalerProduct::findOrFail($request->product_id);

            // Check if product is approved
            if (!$product->isApproved()) {
                return redirect()->back()
                    ->with('error', 'Cannot change price: Product is not approved yet.')
                    ->withInput();
            }

            // Store old values
            $oldSellingPrice = $product->selling_price;
            $oldDisplayPrice = $product->display_price ?? $product->selling_price;
            $oldDiscount = $product->discount ?? 0;
            $oldFinalPrice = $product->final_price;

            // Calculate new final price
            $newSellingPrice = $request->new_selling_price;
            $newDisplayPrice = $request->new_display_price ?? $newSellingPrice;
            $newDiscount = $request->new_discount ?? $oldDiscount;

            $newFinalPrice = $newDiscount > 0
                ? $newDisplayPrice * (1 - $newDiscount / 100)
                : $newDisplayPrice;

            $priceDifference = $newFinalPrice - $oldFinalPrice;
            $percentageChange = $oldFinalPrice > 0
                ? ($priceDifference / $oldFinalPrice) * 100
                : 0;

            // Determine if approval needed
            $status = $request->boolean('require_approval') ? 'pending' : 'approved';

            // Create price change record
            $priceChange = WholesalerProductPriceChange::create([
                'product_id' => $product->id,
                'wholesaler_id' => $product->wholesaler_id,
                'employee_id' => $employee->id,
                'old_selling_price' => $oldSellingPrice,
                'old_display_price' => $oldDisplayPrice,
                'old_discount' => $oldDiscount,
                'old_final_price' => $oldFinalPrice,
                'new_selling_price' => $newSellingPrice,
                'new_display_price' => $newDisplayPrice,
                'new_discount' => $newDiscount,
                'new_final_price' => $newFinalPrice,
                'price_difference' => $priceDifference,
                'percentage_change' => $percentageChange,
                'change_type' => 'manual',
                'change_reason' => $request->change_reason,
                'notes' => $request->notes,
                'status' => $status,
                'change_data' => [
                    'old_cost_price' => $product->cost_price,
                    'new_cost_price' => $product->cost_price,
                    'old_qty' => $product->qty,
                    'new_qty' => $product->qty,
                    'old_min_stock' => $product->min_stock_level,
                    'new_min_stock' => $product->min_stock_level,
                ]
            ]);

            // If approved immediately, update the product
            if ($status === 'approved') {
                $product->update([
                    'previous_selling_price' => $oldSellingPrice,
                    'selling_price' => $newSellingPrice,
                    'display_price' => $newDisplayPrice,
                    'discount' => $newDiscount,
                    'last_price_updated_by' => $employee->id,
                    'last_price_updated_at' => now(),
                ]);

                $product->increment('price_changes_count');

                $priceChange->approve($employee->id);
            }

            DB::commit();

            // Redirect based on status
            if ($status === 'pending') {
                return redirect()->route('employee.wholesaler.price-change.pending')
                    ->with('success', 'Price change submitted for approval.');
            } else {
                return redirect()->route('employee.wholesaler.products.show', $product->id)
                    ->with('success', 'Price updated successfully!');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmployeeWholesalerPriceChangeController@store error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error updating price: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show bulk price update form (only for approved products)
     */
    public function bulkCreate(Request $request)
    {
        try {
            $wholesalers = Wholesaler::orderBy('business_name')->get(['id', 'business_name as name']);

            $selectedProducts = [];
            if ($request->filled('product_ids')) {
                $productIds = explode(',', $request->product_ids);
                $selectedProducts = WholesalerProduct::with('wholesaler')
                    ->whereIn('id', $productIds)
                    ->where('employee_approval_status', 'approved')
                    ->get();
            }

            return view('employee.wholesaler.price-change.bulk', compact('wholesalers', 'selectedProducts'));

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerPriceChangeController@bulkCreate error: ' . $e->getMessage());
            return redirect()->route('employee.wholesaler.price-change.index')
                ->with('error', 'Error loading bulk update form: ' . $e->getMessage());
        }
    }

    /**
     * Process bulk price update (only for approved products)
     */
    public function bulkStore(Request $request)
    {
        DB::beginTransaction();

        try {
            $employee = Auth::guard('employee')->user();

            $validator = Validator::make($request->all(), [
                'product_ids' => 'required|array',
                'product_ids.*' => 'exists:wholesaler_products,id',
                'update_type' => 'required|in:fixed,percentage,formula',
                'price_field' => 'required|in:selling_price,display_price,final_price',
                'value' => 'required|numeric',
                'operation' => 'required|in:set,increase,decrease',
                'apply_discount' => 'nullable|boolean',
                'discount_value' => 'nullable|numeric|min:0|max:100',
                'change_reason' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:1000',
                'require_approval' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $productIds = $request->product_ids;

            // Verify all products are approved
            $unapprovedCount = WholesalerProduct::whereIn('id', $productIds)
                ->where('employee_approval_status', '!=', 'approved')
                ->count();

            if ($unapprovedCount > 0) {
                return redirect()->back()
                    ->with('error', "Cannot update: {$unapprovedCount} product(s) are not approved yet.")
                    ->withInput();
            }

            $updateType = $request->update_type;
            $priceField = $request->price_field;
            $value = $request->value;
            $operation = $request->operation;
            $applyDiscount = $request->boolean('apply_discount');
            $discountValue = $request->discount_value;
            $requireApproval = $request->boolean('require_approval');

            $updatedCount = 0;
            $priceChanges = [];

            foreach ($productIds as $productId) {
                $product = WholesalerProduct::find($productId);

                if (!$product) continue;

                // Store old values
                $oldSellingPrice = $product->selling_price;
                $oldDisplayPrice = $product->display_price ?? $product->selling_price;
                $oldDiscount = $product->discount ?? 0;
                $oldFinalPrice = $product->final_price;

                // Calculate new price based on update type
                $basePrice = $priceField === 'selling_price' ? $oldSellingPrice :
                    ($priceField === 'display_price' ? $oldDisplayPrice : $oldFinalPrice);

                $newBasePrice = $this->calculateNewPrice($basePrice, $updateType, $value, $operation);

                // Set new prices
                if ($priceField === 'selling_price') {
                    $newSellingPrice = $newBasePrice;
                    $newDisplayPrice = $oldDisplayPrice;
                } elseif ($priceField === 'display_price') {
                    $newSellingPrice = $oldSellingPrice;
                    $newDisplayPrice = $newBasePrice;
                } else {
                    // final_price - need to back-calculate
                    $newDisplayPrice = $applyDiscount && $discountValue > 0
                        ? $newBasePrice / (1 - $discountValue / 100)
                        : $newBasePrice;
                    $newSellingPrice = $oldSellingPrice;
                }

                // Apply discount if requested
                $newDiscount = $applyDiscount ? ($discountValue ?? $oldDiscount) : $oldDiscount;

                // Calculate new final price
                $newFinalPrice = $newDiscount > 0
                    ? $newDisplayPrice * (1 - $newDiscount / 100)
                    : $newDisplayPrice;

                $priceDifference = $newFinalPrice - $oldFinalPrice;
                $percentageChange = $oldFinalPrice > 0
                    ? ($priceDifference / $oldFinalPrice) * 100
                    : 0;

                // Create price change record
                $priceChange = WholesalerProductPriceChange::create([
                    'product_id' => $product->id,
                    'wholesaler_id' => $product->wholesaler_id,
                    'employee_id' => $employee->id,
                    'old_selling_price' => $oldSellingPrice,
                    'old_display_price' => $oldDisplayPrice,
                    'old_discount' => $oldDiscount,
                    'old_final_price' => $oldFinalPrice,
                    'new_selling_price' => $newSellingPrice ?? $oldSellingPrice,
                    'new_display_price' => $newDisplayPrice ?? $oldDisplayPrice,
                    'new_discount' => $newDiscount,
                    'new_final_price' => $newFinalPrice,
                    'price_difference' => $priceDifference,
                    'percentage_change' => $percentageChange,
                    'change_type' => 'bulk',
                    'change_reason' => $request->change_reason,
                    'notes' => $request->notes,
                    'status' => $requireApproval ? 'pending' : 'approved',
                    'change_data' => [
                        'update_type' => $updateType,
                        'price_field' => $priceField,
                        'value' => $value,
                        'operation' => $operation,
                        'apply_discount' => $applyDiscount,
                        'discount_value' => $discountValue,
                    ]
                ]);

                // If approved immediately, update product
                if (!$requireApproval) {
                    $updateData = [];

                    if (isset($newSellingPrice)) {
                        $updateData['selling_price'] = $newSellingPrice;
                    }

                    if (isset($newDisplayPrice)) {
                        $updateData['display_price'] = $newDisplayPrice;
                    }

                    $updateData['discount'] = $newDiscount;
                    $updateData['last_price_updated_by'] = $employee->id;
                    $updateData['last_price_updated_at'] = now();

                    $product->update($updateData);
                    $product->increment('price_changes_count');

                    $priceChange->approve($employee->id);
                }

                $priceChanges[] = $priceChange;
                $updatedCount++;
            }

            DB::commit();

            $message = $requireApproval
                ? "{$updatedCount} price changes submitted for approval."
                : "Successfully updated prices for {$updatedCount} products.";

            return redirect()->route('employee.wholesaler.price-change.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmployeeWholesalerPriceChangeController@bulkStore error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error in bulk update: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Calculate new price based on update type
     */
    private function calculateNewPrice($currentPrice, $updateType, $value, $operation)
    {
        switch ($updateType) {
            case 'fixed':
                if ($operation === 'set') {
                    return $value;
                } elseif ($operation === 'increase') {
                    return $currentPrice + $value;
                } elseif ($operation === 'decrease') {
                    return max(0, $currentPrice - $value);
                }
                break;

            case 'percentage':
                if ($operation === 'increase') {
                    return $currentPrice * (1 + $value / 100);
                } elseif ($operation === 'decrease') {
                    return $currentPrice * (1 - $value / 100);
                }
                break;

            case 'formula':
                // Simple formula evaluation (be careful with this)
                try {
                    $formula = str_replace(
                        ['{price}', '{value}'],
                        [$currentPrice, $value],
                        $value
                    );
                    // Only allow basic math operations
                    if (preg_match('/^[0-9\+\-\*\/\(\)\.]+$/', $formula)) {
                        return eval("return {$formula};");
                    }
                } catch (\Exception $e) {
                    return $currentPrice;
                }
                break;
        }

        return $currentPrice;
    }

    /**
     * Show pending price changes for approval
     */
    public function pending(Request $request)
    {
        try {
            $query = WholesalerProductPriceChange::with(['product', 'wholesaler', 'employee'])
                ->where('status', 'pending');

            if ($request->filled('wholesaler_id') && $request->wholesaler_id != 'all') {
                $query->where('wholesaler_id', $request->wholesaler_id);
            }

            if ($request->filled('employee_id') && $request->employee_id != 'all') {
                $query->where('employee_id', $request->employee_id);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $pendingChanges = $query->orderBy('created_at', 'asc')->paginate(20);

            $wholesalers = Wholesaler::orderBy('business_name')->get(['id', 'business_name as name']);
            $employees = Employee::orderBy('name')->get(['id', 'name']);

            // Stats for pending page
            $stats = [
                'total_pending' => WholesalerProductPriceChange::where('status', 'pending')->count(),
                'today_pending' => WholesalerProductPriceChange::where('status', 'pending')
                    ->whereDate('created_at', today())
                    ->count(),
                'average_change' => WholesalerProductPriceChange::where('status', 'pending')
                        ->avg('percentage_change') ?? 0,
                'largest_increase' => WholesalerProductPriceChange::where('status', 'pending')
                        ->where('price_difference', '>', 0)
                        ->max('price_difference') ?? 0,
                'largest_decrease' => WholesalerProductPriceChange::where('status', 'pending')
                        ->where('price_difference', '<', 0)
                        ->min('price_difference') ?? 0,
            ];

            return view('employee.wholesaler.price-change.pending', compact(
                'pendingChanges',
                'wholesalers',
                'employees',
                'stats'
            ));

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerPriceChangeController@pending error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading pending changes: ' . $e->getMessage());
        }
    }

    /**
     * Approve a price change
     */
    public function approve($id)
    {
        DB::beginTransaction();

        try {
            $employee = Auth::guard('employee')->user();

            $priceChange = WholesalerProductPriceChange::findOrFail($id);

            if ($priceChange->status !== 'pending') {
                return redirect()->back()->with('error', 'This price change is not pending.');
            }

            $product = $priceChange->product;

            // Update product
            $updateData = [
                'selling_price' => $priceChange->new_selling_price,
                'display_price' => $priceChange->new_display_price,
                'discount' => $priceChange->new_discount,
                'last_price_updated_by' => $employee->id,
                'last_price_updated_at' => now(),
            ];

            $product->update($updateData);
            $product->increment('price_changes_count');

            // Approve the change
            $priceChange->approve($employee->id);

            DB::commit();

            // Log the approval
            Log::info("Price change #{$priceChange->id} approved by employee #{$employee->id}");

            return redirect()->route('employee.wholesaler.price-change.pending')
                ->with('success', 'Price change approved successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmployeeWholesalerPriceChangeController@approve error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error approving price change: ' . $e->getMessage());
        }
    }

    /**
     * Reject a price change
     */
    public function reject(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $employee = Auth::guard('employee')->user();

            $validator = Validator::make($request->all(), [
                'rejection_reason' => 'required|string|max:500',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $priceChange = WholesalerProductPriceChange::findOrFail($id);

            if ($priceChange->status !== 'pending') {
                return redirect()->back()->with('error', 'This price change is not pending.');
            }

            $priceChange->status = 'rejected';
            $priceChange->notes = $priceChange->notes
                ? $priceChange->notes . "\n\nRejection reason: " . $request->rejection_reason
                : 'Rejection reason: ' . $request->rejection_reason;
            $priceChange->save();

            DB::commit();

            Log::info("Price change #{$priceChange->id} rejected by employee #{$employee->id}. Reason: {$request->rejection_reason}");

            return redirect()->route('employee.wholesaler.price-change.pending')
                ->with('success', 'Price change rejected.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmployeeWholesalerPriceChangeController@reject error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error rejecting price change: ' . $e->getMessage());
        }
    }

    /**
     * Show price change history
     */
    public function history(Request $request)
    {
        try {
            $query = WholesalerProductPriceChange::with(['product', 'wholesaler', 'employee', 'approvedBy']);

            // Apply filters
            if ($request->filled('wholesaler_id') && $request->wholesaler_id != 'all') {
                $query->where('wholesaler_id', $request->wholesaler_id);
            }

            if ($request->filled('employee_id') && $request->employee_id != 'all') {
                $query->where('employee_id', $request->employee_id);
            }

            if ($request->filled('product_id') && $request->product_id != 'all') {
                $query->where('product_id', $request->product_id);
            }

            if ($request->filled('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->filled('change_type') && $request->change_type != 'all') {
                $query->where('change_type', $request->change_type);
            }

            $priceChanges = $query->orderBy('created_at', 'desc')->paginate(50);

            $wholesalers = Wholesaler::orderBy('business_name')->get(['id', 'business_name as name']);
            $employees = Employee::orderBy('name')->get(['id', 'name']);
            $products = WholesalerProduct::orderBy('name')->limit(100)->get(['id', 'name', 'code']);

            // Stats for history page
            $stats = [
                'total_changes' => $priceChanges->total(),
                'total_increase' => $priceChanges->sum('price_difference'),
                'avg_change' => $priceChanges->avg('percentage_change') ?? 0,
                'period' => $request->date_from && $request->date_to
                    ? $request->date_from . ' to ' . $request->date_to
                    : 'All time'
            ];

            return view('employee.wholesaler.price-change.history', compact(
                'priceChanges',
                'wholesalers',
                'employees',
                'products',
                'stats'
            ));

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerPriceChangeController@history error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading price change history: ' . $e->getMessage());
        }
    }

    /**
     * Export price changes to CSV
     */
    public function export(Request $request)
    {
        try {
            $query = WholesalerProductPriceChange::with(['product', 'wholesaler', 'employee']);

            // Apply filters (same as history)
            if ($request->filled('wholesaler_id') && $request->wholesaler_id != 'all') {
                $query->where('wholesaler_id', $request->wholesaler_id);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->filled('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            $priceChanges = $query->orderBy('created_at', 'desc')->get();

            $filename = 'price-changes-' . date('Y-m-d') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}",
            ];

            $callback = function() use ($priceChanges) {
                $file = fopen('php://output', 'w');

                // Headers
                fputcsv($file, [
                    'Date',
                    'Product Code',
                    'Product Name',
                    'Wholesaler',
                    'Employee',
                    'Old Price',
                    'New Price',
                    'Difference',
                    'Percentage',
                    'Change Type',
                    'Status',
                    'Reason'
                ]);

                // Data
                foreach ($priceChanges as $change) {
                    fputcsv($file, [
                        $change->created_at->format('Y-m-d H:i'),
                        $change->product->code ?? 'N/A',
                        $change->product->name ?? 'N/A',
                        $change->wholesaler->business_name ?? 'N/A',
                        $change->employee->name ?? 'N/A',
                        'Rs. ' . number_format($change->old_final_price, 2),
                        'Rs. ' . number_format($change->new_final_price, 2),
                        $change->formatted_difference,
                        $change->formatted_percentage,
                        ucfirst($change->change_type),
                        ucfirst($change->status),
                        $change->change_reason ?? ''
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerPriceChangeController@export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting price changes: ' . $e->getMessage());
        }
    }

    /**
     * Get product price change statistics (AJAX)
     */
    public function stats(Request $request)
    {
        try {
            $employee = Auth::guard('employee')->user();

            $stats = [
                'total_changes' => WholesalerProductPriceChange::count(),
                'total_products_affected' => WholesalerProductPriceChange::distinct('product_id')->count('product_id'),
                'total_wholesalers_affected' => WholesalerProductPriceChange::distinct('wholesaler_id')->count('wholesaler_id'),
                'average_change_per_product' => WholesalerProductPriceChange::avg('percentage_change') ?? 0,
                'total_increase_amount' => WholesalerProductPriceChange::where('price_difference', '>', 0)->sum('price_difference'),
                'total_decrease_amount' => WholesalerProductPriceChange::where('price_difference', '<', 0)->sum('price_difference'),
                'my_changes' => WholesalerProductPriceChange::where('employee_id', $employee->id)->count(),
                'my_total_impact' => WholesalerProductPriceChange::where('employee_id', $employee->id)->sum('price_difference'),
            ];

            // Monthly trends
            $monthlyTrends = WholesalerProductPriceChange::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN price_difference > 0 THEN 1 ELSE 0 END) as increases'),
                DB::raw('SUM(CASE WHEN price_difference < 0 THEN 1 ELSE 0 END) as decreases'),
                DB::raw('SUM(price_difference) as net_impact')
            )
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->get();

            // Top changed products
            $topProducts = WholesalerProductPriceChange::select(
                'product_id',
                DB::raw('COUNT(*) as change_count'),
                DB::raw('AVG(percentage_change) as avg_change')
            )
                ->with('product:id,name,code')
                ->groupBy('product_id')
                ->orderBy('change_count', 'desc')
                ->limit(10)
                ->get()
                ->map(function($item) {
                    return [
                        'product' => $item->product->name . ' (' . $item->product->code . ')',
                        'changes' => $item->change_count,
                        'avg_change' => round($item->avg_change, 2) . '%'
                    ];
                });

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'monthly_trends' => $monthlyTrends,
                'top_products' => $topProducts
            ]);

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerPriceChangeController@stats error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error loading statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product details for quick price change
     */
    public function getProductDetails($id)
    {
        try {
            $product = WholesalerProduct::with('wholesaler')
                ->where('employee_approval_status', 'approved')
                ->find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found or not approved'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'code' => $product->code,
                    'name' => $product->name,
                    'selling_price' => $product->selling_price,
                    'display_price' => $product->display_price,
                    'final_price' => $product->final_price,
                    'discount' => $product->discount,
                    'wholesaler' => $product->wholesaler->business_name,
                    'wholesaler_id' => $product->wholesaler_id,
                    'qty' => $product->qty,
                    'price_changes_count' => $product->price_changes_count,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerPriceChangeController@getProductDetails error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error loading product details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk approve price changes
     */
    public function bulkApprove(Request $request)
    {
        DB::beginTransaction();

        try {
            $employee = Auth::guard('employee')->user();

            $validator = Validator::make($request->all(), [
                'price_change_ids' => 'required|array',
                'price_change_ids.*' => 'exists:wholesaler_product_price_changes,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $priceChangeIds = $request->price_change_ids;
            $approvedCount = 0;

            foreach ($priceChangeIds as $id) {
                $priceChange = WholesalerProductPriceChange::find($id);

                if ($priceChange && $priceChange->status === 'pending') {
                    $product = $priceChange->product;

                    // Update product
                    $product->update([
                        'selling_price' => $priceChange->new_selling_price,
                        'display_price' => $priceChange->new_display_price,
                        'discount' => $priceChange->new_discount,
                        'last_price_updated_by' => $employee->id,
                        'last_price_updated_at' => now(),
                    ]);

                    $product->increment('price_changes_count');

                    // Approve the change
                    $priceChange->approve($employee->id);

                    $approvedCount++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully approved {$approvedCount} price changes",
                'count' => $approvedCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmployeeWholesalerPriceChangeController@bulkApprove error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error approving price changes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk reject price changes
     */
    public function bulkReject(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'price_change_ids' => 'required|array',
                'price_change_ids.*' => 'exists:wholesaler_product_price_changes,id',
                'rejection_reason' => 'required|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $priceChangeIds = $request->price_change_ids;
            $rejectedCount = 0;

            foreach ($priceChangeIds as $id) {
                $priceChange = WholesalerProductPriceChange::find($id);

                if ($priceChange && $priceChange->status === 'pending') {
                    $priceChange->status = 'rejected';
                    $priceChange->notes = $priceChange->notes
                        ? $priceChange->notes . "\n\nRejection reason: " . $request->rejection_reason
                        : 'Rejection reason: ' . $request->rejection_reason;
                    $priceChange->save();

                    $rejectedCount++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully rejected {$rejectedCount} price changes",
                'count' => $rejectedCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmployeeWholesalerPriceChangeController@bulkReject error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error rejecting price changes: ' . $e->getMessage()
            ], 500);
        }
    }
}
