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
        $import = 0;

        // Check if user is seller or admin to return appropriate view
        if (auth()->guard('seller')->check()) {
            return view('seller.new_order', compact('import', 'user'));
        } else {
            return view('admin.new_order', compact('import', 'user'));
        }
    }

    public function store(Request $request)
    {
        try {
            $import = Excel::import(new OrderImport, $request->file('excel'));

            // Fixed route redirection based on guard
            if (auth()->guard('seller')->check()) {
                return redirect()->route('seller.new-order')->with('success', 'Orders imported successfully');
            } else {
                return redirect()->route('admin.new-order')->with('success', 'Orders imported successfully');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing orders: ' . $e->getMessage());
        }
    }

    public function completeOrder()
    {
        // Return appropriate view based on guard
        if (auth()->guard('seller')->check()) {
            return view('seller.complete_order_list');
        } else {
            return view('admin.complete_order_list');
        }
    }

    public function returnOrder()
    {
        // Return appropriate view based on guard
        if (auth()->guard('seller')->check()) {
            return view('seller.return_order_list');
        } else {
            return view('admin.return_order_list');
        }
    }

    public function reschedulingOrder()
    {
        // Return appropriate view based on guard
        if (auth()->guard('seller')->check()) {
            return view('seller.rescheduling_order_list');
        } else {
            return view('admin.rescheduling_order_list');
        }
    }

    public function pendingOrder()
    {
        // Return appropriate view based on guard
        if (auth()->guard('seller')->check()) {
            return view('seller.pending_order_list');
        } else {
            return view('admin.pending_order_list');
        }
    }


}
