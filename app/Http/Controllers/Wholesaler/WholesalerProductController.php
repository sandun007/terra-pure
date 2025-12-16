<?php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use App\Models\WholesalerProduct;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class WholesalerProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:wholesaler');
    }

    public function index()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $products = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->with(['category', 'brand'])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('wholesaler.products.index', compact('products'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading products: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $categories = Category::where('wholesaler_id', $wholesaler->id)
                ->when(Schema::hasColumn('categories', 'status'), function($query) {
                    return $query->where('status', 'active');
                })
                ->get();

            $brands = Brand::where('wholesaler_id', $wholesaler->id)
                ->when(Schema::hasColumn('brands', 'status'), function($query) {
                    return $query->where('status', 'active');
                })
                ->get();

            $productCode = 'WHP' . date('Ymd') . strtoupper(Str::random(6));
            $code = $productCode;

            return view('wholesaler.products.create', compact('categories', 'brands', 'productCode', 'code'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading form: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $validated = $request->validate([
                'code' => 'required|string|unique:wholesaler_products,code',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'cost_price' => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0',
                'discount' => 'nullable|numeric|min:0|max:100',
                'packaging_cost' => 'nullable|numeric|min:0',
                'qty' => 'required|integer|min:0',
                'min_stock_level' => 'required|integer|min:1',
                'category_id' => 'nullable|exists:categories,id',
                'brand_id' => 'nullable|exists:brands,id',
                'features' => 'nullable|string',
                'barcode' => 'nullable|string|max:255|unique:wholesaler_products,barcode',
                'status' => 'required|in:active,inactive',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('wholesaler/products', 'public');
            }

            WholesalerProduct::create([
                'wholesaler_id' => $wholesaler->id,
                'code' => $validated['code'],
                'name' => $validated['name'],
                'description' => $validated['description'],
                'cost_price' => $validated['cost_price'],
                'selling_price' => $validated['selling_price'],
                'discount' => $validated['discount'] ?? 0,
                'packaging_cost' => $validated['packaging_cost'] ?? 0,
                'qty' => $validated['qty'],
                'min_stock_level' => $validated['min_stock_level'],
                'category_id' => $validated['category_id'],
                'brand_id' => $validated['brand_id'],
                'features' => $validated['features'],
                'barcode' => $validated['barcode'],
                'status' => $validated['status'],
                'image' => $imagePath,
            ]);

            return redirect()->route('wholesaler.products.index')->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating product: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->with(['category', 'brand', 'wholesaler'])
                ->findOrFail($id);

            return view('wholesaler.products.show', compact('product'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Product not found: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            $categories = Category::where('wholesaler_id', $wholesaler->id)
                ->when(Schema::hasColumn('categories', 'status'), function($query) {
                    return $query->where('status', 'active');
                })
                ->get();

            $brands = Brand::where('wholesaler_id', $wholesaler->id)
                ->when(Schema::hasColumn('brands', 'status'), function($query) {
                    return $query->where('status', 'active');
                })
                ->get();

            return view('wholesaler.products.edit', compact('product', 'categories', 'brands'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Product not found: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'cost_price' => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0',
                'discount' => 'nullable|numeric|min:0|max:100',
                'packaging_cost' => 'nullable|numeric|min:0',
                'qty' => 'required|integer|min:0',
                'min_stock_level' => 'required|integer|min:1',
                'category_id' => 'nullable|exists:categories,id',
                'brand_id' => 'nullable|exists:brands,id',
                'features' => 'nullable|string',
                'barcode' => 'nullable|string|max:255|unique:wholesaler_products,barcode,' . $id,
                'status' => 'required|in:active,inactive',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $validated['image'] = $request->file('image')->store('wholesaler/products', 'public');
            } else {
                $validated['image'] = $product->image;
            }

            $product->update($validated);

            return redirect()->route('wholesaler.products.index')->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating product: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            // Delete image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();

            return redirect()->route('wholesaler.products.index')->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $product = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            $request->validate([
                'status' => 'required|in:active,inactive',
            ]);

            $product->update(['status' => $request->status]);

            return redirect()->back()->with('success', 'Product status updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating status: ' . $e->getMessage());
        }
    }
}
