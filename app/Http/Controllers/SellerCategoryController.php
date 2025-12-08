<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class SellerCategoryController extends Controller
{
    public function seller_index()
    {
        try {

            $categories = Category::query()
                ->where('seller_code', auth()->user()->code)
                ->get();
            $count = $categories->count();
            return view('seller.product.category_list', compact('categories','count'));
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function seller_create()
    {
        $codeGenerate = new CodeGenerator();
        $code = $codeGenerate->newCodeLoad('category');
        return view('seller.product.category_create',compact('code'));
    }

    public function seller_store(Request $request)
    {
        $imagePath = ImageUploader::uploadImage($request->file('img'), 'category');
        $codeGenerate= new CodeGenerator;
        $code = $codeGenerate->generateCode('category');
        try {
            Category::query()->create([
                'code'=>$code,
                'name'=>$request->name,
                'img'=>$imagePath,
                'status'=>0,
                'add_by'=>'admin',
                'update_by'=>'admin',
                'cancel'=>0,
                'seller_code'=>auth()->user()->code
            ]);
            return redirect()->back();
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function seller_delete($id)
    {
        try {
            Category::query()->find($id)->delete();
            return redirect()->back();
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
