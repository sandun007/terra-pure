<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\SellerBrand;
use Illuminate\Http\Request;

// If these are in App\Helpers or App\Services, import accordingly:
//use App\Helpers\CodeGenerator;
//use App\Helpers\ImageUploader;
use App\Http\Controllers\CodeGenerator;
use App\Http\Controllers\ImageUploader;

class BrandController extends Controller
{
    public function index()
    {
        try {
            $adminBrands = Brand::with('wholesaler')->where(function ($q) {
                $q->where('seller_code', 0)
                  ->orWhereNull('seller_code');
            })->get();

            $sellerBrands = SellerBrand::with('seller')->get();

            $brands = $adminBrands->concat($sellerBrands)->map(function ($brand) {
                if ($brand instanceof SellerBrand) {
                    $brand->setAttribute('store', 'Seller');
                    $brand->setAttribute('creator_name', $brand->seller ? $brand->seller->name . ' (Seller)' : 'Seller');
                } else {
                    if ($brand->wholesaler_id && $brand->wholesaler) {
                        $brand->setAttribute('creator_name', $brand->wholesaler->name . ' (Wholesaler)');
                    } else {
                        $brand->setAttribute('creator_name', 'Admin');
                    }
                    // store attribute is handled by getStoreAttribute on Brand.php, but let's ensure it's explicitly set for the view
                    $brand->setAttribute('store', $brand->store);
                }
                return $brand;
            });

            $brandCount = $brands->count();

            return view('product.brand_list', compact('brands', 'brandCount'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function create()
    {
        $codeGenerate = new CodeGenerator();
        $code = $codeGenerate->newCodeLoad('brand');

        return view('product.brand_create', compact('code'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            // Upload Image
            $imagePath = ImageUploader::uploadImage($request->file('image'), 'brand');

            // Generate Code
            $codeGenerate = new CodeGenerator();
            $code = $codeGenerate->generateCode('brand');

            // Create Brand
            Brand::create([
                'code'        => $code,
                'name'        => $request->name,
                'image'       => $imagePath,
                'status'      => 1,     // active = 1
                'seller_code' => 0,
            ]);

            return redirect()->back()->with('success', 'Brand created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong!')
                ->withInput();
        }
    }

    public function edit(Request $request, $id)
    {
        $type = $request->query('type');
        if ($type === 'seller') {
            $brand = SellerBrand::findOrFail($id);
        } else {
            $brand = Brand::findOrFail($id);
        }
        return view('product.brand_update', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        try {
            $type = $request->query('type');
            if ($type === 'seller') {
                $brand = SellerBrand::findOrFail($id);
            } else {
                $brand = Brand::findOrFail($id);
            }

            $request->validate([
                'name'   => 'required|string|max:255',
                'status' => 'required|in:0,1',
                'img'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // If new image uploaded
            if ($request->hasFile('img')) {

                // Delete old image (if stored in public disk path)
                if ($brand->image && file_exists(storage_path('app/public/' . $brand->image))) {
                    unlink(storage_path('app/public/' . $brand->image));
                }

                // Upload new image using same helper for consistency
                $brand->image = ImageUploader::uploadImage($request->file('img'), 'brand');
            }

            $brand->name = $request->name;
            if ($brand instanceof \App\Models\SellerBrand) {
                $brand->status = $request->status == 1 ? 'active' : 'inactive';
            } else {
                $brand->status = (int) $request->status;
            }
            $brand->save();

            return redirect()->route('brand.index')
                ->with('success', 'Brand updated successfully');

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $type = $request->query('type');
            if ($type === 'seller') {
                $brand = SellerBrand::findOrFail($id);
            } else {
                $brand = Brand::findOrFail($id);
            }

            // Optional: delete image file also
            if ($brand->image && file_exists(storage_path('app/public/' . $brand->image))) {
                unlink(storage_path('app/public/' . $brand->image));
            }

            $brand->delete();

            return redirect()->back()->with('success', 'Brand deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Brand not found');
        }
    }

    public function show(Request $request, $id)
    {
        $type = $request->query('type');
        if ($type === 'seller') {
            $brand = SellerBrand::findOrFail($id);
        } else {
            $brand = Brand::findOrFail($id);
        }
        return view('product.brand_view', compact('brand'));
    }

    public function getCreators(Request $request)
    {
        try {
            $type = $request->input('type');

            if ($type === 'Seller') {
                $sellerIds = SellerBrand::whereNotNull('seller_id')
                    ->distinct()
                    ->pluck('seller_id');
                $creators = \App\Models\Saler::whereIn('id', $sellerIds)
                    ->select('id', 'name')
                    ->get();
            } elseif ($type === 'Wholesaler') {
                $wholesalerIds = Brand::whereNotNull('wholesaler_id')
                    ->distinct()
                    ->pluck('wholesaler_id');
                $creators = \App\Models\Wholesaler::whereIn('id', $wholesalerIds)
                    ->select('id', 'name')
                    ->get();
            } else {
                $creators = [];
            }

            return response()->json($creators);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
