<?php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\WholesalerProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class WholesalerBrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:wholesaler');
    }

    public function index()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $brands = Brand::where('wholesaler_id', $wholesaler->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('wholesaler.brands.index', compact('brands'));
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
                $code = 'WBR' . date('Ymd') . strtoupper(Str::random(6));
            } while (Brand::where('code', $code)->exists());

            return view('wholesaler.brands.create', compact('code'));
        } catch (\Exception $e) {
            \Log::error('Error loading create form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading form. Please try again.');
        }
    }

    public function store(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $validated = $request->validate([
                'code' => [
                    'required',
                    'string',
                    'unique:brands,code'
                ],
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('brands')->where(function ($query) use ($wholesaler) {
                        return $query->where('wholesaler_id', $wholesaler->id);
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
                $filename = time() . '_' . Str::slug($validated['name']) . '.' . $image->getClientOriginalExtension();

                // Store in public disk
                $imagePath = $image->storeAs('wholesaler/brands', $filename, 'public');

                if (!$imagePath) {
                    throw new \Exception('Failed to upload image');
                }
            }

            // Create brand
            $brand = Brand::create([
                'wholesaler_id' => $wholesaler->id,
                'code' => $validated['code'],
                'name' => $validated['name'],
                'image' => $imagePath,
                'status' => $validated['status'],
            ]);

            \Log::info('Brand created: ' . $brand->id . ' for wholesaler: ' . $wholesaler->id);

            return redirect()->route('wholesaler.brands.index')
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

    public function edit($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $brand = Brand::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            return view('wholesaler.brands.edit', compact('brand'));
        } catch (\Exception $e) {
            \Log::error('Error loading brand for edit: ' . $e->getMessage());
            return redirect()->route('wholesaler.brands.index')
                ->with('error', 'Brand not found or you do not have permission to edit it.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $brand = Brand::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            $validated = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('brands')->where(function ($query) use ($wholesaler, $id) {
                        return $query->where('wholesaler_id', $wholesaler->id)
                            ->where('id', '!=', $id);
                    })
                ],
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'status' => 'required|in:active,inactive',
                'remove_current_image' => 'nullable|in:0,1',
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
                $filename = time() . '_' . Str::slug($validated['name']) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('wholesaler/brands', $filename, 'public');
                $updateData['image'] = $imagePath;
            }

            $brand->update($updateData);

            \Log::info('Brand updated: ' . $brand->id);

            return redirect()->route('wholesaler.brands.index')
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

    public function destroy($id)
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
                return redirect()->back()
                    ->with('error', 'Cannot delete brand "' . $brand->name . '". It is being used in ' . $productCount . ' product(s).');
            }

            $brandName = $brand->name;

            // Delete image
            if ($brand->image) {
                Storage::disk('public')->delete($brand->image);
            }

            $brand->delete();

            \Log::info('Brand deleted: ' . $id);

            return redirect()->route('wholesaler.brands.index')
                ->with('success', 'Brand "' . $brandName . '" deleted successfully!');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Brand not found for deletion: ' . $id);
            return redirect()->route('wholesaler.brands.index')
                ->with('error', 'Brand not found.');

        } catch (\Exception $e) {
            \Log::error('Error deleting brand: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error deleting brand: ' . $e->getMessage());
        }
    }
}
