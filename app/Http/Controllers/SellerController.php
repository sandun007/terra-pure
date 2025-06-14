<?php

namespace App\Http\Controllers;

use App\Models\Saler;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function store(Request $request)
    {
        $NIC_front = ImageUploader::uploadImage($request->file('nic_front'), 'seler/nic_front');
        $NIC_back = ImageUploader::uploadImage($request->file('nic_back'), 'saler/nic_back');
//        dd($request->whatsapp);

        try {
            Saler::query()->create([
                'code'=>$request->code,
                'name'=>$request->name,
                'address'=>$request->address,
                'district'=>$request->district,
                'phone'=>$request->phone,
                'whatsapp'=>$request->whatsapp,
                'email'=>$request->email,
                'NIC_no'=>$request->nic_no,
                'NIC_front'=>$NIC_front,
                'NIC_back'=>$NIC_back,
                'username'=>$request->username,
                'password'=>Hash::make($request->password),
                'view_password'=>$request->password,
         ]);

            User::query()->create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'view_password' => $request->password,
                'type' => 'seller',
            ]);
            return redirect()->back();
        } catch (\Exception $e) {
            return $e;
        }
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
