<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;

class SellerPaymentController extends Controller
{
    public function my_payment()
    {
        return view('seller.payment.my_payment');
    }

    public function withdraw()
    {
        return view('seller.payment.seller_withdraw');
    }

    public function payment_history()
    {
        return view('seller.payment.payment_history');
    }
}
