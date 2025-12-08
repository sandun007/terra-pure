<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function create()
    {
        return view('admin.admin_create');
    }

    public function store(Request $request)
    {

        $imagePath = ImageUploader::uploadImage($request->file('img'), 'admin/images');

        try {
            Admin::query()->create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'img' => $imagePath,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'view_password' => $request->password,
            ]);
            User::query()->create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'view_password' => $request->password,
                'type' => 'admin',
            ]);
            return redirect()->route('admin.create');
        } catch (\Exception $e) {
            return $e;
        }
    }
}
