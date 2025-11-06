<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        try {
            $products = Product::query()
                ->where('seller_code',0)
                ->get();
            return view('shop.shop',compact('products'));
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
