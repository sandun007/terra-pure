<?php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use App\Models\WholesalerProduct;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WholesalerProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:wholesaler');
    }

    /**
     * Generate product code in format DX/ITM/0001
     */
    private function generateProductCode(): string
    {
        $wholesaler = Auth::guard('wholesaler')->user();

        $last = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
            ->where('code', 'like', 'DX/ITM/%')
            ->orderBy('id', 'desc')
            ->value('code');

        if ($last) {
            $lastNumber = (int) substr($last, strrpos($last, '/') + 1);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'DX/ITM/' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate unique product code with retry logic
     */
    private function generateUniqueProductCode(): string
    {
        $maxAttempts = 5;
        $attempt = 0;

        while ($attempt < $maxAttempts) {
            $code = $this->generateProductCode();

            $exists = WholesalerProduct::where('wholesaler_id', Auth::guard('wholesaler')->id())
                ->where('code', $code)
                ->exists();

            if (!$exists) {
                return $code;
            }

            $attempt++;
        }

        // Fallback to timestamp-based code if all attempts fail
        return $this->generateProductCodeWithTimestamp();
    }

    /**
     * Fallback code generator with timestamp for race condition resolution
     */
    private function generateProductCodeWithTimestamp(): string
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        $timestamp = now()->format('ymdHis');
        return 'DX/ITM/' . $timestamp . rand(10, 99);
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

            // Generate product code for the form
            $productCode = $this->generateUniqueProductCode();

            return view('wholesaler.products.create', compact('categories', 'brands', 'productCode'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading form: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            DB::beginTransaction();

            // Validate all fields except code (code is auto-generated)
            $validated = $request->validate([
                'name'            => 'required|string|max:255',
                'description'     => 'nullable|string',
                'cost_price'      => 'required|numeric|min:0',
                'selling_price'   => 'required|numeric|min:0',
                'discount'        => 'nullable|numeric|min:0|max:100',
                'packaging_cost'  => 'nullable|numeric|min:0',
                'qty'             => 'required|integer|min:0',
                'min_stock_level' => 'required|integer|min:0',
                'category_id'     => 'nullable|exists:categories,id',
                'brand_id'        => 'nullable|exists:brands,id',
                'features'        => 'nullable|string',
                'barcode'         => 'nullable|string|max:255|unique:wholesaler_products,barcode',
                'status'          => 'required|in:active,inactive',
                'image'           => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('wholesaler/products', 'public');
            }

            // Generate unique product code
            $code = $this->generateUniqueProductCode();

            // Create the product
            $product = WholesalerProduct::create([
                'wholesaler_id'   => $wholesaler->id,
                'code'            => $code,
                'name'            => $validated['name'],
                'description'     => $validated['description'] ?? null,
                'cost_price'      => $validated['cost_price'],
                'selling_price'   => $validated['selling_price'],
                'discount'        => $validated['discount'] ?? 0,
                'packaging_cost'  => $validated['packaging_cost'] ?? 0,
                'qty'             => $validated['qty'],
                'min_stock_level' => $validated['min_stock_level'],
                'category_id'     => $validated['category_id'] ?? null,
                'brand_id'        => $validated['brand_id'] ?? null,
                'features'        => $validated['features'] ?? null,
                'barcode'         => $validated['barcode'] ?? null,
                'status'          => $validated['status'],
                'image'           => $imagePath,
            ]);

            DB::commit();

            return redirect()
                ->route('wholesaler.products.index')
                ->with('success', 'Product created successfully! Code: ' . $code);

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded image if product creation failed
            if (isset($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return redirect()
                ->back()
                ->with('error', 'Error creating product: ' . $e->getMessage())
                ->withInput();
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
                'min_stock_level' => 'required|integer|min:0',
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
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                $validated['image'] = $request->file('image')->store('wholesaler/products', 'public');
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
            if ($product->image && Storage::disk('public')->exists($product->image)) {
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
