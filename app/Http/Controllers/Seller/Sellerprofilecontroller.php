<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Saler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Sellerprofilecontroller extends Controller
{
    /**
     * Display the seller's profile
     */
    public function index()
    {
        $seller = Auth::guard('seller')->user();

        $stats = [
            'total_orders'    => 0,
            'pending_orders'  => 0,
            'completed_orders'=> 0,
            'total_products'  => 0,
            'total_sales'     => $seller->total_sales ?? 0,
            'current_deposit' => $seller->deposit ?? 0,
        ];

        // Load order statistics
        try {
            $stats['total_orders']    = Order::where('saler_id', $seller->id)->count();
            $stats['pending_orders']  = Order::where('saler_id', $seller->id)
                ->where('status', 'pending')->count();
            $stats['completed_orders']= Order::where('saler_id', $seller->id)
                ->where('status', 'completed')->count();
        } catch (\Exception $e) {
            Log::info('Orders stats error: ' . $e->getMessage());
        }

        // Load product statistics
        try {
            $stats['total_products'] = Product::where('saler_id', $seller->id)->count();
        } catch (\Exception $e) {
            Log::info('Products stats error: ' . $e->getMessage());
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
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:salers,email,' . $seller->id,
            'phone'       => 'required|string|max:20',
            'whatsapp'    => 'nullable|string|max:20',
            'address'     => 'required|string',
            'city'        => 'required|string|max:100',
            'district'    => 'required|string|max:100',
            'state'       => 'nullable|string|max:100',
            'country'     => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:20',
            'type'        => 'nullable|string|max:50',
            'img'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'NIC_no'      => 'nullable|string|max:20',
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
            // ── Profile Image Upload ──────────────────────────────────────────
            $imagePath = $seller->img; // keep existing path by default

            if ($request->hasFile('img')) {

                // Delete old image from public disk if it exists
                if ($seller->img && Storage::disk('public')->exists($seller->img)) {
                    Storage::disk('public')->delete($seller->img);
                }

                // Store new image and get its relative path
                // e.g. sellers/profiles/seller_ABC123_1700000000.jpg
                $image     = $request->file('img');
                $imageName = 'seller_' . $seller->code . '_' . time()
                    . '.' . $image->getClientOriginalExtension();

                $imagePath = Storage::disk('public')
                    ->putFileAs('sellers/profiles', $image, $imageName);
            }

            // ── Update Seller Record ──────────────────────────────────────────
            $seller->update([
                'name'        => $request->name,
                'email'       => $request->email,
                'phone'       => $request->phone,
                'whatsapp'    => $request->whatsapp,
                'address'     => $request->address,
                'city'        => $request->city,
                'district'    => $request->district,
                'state'       => $request->state,
                'country'     => $request->country ?? 'Sri Lanka',
                'postal_code' => $request->postal_code,
                'type'        => $request->type,
                'img'         => $imagePath,
                'NIC_no'      => $request->NIC_no,
            ]);

            // ── Build fresh public URL for the response ───────────────────────
            // Storage::disk('public')->url() returns: /storage/sellers/profiles/filename.jpg
            $profileImageUrl = $imagePath
                ? Storage::disk('public')->url($imagePath)
                : asset('asset/img/default-avatar.png');

            if ($request->ajax()) {
                return response()->json([
                    'success'           => true,
                    'message'           => 'Profile updated successfully!',
                    'profile_image_url' => $profileImageUrl,
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
            'new_password'     => 'required|min:6|confirmed',
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

        // Verify current password
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
                'password'      => Hash::make($request->new_password),
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
