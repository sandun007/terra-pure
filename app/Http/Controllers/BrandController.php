<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{

    public function index()
    {
        try {
            $brands = Brand::all();
            return view('product.brand_list',compact('brands'));

        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function create()
    {
        return view('product.brand_create');
    }

    public function store(Request $request)
    {
        $imagePath = ImageUploader::uploadImage($request->file('img'), 'brand');
        try {
            Brand::query()->create([
                'code'=>$request->code,
                'name'=>$request->name,
                'img'=>$imagePath
            ]);
            return redirect()->back();
        }
        catch (\Exception $e) {
            return $e;
        }
    }
}
