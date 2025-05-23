<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellerController extends Controller
{

    public function index()
    {
        return view('saller.saller_list');
    }
    public function create()
    {
        return view('saller.seller_create');
    }
}
