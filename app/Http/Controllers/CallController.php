<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Order;
use Illuminate\Http\Request;

class CallController extends Controller
{
    public function index(){
        $seller = auth()->user();
        
        $orders = Order::query()
    ->where('seller_no', $seller->code)
    ->where('status', 'Pending')
    ->orderByDesc('id') // or ->orderBy('id', 'desc')
    ->get();
    
        return view('seller.pending_call_list',compact('orders'));

    }

    public function call_conform_form($id){
    $cities=City::all();
        $order = Order::query()
            ->where('id',$id)
            ->first();
        return view('seller.call_conform__form',compact('order','id','cities'));
    }
    
      public function conform_call_list(){
        $seller = auth()->user();
        $orders = Order::query()
    ->where('seller_no', $seller->code)
    ->where('status', 'Conform')
    ->orderByDesc('id') // Sort by latest order first
    ->get();

        return view('seller.conform_call_list',compact('orders'));

    }
    
    public function reject_call_list(){
        $seller = auth()->user();
        $orders = Order::query()
            ->where('seller_no',$seller->code)
            ->where('status','Reject')
            ->get();
        return view('seller.reject_call_list',compact('orders'));

    }
    
    public function not_answer_call_list(){
        $seller = auth()->user();
        $orders = Order::query()
            ->where('seller_no',$seller->code)
            ->where('status','Not Answer')
            ->get();
        return view('seller.not_awnser_call_list',compact('orders'));

    }
    
    public function other_call_list(){
        $seller = auth()->user();
        $orders = Order::query()
            ->where('seller_no',$seller->code)
            ->where('status','Other')
            ->orderBy('id')
            ->get();
        return view('seller.other_call_list',compact('orders'));

    }

   public function store(Request $request)
    {

        $total = $request->qty * $request->price;

        if (auth()->user()->type == 'system_seller') {
            try {

                $seller = Saler::query()
                    ->where('code', auth()->user()->code)
                    ->first();

                $item = Product::query()
                    ->where('code', $request->item_code)
                    ->first();
                $deposit = $seller->cal_deposit;
                $newDeposit = $deposit - $item->packing_cost;


                Order::query()
                    ->where('id', $request->id)
                    ->update([
                        'customer_name' => $request->customer_name,
                        'qty' => $request->qty,
                        'price' => $request->price,
                        'status' => $request->status,
                        'status_date' => date('Y-m-d'),
                        'remark' => request()->remark,
                        'city' => $request->city,
                        'total' => $total
                    ]);

                Saler::query()
                    ->where('id', auth()->user()->id)
                    ->update([
                        'cal_deposit' => $newDeposit,
                    ]);


                return redirect()->route('call.index');
            } catch (\Exception $e) {
                return $e;
            }
        }
        else{
            try {
                Order::query()
                    ->where('id', $request->id)
                    ->update([
                        'customer_name' => $request->customer_name,
                        'qty' => $request->qty,
                        'price' => $request->price,
                        'status' => $request->status,
                        'status_date' => date('Y-m-d'),
                        'remark' => request()->remark,
                        'city' => $request->city,
                        'total' => $total
                    ]);

                return redirect()->route('call.index');
            }
            catch (\Exception $e) {
                return $e;
            }

        }
    }

}
