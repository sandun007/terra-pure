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
    public function new_order()
    {
        return view('seller.new_order');
    }
    public function completeOrder()
    {
        return view('seller.complete_order_list');
    }
    public function returnOrder()
    {
        return view('seller.return_order_list');
    }
    public function reschedulingOrder()
    {
        return view('seller.rescheduling_order_list');
    }
    public function pendingOrder()
    {
        return view('seller.pending_order_list');
    }

    public function seller_product()
    {
        return view('seller.seller_product');
    }
}
