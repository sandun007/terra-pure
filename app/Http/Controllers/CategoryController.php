<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('product.category_list');
    }
    public function create()
    {
        return view('product.category_create');
    }

    public function store(Request $request)
    {
        $imagePath = ImageUploader::uploadImage($request->file('img'), 'category');

        try {
            Category::query()->create([
                'code'=>$request->code,
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

}
