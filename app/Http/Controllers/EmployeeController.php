<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Order;
use App\Models\User;
use App\Models\Wholesaler;
use App\Models\WholesalerProduct;
use App\Models\WholesalerOrder;
use App\Models\WholesalerShop;
use App\Models\Saler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
    }

    // Main Dashboard
    public function dashboard()
    {
        try {
            $employee = Auth::guard('employee')->user();

            // Get statistics
            $stats = [
                'pending_orders' => Order::where('status', 'pending')->count(),
                'confirmed_orders' => Order::where('status', 'confirmed')->count(),
                'total_wholesalers' => Wholesaler::count(),
                'total_sellers' => Saler::count(),
                'active_products' => WholesalerProduct::where('status', 'active')->count(),
                'total_revenue' => WholesalerOrder::where('status', 'completed')->sum('total_amount'),
            ];

            // Recent orders
            $recentOrders = Order::with('seller')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Recent wholesaler orders
            $recentWholesalerOrders = WholesalerOrder::with('wholesaler')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return view('employee.dashboard', compact('employee', 'stats', 'recentOrders', 'recentWholesalerOrders'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading dashboard: ' . $e->getMessage());
        }
    }

    // Wholesaler Products Management
    public function wholesalerProducts(Request $request)
    {
        try {
            $search = $request->get('search', '');
            $status = $request->get('status', 'all');
            $wholesaler_id = $request->get('wholesaler_id', 'all');

            $query = WholesalerProduct::with(['wholesaler', 'category', 'brand'])
                ->when($search, function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%");
                })
                ->when($status !== 'all', function ($q) use ($status) {
                    $q->where('status', $status);
                })
                ->when($wholesaler_id !== 'all', function ($q) use ($wholesaler_id) {
                    $q->where('wholesaler_id', $wholesaler_id);
                })
                ->orderBy('created_at', 'desc');

            $products = $query->paginate(20);
            $wholesalers = Wholesaler::all();

            return view('employee.wholesaler.products', compact('products', 'wholesalers', 'search', 'status', 'wholesaler_id'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading products: ' . $e->getMessage());
        }
    }

    // Update Wholesaler Product Price
    public function updateProductPrice(Request $request, $id)
    {
        try {
            $request->validate([
                'selling_price' => 'required|numeric|min:0',
                'wholesale_price' => 'required|numeric|min:0',
                'display_price' => 'required|numeric|min:0',
                'notes' => 'nullable|string|max:500',
            ]);

            $product = WholesalerProduct::findOrFail($id);

            // Store old prices for audit
            $oldPrices = [
                'selling_price' => $product->selling_price,
                'wholesale_price' => $product->wholesale_price,
                'display_price' => $product->display_price,
            ];

            // Update prices
            $product->update([
                'selling_price' => $request->selling_price,
                'wholesale_price' => $request->wholesale_price,
                'display_price' => $request->display_price,
                'last_price_update_by' => Auth::guard('employee')->user()->id,
                'last_price_update_at' => now(),
            ]);

            // Log price change
            DB::table('price_change_logs')->insert([
                'product_id' => $product->id,
                'employee_id' => Auth::guard('employee')->user()->id,
                'old_prices' => json_encode($oldPrices),
                'new_prices' => json_encode($request->only(['selling_price', 'wholesale_price', 'display_price'])),
                'notes' => $request->notes,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product prices updated successfully!',
                'product' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating price: ' . $e->getMessage()
            ], 500);
        }
    }

    // View Wholesaler Product Details
    public function viewProduct($id)
    {
        try {
            $product = WholesalerProduct::with(['wholesaler', 'category', 'brand'])
                ->withCount(['orders as total_orders'])
                ->withSum('orders as total_sales', 'total_amount')
                ->findOrFail($id);

            // Get price change history
            $priceHistory = DB::table('price_change_logs')
                ->where('product_id', $id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return view('employee.wholesaler.product-view', compact('product', 'priceHistory'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Product not found: ' . $e->getMessage());
        }
    }

    // Seller Orders Management
    public function sellerOrders(Request $request)
    {
        try {
            $status = $request->get('status', 'all');
            $search = $request->get('search', '');
            $date_from = $request->get('date_from');
            $date_to = $request->get('date_to');

            $query = Order::with(['seller'])
                ->when($status !== 'all', function ($q) use ($status) {
                    $q->where('status', $status);
                })
                ->when($search, function ($q) use ($search) {
                    $q->where('order_no', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_phone_01', 'like', "%{$search}%");
                })
                ->when($date_from && $date_to, function ($q) use ($date_from, $date_to) {
                    $q->whereBetween('created_at', [$date_from, $date_to]);
                })
                ->orderBy('created_at', 'desc');

            $orders = $query->paginate(20);
            $statuses = [
                'all' => 'All Statuses',
                'pending' => 'Pending',
                'confirmed' => 'Confirmed',
                'processing' => 'Processing',
                'shipped' => 'Shipped',
                'delivered' => 'Delivered',
                'cancelled' => 'Cancelled'
            ];

            return view('employee.orders.seller-orders', compact('orders', 'statuses', 'status', 'search', 'date_from', 'date_to'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading orders: ' . $e->getMessage());
        }
    }

    // Wholesaler Orders Management - FIXED: Removed duplicate method
    public function wholesalerOrders(Request $request)
    {
        try {
            $status = $request->get('status', 'all');
            $search = $request->get('search', '');
            $wholesaler_id = $request->get('wholesaler_id', 'all');
            $date_from = $request->get('date_from');
            $date_to = $request->get('date_to');

            $query = WholesalerOrder::with(['wholesaler', 'items.product'])
                ->when($status !== 'all', function ($q) use ($status) {
                    $q->where('status', $status);
                })
                ->when($search, function ($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%");
                })
                ->when($wholesaler_id !== 'all', function ($q) use ($wholesaler_id) {
                    $q->where('wholesaler_id', $wholesaler_id);
                })
                ->when($date_from && $date_to, function ($q) use ($date_from, $date_to) {
                    $q->whereBetween('created_at', [$date_from, $date_to]);
                })
                ->orderBy('created_at', 'desc');

            $orders = $query->paginate(20);
            $wholesalers = Wholesaler::all();
            $statuses = [
                'all' => 'All Statuses',
                'pending' => 'Pending',
                'confirmed' => 'Confirmed',
                'processing' => 'Processing',
                'shipped' => 'Shipped',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled'
            ];

            return view('employee.wholesaler.orders.index', compact('orders', 'wholesalers', 'statuses', 'status', 'search', 'wholesaler_id', 'date_from', 'date_to'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading orders: ' . $e->getMessage());
        }
    }

    // View Wholesaler Order Details
    public function viewWholesalerOrder($id)
    {
        try {
            $order = WholesalerOrder::with([
                'wholesaler',
                'items.product',
                'statusLogs' => function($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'statusLogs.employee'
            ])->findOrFail($id);

            // If you want to use activity logs instead of statusLogs, use this:
            // Assuming you have an Activity model or similar for logging
            /*
            $activities = DB::table('order_activities')
                ->where('order_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();
            */

            // Calculate profit margins for each item
            foreach ($order->items as $item) {
                if ($item->product) {
                    $product = $item->product;
                    $item->wholesaler_price = $product->wholesale_price ?? $item->unit_price;
                    $item->employee_price = $product->employee_price ?? $product->wholesale_price ?? $item->unit_price;
                    $item->profit = $item->employee_price - $item->wholesaler_price;
                    $item->profit_margin = $item->wholesaler_price > 0 ? ($item->profit / $item->wholesaler_price) * 100 : 0;
                }
            }

            return view('employee.wholesaler.orders.show', compact('order'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Order not found: ' . $e->getMessage());
        }
    }

    // Update Order Status (for seller orders)
    public function updateOrderStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,confirmed,processing,shipped,delivered,completed,cancelled',
                'notes' => 'nullable|string|max:500',
            ]);

            $order = Order::findOrFail($id);
            $oldStatus = $order->status;

            $order->update([
                'status' => $request->status,
                'updated_by' => Auth::guard('employee')->user()->id,
            ]);

            // Log status change
            DB::table('order_status_logs')->insert([
                'order_id' => $order->id,
                'employee_id' => Auth::guard('employee')->user()->id,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'notes' => $request->notes,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    // View Seller Order Details
    public function viewOrder($id)
    {
        try {
            $order = Order::with(['seller'])
                ->findOrFail($id);

            // Get status history
            $statusHistory = DB::table('order_status_logs')
                ->where('order_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('employee.orders.view', compact('order', 'statusHistory'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Order not found: ' . $e->getMessage());
        }
    }

    // Wholesaler Shops Management
    public function wholesalerShops(Request $request)
    {
        try {
            $search = $request->get('search', '');
            $status = $request->get('status', 'all');
            $district = $request->get('district', 'all');

            $query = Wholesaler::withCount(['products', 'orders'])
                ->when($search, function ($q) use ($search) {
                    $q->where('shop_name', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })
                ->when($status !== 'all', function ($q) use ($status) {
                    $q->where('status', $status);
                })
                ->when($district !== 'all', function ($q) use ($district) {
                    $q->where('district', $district);
                })
                ->orderBy('created_at', 'desc');

            $shops = $query->paginate(20);
            $districts = Wholesaler::distinct('district')->pluck('district');

            return view('employee.wholesaler.shops', compact('shops', 'search', 'status', 'district', 'districts'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading shops: ' . $e->getMessage());
        }
    }

    // View Wholesaler Shop Details
    public function viewShop($id)
    {
        try {
            $shop = Wholesaler::withCount(['products', 'orders'])
                ->with(['products' => function($q) {
                    $q->orderBy('created_at', 'desc')->limit(10);
                }, 'orders' => function($q) {
                    $q->orderBy('created_at', 'desc')->limit(10);
                }])
                ->findOrFail($id);

            return view('employee.wholesaler.shop-view', compact('shop'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Shop not found: ' . $e->getMessage());
        }
    }

    // Analytics Dashboard
    public function analytics()
    {
        try {
            // Monthly revenue chart data
            $monthlyRevenue = WholesalerOrder::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(total_amount) as revenue')
            )
                ->where('status', 'completed')
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // Top selling products
            $topProducts = DB::table('wholesaler_order_items')
                ->join('wholesaler_products', 'wholesaler_order_items.product_id', '=', 'wholesaler_products.id')
                ->select('wholesaler_products.name', 'wholesaler_products.code',
                    DB::raw('SUM(wholesaler_order_items.quantity) as total_quantity'),
                    DB::raw('SUM(wholesaler_order_items.total_price) as total_revenue')
                )
                ->groupBy('wholesaler_order_items.product_id', 'wholesaler_products.name', 'wholesaler_products.code')
                ->orderBy('total_quantity', 'desc')
                ->limit(10)
                ->get();

            // Top wholesalers
            $topWholesalers = Wholesaler::select('wholesalers.*',
                DB::raw('COUNT(wholesaler_orders.id) as total_orders'),
                DB::raw('SUM(CASE WHEN wholesaler_orders.status = "completed" THEN wholesaler_orders.total_amount ELSE 0 END) as total_revenue')
            )
                ->leftJoin('wholesaler_orders', 'wholesalers.id', '=', 'wholesaler_orders.wholesaler_id')
                ->groupBy('wholesalers.id')
                ->orderBy('total_revenue', 'desc')
                ->limit(10)
                ->get();

            return view('employee.analytics.index', compact('monthlyRevenue', 'topProducts', 'topWholesalers'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading analytics: ' . $e->getMessage());
        }
    }

    // Employee Profile
    public function profile()
    {
        try {
            $employee = Auth::guard('employee')->user();
            return view('employee.profile.profile', compact('employee'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading profile: ' . $e->getMessage());
        }
    }

    // Update Profile
    public function updateProfile(Request $request)
    {
        try {
            $employee = Auth::guard('employee')->user();

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:employees,email,' . $employee->id,
                'mobile_no' => 'required|string|max:15',
                'whatsapp_no' => 'nullable|string|max:15',
                'address' => 'required|string|max:500',
                'post_code' => 'required|string|max:10',
                'district' => 'required|string|max:100',
                'current_password' => 'nullable|required_with:new_password',
                'new_password' => 'nullable|min:6|confirmed',
            ]);

            $data = $request->only(['name', 'email', 'mobile_no', 'whatsapp_no', 'address', 'post_code', 'district']);

            // Update password if provided
            if ($request->filled('new_password')) {
                if (!Hash::check($request->current_password, $employee->password)) {
                    return redirect()->back()->with('error', 'Current password is incorrect.');
                }
                $data['password'] = Hash::make($request->new_password);
                $data['view_password'] = $request->new_password;
            }

            $employee->update($data);

            // Update user table if exists
            $user = User::where('username', $employee->username)->first();
            if ($user) {
                $user->update([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => $data['password'] ?? $user->password,
                    'view_password' => $data['view_password'] ?? $user->view_password,
                ]);
            }

            return redirect()->back()->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    }

    // Export Reports
    public function exportReport(Request $request)
    {
        try {
            $type = $request->get('type', 'orders');
            $format = $request->get('format', 'csv');

            switch ($type) {
                case 'wholesaler_products':
                    $data = WholesalerProduct::with(['wholesaler', 'category', 'brand'])->get();
                    $fileName = 'wholesaler_products_' . date('Y-m-d') . '.' . $format;
                    break;

                case 'wholesaler_orders':
                    $data = WholesalerOrder::with(['wholesaler', 'items'])->get();
                    $fileName = 'wholesaler_orders_' . date('Y-m-d') . '.' . $format;
                    break;

                case 'seller_orders':
                    $data = Order::with(['seller'])->get();
                    $fileName = 'seller_orders_' . date('Y-m-d') . '.' . $format;
                    break;

                default:
                    return redirect()->back()->with('error', 'Invalid report type.');
            }

            if ($format === 'csv') {
                return $this->exportToCSV($data, $fileName);
            } else {
                return $this->exportToExcel($data, $fileName);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error exporting report: ' . $e->getMessage());
        }
    }

    private function exportToCSV($data, $fileName)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');

            // Write headers
            if (count($data) > 0) {
                fputcsv($file, array_keys((array) $data[0]));
            }

            // Write data
            foreach ($data as $row) {
                fputcsv($file, (array) $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportToExcel($data, $fileName)
    {
        // You'll need to implement Excel export logic here
        // You can use Maatwebsite/Laravel-Excel package or similar
        return redirect()->back()->with('error', 'Excel export not implemented yet.');
    }

    // Bulk Price Update
    public function bulkPriceUpdate(Request $request)
    {
        try {
            $request->validate([
                'products' => 'required|array',
                'price_type' => 'required|in:selling_price,wholesale_price,display_price',
                'update_type' => 'required|in:fixed,percentage',
                'value' => 'required|numeric|min:0',
                'notes' => 'nullable|string|max:500',
            ]);

            $updatedCount = 0;
            $employeeId = Auth::guard('employee')->user()->id;

            foreach ($request->products as $productId) {
                $product = WholesalerProduct::find($productId);
                if ($product) {
                    $oldPrice = $product->{$request->price_type};

                    if ($request->update_type === 'percentage') {
                        $percentage = $request->value;
                        $newPrice = $oldPrice * (1 + ($percentage / 100));
                    } else {
                        $newPrice = $request->value;
                    }

                    // Update product price
                    $product->update([
                        $request->price_type => $newPrice,
                        'last_price_update_by' => $employeeId,
                        'last_price_update_at' => now(),
                    ]);

                    // Log price change
                    DB::table('price_change_logs')->insert([
                        'product_id' => $product->id,
                        'employee_id' => $employeeId,
                        'old_prices' => json_encode([$request->price_type => $oldPrice]),
                        'new_prices' => json_encode([$request->price_type => $newPrice]),
                        'notes' => $request->notes,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $updatedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully updated prices for {$updatedCount} products.",
                'updated_count' => $updatedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating prices: ' . $e->getMessage()
            ], 500);
        }
    }
}
