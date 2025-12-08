<?php

namespace App\Http\Controllers;

use App\Models\Wholesaler;
use App\Models\WholesalerProduct;
use App\Models\Order;
use App\Models\Category;
use App\Models\Brand;
use App\Models\WholesalerShop;
use App\Models\WholesalerPayment;
use App\Models\WholesalerPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class WholesalerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:wholesaler');
    }

    public function dashboard()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $stats = [
                'total_products' => WholesalerProduct::where('wholesaler_id', $wholesaler->id)->count(),
                'total_orders' => Order::where('wholesaler_id', $wholesaler->id)->count(),
                'confirmed_orders' => Order::where('wholesaler_id', $wholesaler->id)
                    ->where('status', 'confirmed')
                    ->count(),
                'total_revenue' => Order::where('wholesaler_id', $wholesaler->id)
                    ->where('status', 'completed')
                    ->sum('total_amount'),
                'pending_orders' => Order::where('wholesaler_id', $wholesaler->id)
                    ->where('status', 'pending')
                    ->count(),
            ];

            // Payment statistics for dashboard
            $paymentStats = $this->calculatePaymentStats($wholesaler->id);

            // Recent orders for dashboard
            $recentOrders = Order::where('wholesaler_id', $wholesaler->id)
                ->with('customer')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Low stock products
            $lowStockProducts = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('qty', '<', 10)
                ->where('status', 'active')
                ->limit(5)
                ->get();

            // Recent payments
            $recentPayments = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return view('wholesaler.dashboard', compact(
                'wholesaler',
                'stats',
                'paymentStats',
                'recentOrders',
                'lowStockProducts',
                'recentPayments'
            ));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading dashboard: ' . $e->getMessage());
        }
    }

    // Shop Management
    public function shopIndex()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $shop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();

            $stats = [
                'total_products' => WholesalerProduct::where('wholesaler_id', $wholesaler->id)->count(),
                'total_orders' => Order::where('wholesaler_id', $wholesaler->id)->count(),
                'total_revenue' => Order::where('wholesaler_id', $wholesaler->id)
                    ->where('status', 'completed')
                    ->sum('total_amount'),
                'active_products' => WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                    ->where('status', 'active')
                    ->count(),
            ];

            return view('wholesaler.shop.index', compact('shop', 'stats'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading shop: ' . $e->getMessage());
        }
    }

    public function storeShop(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            // Check if shop already exists
            $existingShop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();
            if ($existingShop) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a shop. You can only update your existing shop.'
                ], 400);
            }

            $validated = $request->validate([
                'shop_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'contact_email' => 'required|email',
                'contact_phone' => 'required|string|max:20',
                'address' => 'required|string',
                'city' => 'required|string|max:100',
                'state' => 'required|string|max:100',
                'country' => 'required|string|max:100',
                'zip_code' => 'required|string|max:20',
                'return_policy' => 'nullable|string',
                'shipping_policy' => 'nullable|string',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            ]);

            $validated['wholesaler_id'] = $wholesaler->id;
            $validated['shop_slug'] = Str::slug($validated['shop_name']) . '-' . uniqid('', true);
            $validated['status'] = 'active';

            // Handle file uploads
            if ($request->hasFile('logo')) {
                $validated['logo'] = $request->file('logo')->store('wholesaler/shop/logo', 'public');
            }

            if ($request->hasFile('banner')) {
                $validated['banner'] = $request->file('banner')->store('wholesaler/shop/banner', 'public');
            }

            $shop = WholesalerShop::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Shop created successfully!',
                'shop' => $shop
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating shop: ' . $e->getMessage()
            ], 500);
        }
    }

    public function editShop()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $shop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();

            if (!$shop) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shop not found. Please create a shop first.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'shop' => $shop
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading shop: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateShop(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $shop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();

            if (!$shop) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shop not found.'
                ], 404);
            }

            $validated = $request->validate([
                'shop_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'contact_email' => 'required|email',
                'contact_phone' => 'required|string|max:20',
                'address' => 'required|string',
                'city' => 'required|string|max:100',
                'state' => 'required|string|max:100',
                'country' => 'required|string|max:100',
                'zip_code' => 'required|string|max:20',
                'return_policy' => 'nullable|string',
                'shipping_policy' => 'nullable|string',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            ]);

            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo
                if ($shop->logo) {
                    Storage::disk('public')->delete($shop->logo);
                }
                $validated['logo'] = $request->file('logo')->store('wholesaler/shop/logo', 'public');
            } else {
                $validated['logo'] = $shop->logo;
            }

            // Handle banner upload
            if ($request->hasFile('banner')) {
                // Delete old banner
                if ($shop->banner) {
                    Storage::disk('public')->delete($shop->banner);
                }
                $validated['banner'] = $request->file('banner')->store('wholesaler/shop/banner', 'public');
            } else {
                $validated['banner'] = $shop->banner;
            }

            $shop->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Shop updated successfully!',
                'shop' => $shop
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating shop: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyShop(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $shop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();

            if (!$shop) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shop not found.'
                ], 404);
            }

            // Delete associated files
            if ($shop->logo) {
                Storage::disk('public')->delete($shop->logo);
            }
            if ($shop->banner) {
                Storage::disk('public')->delete($shop->banner);
            }

            $shop->delete();

            return response()->json([
                'success' => true,
                'message' => 'Shop deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting shop: ' . $e->getMessage()
            ], 500);
        }
    }

    // Order Management
    public function ordersIndex(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $query = Order::where('wholesaler_id', $wholesaler->id)
                ->with(['customer', 'orderItems.product']);

            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            $orders = $query->orderBy('created_at', 'desc')->get();

            return view('wholesaler.orders.index', compact('orders'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading orders: ' . $e->getMessage());
        }
    }

    public function orderDetails($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $order = Order::where('wholesaler_id', $wholesaler->id)
                ->with(['customer', 'orderItems.product'])
                ->findOrFail($id);

            return view('wholesaler.orders.details', compact('order'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Order not found: ' . $e->getMessage());
        }
    }

    public function updateOrderStatus(Request $request, $id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $order = Order::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            $request->validate([
                'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            ]);

            $order->update(['status' => $request->status]);

            return redirect()->back()->with('success', 'Order status updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating order status: ' . $e->getMessage());
        }
    }

    // Brand Management
    public function brandsIndex()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $brands = Brand::where('wholesaler_id', $wholesaler->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('wholesaler.brands.index', compact('brands'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading brands: ' . $e->getMessage());
        }
    }

    public function createBrand()
    {
        return view('wholesaler.brands.create');
    }

    public function storeBrand(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:brands,name,NULL,id,wholesaler_id,' . $wholesaler->id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'required|in:active,inactive',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('wholesaler/brands', 'public');
            }

            Brand::create([
                'name' => $validated['name'],
                'image' => $imagePath,
                'status' => $validated['status'],
                'wholesaler_id' => $wholesaler->id,
                'code' => 'BRAND' . uniqid('', true),
            ]);

            return redirect()->route('wholesaler.brands.index')->with('success', 'Brand created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating brand: ' . $e->getMessage());
        }
    }

    public function editBrand($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $brand = Brand::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            return view('wholesaler.brands.edit', compact('brand'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Brand not found: ' . $e->getMessage());
        }
    }

    public function updateBrand(Request $request, $id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $brand = Brand::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:brands,name,' . $id . ',id,wholesaler_id,' . $wholesaler->id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'required|in:active,inactive',
            ]);

            if ($request->hasFile('image')) {
                // Delete old image
                if ($brand->image) {
                    Storage::disk('public')->delete($brand->image);
                }
                $validated['image'] = $request->file('image')->store('wholesaler/brands', 'public');
            } else {
                $validated['image'] = $brand->image;
            }

            $brand->update($validated);

            return redirect()->route('wholesaler.brands.index')->with('success', 'Brand updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating brand: ' . $e->getMessage());
        }
    }

    public function destroyBrand($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $brand = Brand::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            // Check if brand is used in products
            $productCount = WholesalerProduct::where('brand_id', $id)
                ->where('wholesaler_id', $wholesaler->id)
                ->count();

            if ($productCount > 0) {
                return redirect()->back()->with('error', 'Cannot delete brand. It is being used in ' . $productCount . ' product(s).');
            }

            // Delete image
            if ($brand->image) {
                Storage::disk('public')->delete($brand->image);
            }

            $brand->delete();

            return redirect()->route('wholesaler.brands.index')->with('success', 'Brand deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting brand: ' . $e->getMessage());
        }
    }

    // Category Management
    public function categoriesIndex()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $categories = Category::where('wholesaler_id', $wholesaler->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('wholesaler.categories.index', compact('categories'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading categories: ' . $e->getMessage());
        }
    }

    public function createCategory()
    {
        return view('wholesaler.categories.create');
    }

    public function storeCategory(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,NULL,id,wholesaler_id,' . $wholesaler->id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'required|in:active,inactive',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('wholesaler/categories', 'public');
            }

            Category::create([
                'name' => $validated['name'],
                'img' => $imagePath,
                'status' => $validated['status'],
                'wholesaler_id' => $wholesaler->id,
                'code' => 'CAT' . uniqid('', true),
            ]);

            return redirect()->route('wholesaler.categories.index')->with('success', 'Category created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating category: ' . $e->getMessage());
        }
    }

    public function editCategory($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $category = Category::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            return view('wholesaler.categories.edit', compact('category'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Category not found: ' . $e->getMessage());
        }
    }

    public function updateCategory(Request $request, $id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $category = Category::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $id . ',id,wholesaler_id,' . $wholesaler->id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'required|in:active,inactive',
            ]);

            if ($request->hasFile('image')) {
                // Delete old image
                if ($category->img) {
                    Storage::disk('public')->delete($category->img);
                }
                $validated['img'] = $request->file('image')->store('wholesaler/categories', 'public');
            } else {
                $validated['img'] = $category->img;
            }

            $category->update($validated);

            return redirect()->route('wholesaler.categories.index')->with('success', 'Category updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating category: ' . $e->getMessage());
        }
    }

    public function destroyCategory($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $category = Category::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            // Check if category is used in products
            $productCount = WholesalerProduct::where('category_id', $id)
                ->where('wholesaler_id', $wholesaler->id)
                ->count();

            if ($productCount > 0) {
                return redirect()->back()->with('error', 'Cannot delete category. It is being used in ' . $productCount . ' product(s).');
            }

            // Delete image
            if ($category->img) {
                Storage::disk('public')->delete($category->img);
            }

            $category->delete();

            return redirect()->route('wholesaler.categories.index')->with('success', 'Category deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting category: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // PAYMENT MANAGEMENT METHODS
    // =========================================================================

    /**
     * Display payment dashboard with statistics
     */
    public function paymentsIndex()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            // Payment statistics
            $totalEarnings = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
                ->where('type', 'credit')
                ->where('status', 'completed')
                ->sum('amount');

            $pendingWithdrawals = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
                ->where('type', 'debit')
                ->where('status', 'pending')
                ->sum('amount');

            $totalWithdrawals = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
                ->where('type', 'debit')
                ->where('status', 'completed')
                ->sum('amount');

            $netBalance = $totalEarnings - $totalWithdrawals - $pendingWithdrawals;
            $availableBalance = max(0, $netBalance);

            // Recent payments
            $recentPayments = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Payment summary by type
            $paymentSummary = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
                ->select('type', 'status', DB::raw('SUM(amount) as total_amount'))
                ->groupBy('type', 'status')
                ->get();

            return view('wholesaler.payments.index', compact(
                'totalEarnings',
                'pendingWithdrawals',
                'totalWithdrawals',
                'availableBalance',
                'recentPayments',
                'paymentSummary'
            ));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading payment dashboard: ' . $e->getMessage());
        }
    }

    /**
     * Display payment history with filtering
     */
    public function paymentHistory(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $query = WholesalerPayment::where('wholesaler_id', $wholesaler->id);

            // Apply filters
            if ($request->has('type') && $request->type != '') {
                $query->where('type', $request->type);
            }

            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            if ($request->has('payment_type') && $request->payment_type != '') {
                $query->where('payment_type', $request->payment_type);
            }

            if ($request->has('date_from') && $request->date_from != '') {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to != '') {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $payments = $query->orderBy('created_at', 'desc')->paginate(20);

            return view('wholesaler.payments.history', compact('payments'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading payment history: ' . $e->getMessage());
        }
    }

    /**
     * Display payment details
     */
    public function paymentShow($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $payment = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            return view('wholesaler.payments.show', compact('payment'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment not found: ' . $e->getMessage());
        }
    }

    /**
     * Payment methods management
     */
    public function paymentMethods()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $paymentMethods = WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
                ->orderBy('is_default', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('wholesaler.payments.methods', compact('paymentMethods'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading payment methods: ' . $e->getMessage());
        }
    }

    public function createPaymentMethod()
    {
        return view('wholesaler.payments.create-method');
    }

    public function storePaymentMethod(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $validated = $request->validate([
                'method_type' => 'required|in:bank,mobile_money',
                'account_name' => 'required|string|max:255',
                'account_number' => 'required|string|max:255',
                'bank_name' => 'required_if:method_type,bank|string|max:255',
                'branch' => 'nullable|string|max:255',
                'mobile_provider' => 'required_if:method_type,mobile_money|string|max:255',
                'is_default' => 'boolean',
            ]);

            // If setting as default, remove default from other methods
            if ($request->has('is_default') && $request->is_default) {
                WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
                    ->update(['is_default' => false]);
            }

            WholesalerPaymentMethod::create([
                'wholesaler_id' => $wholesaler->id,
                'method_type' => $validated['method_type'],
                'account_name' => $validated['account_name'],
                'account_number' => $validated['account_number'],
                'bank_name' => $validated['bank_name'] ?? null,
                'branch' => $validated['branch'] ?? null,
                'mobile_provider' => $validated['mobile_provider'] ?? null,
                'is_default' => $request->has('is_default') ? $request->is_default : false,
                'status' => 'active',
            ]);

            return redirect()->route('wholesaler.payments.methods')->with('success', 'Payment method added successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating payment method: ' . $e->getMessage());
        }
    }

    public function editPaymentMethod($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $paymentMethod = WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            return view('wholesaler.payments.edit-method', compact('paymentMethod'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment method not found: ' . $e->getMessage());
        }
    }

    public function updatePaymentMethod(Request $request, $id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $paymentMethod = WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            $validated = $request->validate([
                'method_type' => 'required|in:bank,mobile_money',
                'account_name' => 'required|string|max:255',
                'account_number' => 'required|string|max:255',
                'bank_name' => 'required_if:method_type,bank|string|max:255',
                'branch' => 'nullable|string|max:255',
                'mobile_provider' => 'required_if:method_type,mobile_money|string|max:255',
                'is_default' => 'boolean',
            ]);

            // If setting as default, remove default from other methods
            if ($request->has('is_default') && $request->is_default) {
                WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
                    ->where('id', '!=', $id)
                    ->update(['is_default' => false]);
            }

            $paymentMethod->update([
                'method_type' => $validated['method_type'],
                'account_name' => $validated['account_name'],
                'account_number' => $validated['account_number'],
                'bank_name' => $validated['bank_name'] ?? null,
                'branch' => $validated['branch'] ?? null,
                'mobile_provider' => $validated['mobile_provider'] ?? null,
                'is_default' => $request->has('is_default') ? $request->is_default : $paymentMethod->is_default,
            ]);

            return redirect()->route('wholesaler.payments.methods')->with('success', 'Payment method updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating payment method: ' . $e->getMessage());
        }
    }

    public function destroyPaymentMethod($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $paymentMethod = WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            if ($paymentMethod->is_default) {
                return redirect()->back()->with('error', 'Cannot delete default payment method.');
            }

            $paymentMethod->delete();

            return redirect()->route('wholesaler.payments.methods')->with('success', 'Payment method deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting payment method: ' . $e->getMessage());
        }
    }

    /**
     * Display withdrawal page
     */
    public function withdraw()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $paymentMethods = WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'active')
                ->get();

            $availableBalance = $this->calculateAvailableBalance($wholesaler->id);

            // Withdrawal limits
            $minWithdrawal = 100; // Minimum withdrawal amount
            $maxWithdrawal = $availableBalance;

            return view('wholesaler.payments.withdraw', compact(
                'paymentMethods',
                'availableBalance',
                'minWithdrawal',
                'maxWithdrawal'
            ));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading withdrawal page: ' . $e->getMessage());
        }
    }

    /**
     * Process withdrawal request
     */
    public function processWithdraw(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $validated = $request->validate([
                'amount' => 'required|numeric|min:100',
                'payment_method_id' => 'required|exists:wholesaler_payment_methods,id',
                'notes' => 'nullable|string|max:500',
            ]);

            $availableBalance = $this->calculateAvailableBalance($wholesaler->id);

            if ($validated['amount'] > $availableBalance) {
                return redirect()->back()->with('error',
                    'Insufficient balance. Available: RS. ' . number_format($availableBalance, 2)
                );
            }

            $paymentMethod = WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($validated['payment_method_id']);

            DB::transaction(function () use ($wholesaler, $validated, $paymentMethod) {
                // Create withdrawal request
                WholesalerPayment::create([
                    'wholesaler_id' => $wholesaler->id,
                    'transaction_id' => 'WDR' . time() . strtoupper(Str::random(6)),
                    'amount' => $validated['amount'],
                    'type' => 'debit',
                    'payment_type' => 'withdrawal',
                    'payment_method' => $paymentMethod->method_type . ' - ' . $paymentMethod->account_number,
                    'status' => 'pending',
                    'description' => 'Withdrawal request',
                    'notes' => $validated['notes'],
                    'metadata' => [
                        'payment_method_id' => $paymentMethod->id,
                        'account_details' => [
                            'account_name' => $paymentMethod->account_name,
                            'account_number' => $paymentMethod->account_number,
                            'method_type' => $paymentMethod->method_type,
                        ]
                    ]
                ]);
            });

            return redirect()->route('wholesaler.payments.index')
                ->with('success', 'Withdrawal request submitted successfully! It will be processed within 24-48 hours.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error processing withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * Cancel pending withdrawal
     */
    public function cancelWithdraw($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $withdrawal = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
                ->where('id', $id)
                ->where('type', 'debit')
                ->where('status', 'pending')
                ->firstOrFail();

            $withdrawal->update(['status' => 'cancelled']);

            return redirect()->back()->with('success', 'Withdrawal request cancelled successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error cancelling withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * Export payments to CSV
     */
    public function exportPayments(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $payments = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
                ->when($request->date_from, function($query) use ($request) {
                    return $query->whereDate('created_at', '>=', $request->date_from);
                })
                ->when($request->date_to, function($query) use ($request) {
                    return $query->whereDate('created_at', '<=', $request->date_to);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $fileName = 'payments-' . date('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=$fileName",
            ];

            $callback = function() use ($payments) {
                $file = fopen('php://output', 'w');

                // Add headers
                fputcsv($file, [
                    'Date',
                    'Transaction ID',
                    'Type',
                    'Amount',
                    'Payment Type',
                    'Status',
                    'Description'
                ]);

                // Add data
                foreach ($payments as $payment) {
                    fputcsv($file, [
                        $payment->created_at->format('Y-m-d H:i:s'),
                        $payment->transaction_id,
                        ucfirst($payment->type),
                        'RS. ' . number_format($payment->amount, 2),
                        ucfirst(str_replace('_', ' ', $payment->payment_type)),
                        ucfirst($payment->status),
                        $payment->description
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error exporting payments: ' . $e->getMessage());
        }
    }

    // Profile Management
    public function profile()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $stats = [
                'total_products' => WholesalerProduct::where('wholesaler_id', $wholesaler->id)->count(),
                'total_orders' => Order::where('wholesaler_id', $wholesaler->id)->count(),
                'total_revenue' => Order::where('wholesaler_id', $wholesaler->id)
                    ->where('status', 'completed')
                    ->sum('total_amount'),
            ];

            return view('wholesaler.profile', compact('wholesaler', 'stats'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading profile: ' . $e->getMessage());
        }
    }

// In WholesalerController.php - updateProfile method
    public function updateProfile(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:wholesalers,email,' . $wholesaler->id,
                'phone' => 'required|string|max:20',
                'whatsapp' => 'nullable|string|max:20',
                'business_name' => 'required|string|max:255',
                'address' => 'required|string',
                'city' => 'required|string|max:100',
                'district' => 'required|string|max:100',
                'postal_code' => 'required|string|max:20',
                'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            // Handle profile image upload
            if ($request->hasFile('img')) {
                // Delete old image if exists
                if ($wholesaler->img && Storage::disk('public')->exists($wholesaler->img)) {
                    Storage::disk('public')->delete($wholesaler->img);
                }

                // Store new image
                $imagePath = $request->file('img')->store('wholesaler/profile', 'public');

                // Optimize image (optional - requires intervention/image package)
                // $image = Image::make(storage_path('app/public/' . $imagePath))
                //     ->fit(400, 400)
                //     ->save(storage_path('app/public/' . $imagePath));

                $validated['img'] = $imagePath;
            }

            $wholesaler->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'wholesaler' => $wholesaler
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating profile: ' . $e->getMessage()
            ], 500);
        }
    }
    public function logout(Request $request)
    {
        Auth::guard('wholesaler')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/wholesaler/login');
    }

    // =========================================================================
    // PRIVATE HELPER METHODS
    // =========================================================================

    /**
     * Calculate available balance for withdrawal
     */
    private function calculateAvailableBalance($wholesalerId)
    {
        $totalCredits = WholesalerPayment::where('wholesaler_id', $wholesalerId)
            ->where('type', 'credit')
            ->where('status', 'completed')
            ->sum('amount');

        $totalCompletedDebits = WholesalerPayment::where('wholesaler_id', $wholesalerId)
            ->where('type', 'debit')
            ->where('status', 'completed')
            ->sum('amount');

        $pendingDebits = WholesalerPayment::where('wholesaler_id', $wholesalerId)
            ->where('type', 'debit')
            ->where('status', 'pending')
            ->sum('amount');

        $available = $totalCredits - $totalCompletedDebits - $pendingDebits;

        return max(0, $available);
    }

    /**
     * Calculate payment statistics for dashboard
     */
    private function calculatePaymentStats($wholesalerId)
    {
        $totalEarnings = WholesalerPayment::where('wholesaler_id', $wholesalerId)
            ->where('type', 'credit')
            ->where('status', 'completed')
            ->sum('amount');

        $pendingWithdrawals = WholesalerPayment::where('wholesaler_id', $wholesalerId)
            ->where('type', 'debit')
            ->where('status', 'pending')
            ->sum('amount');

        $totalWithdrawals = WholesalerPayment::where('wholesaler_id', $wholesalerId)
            ->where('type', 'debit')
            ->where('status', 'completed')
            ->sum('amount');

        $netBalance = $totalEarnings - $totalWithdrawals - $pendingWithdrawals;
        $availableBalance = max(0, $netBalance);

        return [
            'total_earnings' => $totalEarnings,
            'pending_withdrawals' => $pendingWithdrawals,
            'total_withdrawals' => $totalWithdrawals,
            'net_balance' => $netBalance,
            'available_balance' => $availableBalance,
        ];
    }
}
