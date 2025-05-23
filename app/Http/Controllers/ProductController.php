<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('product.product_list');
    }
    public function create()
    {
        return view('product.product_create');
    }


}
