<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\City;
use App\Models\Saler;



class EmployeeController extends Controller
{


    public function dashboard()
    {
        try {
            $orders = Order::query()
                ->where('status', 'Conform') // or 'Confirm' if typo
                ->orderBy('updated_at', 'asc')
                ->get();

            return view('employee.dashboard', compact('orders'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function index()
    {
        $employees = Employee::all();
        return view('admin.employee.employee_list', compact('employees'));
    }


    public function create(Request $request)
    {
        $codeGenerate = new CodeGenerator();
        $code = $codeGenerate->newCodeLoad('employee');
        return view('admin.employee.employee_create', compact('code'));
    }

    public function store(Request $request)
    {
        $NIC_front = ImageUploader::uploadImage($request->file('nic_front'), 'seller/nic_front');
        $NIC_back = ImageUploader::uploadImage($request->file('nic_back'), 'seller/nic_back');
        $codeGenerate = new CodeGenerator;
        $code = $codeGenerate->generateCode('employee');

        try {
            Employee::query()->create([
                'code' => $request->code,
                'name' => $request->name,
                'nic_no' => $request->nic_no,
                'mobile_no' => $request->phone,
                'whatsapp_no' => $request->whatsapp,
                'email' => $request->email,
                'address' => $request->address,
                'post_code' => $request->postal_code,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'view_password' => $request->password,
                'district' => $request->district,
                'nic_front' => $NIC_front,
                'nic_back' => $NIC_back,
            ]);
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'view_password' => $request->password,
                'type' => 'employee',
            ]);
            return redirect()->back()->with('success', 'Employee Created Successfully');
        } catch (\Exception $e) {
            return $e;
        }
    }
    
      public function view_order($id)
    {
        try {
            $order = Order::query()
                ->where('id',$id)
                ->first();
            $cities = City::all();
            return view('employee.order_view',compact('order','id','cities'));
        }
        catch (\Exception $e) {
            return $e;
        }
    }

    public function delivery_label_print($id)
    {
        $order = Order::query()
            ->where('id',$id)
            ->first();
        $seller = Saler::query()
            ->where('code',$order->seller_no)
            ->first();
        return view('employee.delivery_print_label',compact('order','seller'));
    }

}
