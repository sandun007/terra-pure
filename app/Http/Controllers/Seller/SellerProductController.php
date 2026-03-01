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
     * Display the specified product.
     */
    public function show($id)
    {
        try {
            $seller = Auth::guard('seller')->user();

            $product = SellerProduct::where('seller_id', $seller->id)
                ->with(['category', 'brand'])
                ->findOrFail($id);

            return view('seller.product.show', compact('product'));

        } catch (\Exception $e) {
            return redirect()->route('seller.product.index')
                ->with('error', 'Product not found: ' . $e->getMessage());
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
     * FIXED: seller_code cast to string, removed manual sku, fixed type handling
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $seller = Auth::guard('seller')->user();

            $validator = Validator::make($request->all(), [
                'code'           => 'required|string|unique:seller_products,code',
                'name'           => 'required|string|max:255',
                'description'    => 'nullable|string',
                'stock_price'    => 'required|numeric|min:0',
                'display_price'  => 'required|numeric|min:0',
                'discount'       => 'nullable|numeric|min:0|max:100',
                'packaging_cost' => 'nullable|numeric|min:0',
                'qty'            => 'required|integer|min:0',
                'min_qty'        => 'required|integer|min:1',
                'category_id'    => 'nullable|exists:seller_categories,id',
                'brand_id'       => 'nullable|exists:seller_brands,id',
                'features'       => 'nullable|string|max:2000',
                'barcode'        => 'nullable|string|max:255|unique:seller_products,barcode',
                'type'           => 'required|in:physical,digital',
                'img'            => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ], [
                'img.required' => 'Product image is required',
                'img.image'    => 'File must be an image',
                'img.mimes'    => 'Only JPG, PNG, GIF, and WEBP images are allowed',
                'img.max'      => 'Image size must not exceed 2MB',
                'features.max' => 'Features text is too long (maximum 2000 characters)',
                'code.unique'  => 'This product code is already in use',
                'type.in'      => 'Product type must be either physical or digital',
            ]);

            // Additional cross-field validation
            $validator->after(function ($validator) use ($request) {
                if (
                    $request->filled('display_price') &&
                    $request->filled('stock_price') &&
                    (float) $request->display_price < (float) $request->stock_price
                ) {
                    $validator->errors()->add('display_price', 'Display price cannot be lower than stock price.');
                }
            });

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors(),
                    'message' => 'Please fix the validation errors and try again.',
                ], 422);
            }

            $validatedData = $validator->validated();

            // ── Image Upload ─────────────────────────────────────────────
            $imagePath = null;
            if ($request->hasFile('img')) {
                $image     = $request->file('img');
                $filename  = 'product_' . time() . '_' . Str::slug($validatedData['name'])
                    . '.' . $image->getClientOriginalExtension();
                $directory = 'seller/products';
                $imagePath = $image->storeAs($directory, $filename, 'public');

                if (!$imagePath) {
                    throw new \Exception('Failed to upload image. Please try again.');
                }
            }

            // ── Build Product Data ────────────────────────────────────────
            // FIX 1: seller_code cast to (string) — prevents int/varchar mismatch
            // FIX 2: type is guaranteed 'physical' or 'digital' via validation
            // FIX 3: sku is NOT set here — Model boot() auto-generates it safely
            $productData = [
                'seller_id'      => (int)    $seller->id,
                'seller_code'    => (string) $seller->code,          // ← FIXED: was raw int
                'code'           => $validatedData['code'],
                'name'           => $validatedData['name'],
                'slug'           => Str::slug($validatedData['name']),
                'description'    => $validatedData['description']    ?? null,
                'stock_price'    => (float)  $validatedData['stock_price'],
                'display_price'  => (float)  $validatedData['display_price'],
                'discount'       => (float) ($validatedData['discount']       ?? 0),
                'packaging_cost' => (float) ($validatedData['packaging_cost'] ?? 0),
                'qty'            => (int)    $validatedData['qty'],
                'min_qty'        => (int)    $validatedData['min_qty'],
                'category_id'    => $validatedData['category_id'] ?? null,
                'brand_id'       => $validatedData['brand_id']    ?? null,
                'features'       => isset($validatedData['features'])
                    ? substr($validatedData['features'], 0, 2000)
                    : null,
                'barcode'        => $validatedData['barcode'] ?? null,
                'type'           => $validatedData['type'],           // ← 'physical' | 'digital'
                'add_by'         => 'seller',
                'update_by'      => 'seller',
                'status'         => 'active',
                'cancel'         => false,
                'image'          => $imagePath,
                // 'sku' intentionally omitted — auto-generated by Model boot()
            ];

            $product = SellerProduct::create($productData);

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Product "' . $product->name . '" created successfully!',
                'product'  => [
                    'id'   => $product->id,
                    'code' => $product->code,
                    'name' => $product->name,
                ],
                'redirect' => route('seller.product.index'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up uploaded image on failure
            if (isset($imagePath) && $imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            Log::error('Product creation error: ' . $e->getMessage(), [
                'seller_id' => Auth::guard('seller')->id(),
                'trace'     => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error creating product: ' . $e->getMessage(),
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
                'name'           => 'required|string|max:255',
                'description'    => 'nullable|string',
                'stock_price'    => 'required|numeric|min:0',
                'display_price'  => 'required|numeric|min:0',
                'discount'       => 'nullable|numeric|min:0|max:100',
                'packaging_cost' => 'nullable|numeric|min:0',
                'qty'            => 'required|integer|min:0',
                'min_qty'        => 'required|integer|min:1',
                'category_id'    => 'nullable|exists:seller_categories,id',
                'brand_id'       => 'nullable|exists:seller_brands,id',
                'features'       => 'nullable|string|max:2000',
                'barcode'        => 'nullable|string|max:255|unique:seller_products,barcode,' . $id,
                'type'           => 'required|in:physical,digital',
                'img'            => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ], [
                'type.in' => 'Product type must be either physical or digital',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $validatedData = $validator->validated();

            // Handle image upload
            if ($request->hasFile('img')) {
                // Delete old image
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }

                $image     = $request->file('img');
                $filename  = 'product_' . time() . '_' . Str::slug($validatedData['name'])
                    . '.' . $image->getClientOriginalExtension();
                $directory = 'seller/products';
                $imagePath = $image->storeAs($directory, $filename, 'public');

                $product->image = $imagePath;
            }

            $product->update([
                'name'           => $validatedData['name'],
                'slug'           => Str::slug($validatedData['name']),
                'description'    => $validatedData['description']    ?? null,
                'stock_price'    => (float)  $validatedData['stock_price'],
                'display_price'  => (float)  $validatedData['display_price'],
                'discount'       => (float) ($validatedData['discount']       ?? 0),
                'packaging_cost' => (float) ($validatedData['packaging_cost'] ?? 0),
                'qty'            => (int)    $validatedData['qty'],
                'min_qty'        => (int)    $validatedData['min_qty'],
                'category_id'    => $validatedData['category_id'] ?? null,
                'brand_id'       => $validatedData['brand_id']    ?? null,
                'features'       => isset($validatedData['features'])
                    ? substr($validatedData['features'], 0, 2000)
                    : null,
                'barcode'        => $validatedData['barcode'] ?? null,
                'type'           => $validatedData['type'],
                'update_by'      => 'seller',
            ]);

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Product updated successfully!',
                'redirect' => route('seller.product.index'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product update error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating product: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Permanently delete a product from the database
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $seller = Auth::guard('seller')->user();

            $product = SellerProduct::withTrashed()
                ->where('seller_id', $seller->id)
                ->findOrFail($id);

            $imagePath = $product->image;

            $product->forceDelete();

            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product permanently deleted successfully!',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product deletion error: ' . $e->getMessage(), [
                'product_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error deleting product: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Soft delete product (move to trash)
     */
    public function trash($id)
    {
        try {
            $seller  = Auth::guard('seller')->user();
            $product = SellerProduct::where('seller_id', $seller->id)->findOrFail($id);
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product moved to trash successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error moving product to trash: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Restore soft deleted product
     */
    public function restore($id)
    {
        try {
            $seller = Auth::guard('seller')->user();

            $product = SellerProduct::onlyTrashed()
                ->where('seller_id', $seller->id)
                ->findOrFail($id);

            $product->restore();

            return response()->json([
                'success' => true,
                'message' => 'Product restored successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error restoring product: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get trashed products
     */
    public function trashed()
    {
        try {
            $seller = Auth::guard('seller')->user();

            $products = SellerProduct::onlyTrashed()
                ->where('seller_id', $seller->id)
                ->with(['category', 'brand'])
                ->orderBy('deleted_at', 'desc')
                ->get();

            return view('seller.product.trashed', compact('products'));
        } catch (\Exception $e) {
            Log::error('Error loading trashed products: ' . $e->getMessage());
            return redirect()->route('seller.product.index')
                ->with('error', 'Error loading trashed products.');
        }
    }

    /**
     * Update product status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $seller  = Auth::guard('seller')->user();
            $product = SellerProduct::where('seller_id', $seller->id)->findOrFail($id);
            $product->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Product status updated successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate unique product code in format DX/ITM/0001
     */
    private function generateProductCode(): string
    {
        $prefix = 'DX/ITM/';

        $lastProduct = SellerProduct::where('code', 'LIKE', $prefix . '%')
            ->orderBy('code', 'desc')
            ->first();

        if ($lastProduct) {
            $lastNumber = (int) str_replace($prefix, '', $lastProduct->code);
            $newNumber  = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $code = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // Safety: ensure uniqueness
        while (SellerProduct::where('code', $code)->exists()) {
            $newNumber++;
            $code = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }

        return $code;
    }

    /**
     * Get next available product code (API endpoint)
     */
    public function getNextProductCode()
    {
        try {
            return response()->json([
                'success' => true,
                'code'    => $this->generateProductCode(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating product code: ' . $e->getMessage(),
            ], 500);
        }
    }
}
