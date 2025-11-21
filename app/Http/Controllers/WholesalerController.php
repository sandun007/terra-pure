<?php

namespace App\Http\Controllers;

use App\Models\Wholesaler;
use App\Models\Product;
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

class WholesalerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:wholesaler');
    }

    public function dashboard()
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        $totalProducts = Product::where('wholesaler_id', $wholesaler->id)->count();
        $totalOrders = Order::where('wholesaler_id', $wholesaler->id)->count();
        $confirmedOrders = Order::where('wholesaler_id', $wholesaler->id)
            ->where('status', 'confirmed')
            ->count();
        $totalRevenue = Order::where('wholesaler_id', $wholesaler->id)
            ->where('status', 'completed')
            ->sum('total_amount');

        // Get monthly order data for the chart
        $monthlyOrders = Order::where('wholesaler_id', $wholesaler->id)
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count, SUM(total_amount) as revenue')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        return view('wholesaler.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'confirmedOrders',
            'totalRevenue',
            'wholesaler',
            'monthlyOrders'
        ));
    }

    // Shop Management
    public function shop()
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $shop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();

        if (!$shop) {
            return redirect()->route('wholesaler.shop.edit');
        }

        return view('wholesaler.shop.index', compact('shop'));
    }

    public function editShop()
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $shop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();

        return view('wholesaler.shop.edit', compact('shop'));
    }

    public function updateShop(Request $request)
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        $validated = $request->validate([
            'shop_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
            'zip_code' => 'nullable|string',
            'return_policy' => 'nullable|string',
            'shipping_policy' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $shop = WholesalerShop::where('wholesaler_id', $wholesaler->id)->first();

        if (!$shop) {
            $validated['wholesaler_id'] = $wholesaler->id;
            $validated['shop_slug'] = Str::slug($validated['shop_name']) . '-' . uniqid('', true);
            $shop = WholesalerShop::create($validated);
        } else {
            $shop->update($validated);
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($shop->logo) {
                Storage::disk('public')->delete($shop->logo);
            }
            $shop->logo = $request->file('logo')->store('wholesaler/shop/logo', 'public');
        }

        // Handle banner upload
        if ($request->hasFile('banner')) {
            if ($shop->banner) {
                Storage::disk('public')->delete($shop->banner);
            }
            $shop->banner = $request->file('banner')->store('wholesaler/shop/banner', 'public');
        }

        $shop->save();

        return redirect()->route('wholesaler.shop')->with('success', 'Shop updated successfully.');
    }

    // Product Management
    public function products()
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $products = Product::where('wholesaler_id', $wholesaler->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('wholesaler.products.index', compact('products'));
    }

    public function createProduct()
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $categories = Category::where('wholesaler_id', $wholesaler->id)->get();
        $brands = Brand::where('wholesaler_id', $wholesaler->id)->get();

        return view('wholesaler.products.create', compact('categories', 'brands'));
    }

    public function storeProduct(Request $request)
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'wholesale_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_order_quantity' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'nullable|string',
            'brand' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string',
            'features' => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('wholesaler/products', 'public');
        }

        Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'display_price' => $validated['price'],
            'wholesale_price' => $validated['wholesale_price'],
            'qty' => $validated['stock'],
            'min_order_quantity' => $validated['min_order_quantity'],
            'wholesaler_id' => $wholesaler->id,
            'img' => $imagePath,
            'status' => 'active',
            'category' => $validated['category'] ?? null,
            'brand' => $validated['brand'] ?? null,
            'weight' => $validated['weight'] ?? null,
            'dimensions' => $validated['dimensions'] ?? null,
            'features' => $validated['features'] ?? null,
            'code' => 'PROD' . uniqid('', true),
        ]);

        return redirect()->route('wholesaler.products')->with('success', 'Product added successfully.');
    }

    public function editProduct($id)
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $product = Product::where('wholesaler_id', $wholesaler->id)
            ->where('id', $id)
            ->firstOrFail();
        $categories = Category::where('wholesaler_id', $wholesaler->id)->get();
        $brands = Brand::where('wholesaler_id', $wholesaler->id)->get();

        return view('wholesaler.products.edit', compact('product', 'categories', 'brands'));
    }

    public function updateProduct(Request $request, $id)
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $product = Product::where('wholesaler_id', $wholesaler->id)
            ->where('id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'wholesale_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_order_quantity' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'nullable|string',
            'brand' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string',
            'features' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->img) {
                Storage::disk('public')->delete($product->img);
            }
            $validated['img'] = $request->file('image')->store('wholesaler/products', 'public');
        }

        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'display_price' => $validated['price'],
            'wholesale_price' => $validated['wholesale_price'],
            'qty' => $validated['stock'],
            'min_order_quantity' => $validated['min_order_quantity'],
            'status' => $validated['status'],
            'img' => $validated['img'] ?? $product->img,
            'category' => $validated['category'] ?? null,
            'brand' => $validated['brand'] ?? null,
            'weight' => $validated['weight'] ?? null,
            'dimensions' => $validated['dimensions'] ?? null,
            'features' => $validated['features'] ?? null,
        ]);

        return redirect()->route('wholesaler.products')->with('success', 'Product updated successfully.');
    }

    public function destroyProduct($id)
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $product = Product::where('wholesaler_id', $wholesaler->id)
            ->where('id', $id)
            ->firstOrFail();

        // Delete image
        if ($product->img) {
            Storage::disk('public')->delete($product->img);
        }

        $product->delete();

        return redirect()->route('wholesaler.products')->with('success', 'Product deleted successfully.');
    }

    // Brand Management
    public function brands()
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $brands = Brand::where('wholesaler_id', $wholesaler->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('wholesaler.brands.index', compact('brands'));
    }

    public function createBrand()
    {
        return view('wholesaler.brands.create');
    }

    public function storeBrand(Request $request)
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,NULL,id,wholesaler_id,' . $wholesaler->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('wholesaler/brands', 'public');
        }

        Brand::create([
            'name' => $validated['name'],
            'img' => $imagePath,
            'wholesaler_id' => $wholesaler->id,
            'code' => 'BRAND' . uniqid('', true),
        ]);

        return redirect()->route('wholesaler.brands')->with('success', 'Brand added successfully.');
    }

    public function editBrand($id)
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $brand = Brand::where('wholesaler_id', $wholesaler->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('wholesaler.brands.edit', compact('brand'));
    }

    public function updateBrand(Request $request, $id)
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $brand = Brand::where('wholesaler_id', $wholesaler->id)
            ->where('id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $id . ',id,wholesaler_id,' . $wholesaler->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($brand->img) {
                Storage::disk('public')->delete($brand->img);
            }
            $validated['img'] = $request->file('image')->store('wholesaler/brands', 'public');
        }

        $brand->update($validated);

        return redirect()->route('wholesaler.brands')->with('success', 'Brand updated successfully.');
    }

    public function destroyBrand($id)
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $brand = Brand::where('wholesaler_id', $wholesaler->id)
            ->where('id', $id)
            ->firstOrFail();

        // Check if brand is used in products
        $productCount = Product::where('brand', $brand->name)
            ->where('wholesaler_id', $wholesaler->id)
            ->count();

        if ($productCount > 0) {
            return redirect()->back()->with('error', 'Cannot delete brand. It is being used in ' . $productCount . ' product(s).');
        }

        // Delete image
        if ($brand->img) {
            Storage::disk('public')->delete($brand->img);
        }

        $brand->delete();

        return redirect()->route('wholesaler.brands')->with('success', 'Brand deleted successfully.');
    }

    // Category Management
    public function categories()
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $categories = Category::where('wholesaler_id', $wholesaler->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('wholesaler.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('wholesaler.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,NULL,id,wholesaler_id,' . $wholesaler->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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

        return redirect()->route('wholesaler.categories')->with('success', 'Category added successfully.');
    }

    public function editCategory($id)
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $category = Category::where('wholesaler_id', $wholesaler->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('wholesaler.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, $id)
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $category = Category::where('wholesaler_id', $wholesaler->id)
            ->where('id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id . ',id,wholesaler_id,' . $wholesaler->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->img) {
                Storage::disk('public')->delete($category->img);
            }
            $validated['img'] = $request->file('image')->store('wholesaler/categories', 'public');
        }

        $category->update($validated);

        return redirect()->route('wholesaler.categories')->with('success', 'Category updated successfully.');
    }

    public function destroyCategory($id)
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $category = Category::where('wholesaler_id', $wholesaler->id)
            ->where('id', $id)
            ->firstOrFail();

        // Check if category is used in products
        $productCount = Product::where('category', $category->name)
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

        return redirect()->route('wholesaler.categories')->with('success', 'Category deleted successfully.');
    }

    // Order Management
    public function orders(Request $request)
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $query = Order::where('wholesaler_id', $wholesaler->id);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        return view('wholesaler.orders.index', compact('orders'));
    }

    public function orderDetails($id)
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $order = Order::where('wholesaler_id', $wholesaler->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('wholesaler.orders.details', compact('order'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $order = Order::where('wholesaler_id', $wholesaler->id)
            ->where('id', $id)
            ->firstOrFail();

        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    // Payment Management
    public function payments()
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $payments = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalEarnings = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
            ->where('type', 'credit')
            ->where('status', 'completed')
            ->sum('amount');

        $pendingWithdrawals = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
            ->where('type', 'debit')
            ->where('status', 'pending')
            ->sum('amount');

        return view('wholesaler.payments.index', compact('payments', 'totalEarnings', 'pendingWithdrawals'));
    }

    public function paymentMethods()
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $paymentMethods = WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('wholesaler.payments.methods', compact('paymentMethods'));
    }

    public function createPaymentMethod()
    {
        return view('wholesaler.payments.create-method');
    }

    public function storePaymentMethod(Request $request)
    {
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
        ]);

        return redirect()->route('wholesaler.payments.methods')->with('success', 'Payment method added successfully.');
    }

    public function editPaymentMethod($id)
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $paymentMethod = WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('wholesaler.payments.edit-method', compact('paymentMethod'));
    }

    public function updatePaymentMethod(Request $request, $id)
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $paymentMethod = WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
            ->where('id', $id)
            ->firstOrFail();

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

        return redirect()->route('wholesaler.payments.methods')->with('success', 'Payment method updated successfully.');
    }

    public function destroyPaymentMethod($id)
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $paymentMethod = WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
            ->where('id', $id)
            ->firstOrFail();

        if ($paymentMethod->is_default) {
            return redirect()->back()->with('error', 'Cannot delete default payment method.');
        }

        $paymentMethod->delete();

        return redirect()->route('wholesaler.payments.methods')->with('success', 'Payment method deleted successfully.');
    }

    public function withdraw()
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $paymentMethods = WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
            ->where('status', 'active')
            ->get();

        $availableBalance = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
            ->where('type', 'credit')
            ->where('status', 'completed')
            ->sum('amount');

        $pendingWithdrawals = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
            ->where('type', 'debit')
            ->where('status', 'pending')
            ->sum('amount');

        $netBalance = $availableBalance - $pendingWithdrawals;

        return view('wholesaler.payments.withdraw', compact('paymentMethods', 'netBalance'));
    }

    public function processWithdraw(Request $request)
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        $validated = $request->validate([
            'amount' => 'required|numeric|min:100',
            'payment_method_id' => 'required|exists:wholesaler_payment_methods,id',
            'notes' => 'nullable|string',
        ]);

        // Check available balance
        $availableBalance = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
            ->where('type', 'credit')
            ->where('status', 'completed')
            ->sum('amount');

        $pendingWithdrawals = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
            ->where('type', 'debit')
            ->where('status', 'pending')
            ->sum('amount');

        $netBalance = $availableBalance - $pendingWithdrawals;

        if ($validated['amount'] > $netBalance) {
            return redirect()->back()->with('error', 'Insufficient balance. Available: RS. ' . number_format($netBalance, 2));
        }

        $paymentMethod = WholesalerPaymentMethod::findOrFail($validated['payment_method_id']);

        // Create withdrawal request
        WholesalerPayment::create([
            'wholesaler_id' => $wholesaler->id,
            'transaction_id' => 'WDR' . uniqid('', true),
            'amount' => $validated['amount'],
            'type' => 'debit',
            'payment_method' => $paymentMethod->method_type . ' - ' . $paymentMethod->account_number,
            'status' => 'pending',
            'description' => $validated['notes'] ?? 'Withdrawal request',
        ]);

        return redirect()->route('wholesaler.payments')->with('success', 'Withdrawal request submitted successfully.');
    }

    // Profile Management
    public function profile()
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        // Add these calculations
        $totalProducts = Product::where('wholesaler_id', $wholesaler->id)->count();
        $totalOrders = Order::where('wholesaler_id', $wholesaler->id)->count();

        return view('wholesaler.profile', compact('wholesaler', 'totalProducts', 'totalOrders'));
    }

    public function updateProfile(Request $request)
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:wholesalers,email,' . $wholesaler->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'business_name' => 'required|string|max:255',
            'business_reg_no' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:100',
            'img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('img')) {
            // Delete old image
            if ($wholesaler->img) {
                Storage::disk('public')->delete($wholesaler->img);
            }
            $validated['img'] = $request->file('img')->store('wholesaler/profile', 'public');
        }

        $wholesaler->update($validated);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
