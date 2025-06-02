<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellerPaymentController extends Controller
{
    public function my_payment()
    {
        return view('seller.payment.my_payment');
    }
}
