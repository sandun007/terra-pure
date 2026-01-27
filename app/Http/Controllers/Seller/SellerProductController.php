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

            $products = SellerProduct::where('seller_code', $seller->code)
                ->with(['categoryModel', 'brandModel'])
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

            // Get seller's categories
            $categories = SellerCategory::where('seller_id', $seller->id)
                ->where('status', 'active')
                ->orderBy('name', 'asc')
                ->get();

            // Get seller's brands
            $brands = SellerBrand::where('seller_id', $seller->id)
                ->where('status', 'active')
                ->orderBy('name', 'asc')
                ->get();

            // Generate unique product code
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
        // Start transaction for data consistency
        DB::beginTransaction();

        try {
            $seller = Auth::guard('seller')->user();

            Log::info('Starting product creation for seller: ' . $seller->code);
            Log::info('Request data: ' . json_encode($request->except('img')));
            Log::info('Has file: ' . ($request->hasFile('img') ? 'YES' : 'NO'));

            // Validation rules
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
                'features' => 'nullable|string',
                'barcode' => 'nullable|string|max:255|unique:seller_products,barcode',
                'type' => 'required|in:stock_item,service,digital',
                'img' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ], [
                'code.required' => 'Product code is required',
                'code.unique' => 'This product code already exists',
                'name.required' => 'Product name is required',
                'stock_price.required' => 'Stock price is required',
                'stock_price.min' => 'Stock price must be at least 0',
                'display_price.required' => 'Display price is required',
                'display_price.min' => 'Display price must be at least 0',
                'qty.required' => 'Quantity is required',
                'qty.min' => 'Quantity must be at least 0',
                'min_qty.required' => 'Minimum quantity is required',
                'min_qty.min' => 'Minimum quantity must be at least 1',
                'img.required' => 'Product image is required',
                'img.image' => 'File must be an image',
                'img.mimes' => 'Only JPG, PNG, GIF, and WEBP images are allowed',
                'img.max' => 'Image size must not exceed 2MB',
            ]);

            // Additional validation: display price should be >= stock price
            $validator->after(function ($validator) use ($request) {
                if ($request->display_price < $request->stock_price) {
                    $validator->errors()->add('display_price', 'Display price cannot be lower than stock price.');
                }
            });

            if ($validator->fails()) {
                Log::error('Validation failed: ' . json_encode($validator->errors()->all()));
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please fix the validation errors below.');
            }

            $validatedData = $validator->validated();

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('img') && $request->file('img')->isValid()) {
                $image = $request->file('img');
                $originalName = $image->getClientOriginalName();
                $extension = $image->getClientOriginalExtension();
                $fileSize = $image->getSize();

                Log::info('Uploading image: ' . $originalName . ', Size: ' . $fileSize . ', Extension: ' . $extension);

                // Create safe filename
                $safeName = Str::slug($validatedData['name'], '_');
                $filename = 'product_' . time() . '_' . $safeName . '.' . $extension;

                // Ensure directory exists
                $directory = 'seller/products';
                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                    Log::info('Created directory: ' . $directory);
                }

                // Store the image
                $imagePath = $image->storeAs($directory, $filename, 'public');

                if ($imagePath) {
                    Log::info('Image uploaded successfully: ' . $imagePath);

                    // Verify file exists
                    if (!Storage::disk('public')->exists($imagePath)) {
                        Log::error('Image file not found after upload: ' . $imagePath);
                        throw new \Exception('Failed to upload image. Please try again.');
                    }
                } else {
                    Log::error('Image upload failed - no path returned');
                    throw new \Exception('Failed to upload image. Please try again.');
                }
            } else {
                Log::error('No valid image file found in request');
                throw new \Exception('Product image is required and must be a valid image file.');
            }

            // Create product
            $productData = [
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
                'features' => $validatedData['features'] ?? null,
                'barcode' => $validatedData['barcode'] ?? null,
                'type' => $validatedData['type'],
                'add_by' => 'seller',
                'update_by' => 'seller',
                'status' => 'active',
                'cancel' => 0,
                'img' => $imagePath,
            ];

            Log::info('Creating product with data: ' . json_encode($productData));

            $product = SellerProduct::create($productData);

            if (!$product) {
                Log::error('Product creation failed - no product returned');
                throw new \Exception('Failed to create product in database.');
            }

            Log::info('Product created successfully with ID: ' . $product->id);

            // Commit transaction
            DB::commit();

            return redirect()->route('seller.product.index')
                ->with('success', 'Product "' . $validatedData['name'] . '" created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation exception: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up uploaded image if product creation failed
            if (isset($imagePath) && $imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
                Log::info('Cleaned up image file after error: ' . $imagePath);
            }

            Log::error('Product creation error: ' . $e->getMessage() . PHP_EOL . $e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Error creating product: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified product
     */
    public function show($id)
    {
        try {
            $seller = Auth::guard('seller')->user();

            $product = SellerProduct::where('seller_code', $seller->code)
                ->with(['categoryModel', 'brandModel'])
                ->findOrFail($id);

            return view('seller.product.show', compact('product'));
        } catch (\Exception $e) {
            Log::error('Error showing product: ' . $e->getMessage());
            return redirect()->route('seller.product.index')->with('error', 'Product not found.');
        }
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit($id)
    {
        try {
            $seller = Auth::guard('seller')->user();

            $product = SellerProduct::where('seller_code', $seller->code)
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
            Log::error('Error loading edit form: ' . $e->getMessage());
            return redirect()->route('seller.product.index')->with('error', 'Product not found or you don\'t have permission to edit it.');
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

            $product = SellerProduct::where('seller_code', $seller->code)
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
                'features' => 'nullable|string',
                'barcode' => 'nullable|string|max:255|unique:seller_products,barcode,' . $id,
                'type' => 'required|in:stock_item,service,digital',
                'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ], [
                'name.required' => 'Product name is required',
                'stock_price.required' => 'Stock price is required',
                'stock_price.min' => 'Stock price must be at least 0',
                'display_price.required' => 'Display price is required',
                'display_price.min' => 'Display price must be at least 0',
                'qty.required' => 'Quantity is required',
                'qty.min' => 'Quantity must be at least 0',
                'min_qty.required' => 'Minimum quantity is required',
                'min_qty.min' => 'Minimum quantity must be at least 1',
                'img.image' => 'File must be an image',
                'img.mimes' => 'Only JPG, PNG, GIF, and WEBP images are allowed',
                'img.max' => 'Image size must not exceed 2MB',
            ]);

            // Additional validation
            $validator->after(function ($validator) use ($request) {
                if ($request->display_price < $request->stock_price) {
                    $validator->errors()->add('display_price', 'Display price cannot be lower than stock price.');
                }
            });

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please fix the validation errors below.');
            }

            $validatedData = $validator->validated();

            // Update data array
            $updateData = [
                'name' => $validatedData['name'],
                'description' => $validatedData['description'] ?? null,
                'stock_price' => $validatedData['stock_price'],
                'display_price' => $validatedData['display_price'],
                'discount' => $validatedData['discount'] ?? 0,
                'packaging_cost' => $validatedData['packaging_cost'] ?? 0,
                'qty' => $validatedData['qty'],
                'min_qty' => $validatedData['min_qty'],
                'category_id' => $validatedData['category_id'] ?? null,
                'brand_id' => $validatedData['brand_id'] ?? null,
                'features' => $validatedData['features'] ?? null,
                'barcode' => $validatedData['barcode'] ?? null,
                'type' => $validatedData['type'],
                'update_by' => 'seller',
            ];

            // Handle image upload
            if ($request->hasFile('img') && $request->file('img')->isValid()) {
                // Delete old image
                if ($product->img && Storage::disk('public')->exists($product->img)) {
                    Storage::disk('public')->delete($product->img);
                }

                $image = $request->file('img');
                $safeName = Str::slug($validatedData['name'], '_');
                $filename = 'product_' . time() . '_' . $safeName . '.' . $image->getClientOriginalExtension();

                // Ensure directory exists
                $directory = 'seller/products';
                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                }

                $imagePath = $image->storeAs($directory, $filename, 'public');

                if ($imagePath) {
                    $updateData['img'] = $imagePath;
                }
            }

            // Update slug if name changed
            if ($product->name !== $validatedData['name']) {
                $updateData['slug'] = Str::slug($validatedData['name']);
            }

            $product->update($updateData);

            DB::commit();

            return redirect()->route('seller.product.index')
                ->with('success', 'Product "' . $validatedData['name'] . '" updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product update error: ' . $e->getMessage() . PHP_EOL . $e->getTraceAsString());
            return redirect()->back()
                ->with('error', 'Error updating product: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified product
     */
    public function destroy($id)
    {
        try {
            $seller = Auth::guard('seller')->user();

            $product = SellerProduct::where('seller_code', $seller->code)
                ->findOrFail($id);

            $productName = $product->name;

            // Delete image if exists
            if ($product->img && Storage::disk('public')->exists($product->img)) {
                Storage::disk('public')->delete($product->img);
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product "' . $productName . '" deleted successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Product deletion error: ' . $e->getMessage());
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

            $product = SellerProduct::where('seller_code', $seller->code)
                ->findOrFail($id);

            $request->validate([
                'status' => 'required|in:active,inactive',
            ]);

            $product->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Product status updated successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Status update error: ' . $e->getMessage());
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
