<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginCheck(Request $request)
    {

        // Validate the input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        $check=User::query()
            ->where('username',$request->username)
            ->where('view_password',$request->password)
            ->first();


        if ($check && $check->type == 'admin'){

            if (Auth::guard('web')->attempt(['username' => $request->username, 'password' => $request->password])) {
                $user = Auth::guard('web')->user();
                return redirect()->route('admin.dashboard');
            }
        }
        elseif ($check && $check->type == 'seller'){
            if (Auth::guard('web')->attempt(['username' => $request->username, 'password' => $request->password])) {
                $user = Auth::guard('web')->user();
                return redirect()->route('seller.dashboard');
            }
        }

        return redirect()->route('login')->withErrors([
            'login' => 'Invalid credentials.',
        ]);
    }
}
