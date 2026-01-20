<?php

namespace App\Http\Controllers;

use App\Models\Wholesaler;
use App\Models\WholesalerProduct;
use App\Models\Order;
use App\Models\WholesalerOrder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\EmployeePriceChange;
use App\Models\WholesalerProductPriceChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    protected $storageService;

    public function __construct()
    {
        $this->middleware('auth:employee');
    }

    /**
     * Get the correct image column name from database
     */
    private function getImageColumnName()
    {
        $columns = Schema::getColumnListing('employees');

        // Check for possible column names in order of preference
        $possibleColumns = ['profile_image', 'image', 'img', 'photo', 'avatar', 'picture'];

        foreach ($possibleColumns as $column) {
            if (in_array($column, $columns)) {
                return $column;
            }
        }

        // Default to 'img' if none found (will be created later)
        return 'img';
    }

    /**
     * Get profile image URL
     */
    private function getProfileImageUrl($employee)
    {
        $imageColumn = $this->getImageColumnName();

        if (isset($employee->$imageColumn) && $employee->$imageColumn) {
            // Check if it's a full URL
            if (filter_var($employee->$imageColumn, FILTER_VALIDATE_URL)) {
                return $employee->$imageColumn;
            }

            // Check if file exists in storage
            if (Storage::disk('public')->exists($employee->$imageColumn)) {
                return asset('storage/' . $employee->$imageColumn);
            }

            // Check if it's a direct public path
            if (file_exists(public_path($employee->$imageColumn))) {
                return asset($employee->$imageColumn);
            }
        }

        // Return default avatar
        return asset('asset/img/default-avatar.png');
    }

    /**
     * Delete old profile image
     */
    private function deleteOldImage($employee)
    {
        $imageColumn = $this->getImageColumnName();

        if (isset($employee->$imageColumn) && $employee->$imageColumn) {
            // Try to delete from storage
            if (Storage::disk('public')->exists($employee->$imageColumn)) {
                Storage::disk('public')->delete($employee->$imageColumn);
                Log::info('Deleted old image from storage', ['path' => $employee->$imageColumn]);
            }

            // Try to delete from public directory
            $publicPath = public_path($employee->$imageColumn);
            if (file_exists($publicPath) && is_file($publicPath)) {
                @unlink($publicPath);
                Log::info('Deleted old image from public', ['path' => $publicPath]);
            }
        }
    }

    /**
     * Display employee dashboard
     */
    public function dashboard()
    {
        try {
            $employee = Auth::guard('employee')->user();

            // Calculate statistics
            $stats = [
                'total_products' => WholesalerProduct::count(),
                'total_orders' => Order::count() + WholesalerOrder::count(),
                'confirmed_orders' => Order::where('status', 'confirmed')->count() + WholesalerOrder::where('status', 'confirmed')->count(),
                'total_revenue' => Order::where('status', 'completed')->sum('total_amount') + WholesalerOrder::where('status', 'completed')->sum('total_amount'),
                'pending_orders' => Order::where('status', 'pending')->count() + WholesalerOrder::where('status', 'pending')->count(),
                'total_wholesalers' => \App\Models\Wholesaler::count(),
                'today_orders' => Order::whereDate('created_at', today())->count() + WholesalerOrder::whereDate('created_at', today())->count(),
                'my_price_changes' => EmployeePriceChange::where('employee_id', $employee->id)->count(),
                'today_price_changes' => EmployeePriceChange::where('employee_id', $employee->id)->whereDate('created_at', today())->count(),
            ];

            // Recent orders
            $recentOrders = Order::with('customer')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Recent wholesaler orders
            $recentWholesalerOrders = WholesalerOrder::with('wholesaler')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Recent wholesalers
            $recentWholesalers = \App\Models\Wholesaler::withCount('products')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Recent price changes by this employee
            $recentPriceChanges = EmployeePriceChange::where('employee_id', $employee->id)
                ->with(['product', 'employee'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return view('employee.dashboard', compact(
                'employee',
                'stats',
                'recentOrders',
                'recentWholesalerOrders',
                'recentWholesalers',
                'recentPriceChanges'
            ));

        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading dashboard: ' . $e->getMessage());
        }
    }


    /**
     * Display wholesaler products for employee with price management
     */
    public function wholesalerProducts(Request $request)
    {
        try {
            // Get all wholesalers for the filter dropdown
            $wholesalers = Wholesaler::orderBy('business_name')->get(['id', 'business_name as name']);

            $query = WholesalerProduct::with(['wholesaler', 'category', 'brand'])
                ->latest();

            // Apply search filter
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%")
                        ->orWhereHas('wholesaler', function($q2) use ($search) {
                            $q2->where('business_name', 'like', "%{$search}%");
                        });
                });
            }

            // Apply status filter
            if ($request->has('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            // Apply wholesaler filter
            if ($request->has('wholesaler_id') && $request->wholesaler_id != 'all') {
                $query->where('wholesaler_id', $request->wholesaler_id);
            }

            // Apply category filter
            if ($request->has('category_id') && $request->category_id != 'all') {
                $query->where('category_id', $request->category_id);
            }

            $products = $query->paginate(20)->withQueryString();

            // Get categories for filter
            $categories = Category::orderBy('name')->get(['id', 'name']);

            return view('employee.wholesaler.products', compact('products', 'wholesalers', 'categories'));

        } catch (\Exception $e) {
            Log::error('Wholesaler products error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading products: ' . $e->getMessage());
        }
    }

    /**
     * Show price update form for a specific product
     */
    public function showPriceUpdateForm($id)
    {
        try {
            $product = WholesalerProduct::with(['wholesaler', 'category'])->find($id);

            if (!$product) {
                return redirect()->route('employee.wholesaler.products')
                    ->with('error', 'Product not found.');
            }

            // Get price history for this product
            $priceHistory = EmployeePriceChange::with('employee')
                ->where('product_id', $id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return view('employee.wholesaler.price-update', [
                'product' => $product,
                'priceHistory' => $priceHistory
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading price update form: ' . $e->getMessage());
            return redirect()->route('employee.wholesaler.products')
                ->with('error', 'Error loading price update form.');
        }
    }

    /**
     * Update product selling price (FIXED TO UPDATE WHOLESALER PRODUCT PRICES)
     */
    public function updateProductPrice(Request $request)
    {
        try {
            // 🔐 Get logged-in employee
            $employee = Auth::guard('employee')->user();

            Log::info('PRICE UPDATE REQUEST', [
                'employee_id' => $employee->id,
                'request' => $request->all()
            ]);

            // ✅ Validate request
            $validator = Validator::make($request->all(), [
                'product_id'    => 'required|exists:wholesaler_products,id',
                'selling_price' => 'required|numeric|min:0.01',
                'notes'         => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $validated = $validator->validated();

            DB::beginTransaction();

            // 🔎 Lock product row for update
            $product = WholesalerProduct::lockForUpdate()
                ->findOrFail($validated['product_id']);

            // 💾 Store old values
            $oldCostPrice = $product->cost_price ?? 0;
            $oldSellingPrice = $product->selling_price;
            $oldDisplayPrice = $product->display_price ?? $oldSellingPrice;
            $oldFinalPrice = $product->final_price;

            // Calculate new final price with discount
            $newSellingPrice = $validated['selling_price'];
            $newFinalPrice = $product->discount > 0
                ? $newSellingPrice - ($newSellingPrice * ($product->discount / 100))
                : $newSellingPrice;

            // 🔁 Update ALL product prices for wholesaler
            $product->update([
                'previous_selling_price' => $oldSellingPrice,
                'selling_price'          => $newSellingPrice,
                'display_price'          => $newSellingPrice, // This is what wholesaler sees
                'last_price_updated_by'  => $employee->id,
                'last_price_updated_at'  => now(),
            ]);

            // 🔄 Refresh to get calculated values
            $product->refresh();

            // 📊 Calculate changes
            $priceDifference = $newFinalPrice - $oldFinalPrice;
            $percentageChange = $oldFinalPrice > 0
                ? (($priceDifference / $oldFinalPrice) * 100)
                : 100;

            // 🧾 Store price change history
            $priceChangeData = [
                'product_id'        => $product->id,
                'employee_id'       => $employee->id,
                'old_selling_price' => $oldSellingPrice,
                'new_selling_price' => $newSellingPrice,
                'old_display_price' => $oldDisplayPrice,
                'new_display_price' => $newSellingPrice,
                'old_final_price'   => $oldFinalPrice,
                'new_final_price'   => $newFinalPrice,
                'price_difference'  => $priceDifference,
                'percentage_change' => $percentageChange,
                'notes'             => $validated['notes'] ?? null,
                'change_reason'     => 'manual_update',
            ];

            // Add cost price fields if they exist
            if (Schema::hasColumn('employee_price_changes', 'old_cost_price')) {
                $priceChangeData['old_cost_price'] = $oldCostPrice;
                $priceChangeData['new_cost_price'] = $product->cost_price ?? $oldCostPrice;
            }

            EmployeePriceChange::create($priceChangeData);

            // ➕ Increment change count
            $product->increment('price_changes_count');

            DB::commit();

            Log::info('PRICE UPDATE SUCCESS', [
                'product_id' => $product->id,
                'employee_id' => $employee->id,
                'old_price' => $oldSellingPrice,
                'new_price' => $newSellingPrice,
                'wholesaler_id' => $product->wholesaler_id,
            ]);

            // ✅ Response
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Selling price updated successfully',
                    'product' => [
                        'id' => $product->id,
                        'selling_price' => number_format($product->selling_price, 2),
                        'display_price' => number_format($product->display_price, 2),
                        'final_price'   => number_format($product->final_price, 2),
                        'formatted_selling_price' => 'Rs. ' . number_format($product->selling_price, 2),
                        'formatted_display_price' => 'Rs. ' . number_format($product->display_price, 2),
                        'formatted_final_price' => 'Rs. ' . number_format($product->final_price, 2),
                    ]
                ]);
            }

            return redirect()
                ->route('employee.wholesaler.products')
                ->with('success', 'Selling price updated successfully');

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('PRICE UPDATE FAILED', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Price update failed: ' . $e->getMessage(),
                    'error' => $e->getMessage(),
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error updating price: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Bulk price update - FIXED TO UPDATE WHOLESALER PRODUCT PRICES
     */
    public function bulkPriceUpdate(Request $request)
    {
        DB::beginTransaction();

        try {
            $employee = Auth::guard('employee')->user();

            Log::info('BULK PRICE UPDATE REQUEST', [
                'employee_id' => $employee->id,
                'request_data' => $request->all()
            ]);

            // Validate request
            $validator = Validator::make($request->all(), [
                'products' => 'required|string',
                'update_type' => 'required|in:fixed,percentage',
                'value' => 'required|numeric',
                'notes' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error: ' . implode(', ', $validator->errors()->all())
                ], 422);
            }

            $validated = $validator->validated();
            $productIds = json_decode($validated['products'], true);

            if (!is_array($productIds) || empty($productIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No products selected'
                ], 400);
            }

            Log::info('Product IDs for bulk update', ['product_ids' => $productIds]);

            $products = WholesalerProduct::whereIn('id', $productIds)->get();

            if ($products->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid products found'
                ], 400);
            }

            $updatedCount = 0;

            foreach ($products as $product) {
                // Store old values
                $oldCostPrice = $product->cost_price ?? 0;
                $oldSellingPrice = $product->selling_price;
                $oldDisplayPrice = $product->display_price ?? $oldSellingPrice;
                $oldFinalPrice = $product->final_price;

                // Calculate new selling price
                if ($validated['update_type'] === 'fixed') {
                    $newSellingPrice = $validated['value'];
                } else {
                    // Percentage change
                    $newSellingPrice = $oldSellingPrice * (1 + ($validated['value'] / 100));
                }

                // Ensure non-negative price
                $newSellingPrice = max(0.01, round($newSellingPrice, 2));

                Log::info('Updating product', [
                    'product_id' => $product->id,
                    'old_price' => $oldSellingPrice,
                    'new_price' => $newSellingPrice,
                    'update_type' => $validated['update_type'],
                    'value' => $validated['value'],
                    'wholesaler_id' => $product->wholesaler_id
                ]);

                // Update product - SET BOTH selling_price AND display_price
                $product->update([
                    'selling_price' => $newSellingPrice,
                    'display_price' => $newSellingPrice, // This is what wholesaler sees
                    'last_price_updated_by' => $employee->id,
                    'last_price_updated_at' => now(),
                ]);

                // Refresh to get calculated values
                $product->refresh();

                // Calculate differences
                $newFinalPrice = $product->final_price;
                $priceDifference = $newFinalPrice - $oldFinalPrice;
                $percentageChange = $oldFinalPrice > 0
                    ? (($priceDifference / $oldFinalPrice) * 100)
                    : 0;

                // Log change
                $priceChangeData = [
                    'product_id' => $product->id,
                    'employee_id' => $employee->id,
                    'old_selling_price' => $oldSellingPrice,
                    'new_selling_price' => $product->selling_price,
                    'old_display_price' => $oldDisplayPrice,
                    'new_display_price' => $product->display_price,
                    'old_final_price' => $oldFinalPrice,
                    'new_final_price' => $newFinalPrice,
                    'price_difference' => $priceDifference,
                    'percentage_change' => $percentageChange,
                    'notes' => $validated['notes'] ?? "Bulk update: {$validated['update_type']} change",
                    'change_reason' => 'bulk_update',
                ];

                // Add cost price fields if they exist
                if (Schema::hasColumn('employee_price_changes', 'old_cost_price')) {
                    $priceChangeData['old_cost_price'] = $oldCostPrice;
                    $priceChangeData['new_cost_price'] = $product->cost_price ?? $oldCostPrice;
                }

                EmployeePriceChange::create($priceChangeData);

                // Increment price changes count
                $product->increment('price_changes_count');

                $updatedCount++;
            }

            DB::commit();

            Log::info('Bulk update completed successfully', [
                'updated_count' => $updatedCount,
                'employee_id' => $employee->id
            ]);

            return response()->json([
                'success' => true,
                'message' => "Successfully updated selling price for {$updatedCount} products!",
                'updated_count' => $updatedCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error in bulk update: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View product details with price history
     */
    public function viewProduct($id)
    {
        try {
            $product = WholesalerProduct::with(['wholesaler', 'category', 'brand', 'lastPriceUpdatedBy'])
                ->findOrFail($id);

            // Get price change history
            $priceHistory = EmployeePriceChange::where('product_id', $id)
                ->with('employee')
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            // Calculate total orders and revenue for this product
            $productStats = [
                'total_orders' => 0,
                'total_revenue' => 0,
                'average_order_value' => 0,
            ];

            return view('employee.wholesaler.product-view', compact('product', 'priceHistory', 'productStats'));

        } catch (\Exception $e) {
            Log::error('View product error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Product not found: ' . $e->getMessage());
        }
    }

    /**
     * Display wholesaler orders
     */
    public function wholesalerOrders(Request $request)
    {
        try {
            $query = WholesalerOrder::with(['wholesaler'])
                ->latest();

            if ($request->has('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%")
                        ->orWhereHas('wholesaler', function($q2) use ($search) {
                            $q2->where('business_name', 'like', "%{$search}%");
                        });
                });
            }

            $orders = $query->paginate(20);

            $statuses = [
                'all' => 'All Orders',
                'pending' => 'Pending',
                'confirmed' => 'Confirmed',
                'processing' => 'Processing',
                'shipped' => 'Shipped',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            ];

            return view('employee.wholesaler.orders', compact('orders', 'statuses'));

        } catch (\Exception $e) {
            Log::error('Wholesaler orders error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading orders: ' . $e->getMessage());
        }
    }

    /**
     * View wholesaler order
     */
    public function viewWholesalerOrder($id)
    {
        try {
            $order = WholesalerOrder::with(['wholesaler', 'orderItems.product'])
                ->findOrFail($id);

            return view('employee.wholesaler.orders.show', compact('order'));

        } catch (\Exception $e) {
            Log::error('View wholesaler order error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Order not found: ' . $e->getMessage());
        }
    }

    /**
     * Display price change history report
     */
    public function priceChangeHistory(Request $request)
    {
        try {
            $employee = Auth::guard('employee')->user();

            $query = EmployeePriceChange::with(['product', 'employee'])
                ->latest();

            // Filter by employee (admin can see all, regular employee sees only theirs)
            if ($request->has('employee_id') && $request->employee_id != 'all') {
                $query->where('employee_id', $request->employee_id);
            } elseif (!$employee->is_admin) {
                // If not admin, only show their own changes
                $query->where('employee_id', $employee->id);
            }

            // Date range filter
            if ($request->has('date_from') && $request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Product filter
            if ($request->has('product_id') && $request->product_id) {
                $query->where('product_id', $request->product_id);
            }

            $priceChanges = $query->paginate(50)->withQueryString();

            // Get employees for filter
            $employees = Employee::orderBy('name')->get(['id', 'name']);

            return view('employee.reports.price-changes', compact('priceChanges', 'employees'));

        } catch (\Exception $e) {
            Log::error('Price change history error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading price history: ' . $e->getMessage());
        }
    }

    /**
     * Export price changes to CSV
     */
    public function exportPriceChanges(Request $request)
    {
        try {
            $query = EmployeePriceChange::with(['product', 'employee'])->latest();

            if ($request->has('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $changes = $query->get();

            $filename = 'price-changes-' . date('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}",
            ];

            $callback = function() use ($changes) {
                $file = fopen('php://output', 'w');

                // Add headers
                fputcsv($file, [
                    'Date',
                    'Product Name',
                    'Product Code',
                    'Employee',
                    'Old Selling Price',
                    'New Selling Price',
                    'Old Final Price',
                    'New Final Price',
                    'Price Difference',
                    'Percentage Change',
                    'Notes'
                ]);

                // Add data
                foreach ($changes as $change) {
                    fputcsv($file, [
                        $change->created_at->format('Y-m-d H:i:s'),
                        $change->product->name,
                        $change->product->code,
                        $change->employee->name,
                        number_format($change->old_selling_price, 2),
                        number_format($change->new_selling_price, 2),
                        number_format($change->old_final_price, 2),
                        number_format($change->new_final_price, 2),
                        number_format($change->price_difference, 2),
                        number_format($change->percentage_change, 2) . '%',
                        $change->notes ?? ''
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Export price changes error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting data: ' . $e->getMessage());
        }
    }

    /**
     * Display analytics
     */
    public function analytics()
    {
        try {
            // Sales data for chart
            $salesData = [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [10000, 15000, 12000, 18000, 20000, 25000]
            ];

            // Top selling products
            $topProducts = WholesalerProduct::with('wholesaler')
                ->orderBy('sold_count', 'desc')
                ->limit(10)
                ->get();

            // Wholesaler performance
            $wholesalerPerformance = Wholesaler::withCount(['products', 'orders'])
                ->orderBy('orders_count', 'desc')
                ->limit(10)
                ->get();

            return view('employee.analytics.index', compact('salesData', 'topProducts', 'wholesalerPerformance'));

        } catch (\Exception $e) {
            Log::error('Analytics error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading analytics: ' . $e->getMessage());
        }
    }

    /**
     * Display employee profile
     */
    public function profile()
    {
        try {
            $employee = Auth::guard('employee')->user();

            // Add profile_image_url attribute
            if (!isset($employee->profile_image_url)) {
                $employee->profile_image_url = $this->getProfileImageUrl($employee);
            }

            // Get employee statistics
            $stats = [
                'total_price_changes' => EmployeePriceChange::where('employee_id', $employee->id)->count(),
                'this_month_changes' => EmployeePriceChange::where('employee_id', $employee->id)
                    ->whereMonth('created_at', now()->month)
                    ->count(),
                'products_updated' => EmployeePriceChange::where('employee_id', $employee->id)
                    ->distinct('product_id')
                    ->count('product_id'),
                'total_orders_processed' => \App\Models\Order::count() + \App\Models\WholesalerOrder::count(),
            ];

            return view('employee.profile.profile', compact('employee', 'stats'));

        } catch (\Exception $e) {
            Log::error('Profile error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading profile: ' . $e->getMessage());
        }
    }

    /**
     * Update employee profile (FIXED WITH PROPER IMAGE COLUMN HANDLING)
     */
    public function updateProfile(Request $request)
    {
        DB::beginTransaction();

        try {
            $employee = Auth::guard('employee')->user();
            $employeeId = $employee->id;

            Log::info('Updating employee profile', [
                'employee_id' => $employeeId,
                'request_data' => $request->except(['password', 'password_confirmation', 'img'])
            ]);

            // Define validation rules
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:employees,email,' . $employeeId,
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:500',
                'district' => 'required|string|max:100',
                'post_code' => 'required|string|max:20',
            ];

            // Add password rules conditionally
            if ($request->filled('password')) {
                $rules['password'] = 'required|string|min:8|confirmed';
                $rules['current_password'] = 'required|string|min:8';
            }

            $validator = Validator::make($request->all(), $rules, [
                'email.unique' => 'This email is already registered.',
                'current_password.required' => 'Current password is required to change password.',
            ]);

            if ($validator->fails()) {
                Log::warning('Profile update validation failed', [
                    'errors' => $validator->errors()->all()
                ]);

                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $validated = $validator->validated();

            // Verify current password if trying to change password
            if ($request->filled('password')) {
                if (!Hash::check($request->current_password, $employee->password)) {
                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Current password is incorrect.',
                            'errors' => ['current_password' => ['Current password is incorrect.']]
                        ], 422);
                    }
                    return redirect()->back()
                        ->withErrors(['current_password' => 'Current password is incorrect.'])
                        ->withInput();
                }

                $validated['password'] = Hash::make($request->password);

                // Add view_password if column exists
                if (Schema::hasColumn('employees', 'view_password')) {
                    $validated['view_password'] = $request->password;
                }
            } else {
                // Remove password fields if not changing password
                unset($validated['password']);
                unset($validated['password_confirmation']);
                unset($validated['current_password']);
            }

            // Update employee data
            $employee->update($validated);

            // Refresh employee data
            $employee->refresh();

            DB::commit();

            Log::info('Employee profile updated successfully', [
                'employee_id' => $employeeId,
                'updated_fields' => array_keys($validated)
            ]);

            // Prepare response
            $response = [
                'success' => true,
                'message' => 'Profile updated successfully!',
                'employee' => [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'email' => $employee->email,
                    'phone' => $employee->phone,
                    'profile_image_url' => $this->getProfileImageUrl($employee),
                    'role' => $employee->role,
                    'status' => $employee->status,
                    'code' => $employee->code,
                    'address' => $employee->address,
                    'district' => $employee->district,
                    'post_code' => $employee->post_code,
                ]
            ];

            if ($request->ajax()) {
                return response()->json($response);
            }

            return redirect()->route('employee.profile')
                ->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Profile update failed: ' . $e->getMessage(), [
                'employee_id' => $employeeId ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);

            $errorMessage = 'Error updating profile. ' . $e->getMessage();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }

            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
        }
    }

    /**
     * Get employee profile data (for AJAX requests)
     */
    public function getProfileData(Request $request)
    {
        try {
            $employee = Auth::guard('employee')->user();

            // Get statistics
            $totalPriceChanges = EmployeePriceChange::where('employee_id', $employee->id)->count();
            $thisMonthChanges = EmployeePriceChange::where('employee_id', $employee->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            $productsUpdated = EmployeePriceChange::where('employee_id', $employee->id)
                ->distinct('product_id')
                ->count('product_id');

            return response()->json([
                'success' => true,
                'employee' => [
                    'id' => $employee->id,
                    'code' => $employee->code,
                    'name' => $employee->name,
                    'email' => $employee->email,
                    'phone' => $employee->phone,
                    'address' => $employee->address,
                    'district' => $employee->district,
                    'post_code' => $employee->post_code,
                    'username' => $employee->username,
                    'role' => $employee->role,
                    'status' => $employee->status,
                    'profile_image_url' => $this->getProfileImageUrl($employee),
                    'created_at' => $employee->created_at ? $employee->created_at->format('Y-m-d H:i:s') : null,
                    'last_login_at' => $employee->last_login_at ? $employee->last_login_at->format('Y-m-d H:i:s') : null,
                    'stats' => [
                        'total_price_changes' => $totalPriceChanges,
                        'this_month_changes' => $thisMonthChanges,
                        'products_updated' => $productsUpdated,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting profile data: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error loading profile data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload profile image only (FIXED WITH PROPER COLUMN DETECTION)
     */
    public function uploadProfileImage(Request $request)
    {
        try {
            $employee = Auth::guard('employee')->user();

            Log::info('Profile image upload request', [
                'employee_id' => $employee->id,
                'has_file' => $request->hasFile('img')
            ]);

            $validator = Validator::make($request->all(), [
                'img' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Delete old image if exists
            $this->deleteOldImage($employee);

            // Upload new image
            $path = 'employee/profiles';
            $filename = 'profile_' . $employee->id . '_' . time() . '.' . $request->file('img')->getClientOriginalExtension();
            $imagePath = $request->file('img')->storeAs($path, $filename, 'public');

            if (!$imagePath) {
                throw new \Exception('Failed to upload image.');
            }

            Log::info('Image uploaded successfully', [
                'path' => $imagePath,
                'employee_id' => $employee->id
            ]);

            // Get the correct image column name
            $imageColumn = $this->getImageColumnName();

            // Update employee record
            $employee->$imageColumn = $imagePath;
            $employee->save();

            Log::info('Employee record updated', [
                'column' => $imageColumn,
                'path' => $imagePath
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile image updated successfully!',
                'image_url' => asset('storage/' . $imagePath),
                'image_path' => $imagePath
            ]);

        } catch (\Exception $e) {
            Log::error('Profile image upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error uploading image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete profile image (FIXED WITH PROPER COLUMN DETECTION)
     */
    public function deleteProfileImage(Request $request)
    {
        try {
            $employee = Auth::guard('employee')->user();

            Log::info('Delete profile image request', [
                'employee_id' => $employee->id
            ]);

            // Delete the image file
            $this->deleteOldImage($employee);

            // Get the correct image column name
            $imageColumn = $this->getImageColumnName();

            // Update employee record
            $employee->$imageColumn = null;
            $employee->save();

            Log::info('Profile image deleted successfully', [
                'employee_id' => $employee->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile image removed successfully!',
                'default_image' => asset('asset/img/default-avatar.png')
            ]);

        } catch (\Exception $e) {
            Log::error('Delete profile image failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error deleting image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display seller orders
     */
    public function sellerOrders(Request $request)
    {
        try {
            $query = Order::with(['seller', 'customer'])
                ->latest();

            if ($request->has('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('order_no', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%")
                        ->orWhereHas('seller', function($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%");
                        });
                });
            }

            $orders = $query->paginate(20);

            $statuses = [
                'all' => 'All Orders',
                'pending' => 'Pending',
                'confirmed' => 'Confirmed',
                'processing' => 'Processing',
                'shipped' => 'Shipped',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            ];

            return view('employee.seller.orders', compact('orders', 'statuses'));

        } catch (\Exception $e) {
            Log::error('Seller orders error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading orders: ' . $e->getMessage());
        }
    }

    /**
     * Update seller order status
     */
    public function updateOrderStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,confirmed,processing,shipped,completed,cancelled',
            ]);

            $order = Order::findOrFail($id);
            $order->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Update order status error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View seller order
     */
    public function viewOrder($id)
    {
        try {
            $order = Order::with(['seller', 'orderItems.product'])
                ->findOrFail($id);

            return view('employee.seller.order-view', compact('order'));

        } catch (\Exception $e) {
            Log::error('View seller order error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Order not found: ' . $e->getMessage());
        }
    }

    /**
     * Export reports
     */
    public function exportReport(Request $request)
    {
        try {
            $type = $request->get('type', 'orders');
            $format = $request->get('format', 'csv');

            switch ($type) {
                case 'wholesaler_orders':
                    $fileName = 'wholesaler-orders-' . date('Y-m-d') . '.' . $format;
                    $headers = $this->getExportHeaders($fileName, $format);

                    $orders = WholesalerOrder::with('wholesaler')->get();

                    return response()->streamDownload(function () use ($orders) {
                        $file = fopen('php://output', 'w');

                        fputcsv($file, ['Order Number', 'Wholesaler', 'Date', 'Customer', 'Total', 'Status']);

                        foreach ($orders as $order) {
                            fputcsv($file, [
                                $order->order_number,
                                $order->wholesaler->business_name ?? 'N/A',
                                $order->created_at->format('Y-m-d'),
                                $order->customer_name,
                                $order->total_amount,
                                $order->status
                            ]);
                        }

                        fclose($file);
                    }, $fileName, $headers);

                case 'seller_orders':
                    $fileName = 'seller-orders-' . date('Y-m-d') . '.' . $format;
                    $headers = $this->getExportHeaders($fileName, $format);

                    $orders = Order::with('seller')->get();

                    return response()->streamDownload(function () use ($orders) {
                        $file = fopen('php://output', 'w');

                        fputcsv($file, ['Order Number', 'Seller', 'Date', 'Customer', 'Total', 'Status']);

                        foreach ($orders as $order) {
                            fputcsv($file, [
                                $order->order_no,
                                $order->seller->name ?? 'N/A',
                                $order->created_at->format('Y-m-d'),
                                $order->customer_name,
                                $order->total_amount,
                                $order->status
                            ]);
                        }

                        fclose($file);
                    }, $fileName, $headers);

                default:
                    return redirect()->back()->with('error', 'Invalid export type');
            }

        } catch (\Exception $e) {
            Log::error('Export report error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting report: ' . $e->getMessage());
        }
    }

    /**
     * Get export headers
     */
    private function getExportHeaders($fileName, $format)
    {
        $contentType = $format == 'csv' ? 'text/csv' : 'application/vnd.ms-excel';

        return [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
    }

    /**
     * Delivery label print
     */
    public function delivery_label_print($id)
    {
        try {
            $order = Order::with(['seller', 'customer'])
                ->findOrFail($id);

            return view('employee.print.delivery-label', compact('order'));

        } catch (\Exception $e) {
            Log::error('Delivery label print error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error printing label: ' . $e->getMessage());
        }
    }

    // ========== Employee Management Methods (for admin) ==========

    /**
     * Display all employees (Admin only)
     */
    public function index()
    {
        try {
            $employees = Employee::all();
            return view('admin.employee.index', compact('employees'));
        } catch (\Exception $e) {
            Log::error('Employee index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading employees: ' . $e->getMessage());
        }
    }

    /**
     * Show create employee form (Admin only)
     */
    public function create()
    {
        return view('admin.employee.create');
    }

    /**
     * Store new employee (Admin only)
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:employees',
                'phone' => 'required|string|max:20',
                'password' => 'required|string|min:8',
                'address' => 'nullable|string',
                'role' => 'required|in:admin,manager,staff',
            ]);

            $validated['password'] = bcrypt($validated['password']);
            $validated['code'] = 'EMP' . time() . rand(100, 999);
            $validated['status'] = 'active';

            Employee::create($validated);

            return redirect()->route('employee.list')->with('success', 'Employee created successfully!');

        } catch (\Exception $e) {
            Log::error('Create employee error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error creating employee: ' . $e->getMessage());
        }
    }

    /**
     * Display specific employee
     */
    public function show($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            return view('admin.employee.show', compact('employee'));
        } catch (\Exception $e) {
            Log::error('Show employee error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading employee: ' . $e->getMessage());
        }
    }

    /**
     * Edit employee (Admin only)
     */
    public function edit($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            return view('admin.employee.edit', compact('employee'));
        } catch (\Exception $e) {
            Log::error('Edit employee error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading employee: ' . $e->getMessage());
        }
    }

    /**
     * Update employee (Admin only)
     */
    public function update(Request $request, $id)
    {
        try {
            $employee = Employee::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:employees,email,' . $employee->id,
                'phone' => 'required|string|max:20',
                'address' => 'nullable|string',
                'role' => 'required|in:admin,manager,staff',
                'status' => 'required|in:active,inactive',
            ]);

            // Update password if provided
            if ($request->filled('password')) {
                $validated['password'] = bcrypt($request->password);
            }

            $employee->update($validated);

            return redirect()->route('employee.list')->with('success', 'Employee updated successfully!');

        } catch (\Exception $e) {
            Log::error('Update employee error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating employee: ' . $e->getMessage());
        }
    }

    /**
     * Delete employee (Admin only)
     */
    public function destroy($id)
    {
        try {
            $employee = Employee::findOrFail($id);

            // Check if employee has made any price changes
            $priceChanges = EmployeePriceChange::where('employee_id', $employee->id)->count();

            if ($priceChanges > 0) {
                return redirect()->back()->with('error', 'Cannot delete employee. They have made ' . $priceChanges . ' price changes.');
            }

            $employee->delete();

            return redirect()->route('employee.list')->with('success', 'Employee deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Delete employee error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting employee: ' . $e->getMessage());
        }
    }
}
