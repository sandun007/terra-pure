<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BrandController extends Controller
{

    public function index()
    {
        return view('product.brand_list');
    }
    public function create()
    {
        return view('product.brand_create');
    }

    public function store(Request $request)
    {
        try {
            
        }
        catch (\Exception $e) {
            return $e;
        }
    }
}
