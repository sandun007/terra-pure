<?php
// app/Http/Controllers/Employee/EmployeeSellerProductController.php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Saler;  // Changed from Seller to Saler
use App\Models\SalerProduct;  // We'll create this model
use App\Models\EmployeePriceChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeSellerProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
    }

    /**
     * Display all seller products
     */
    public function index(Request $request)
    {
        try {
            $query = SalerProduct::with(['saler', 'category', 'brand'])
                ->orderBy('created_at', 'desc');

            // Filter by seller
            if ($request->filled('seller_id')) {
                $query->where('saler_id', $request->seller_id);
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter by approval status
            if ($request->filled('approval')) {
                if ($request->approval === 'pending') {
                    $query->whereNull('approved_by')->where('status', 'active');
                } elseif ($request->approval === 'approved') {
                    $query->whereNotNull('approved_by');
                } elseif ($request->approval === 'rejected') {
                    $query->where('status', 'rejected');
                }
            }

            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhereHas('saler', function($sq) use ($search) {
                            $sq->where('name', 'like', "%{$search}%")
                                ->orWhere('code', 'like', "%{$search}%");
                        });
                });
            }

            $products = $query->paginate(20)->withQueryString();
            $sellers = Saler::orderBy('name')->get(['id', 'name', 'code']);

            return view('employee.seller.products.index', compact('products', 'sellers'));

        } catch (\Exception $e) {
            Log::error('Error loading seller products: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load products: ' . $e->getMessage());
        }
    }

    /**
     * Show product details
     */
    public function show($id)
    {
        try {
            $product = SalerProduct::with(['saler', 'category', 'brand', 'shop'])
                ->findOrFail($id);

            $priceHistory = EmployeePriceChange::where('product_id', $id)
                ->where('product_type', 'seller')
                ->with('employee')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return view('employee.seller.products.show', compact('product', 'priceHistory'));

        } catch (\Exception $e) {
            Log::error('Error showing product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Product not found');
        }
    }

    /**
     * Show price update form
     */
    public function editPrice($id)
    {
        try {
            $product = SalerProduct::with('saler')->findOrFail($id);

            return view('employee.seller.products.price-edit', compact('product'));

        } catch (\Exception $e) {
            Log::error('Error loading price edit form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Product not found');
        }
    }

    /**
     * Update product price (requires approval)
     */
    public function updatePrice(Request $request, $id)
    {
        $request->validate([
            'display_price' => 'required|numeric|min:0',
            'stock_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'packaging_cost' => 'nullable|numeric|min:0',
            'change_reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $product = SalerProduct::findOrFail($id);
            $employee = Auth::guard('employee')->user();

            // Store old prices
            $oldDisplayPrice = $product->display_price;
            $oldStockPrice = $product->stock_price;
            $oldDiscount = $product->discount ?? 0;
            $oldPackagingCost = $product->packaging_cost ?? 0;

            // Calculate new final price
            $newDisplayPrice = $request->display_price;
            $newDiscount = $request->discount ?? 0;
            $newFinalPrice = $newDisplayPrice * (1 - $newDiscount / 100);

            // Create price change request (pending approval)
            EmployeePriceChange::create([
                'product_id' => $product->id,
                'product_type' => 'seller',
                'employee_id' => $employee->id,
                'old_display_price' => $oldDisplayPrice,
                'new_display_price' => $newDisplayPrice,
                'old_stock_price' => $oldStockPrice,
                'new_stock_price' => $request->stock_price,
                'old_discount' => $oldDiscount,
                'new_discount' => $newDiscount,
                'old_packaging_cost' => $oldPackagingCost,
                'new_packaging_cost' => $request->packaging_cost ?? 0,
                'old_final_price' => $product->final_price,
                'new_final_price' => $newFinalPrice,
                'price_difference' => $newFinalPrice - $product->final_price,
                'percentage_change' => $product->final_price > 0
                    ? (($newFinalPrice - $product->final_price) / $product->final_price) * 100
                    : 0,
                'change_reason' => $request->change_reason,
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('employee.seller.products.show', $id)
                ->with('success', 'Price update request submitted for approval. Waiting for manager approval.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating price: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update price: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Approve product (new products from sellers)
     */
    public function approveProduct($id)
    {
        DB::beginTransaction();

        try {
            $product = SalerProduct::findOrFail($id);
            $employee = Auth::guard('employee')->user();

            $product->update([
                'approved_by' => $employee->id,
                'approved_at' => now(),
                'status' => 'active',
                'approval_notes' => 'Approved by employee',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product approved successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error approving product: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve product'
            ], 500);
        }
    }

    /**
     * Reject product
     */
    public function rejectProduct(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $product = SalerProduct::findOrFail($id);
            $employee = Auth::guard('employee')->user();

            $product->update([
                'status' => 'rejected',
                'rejected_by' => $employee->id,
                'rejected_at' => now(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product rejected'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting product: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject product'
            ], 500);
        }
    }

    /**
     * Approve price change
     */
    public function approvePriceChange($priceChangeId)
    {
        DB::beginTransaction();

        try {
            $priceChange = EmployeePriceChange::findOrFail($priceChangeId);
            $employee = Auth::guard('employee')->user();

            // Update the actual product
            $product = SalerProduct::findOrFail($priceChange->product_id);

            $product->update([
                'display_price' => $priceChange->new_display_price,
                'stock_price' => $priceChange->new_stock_price,
                'discount' => $priceChange->new_discount,
                'packaging_cost' => $priceChange->new_packaging_cost ?? 0,
                'previous_display_price' => $priceChange->old_display_price,
                'last_price_updated_by' => $employee->id,
                'last_price_updated_at' => now(),
                'price_changes_count' => ($product->price_changes_count ?? 0) + 1,
            ]);

            // Update price change record
            $priceChange->update([
                'status' => 'approved',
                'approved_by' => $employee->id,
                'approved_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Price change approved and applied'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error approving price change: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve price change'
            ], 500);
        }
    }

    /**
     * Reject price change
     */
    public function rejectPriceChange(Request $request, $priceChangeId)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        try {
            $priceChange = EmployeePriceChange::findOrFail($priceChangeId);

            $priceChange->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'rejected_by' => Auth::guard('employee')->id(),
                'rejected_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Price change rejected'
            ]);

        } catch (\Exception $e) {
            Log::error('Error rejecting price change: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject price change'
            ], 500);
        }
    }

    /**
     * Bulk price update
     */
    public function bulkPriceUpdate(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|string',
            'update_type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric',
            'operation' => 'required|in:increase,decrease',
            'apply_to' => 'required|in:display_price,stock_price,both',
            'change_reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $employee = Auth::guard('employee')->user();
            $productIds = explode(',', $request->product_ids);
            $products = SalerProduct::whereIn('id', $productIds)->get();

            foreach ($products as $product) {
                $oldDisplayPrice = $product->display_price;
                $oldStockPrice = $product->stock_price;

                $newDisplayPrice = $oldDisplayPrice;
                $newStockPrice = $oldStockPrice;

                // Calculate new prices based on operation
                if ($request->apply_to == 'display_price' || $request->apply_to == 'both') {
                    if ($request->update_type == 'percentage') {
                        $change = $oldDisplayPrice * ($request->value / 100);
                        $newDisplayPrice = $request->operation == 'increase'
                            ? $oldDisplayPrice + $change
                            : $oldDisplayPrice - $change;
                    } else {
                        $newDisplayPrice = $request->operation == 'increase'
                            ? $oldDisplayPrice + $request->value
                            : $oldDisplayPrice - $request->value;
                    }
                    $newDisplayPrice = max(0, round($newDisplayPrice, 2));
                }

                if ($request->apply_to == 'stock_price' || $request->apply_to == 'both') {
                    if ($request->update_type == 'percentage') {
                        $change = $oldStockPrice * ($request->value / 100);
                        $newStockPrice = $request->operation == 'increase'
                            ? $oldStockPrice + $change
                            : $oldStockPrice - $change;
                    } else {
                        $newStockPrice = $request->operation == 'increase'
                            ? $oldStockPrice + $request->value
                            : $oldStockPrice - $request->value;
                    }
                    $newStockPrice = max(0, round($newStockPrice, 2));
                }

                // Create price change request (pending)
                EmployeePriceChange::create([
                    'product_id' => $product->id,
                    'product_type' => 'seller',
                    'employee_id' => $employee->id,
                    'old_display_price' => $oldDisplayPrice,
                    'new_display_price' => $newDisplayPrice,
                    'old_stock_price' => $oldStockPrice,
                    'new_stock_price' => $newStockPrice,
                    'old_discount' => $product->discount ?? 0,
                    'new_discount' => $product->discount ?? 0,
                    'old_final_price' => $product->final_price,
                    'new_final_price' => $newDisplayPrice * (1 - ($product->discount ?? 0) / 100),
                    'price_difference' => $newDisplayPrice - $oldDisplayPrice,
                    'percentage_change' => $oldDisplayPrice > 0
                        ? (($newDisplayPrice - $oldDisplayPrice) / $oldDisplayPrice) * 100
                        : 0,
                    'change_reason' => $request->change_reason,
                    'status' => 'pending',
                ]);
            }

            DB::commit();

            return redirect()->route('employee.seller.products.index')
                ->with('success', count($products) . ' products queued for price update (pending approval)');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk price update: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to process bulk update: ' . $e->getMessage());
        }
    }

    /**
     * Export products list
     */
    public function export(Request $request)
    {
        try {
            $query = SalerProduct::with('saler');

            if ($request->filled('seller_id')) {
                $query->where('saler_id', $request->seller_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $products = $query->get();

            $filename = 'seller_products_' . date('Y-m-d') . '.csv';
            $handle = fopen('php://output', 'w');

            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            // Add headers
            fputcsv($handle, [
                'ID', 'Seller Name', 'Seller Code', 'Product Code',
                'Product Name', 'Display Price', 'Stock Price',
                'Discount (%)', 'Final Price', 'Stock', 'Status',
                'Approved', 'Created At'
            ]);

            foreach ($products as $product) {
                fputcsv($handle, [
                    $product->id,
                    $product->saler->name ?? 'N/A',
                    $product->saler_code,
                    $product->code,
                    $product->name,
                    $product->display_price,
                    $product->stock_price,
                    $product->discount ?? 0,
                    $product->final_price,
                    $product->qty,
                    $product->status,
                    $product->approved_by ? 'Yes' : 'No',
                    $product->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
            exit();

        } catch (\Exception $e) {
            Log::error('Error exporting products: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export products');
        }
    }

    /**
     * Price change history
     */
    public function priceHistory(Request $request)
    {
        try {
            $query = EmployeePriceChange::where('product_type', 'seller')
                ->with(['product', 'employee', 'approver']);

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('product_id')) {
                $query->where('product_id', $request->product_id);
            }

            $history = $query->orderBy('created_at', 'desc')->paginate(20);

            return view('employee.seller.products.price-history', compact('history'));

        } catch (\Exception $e) {
            Log::error('Error loading price history: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load price history');
        }
    }
}
