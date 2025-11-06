<?php

namespace App\Http\Controllers;

use App\Imports\OrderImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{

    public function new_order()
    {
         $user = auth()->user();
        $import=0;
        return view('seller.new_order',compact('import','user'));
    }

    public function store(Request $request)
    {
        try {
  $import = Excel::import(new OrderImport, $request->file('excel'));
            return redirect()->route('seller.new-order',compact('import'));
          
        }
        catch (\Exception $e) {
            return $e;
        }
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
