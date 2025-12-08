<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class SellerBrandController extends Controller
{
    public function seller_index()
    {
        try {
            $brands = Brand::query()
                ->where('seller_code',auth()->user()->code)
                ->get();
            $brandCount = $brands->count(); // Get total number of brands

            return view('seller.product.brand_list', compact('brands', 'brandCount'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function seller_create()
    {
        $codeGenerate = new CodeGenerator();
        $code = $codeGenerate->newCodeLoad('brand');
        return view('seller.product.brand_create', compact('code'));
    }

    public function seller_store(Request $request)
    {
        $imagePath = ImageUploader::uploadImage($request->file('img'), 'brand');
        $codeGenerate= new CodeGenerator;
        $code = $codeGenerate->generateCode('brand');
        try {
            Brand::query()->create([
                'code'=>$code,
                'name'=>$request->name,
                'img'=>$imagePath,
                'seller_code'=>auth()->user()->code
            ]);
            return redirect()->back();
        }
        catch (\Exception $e) {
            return $e;
        }
    }

    public function seller_delete($id)
    {
        try {

            Brand::query()->find($id)->delete();
            return redirect()->back();
        }
        catch (\Exception $e) {
            return $e;
        }
    }
}
