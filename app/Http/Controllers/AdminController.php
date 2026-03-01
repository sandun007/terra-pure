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

    public function index()
    {
        try {
            $admins = Admin::query()
                ->latest()
                ->get();
            $count = $admins->count();

            return view('admin.admin_list', compact('admins', 'count'));
        } catch (\Exception $exception) {
            return back()->with('error', 'Failed to load admins: ' . $exception->getMessage());
        }
    }

    public function store(Request $request)
    {
        $imagePath = null;
        if ($request->hasFile('img')) {
            $imagePath = ImageUploader::uploadImage($request->file('img'), 'admin/images');
        }

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
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'view_password' => $request->password,
                'type' => 'admin',
            ]);
            $user->saveQuietly();

            return redirect()->route('admin.admins.index')->with('success', 'Admin created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create admin: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.admin_view', compact('admin'));
    }

}
