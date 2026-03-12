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
        $check = User::query()
            ->where('username', $request->username)
            ->where('view_password', $request->password)
            ->first();

        // If user is not in users table, check wholesaler guard (they might only be in wholesalers table)
        if (!$check) {
            if (Auth::guard('wholesaler')->attempt(['username' => $request->username, 'password' => $request->password])) {
                return redirect()->route('wholesaler.dashboard');
            }

            return redirect()->route('login')->withErrors([
                'login' => 'Invalid credentials.',
            ]);
        }

        // Admin login
        if ($check->type === 'admin') {
            if (Auth::guard('admin')->attempt(['username' => $request->username, 'password' => $request->password])) {
                return redirect()->route('admin.dashboard');
            }
        }
        // Seller login
        elseif ($check->type === 'seller') {
            if (Auth::guard('seller')->attempt(['username' => $request->username, 'password' => $request->password])) {
                // All categorized sellers (system, retail, wholesale, self) go to seller dashboard
                if (in_array($check->seller_type, ['system', 'retail', 'wholesale', 'self'])) {
                    return redirect()->route('seller.dashboard');
                }
                
                // Default fallback for sellers
                return redirect()->route('seller.dashboard');
            }
        }
        // Wholesaler login (if they exist in users table)
        elseif ($check->type === 'wholesalers' || $check->type === 'wholesaler') {
            if (Auth::guard('wholesaler')->attempt(['username' => $request->username, 'password' => $request->password])) {
                return redirect()->route('wholesaler.dashboard');
            }
        }
        // Employee login
        elseif ($check->type === 'employee') {
            if (Auth::guard('employee')->attempt(['username' => $request->username, 'password' => $request->password])) {
                return redirect()->route('employee.dashboard');
            }
        }

        // Fallback for Wholesaler login, since they are largely stored only in wholesalers table
        if (Auth::guard('wholesaler')->attempt(['username' => $request->username, 'password' => $request->password])) {
            return redirect()->route('wholesaler.dashboard');
        }

        // If authentication fails for any guard
        return redirect()->route('login')->withErrors([
            'login' => 'Invalid credentials or user type.',
        ]);
    }

    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } elseif (Auth::guard('seller')->check()) {
            Auth::guard('seller')->logout();
        } elseif (Auth::guard('wholesaler')->check()) {
            Auth::guard('wholesaler')->logout();
        } elseif (Auth::guard('employee')->check()) {
            Auth::guard('employee')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
