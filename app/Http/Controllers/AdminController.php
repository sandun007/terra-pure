<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

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

    public function profile()
    {
        $admin = auth()->user();
        $isOnline = Cache::has('admin-is-online-' . $admin->id);
        return view('admin.profile', compact('admin', 'isOnline'));
    }

    public function profileEdit()
    {
        $admin = auth()->user();
        return view('admin.profile_edit', compact('admin'));
    }

    public function profileUpdate(Request $request)
    {
        $admin = Admin::findOrFail(auth()->id());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'username' => 'required|string|max:255|unique:admins,username,' . $admin->id,
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'username' => $request->username,
            ];

            if ($request->hasFile('img')) {
                // Delete old image if it exists
                if ($admin->img) {
                    ImageUploader::deleteImage($admin->img);
                }
                $data['img'] = ImageUploader::uploadImage($request->file('img'), 'admin/images');
            }

            if ($request->password) {
                $data['password'] = Hash::make($request->password);
                $data['view_password'] = $request->password;
            }

            $admin->update($data);

            // Synchronize with User model
            $user = User::where('username', $admin->username)->orWhere('email', $admin->email)->where('type', 'admin')->first();
            if ($user) {
                $userData = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'username' => $request->username,
                ];
                if ($request->password) {
                    $userData['password'] = Hash::make($request->password);
                    $userData['view_password'] = $request->password;
                }
                $user->update($userData);
            }

            return back()->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }
}
