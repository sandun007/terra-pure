<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerOrder;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SellerOrdersImport;
use Illuminate\Support\Str;
use Exception;

class SellerOrderController extends Controller
{
    /**
     * Display the order creation form
     */
    public function index()
    {
        $user = Auth::user();

        try {
            // Check if table exists before generating order ID
            if (Schema::hasTable('seller_orders')) {
                $code = SellerOrder::generateOrderId();
            } else {
                $code = 'ORD/00001';
                Log::warning('seller_orders table does not exist');
            }
        } catch (Exception $e) {
            $code = 'ORD/00001';
            Log::error('Error generating order ID: ' . $e->getMessage());
        }

        // Calculate deposit - using cal_deposit if available, otherwise deposit
        $import = $user->cal_deposit ?? $user->deposit ?? 0;

        return view('seller.order.new_order', compact('user', 'code', 'import'));
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        $request->validate([
            'excel' => 'nullable|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
            'date' => 'required|date',
            'platform' => 'nullable|string',
            'product_id' => 'required_without:excel',
            'customer_name' => 'required_without:excel|string|max:255',
            'customer_phone' => 'required_without:excel|string|max:20',
            'customer_address' => 'nullable|string',
            'district' => 'nullable|string|max:100',
            'call_status' => 'nullable|string|max:50',
            'remark' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Check if user has sufficient deposit
        $availableDeposit = $user->cal_deposit ?? $user->deposit ?? 0;
        if ($availableDeposit <= 0) {
            return redirect()->route('seller.new-order')
                ->with('error', 'You do not have sufficient deposit to create orders.');
        }

        try {
            DB::beginTransaction();

            // Handle Excel bulk upload
            if ($request->hasFile('excel')) {
                $result = $this->handleExcelUpload($request, $user);
                DB::commit();
                return redirect()->route('seller.pending-order')
                    ->with('success', $result['message']);
            }

            // Handle single order creation
            $result = $this->handleSingleOrder($request, $user);
            DB::commit();

            return redirect()->route('seller.pending-order')
                ->with('success', 'Order created successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating order: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('seller.new-order')
                ->with('error', 'Error creating order: ' . $e->getMessage());
        }
    }

    /**
     * Handle Excel file upload for bulk orders
     */
    private function handleExcelUpload(Request $request, User $user)
    {
        $file = $request->file('excel');
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('seller-orders', $filename, 'public');

        // Import Excel file
        $import = new SellerOrdersImport($user->id, $request->date, $request->platform);
        Excel::import($import, $file);

        $rowCount = $import->getRowCount();

        // Update last row count
        $user->update(['last_row_count' => $rowCount]);

        // Update user's deposit (deduct cost per lead)
        $totalCost = $rowCount * $user->cost;

        // Use cal_deposit if available, otherwise use deposit
        if ($user->cal_deposit !== null) {
            $user->decrement('cal_deposit', $totalCost);
        } else {
            $user->decrement('deposit', $totalCost);
        }

        return [
            'success' => true,
            'message' => "Order uploaded successfully! {$rowCount} records imported."
        ];
    }

    /**
     * Handle single order creation
     */
    private function handleSingleOrder(Request $request, User $user)
    {
        // Verify product exists and has stock
        $product = Product::findOrFail($request->product_id);

        if ($product->qty <= 0) {
            throw new Exception('Selected product is out of stock');
        }

        // Create the order
        $orderData = [
            'seller_id' => $user->id,
            'order_id' => SellerOrder::generateOrderId(),
            'product_id' => $request->product_id,
            'product_name' => $request->product_name,
            'product_code' => $request->product_code,
            'product_price' => $request->product_price,
            'product_image' => $request->product_image,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'district' => $request->district,
            'platform' => $request->platform,
            'call_status' => $request->call_status ?? 'Conform',
            'remark' => $request->remark,
            'order_date' => $request->date,
            'status' => 'pending',
            'quantity' => 1, // Default quantity
        ];

        $order = SellerOrder::create($orderData);

        // Deduct cost from user deposit
        $cost = $user->cost;
        if ($user->cal_deposit !== null) {
            $user->decrement('cal_deposit', $cost);
        } else {
            $user->decrement('deposit', $cost);
        }

        // Update last row count
        $user->update(['last_row_count' => 1]);

        // Optionally decrement product stock
        // $product->decrement('qty', 1);

        return [
            'success' => true,
            'order' => $order
        ];
    }

    /**
     * Get product details via AJAX
     */
    public function getProductDetails($id)
    {
        try {
            $product = Product::findOrFail($id);

            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code ?? $product->id,
                    'display_price' => $product->price,
                    'qty' => $product->qty ?? 0,
                    'category' => $product->category->name ?? 'Uncategorized',
                    'description' => $product->description ?? '',
                    'image' => $product->image ? Storage::url($product->image) : asset('images/no-product-image.png'),
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
    }

    /**
     * Display pending orders
     */
    public function pendingOrder()
    {
        $user = Auth::user();

        if (!Schema::hasTable('seller_orders')) {
            $orders = collect();
            $count = 0;
        } else {
            $orders = SellerOrder::where('seller_id', $user->id)
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();

            $count = $orders->count();
        }

        return view('seller.order.pending_order_list', compact('orders', 'count'));
    }

    /**
     * Display completed orders
     */
    public function completeOrder()
    {
        $user = Auth::user();

        if (!Schema::hasTable('seller_orders')) {
            $orders = collect();
            $count = 0;
        } else {
            $orders = SellerOrder::where('seller_id', $user->id)
                ->where('status', 'complete')
                ->orderBy('delivery_date', 'desc')
                ->get();

            $count = $orders->count();
        }

        return view('seller.order.complete_order_list', compact('orders', 'count'));
    }

    /**
     * Display returned orders
     */
    public function returnOrder()
    {
        $user = Auth::user();

        if (!Schema::hasTable('seller_orders')) {
            $orders = collect();
            $count = 0;
        } else {
            $orders = SellerOrder::where('seller_id', $user->id)
                ->where('status', 'return')
                ->orderBy('return_date', 'desc')
                ->get();

            $count = $orders->count();
        }

        return view('seller.order.return_order_list', compact('orders', 'count'));
    }

    /**
     * Display rescheduled orders
     */
    public function reschedulingOrder()
    {
        $user = Auth::user();

        if (!Schema::hasTable('seller_orders')) {
            $orders = collect();
            $count = 0;
        } else {
            $orders = SellerOrder::where('seller_id', $user->id)
                ->where('status', 'rescheduling')
                ->orderBy('rescheduling_date', 'desc')
                ->get();

            $count = $orders->count();
        }

        return view('seller.order.rescheduling_order_list', compact('orders', 'count'));
    }

    /**
     * Display a single order
     */
    public function show($id)
    {
        if (!Schema::hasTable('seller_orders')) {
            abort(404, 'Orders table not found');
        }

        $order = SellerOrder::where('seller_id', Auth::id())
            ->findOrFail($id);

        return view('seller.order.order-show', compact('order'));
    }

    /**
     * Update an order
     */
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

    /**
     * Delete an order
     */
    public function destroy($id)
    {
        if (!Schema::hasTable('seller_orders')) {
            return redirect()->back()->with('error', 'Orders table not found');
        }

        $user = Auth::user();
        $order = SellerOrder::where('seller_id', $user->id)
            ->findOrFail($id);

        // Only allow deletion if order is still pending
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending orders can be deleted.');
        }

        // Refund the cost back to user
        $refundAmount = $user->cost;
        if ($user->cal_deposit !== null) {
            $user->increment('cal_deposit', $refundAmount);
        } else {
            $user->increment('deposit', $refundAmount);
        }

        $order->delete();

        return redirect()->route('seller.pending-order')
            ->with('success', 'Order deleted successfully! Amount refunded.');
    }
}
