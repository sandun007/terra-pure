<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{

    public function index()
    {
        try {
            $brands = Brand::query()
                ->where('seller_code',0)
                ->get();
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
        $imagePath = ImageUploader::uploadImage($request->file('img'), 'brand');
        $codeGenerate= new CodeGenerator;
        $code = $codeGenerate->generateCode('brand');
        try {
            Brand::query()->create([
                'code'=>$code,
                'name'=>$request->name,
                'img'=>$imagePath,
                'seller_code'=>0
            ]);
            return redirect()->back();
        }
        catch (\Exception $e) {
            return $e;
        }
    }

    public function delete($id)
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
