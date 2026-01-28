<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerProduct;
use App\Models\SellerCategory;
use App\Models\SellerBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SellerProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:seller');
    }

    /**
     * Display a listing of products
     */
    public function index()
    {
        try {
            $seller = Auth::guard('seller')->user();

            $products = SellerProduct::where('seller_id', $seller->id)
                ->with(['category', 'brand'])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('seller.product.index', compact('products'));
        } catch (\Exception $e) {
            Log::error('Error loading products: ' . $e->getMessage());
            return redirect()->route('seller.dashboard')->with('error', 'Error loading products. Please try again.');
        }
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        try {
            $seller = Auth::guard('seller')->user();

            $categories = SellerCategory::where('seller_id', $seller->id)
                ->where('status', 'active')
                ->orderBy('name', 'asc')
                ->get();

            $brands = SellerBrand::where('seller_id', $seller->id)
                ->where('status', 'active')
                ->orderBy('name', 'asc')
                ->get();

            $code = $this->generateProductCode();

            return view('seller.product.create', compact('categories', 'brands', 'code'));
        } catch (\Exception $e) {
            Log::error('Error loading product create form: ' . $e->getMessage());
            return redirect()->route('seller.product.index')->with('error', 'Error loading form. Please try again.');
        }
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $seller = Auth::guard('seller')->user();

            // Use 'img' for validation (matching form field name)
            $validator = Validator::make($request->all(), [
                'code' => 'required|string|unique:seller_products,code',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'stock_price' => 'required|numeric|min:0',
                'display_price' => 'required|numeric|min:0',
                'discount' => 'nullable|numeric|min:0|max:100',
                'packaging_cost' => 'nullable|numeric|min:0',
                'qty' => 'required|integer|min:0',
                'min_qty' => 'required|integer|min:1',
                'category_id' => 'nullable|exists:seller_categories,id',
                'brand_id' => 'nullable|exists:seller_brands,id',
                'features' => 'nullable|string|max:2000', // FIXED: Added max length
                'barcode' => 'nullable|string|max:255|unique:seller_products,barcode',
                'type' => 'required|in:physical,digital',
                'img' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ], [
                'img.required' => 'Product image is required',
                'img.image' => 'File must be an image',
                'img.mimes' => 'Only JPG, PNG, GIF, and WEBP images are allowed',
                'img.max' => 'Image size must not exceed 2MB',
                'features.max' => 'Features text is too long (maximum 2000 characters)',
            ]);

            // Additional validation
            $validator->after(function ($validator) use ($request) {
                if ($request->display_price < $request->stock_price) {
                    $validator->errors()->add('display_price', 'Display price cannot be lower than stock price.');
                }
            });

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Please fix validation errors.'
                ], 422);
            }

            $validatedData = $validator->validated();

            // Handle image upload using 'img'
            $imagePath = null;
            if ($request->hasFile('img')) {
                $image = $request->file('img');
                $filename = 'product_' . time() . '_' . Str::slug($validatedData['name']) . '.' . $image->getClientOriginalExtension();

                // Store in public/seller/products directory
                $directory = 'seller/products';
                $imagePath = $image->storeAs($directory, $filename, 'public');

                if (!$imagePath) {
                    throw new \Exception('Failed to upload image.');
                }
            }

            // Create product - Truncate features if too long
            $productData = [
                'seller_id' => $seller->id,
                'seller_code' => $seller->code,
                'code' => $validatedData['code'],
                'name' => $validatedData['name'],
                'slug' => Str::slug($validatedData['name']),
                'description' => $validatedData['description'] ?? null,
                'stock_price' => $validatedData['stock_price'],
                'display_price' => $validatedData['display_price'],
                'discount' => $validatedData['discount'] ?? 0,
                'packaging_cost' => $validatedData['packaging_cost'] ?? 0,
                'qty' => $validatedData['qty'],
                'min_qty' => $validatedData['min_qty'],
                'category_id' => $validatedData['category_id'] ?? null,
                'brand_id' => $validatedData['brand_id'] ?? null,
                'features' => isset($validatedData['features']) ?
                    substr($validatedData['features'], 0, 2000) : null, // FIXED: Truncate to 2000 chars
                'barcode' => $validatedData['barcode'] ?? null,
                'type' => $validatedData['type'],
                'add_by' => 'seller',
                'update_by' => 'seller',
                'status' => 'active',
                'cancel' => false,
                'image' => $imagePath,
            ];

            $product = SellerProduct::create($productData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully!',
                'redirect' => route('seller.product.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product creation error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error creating product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit($id)
    {
        try {
            $seller = Auth::guard('seller')->user();

            $product = SellerProduct::where('seller_id', $seller->id)
                ->findOrFail($id);

            $categories = SellerCategory::where('seller_id', $seller->id)
                ->where('status', 'active')
                ->orderBy('name', 'asc')
                ->get();

            $brands = SellerBrand::where('seller_id', $seller->id)
                ->where('status', 'active')
                ->orderBy('name', 'asc')
                ->get();

            return view('seller.product.edit', compact('product', 'categories', 'brands'));
        } catch (\Exception $e) {
            return redirect()->route('seller.product.index')->with('error', 'Product not found.');
        }
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $seller = Auth::guard('seller')->user();

            $product = SellerProduct::where('seller_id', $seller->id)
                ->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'stock_price' => 'required|numeric|min:0',
                'display_price' => 'required|numeric|min:0',
                'discount' => 'nullable|numeric|min:0|max:100',
                'packaging_cost' => 'nullable|numeric|min:0',
                'qty' => 'required|integer|min:0',
                'min_qty' => 'required|integer|min:1',
                'category_id' => 'nullable|exists:seller_categories,id',
                'brand_id' => 'nullable|exists:seller_brands,id',
                'features' => 'nullable|string|max:2000', // FIXED: Added max length
                'barcode' => 'nullable|string|max:255|unique:seller_products,barcode,' . $id,
                'type' => 'required|in:physical,digital',
                'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $validatedData = $validator->validated();

            // Handle image upload
            if ($request->hasFile('img')) {
                // Delete old image
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }

                $image = $request->file('img');
                $filename = 'product_' . time() . '_' . Str::slug($validatedData['name']) . '.' . $image->getClientOriginalExtension();
                $directory = 'seller/products';
                $imagePath = $image->storeAs($directory, $filename, 'public');

                $product->image = $imagePath;
            }

            // Update product data - Truncate features if too long
            $updateData = [
                'name' => $validatedData['name'],
                'slug' => Str::slug($validatedData['name']),
                'description' => $validatedData['description'] ?? null,
                'stock_price' => $validatedData['stock_price'],
                'display_price' => $validatedData['display_price'],
                'discount' => $validatedData['discount'] ?? 0,
                'packaging_cost' => $validatedData['packaging_cost'] ?? 0,
                'qty' => $validatedData['qty'],
                'min_qty' => $validatedData['min_qty'],
                'category_id' => $validatedData['category_id'] ?? null,
                'brand_id' => $validatedData['brand_id'] ?? null,
                'features' => isset($validatedData['features']) ?
                    substr($validatedData['features'], 0, 2000) : null, // FIXED: Truncate to 2000 chars
                'barcode' => $validatedData['barcode'] ?? null,
                'type' => $validatedData['type'],
                'update_by' => 'seller',
            ];

            $product->update($updateData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully!',
                'redirect' => route('seller.product.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product update error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified product
     */
    public function destroy($id)
    {
        try {
            $seller = Auth::guard('seller')->user();

            $product = SellerProduct::where('seller_id', $seller->id)
                ->findOrFail($id);

            // Delete image
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update product status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $seller = Auth::guard('seller')->user();

            $product = SellerProduct::where('seller_id', $seller->id)
                ->findOrFail($id);

            $product->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Product status updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate unique product code
     */
    private function generateProductCode()
    {
        do {
            $code = 'PRD' . date('Ymd') . strtoupper(Str::random(6));
        } while (SellerProduct::where('code', $code)->exists());

        return $code;
    }
}
