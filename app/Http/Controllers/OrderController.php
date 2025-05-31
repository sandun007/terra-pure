<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function new_order()
    {
        return view('seller.new_order');
    }
    public function completeOrder()
    {
        return view('sales.complete_order_list');
    }
    public function returnOrder()
    {
        return view('sales.return_order_list');
    }
    public function reschedulingOrder()
    {
        return view('sales.rescheduling_order_list');
    }
    public function pendingOrder()
    {
        return view('sales.pending_order_list');
    }
}
