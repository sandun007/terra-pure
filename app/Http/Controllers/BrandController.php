<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function create()
    {
        return view('product.brand_create');
    }
}
