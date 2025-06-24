<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('product.product_list');
    }
    public function create()
    {
        try {
            $brands = Brand::all();
            $categories = Category::all();
            return view('product.product_create',compact('brands','categories'));
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function store(Request $request)
    {
        $imagePath = ImageUploader::uploadImage($request->file('img'), 'product');

        try {
            Product::query()->create([
                'code'=>$request->code,
                'name'=>$request->name,
                'brand'=>$request->brand,
                'category'=>$request->category,
                'description'=>$request->description,
                'type'=>'stock_item',
                'img'=>$imagePath,
                'add_by'=>'admin',
                'update_by'=>'admin',
                'status'=>'active',
                'cancel'=>0,
            ]);

            return redirect()->back();
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function product_view($id)
    {
        return view('product.product_view');
    }
}
