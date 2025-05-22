<?php

namespace App\Http\Controllers;

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
}
