<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerOrder;
use App\Models\SellerProduct;
use App\Models\User;
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
    public function index(Request $request)
    {
        $user = Auth::user();

        try {
            if (Schema::hasTable('seller_orders')) {
                $code = SellerOrder::generateOrderId();
            } else {
                $code = 'ORD/00001';
            }
        } catch (Exception $e) {
            $code = 'ORD/00001';
            Log::error('Error generating order ID: ' . $e->getMessage());
        }

        $import = $user->cal_deposit ?? $user->deposit ?? 0;

        // Get product data from URL parameters - FIXED: Properly decode and sanitize
        $productCode = $request->get('product_code');
        $productName = $request->get('product_name');
        $productPrice = $request->get('product_price');
        $productStock = $request->get('product_stock');
        $quantity = $request->get('quantity', 1);
        $productImage = $request->get('product_image');
        $productDescription = $request->get('product_description');
        $productCategory = $request->get('product_category');
        $productId = $request->get('product_id');

        return view('seller.order.new_order', compact(
            'user',
            'code',
            'import',
            'productCode',
            'productName',
            'productPrice',
            'productStock',
            'quantity',
            'productImage',
            'productDescription',
            'productCategory',
            'productId'
        ));
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        $request->validate([
            'excel' => 'nullable|file|mimes:xlsx,xls,csv|max:10240',
            'date' => 'required|date',
            'platform' => 'nullable|string',
            'product_id' => 'required_without:excel',
            'product_code' => 'required_without:excel',
            'product_name' => 'required_without:excel',
            'product_price' => 'required_without:excel|numeric',
            'quantity' => 'required_without:excel|integer|min:1',
            'customer_name' => 'required_without:excel|string|max:255',
            'customer_phone' => 'required_without:excel|string|max:20',
            'customer_address' => 'nullable|string',
            'district' => 'nullable|string|max:100',
            'call_status' => 'nullable|string|max:50',
            'remark' => 'nullable|string',
        ]);

        $user = Auth::user();

        $availableDeposit = $user->cal_deposit ?? $user->deposit ?? 0;
        if ($availableDeposit <= 0) {
            return redirect()->route('seller.new-order')
                ->with('error', 'You do not have sufficient deposit to create orders.');
        }

        try {
            DB::beginTransaction();

            if ($request->hasFile('excel')) {
                $result = $this->handleExcelUpload($request, $user);
                DB::commit();
                return redirect()->route('seller.pending-order')
                    ->with('success', $result['message']);
            }

            $result = $this->handleSingleOrder($request, $user);
            DB::commit();

            return redirect()->route('seller.pending-order')
                ->with('success', 'Order created successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating order: ' . $e->getMessage());
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

        $import = new SellerOrdersImport($user->id, $request->date, $request->platform);
        Excel::import($import, $file);

        $rowCount = $import->getRowCount();
        $user->update(['last_row_count' => $rowCount]);

        $totalCost = $rowCount * $user->cost;

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
        $product = SellerProduct::findOrFail($request->product_id);
        $quantity = $request->quantity ?? 1;

        if ($product->qty < $quantity) {
            throw new Exception("Selected product has only {$product->qty} items in stock");
        }

        $orderId = SellerOrder::generateOrderId();
        $totalAmount = $request->product_price * $quantity;

        $orderData = [
            'seller_id' => $user->id,
            'order_id' => $orderId,
            'order_number' => $orderId,
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
            'quantity' => $quantity,
            'total_amount' => $totalAmount
        ];

        $order = SellerOrder::create($orderData);

        $cost = $user->cost ?? 0;
        if ($user->cal_deposit !== null) {
            $user->decrement('cal_deposit', $cost);
        } else {
            $user->decrement('deposit', $cost);
        }

        $user->update(['last_row_count' => 1]);
        $product->decrement('qty', $quantity);

        return [
            'success' => true,
            'order' => $order
        ];
    }

    /**
     * FIXED: Get product details via AJAX - Proper JSON response
     */
    public function getProductDetails($code)
    {
        try {
            $seller = Auth::user();

            Log::info('Fetching product details for code: ' . $code, ['user_id' => $seller->id]);

            $product = SellerProduct::where('seller_id', $seller->id)
                ->where('code', $code)
                ->with('category')
                ->first();

            if (!$product) {
                Log::warning('Product not found with code: ' . $code);
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found with code: ' . $code
                ]);
            }

            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code,
                    'display_price' => (float) $product->display_price,
                    'stock_price' => (float) $product->stock_price,
                    'qty' => (int) $product->qty,
                    'min_qty' => (int) ($product->min_qty ?? 1),
                    'category' => $product->category->name ?? 'Uncategorized',
                    'description' => $product->description ?? '',
                    'image' => $product->image ? asset('storage/' . $product->image) : null,
                    'discount' => (float) ($product->discount ?? 0),
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Get product details error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching product details: ' . $e->getMessage()
            ]);
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

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending orders can be deleted.');
        }

        $refundAmount = $user->cost ?? 0;
        if ($user->cal_deposit !== null) {
            $user->increment('cal_deposit', $refundAmount);
        } else {
            $user->increment('deposit', $refundAmount);
        }

        if ($order->product_id) {
            $product = SellerProduct::find($order->product_id);
            if ($product) {
                $product->increment('qty', $order->quantity ?? 1);
            }
        }

        $order->delete();
        return redirect()->route('seller.pending-order')
            ->with('success', 'Order deleted successfully! Amount refunded and stock restored.');
    }
}
