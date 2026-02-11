<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Saler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SellerProfileController extends Controller
{
    /**
     * Display the seller's profile
     */
    public function index()
    {
        $seller = Auth::guard('seller')->user();

        // Get statistics with proper relationship checks
        $stats = [
            'total_orders' => 0,
            'pending_orders' => 0,
            'completed_orders' => 0,
            'total_products' => 0,
            'total_sales' => $seller->total_sales ?? 0,
            'current_deposit' => $seller->deposit ?? 0,
        ];

        // Try to get orders count - check which column exists in your orders table
        try {
            // First, try to get count based on seller ID
            if (method_exists($seller, 'orders')) {
                // If your orders table has 'saler_id' column
                $stats['total_orders'] = \App\Models\Order::where('saler_id', $seller->id)->count();
                $stats['pending_orders'] = \App\Models\Order::where('saler_id', $seller->id)
                    ->where('status', 'pending')->count();
                $stats['completed_orders'] = \App\Models\Order::where('saler_id', $seller->id)
                    ->where('status', 'completed')->count();
            }
        } catch (\Exception $e) {
            // If relationship doesn't exist or column not found, stats remain 0
            \Log::info('Orders relationship error: ' . $e->getMessage());
        }

        // Try to get products count
        try {
            if (method_exists($seller, 'products')) {
                // If your products table has 'saler_id' column
                $stats['total_products'] = \App\Models\Product::where('saler_id', $seller->id)->count();
            }
        } catch (\Exception $e) {
            // If relationship doesn't exist or column not found, stats remain 0
            \Log::info('Products relationship error: ' . $e->getMessage());
        }

        return view('seller.profile.profile', compact('seller', 'stats'));
    }

    /**
     * Update the seller's profile
     */
    public function update(Request $request)
    {
        $seller = Auth::guard('seller')->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:salers,email,' . $seller->id,
            'phone' => 'required|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:20',
            'type' => 'nullable|string|max:50',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'NIC_no' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Handle profile image upload
            $imagePath = $seller->img;
            if ($request->hasFile('img')) {
                // Delete old image if exists
                if ($seller->img && Storage::disk('public')->exists($seller->img)) {
                    Storage::disk('public')->delete($seller->img);
                }

                $image = $request->file('img');
                $imageName = 'seller_' . $seller->code . '_' . time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('sellers/profiles', $imageName, 'public');
            }

            // Update seller information
            $seller->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'whatsapp' => $request->whatsapp,
                'address' => $request->address,
                'city' => $request->city,
                'district' => $request->district,
                'state' => $request->state,
                'country' => $request->country ?? 'Sri Lanka',
                'postal_code' => $request->postal_code,
                'type' => $request->type,
                'img' => $imagePath,
                'NIC_no' => $request->NIC_no,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile updated successfully!',
                    'profile_image_url' => $seller->profile_image_url
                ]);
            }

            return redirect()->route('profile.index')
                ->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update profile: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Failed to update profile: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update seller password
     */
    public function updatePassword(Request $request)
    {
        $seller = Auth::guard('seller')->user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            return redirect()->back()->withErrors($validator);
        }

        // Check current password
        if (!Hash::check($request->current_password, $seller->password)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ], 422);
            }
            return redirect()->back()->with('error', 'Current password is incorrect');
        }

        try {
            $seller->update([
                'password' => Hash::make($request->new_password),
                'view_password' => $request->new_password,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password updated successfully!'
                ]);
            }

            return redirect()->route('profile.index')
                ->with('success', 'Password updated successfully!');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update password'
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to update password');
        }
    }
}
