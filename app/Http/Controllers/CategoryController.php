<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::query()
                ->where('seller_code',0)
                ->get();
            $count = $categories->count();
            return view('product.category_list', compact('categories','count'));
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function create()
    {
        $codeGenerate = new CodeGenerator();
        $code = $codeGenerate->newCodeLoad('category');
        return view('product.category_create',compact('code'));
    }

    public function store(Request $request)
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
                'cancel'=>0
            ]);
            return redirect()->back();
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete($id)
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
