<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StockController extends Controller
{
    public function low_stock()
    {
        return view('product.low_stock');
    }
}
