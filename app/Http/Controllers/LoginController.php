<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function loginCheck(Request $request)
    {
        // Validate the request
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $check=User::query()
            ->where('username',$request->username)
            ->where('view_password',$request->password)
            ->first();

        if ($check){
            if ($check->type == 'admin'){
                return redirect()->route('admin.dashboard');
            }
            else{
                return redirect()->route('seller.dashboard');
            }
        }
        else{
            return redirect()->route('login');
        }


    }

}
