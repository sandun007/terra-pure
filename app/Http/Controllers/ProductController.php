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
        try {
            $products = Product::query()
                ->where('seller_code',0)
                ->get();
            $count = $products->count();
            return view('product.product_list',compact('products','count'));
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function create()
    {
        try {
            $brands = Brand::query()
                ->where('seller_code',0)
                ->get();
            $categories = Category::query()
                ->where('seller_code',0)
                ->get();
            $codeGenerate = new CodeGenerator();
            $code = $codeGenerate->newCodeLoad('product');

            return view('product.product_create',compact('brands','categories','code'));
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function edit($id)
    {
        try {
            $brands = Brand::query()
                ->where('seller_code',0)
                ->get();
            $categories = Category::query()
                ->where('seller_code',0)
                ->get();
            $codeGenerate = new CodeGenerator();
            $code = $codeGenerate->newCodeLoad('product');

            $product = Product::query()->findOrFail($id);

            return view('product.product_update',compact('brands','categories','code','product'));
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

      public function store(Request $request)
    {
        try {
            $productId = $request->id;
            $imagePath = $request->hasFile('img')
                ? ImageUploader::uploadImage($request->file('img'), 'product')
                : null;

            $codeGenerate = new CodeGenerator;
            $product = $productId ? Product::find($productId) : new Product();

            if (!$productId) {
                $product->code = $codeGenerate->generateCode('product');
            }
            $product->name = $request->name;
            $product->barcode = $request->barcode;
            $product->brand = $request->brand;
            $product->category = $request->category;
            $product->description = $request->description;
            $product->stock_price = $request->stock_price;
            $product->display_price = $request->display_price;
            $product->discount = $request->discount;
            $product->qty = $request->qty;
            $product->min_qty = $request->min_qty;
            $product->packing_cost = $request->packing_cost;
            $product->type = 'stock_item';
            $product->add_by = 'admin';
            $product->update_by = 'admin';
            $product->status = 'active';
            $product->cancel = 0;

            if ($imagePath) {
                $product->img = $imagePath;
            }
            $product->save();

            return redirect()->back()->with('success', 'Product saved successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

      public function product_view($id)
    {
        $product = Product::query()->findOrFail($id);
        return view('product.product_view',compact('product'));
    }

    public function delete($id)
    {
        try {
            Product::query()->find($id)->delete();
            return redirect()->back();
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
