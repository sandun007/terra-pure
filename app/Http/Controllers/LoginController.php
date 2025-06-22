<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    public function loginCheck(Request $request)
    {

//        // Validate the input
//        $request->validate([
//            'username' => 'required|string',
//            'password' => 'required|string',
//        ]);
        $check = User::query()
            ->where('username', $request->username)
            ->where('view_password', $request->password)
            ->first();
        $password = Hash::make($request->password);

        if ($check && $check->type == 'admin') {
//            dd($request->all());
            if (Auth::guard('admin')->attempt(['username' => $request->username, 'password' => $request->password])) {
                $user = Auth::guard('admin')->user();
                return redirect()->route('admin.dashboard');
            }
        } elseif ($check && $check->type == 'seller') {
            if (Auth::guard('seller')->attempt(['username' => $request->username, 'password' => $request->password])) {
                $user = Auth::guard('seller')->user();
                return redirect()->route('seller.dashboard');
            }
        }

        return redirect()->route('login')->withErrors([
            'login' => 'Invalid credentials.',
        ]);
    }


    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login');
        }

        if (Auth::guard('seller')->check()) {
            Auth::guard('seller')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login');
        }

        // fallback (optional)
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

}
