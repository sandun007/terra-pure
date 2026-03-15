<?php
// app/Http/Controllers/Employee/EmployeeWholesalerApprovalController.php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\WholesalerProduct;
use App\Models\Wholesaler;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmployeeWholesalerApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
    }

    /**
     * Display products pending approval
     */
    public function pendingApproval(Request $request)
    {
        try {
            $employee = Auth::guard('employee')->user();

            $query = WholesalerProduct::with([
                'wholesaler',
                'category',
                'brand'
            ])->where('employee_approval_status', 'pending');

            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhereHas('wholesaler', function($q2) use ($search) {
                            $q2->where('business_name', 'like', "%{$search}%");
                        });
                });
            }

            // Wholesaler filter
            if ($request->filled('wholesaler_id') && $request->wholesaler_id != 'all') {
                $query->where('wholesaler_id', $request->wholesaler_id);
            }

            $products = $query->orderBy('created_at', 'asc')->paginate(20);

            // Get filter data
            $wholesalers = Wholesaler::orderBy('business_name')->get(['id', 'business_name as name']);
            $categories = Category::orderBy('name')->get(['id', 'name']);

            // Stats
            $stats = [
                'total_pending' => WholesalerProduct::where('employee_approval_status', 'pending')->count(),
                'total_approved' => WholesalerProduct::where('employee_approval_status', 'approved')->count(),
                'total_rejected' => WholesalerProduct::where('employee_approval_status', 'rejected')->count(),
            ];

            return view('employee.wholesaler.approvals.pending', compact(
                'products',
                'wholesalers',
                'categories',
                'stats',
                'employee'
            ));

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerApprovalController@pendingApproval error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading pending approvals: ' . $e->getMessage());
        }
    }

    /**
     * Approve a product (AJAX endpoint) - FIXED: removed final_price from update
     */
    public function approve(Request $request, $id)
    {
        try {
            $employee = Auth::guard('employee')->user();

            $validator = Validator::make($request->all(), [
                'notes' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $product = WholesalerProduct::findOrFail($id);

            if ($product->employee_approval_status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This product is not pending approval.'
                ], 400);
            }

            DB::beginTransaction();

            // FIXED: Update only the approval-related fields, NOT final_price
            $product->employee_approval_status = 'approved';
            $product->display_in_seller_shop = true;
            $product->approved_by_employee = $employee->id;
            $product->approved_at = now();
            $product->rejection_reason = null;

            // DO NOT update final_price - it's a generated column
            // $product->final_price = ...; // REMOVED

            $product->save();

            DB::commit();

            Log::info("Employee {$employee->id} approved product {$product->id}");

            return response()->json([
                'success' => true,
                'message' => "Product '{$product->name}' approved successfully!",
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'status' => $product->employee_approval_status
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmployeeWholesalerApprovalController@approve error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error approving product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a product (AJAX endpoint) - FIXED: removed final_price from update
     */
    public function reject(Request $request, $id)
    {
        try {
            $employee = Auth::guard('employee')->user();

            $validator = Validator::make($request->all(), [
                'rejection_reason' => 'required|string|min:10|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $product = WholesalerProduct::findOrFail($id);

            if ($product->employee_approval_status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This product is not pending approval.'
                ], 400);
            }

            DB::beginTransaction();

            // FIXED: Update only the approval-related fields, NOT final_price
            $product->employee_approval_status = 'rejected';
            $product->display_in_seller_shop = false;
            $product->approved_by_employee = $employee->id;
            $product->approved_at = now();
            $product->rejection_reason = $request->rejection_reason;

            // DO NOT update final_price - it's a generated column
            // $product->final_price = ...; // REMOVED

            $product->save();

            DB::commit();

            Log::info("Employee {$employee->id} rejected product {$product->id}");

            return response()->json([
                'success' => true,
                'message' => "Product '{$product->name}' rejected.",
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'status' => $product->employee_approval_status
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmployeeWholesalerApprovalController@reject error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error rejecting product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk approve products - FIXED: removed final_price from update
     */
    public function bulkApprove(Request $request)
    {
        try {
            $employee = Auth::guard('employee')->user();

            $validator = Validator::make($request->all(), [
                'product_ids' => 'required|array',
                'product_ids.*' => 'exists:wholesaler_products,id',
                'notes' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $productIds = $request->product_ids;
            $count = 0;
            $failed = [];

            DB::beginTransaction();

            foreach ($productIds as $id) {
                $product = WholesalerProduct::find($id);

                if ($product && $product->employee_approval_status === 'pending') {
                    // FIXED: Update only approval fields
                    $product->employee_approval_status = 'approved';
                    $product->display_in_seller_shop = true;
                    $product->approved_by_employee = $employee->id;
                    $product->approved_at = now();
                    $product->rejection_reason = null;
                    $product->save();
                    $count++;
                } else {
                    $failed[] = $id;
                }
            }

            DB::commit();

            $message = "Successfully approved {$count} products.";
            if (count($failed) > 0) {
                $message .= " Failed to approve " . count($failed) . " products (not pending).";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'count' => $count,
                'failed' => $failed
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmployeeWholesalerApprovalController@bulkApprove error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error approving products: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk reject products - FIXED: removed final_price from update
     */
    public function bulkReject(Request $request)
    {
        try {
            $employee = Auth::guard('employee')->user();

            $validator = Validator::make($request->all(), [
                'product_ids' => 'required|array',
                'product_ids.*' => 'exists:wholesaler_products,id',
                'rejection_reason' => 'required|string|min:10|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $productIds = $request->product_ids;
            $count = 0;
            $failed = [];

            DB::beginTransaction();

            foreach ($productIds as $id) {
                $product = WholesalerProduct::find($id);

                if ($product && $product->employee_approval_status === 'pending') {
                    // FIXED: Update only approval fields
                    $product->employee_approval_status = 'rejected';
                    $product->display_in_seller_shop = false;
                    $product->approved_by_employee = $employee->id;
                    $product->approved_at = now();
                    $product->rejection_reason = $request->rejection_reason;
                    $product->save();
                    $count++;
                } else {
                    $failed[] = $id;
                }
            }

            DB::commit();

            $message = "Successfully rejected {$count} products.";
            if (count($failed) > 0) {
                $message .= " Failed to reject " . count($failed) . " products (not pending).";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'count' => $count,
                'failed' => $failed
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmployeeWholesalerApprovalController@bulkReject error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error rejecting products: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product details for quick view
     */
    public function quickView($id)
    {
        try {
            $product = WholesalerProduct::with([
                'wholesaler',
                'category',
                'brand'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'code' => $product->code,
                    'name' => $product->name,
                    'description' => $product->description,
                    'selling_price' => $product->selling_price,
                    'display_price' => $product->display_price,
                    'final_price' => $product->final_price,
                    'discount' => $product->discount,
                    'qty' => $product->qty,
                    'wholesaler' => $product->wholesaler->business_name,
                    'category' => $product->category->name ?? 'N/A',
                    'brand' => $product->brand->name ?? 'N/A',
                    'image' => $product->image ? asset('storage/' . $product->image) : null,
                    'created_at' => $product->created_at->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerApprovalController@quickView error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error loading product details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export pending approvals
     */
    public function export(Request $request)
    {
        try {
            $query = WholesalerProduct::with(['wholesaler', 'category', 'brand'])
                ->where('employee_approval_status', 'pending');

            // Apply filters
            if ($request->filled('wholesaler_id') && $request->wholesaler_id != 'all') {
                $query->where('wholesaler_id', $request->wholesaler_id);
            }

            $products = $query->get();

            $filename = 'pending-approvals-' . date('Y-m-d') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}",
            ];

            $callback = function() use ($products) {
                $file = fopen('php://output', 'w');

                // Headers
                fputcsv($file, [
                    'ID',
                    'Product Code',
                    'Product Name',
                    'Wholesaler',
                    'Category',
                    'Brand',
                    'Selling Price',
                    'Display Price',
                    'Final Price',
                    'Stock Qty',
                    'Created Date'
                ]);

                // Data
                foreach ($products as $product) {
                    fputcsv($file, [
                        $product->id,
                        $product->code,
                        $product->name,
                        $product->wholesaler->business_name ?? 'N/A',
                        $product->category->name ?? 'N/A',
                        $product->brand->name ?? 'N/A',
                        $product->selling_price,
                        $product->display_price,
                        $product->final_price,
                        $product->qty,
                        $product->created_at->format('Y-m-d H:i')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerApprovalController@export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting: ' . $e->getMessage());
        }
    }

    /**
     * Get approval statistics
     */
    public function stats()
    {
        try {
            $employee = Auth::guard('employee')->user();

            $stats = [
                'total_pending' => WholesalerProduct::where('employee_approval_status', 'pending')->count(),
                'total_approved' => WholesalerProduct::where('employee_approval_status', 'approved')->count(),
                'total_rejected' => WholesalerProduct::where('employee_approval_status', 'rejected')->count(),

                'approved_today' => WholesalerProduct::where('employee_approval_status', 'approved')
                    ->whereDate('approved_at', today())
                    ->count(),

                'rejected_today' => WholesalerProduct::where('employee_approval_status', 'rejected')
                    ->whereDate('approved_at', today())
                    ->count(),

                'my_approvals' => WholesalerProduct::where('approved_by_employee', $employee->id)->count(),

                'by_wholesaler' => WholesalerProduct::where('employee_approval_status', 'pending')
                    ->select('wholesaler_id', DB::raw('count(*) as total'))
                    ->with('wholesaler:id,business_name')
                    ->groupBy('wholesaler_id')
                    ->get()
                    ->map(function($item) {
                        return [
                            'wholesaler' => $item->wholesaler->business_name ?? 'Unknown',
                            'pending' => $item->total
                        ];
                    })
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('EmployeeWholesalerApprovalController@stats error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error loading statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}
