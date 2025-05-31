<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellerController extends Controller
{

    public function dashboard()
    {
        return view('seller.dashboard');
    }

    public function shop()
    {
        return view('seller.shop.shop');
    }
    public function index()
    {
        return view('seller.seller_list');
    }
    public function create()
    {
        return view('seller.seller_create');
    }
}
