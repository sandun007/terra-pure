<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{

    public function index()
    {
        try {
            $brands = Brand::where(function ($q) {
    $q->where('seller_code', 0)
      ->orWhereNull('seller_code');
})->get();
            $brandCount = $brands->count(); // Get total number of brands

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
    // ✅ Validation
    $request->validate([
        'name' => 'required|string|max:255',
        'image'  => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    try {

        // Upload Image
        $imagePath = ImageUploader::uploadImage($request->file('image'), 'brand');

        // Generate Code
        $codeGenerate = new CodeGenerator;
        $code = $codeGenerate->generateCode('brand');

        // Create Brand
        Brand::create([
            'code'        => $code,
            'name'        => $request->name,
            'image'         => $imagePath,
            'status'      => 1,
            'seller_code' => 0
        ]);

        return redirect()->back()->with('success', 'Brand created successfully');

    } catch (\Exception $e) {

        return redirect()->back()
            ->with('error', 'Something went wrong!')
            ->withInput();
    }
}


    public function edit($id)
{
    $brand = Brand::findOrFail($id);
    return view('product.brand_update', compact('brand'));
}

public function update(Request $request, $id)
{
    try {

        $brand = Brand::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:0,1',
            'img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // If new image uploaded
        if ($request->hasFile('img')) {

            // Delete old image
            if ($brand->image && file_exists(storage_path('app/public/' . $brand->image))) {
                unlink(storage_path('app/public/' . $brand->image));
            }

            $file = $request->file('img');
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->storeAs('brand', $filename, 'public');

            $brand->image = 'brand/'.$filename;   // ✅ correct column
        }

        $brand->name = $request->name;
        $brand->status = $request->status;

        $brand->save();

        return redirect()->route('brand.index')
                         ->with('success', 'Brand updated successfully');

    } catch (\Exception $e) {
        return $e->getMessage();
    }
}

    public function delete($id)
{
    try {

        $brand = Brand::findOrFail($id); // will auto throw 404 if not found
        $brand->delete();

        return redirect()->back()->with('success', 'Brand deleted successfully');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Brand not found');
    }
}

    public function show($id)
{
    $brand = Brand::findOrFail($id);
    return view('product.brand_view', compact('brand'));
}

}
