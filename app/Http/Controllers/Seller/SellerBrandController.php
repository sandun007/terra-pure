<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerBrand;
use App\Models\SellerProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SellerBrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:seller');
    }

    public function index()
    {
        try {
            $seller = Auth::guard('seller')->user();

            $brands = SellerBrand::where('seller_id', $seller->id)
                ->withCount('products')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('seller.brands.index', compact('brands'));

        } catch (\Exception $e) {
            \Log::error('Error loading brands: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading brands. Please try again.');
        }
    }

    public function create()
    {
        try {
            // Generate unique brand code
            do {
                $code = 'SBR' . date('Ymd') . strtoupper(Str::random(6));
            } while (SellerBrand::where('code', $code)->exists());

            return view('seller.brands.create', compact('code'));
        } catch (\Exception $e) {
            \Log::error('Error loading create form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading form. Please try again.');
        }
    }

    public function store(Request $request)
    {
        try {
            $seller = Auth::guard('seller')->user();

            $validated = $request->validate([
                'code' => [
                    'required',
                    'string',
                    'unique:seller_brands,code'
                ],
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('seller_brands')->where(function ($query) use ($seller) {
                        return $query->where('seller_id', $seller->id);
                    })
                ],
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'status' => 'required|in:active,inactive',
            ], [
                'code.required' => 'Brand code is required',
                'code.unique' => 'This brand code already exists',
                'name.required' => 'Brand name is required',
                'name.unique' => 'A brand with this name already exists in your account',
                'image.required' => 'Brand image is required',
                'image.image' => 'File must be an image',
                'image.mimes' => 'Image must be: jpeg, png, jpg, gif, or webp',
                'image.max' => 'Image size must not exceed 2MB',
                'status.required' => 'Status is required',
            ]);

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = 'brand_' . time() . '_' . Str::slug($validated['name']) . '.' . $image->getClientOriginalExtension();

                // Store in public disk
                $imagePath = $image->storeAs('seller/brands', $filename, 'public');

                if (!$imagePath) {
                    throw new \Exception('Failed to upload image');
                }
            }

            // Create brand
            $brand = SellerBrand::create([
                'seller_id' => $seller->id,
                'code' => $validated['code'],
                'name' => $validated['name'],
                'image' => $imagePath,
                'status' => $validated['status'],
            ]);

            \Log::info('Brand created: ' . $brand->id . ' for seller: ' . $seller->id);

            return redirect()->route('seller.brands.index')
                ->with('success', 'Brand "' . $validated['name'] . '" created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error creating brand: ' . json_encode($e->errors()));
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors below.');

        } catch (\Exception $e) {
            \Log::error('Error creating brand: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error creating brand: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(SellerBrand $brand)
    {
        try {
            $seller = Auth::guard('seller')->user();

            // Verify the brand belongs to the seller
            if ($brand->seller_id != $seller->id) {
                return redirect()->route('seller.brands.index')
                    ->with('error', 'You do not have permission to edit this brand.');
            }

            return view('seller.brands.edit', compact('brand'));
        } catch (\Exception $e) {
            \Log::error('Error loading brand for edit: ' . $e->getMessage());
            return redirect()->route('seller.brands.index')
                ->with('error', 'Brand not found or you do not have permission to edit it.');
        }
    }

    public function update(Request $request, SellerBrand $brand)
    {
        try {
            $seller = Auth::guard('seller')->user();

            // Verify the brand belongs to the seller
            if ($brand->seller_id != $seller->id) {
                return redirect()->route('seller.brands.index')
                    ->with('error', 'You do not have permission to update this brand.');
            }

            $validated = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('seller_brands')->where(function ($query) use ($seller, $brand) {
                        return $query->where('seller_id', $seller->id)
                            ->where('id', '!=', $brand->id);
                    })
                ],
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'status' => 'required|in:active,inactive',
                'remove_current_image' => 'nullable|in:0,1',
            ], [
                'name.unique' => 'A brand with this name already exists in your account',
            ]);

            $updateData = [
                'name' => $validated['name'],
                'status' => $validated['status'],
            ];

            // Handle image removal
            if ($request->input('remove_current_image') == '1' && $brand->image) {
                Storage::disk('public')->delete($brand->image);
                $updateData['image'] = null;
            }

            // Handle new image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($brand->image) {
                    Storage::disk('public')->delete($brand->image);
                }

                $image = $request->file('image');
                $filename = 'brand_' . time() . '_' . Str::slug($validated['name']) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('seller/brands', $filename, 'public');
                $updateData['image'] = $imagePath;
            }

            $brand->update($updateData);

            \Log::info('Brand updated: ' . $brand->id);

            return redirect()->route('seller.brands.index')
                ->with('success', 'Brand "' . $validated['name'] . '" updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error updating brand: ' . json_encode($e->errors()));
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();

        } catch (\Exception $e) {
            \Log::error('Error updating brand: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error updating brand: ' . $e->getMessage())
                ->withInput();
        }
    }
    /**
     * Display the specified brand.
     */
    public function show(SellerBrand $brand)
    {
        try {
            $seller = Auth::guard('seller')->user();

            // Verify the brand belongs to the seller
            if ($brand->seller_id != $seller->id) {
                return redirect()->route('seller.brands.index')
                    ->with('error', 'You do not have permission to view this brand.');
            }

            // Load products count
            $brand->loadCount('products');

            // Get recent products under this brand
            $recentProducts = SellerProduct::where('seller_brand_id', $brand->id)
                ->where('seller_id', $seller->id)
                ->with(['category', 'subcategory'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            return view('seller.brands.show', compact('brand', 'recentProducts'));

        } catch (\Exception $e) {
            \Log::error('Error loading brand details: ' . $e->getMessage());
            return redirect()->route('seller.brands.index')
                ->with('error', 'Brand not found or you do not have permission to view it.');
        }
    }

    public function destroy(SellerBrand $brand)
    {
        try {
            $seller = Auth::guard('seller')->user();

            // Verify the brand belongs to the seller
            if ($brand->seller_id != $seller->id) {
                return redirect()->route('seller.brands.index')
                    ->with('error', 'You do not have permission to delete this brand.');
            }

            // Check if brand is used in products
            $productCount = $brand->products()->count();

            if ($productCount > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete brand "' . $brand->name . '". It is being used in ' . $productCount . ' product(s).');
            }

            $brandName = $brand->name;

            // Delete image
            if ($brand->image && Storage::disk('public')->exists($brand->image)) {
                Storage::disk('public')->delete($brand->image);
            }

            $brand->delete();

            \Log::info('Brand deleted: ' . $brand->id);

            return redirect()->route('seller.brands.index')
                ->with('success', 'Brand "' . $brandName . '" deleted successfully!');

        } catch (\Exception $e) {
            \Log::error('Error deleting brand: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error deleting brand: ' . $e->getMessage());
        }
    }
}
