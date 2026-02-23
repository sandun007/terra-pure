<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Referral;
use App\Models\Saler;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{

    public function dashboard()
    {
        return view('seller.dashboard');
    }

    public function shop()
    {
        $products = Product::query()
            ->where('seller_code',0)
            ->get();
        return view('seller.shop.shop',compact('products'));
    }

    public function index()
    {
        $sellers = Saler::all();
        return view('seller.seller_list', compact('sellers'));
    }

    public function create()
    {
        $codeGenerate = new CodeGenerator();
        $code = $codeGenerate->newCodeLoad('seller');
        return view('seller.seller_create', compact('code'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Upload images
            $NIC_front = ImageUploader::uploadImage($request->file('nic_front'), 'seller/nic_front');
            $NIC_back = ImageUploader::uploadImage($request->file('nic_back'), 'seller/nic_back');

            // Generate seller code
            $codeGenerate = new CodeGenerator;
            $code = $codeGenerate->generateCode('seller');

            // Referral handling
            $level_02_seller_01 = 0;
            $level_03_seller_01 = 0;

            if ($request->ref_code != '0') {

                $level_01_seller_02_check = Referral::query()
                    ->where('level_01_seller_02', $request->ref_code)
                    ->first();

                if ($level_01_seller_02_check) {
                    $level_02_seller_01 = $level_01_seller_02_check->level_01_seller_01;

                    $level_01_seller_02_check_2 = Referral::query()
                        ->where('level_01_seller_02', $level_02_seller_01)
                        ->first();

                    if ($level_01_seller_02_check_2) {
                        $level_03_seller_01 = $level_01_seller_02_check_2->level_01_seller_01;
                    }
                }

                Referral::create([
                    'level_01_seller_01' => $request->ref_code,
                    'level_01_seller_02' => $code,

                    'level_02_seller_02' => $code,
                    'level_02_seller_01' => $level_02_seller_01,

                    'level_03_seller_02' => $code,
                    'level_03_seller_01' => $level_03_seller_01,
                ]);
            } else {
                Referral::create([
                    'level_01_seller_01' => 0,
                    'level_01_seller_02' => $code,

                    'level_02_seller_02' => $code,
                    'level_02_seller_01' => 0,

                    'level_03_seller_02' => $code,
                    'level_03_seller_01' => 0,
                ]);
            }

            // Create seller
            Saler::create([
                'code' => $code,
                'name' => $request->name,
                'address' => $request->address,
                'city' => $request->city ?? '',
                'district' => $request->district,
                'state' => $request->state ?? null,
                'country' => $request->country ?? 'Sri Lanka',
                'postal_code' => $request->postal_code ?? '',
                'phone' => $request->phone,
                'whatsapp' => $request->whatsapp,
                'email' => $request->email,
                'NIC_no' => $request->nic_no,
                'NIC_front' => $NIC_front,
                'NIC_back' => $NIC_back,
                'cost' => 0,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'view_password' => $request->password,
                'status' => 'pending',
            ]);

            // Create user login
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'view_password' => $request->password,
                'type' => 'seller',
            ]);

            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }

    public function edit($id)
    {
        $seller = Saler::query()
            ->where('id',$id)
            ->first();

        return view('seller.seller_update', compact('seller'));
    }

    public function update(Request $request)
    {
        $seller = Saler::query()
            ->where('id',$request->id)
            ->first();

        $NIC_front = $request->hasFile('nic_front')
            ? ImageUploader::uploadImage($request->file('nic_front'), 'seller/nic_front')
            : $seller->NIC_front;

        $NIC_back = $request->hasFile('nic_back')
            ? ImageUploader::uploadImage($request->file('nic_back'), 'seller/nic_back')
            : $seller->NIC_back;

        $new_deposit = $seller->cal_deposit + $request->deposit;
        $full_deposit =$seller->deposit + $request->deposit;

        try {
            Saler::query()
                ->where('id',$request->id)
                ->update([
                    'code'=>$request->code,
                    'name'=>$request->name,
                    'address'=>$request->address,
                    'city'=>$request->city,
                    'postal_code'=>$request->postal_code,
                    'district'=>$request->district,
                    'state'=>$request->state ?? null,
                    'country'=>$request->country ?? 'Sri Lanka',
                    'phone'=>$request->phone,
                    'whatsapp'=>$request->whatsapp,
                    'email'=>$request->email,
                    'NIC_no'=>$request->nic_no,
                    'NIC_front'=>$NIC_front,
                    'NIC_back'=>$NIC_back,
                    'deposit'=>$full_deposit,
                    'cal_deposit'=>$new_deposit,
                    'username'=>$request->username,
                    'password'=>Hash::make($request->password),
                    'view_password'=>$request->password,
                    'type'=>$request->type,
                ]);
            return redirect()->route('seller.list');
        }
        catch (\Exception $e) {
            return $e;
        }
    }

    public function new_order()
    {
        $codeGenerate = new CodeGenerator();
        $code = $codeGenerate->newCodeLoad('file');
        $user = auth()->user();
        $import='0';
        return view('seller.new_order',compact('import','user','code'));

    }

    // In SellerController
    public function completeOrder()
    {
        return view('seller.complete_order_list');
    }

    public function returnOrder()
    {
        return view('seller.return_order_list');
    }

    public function reschedulingOrder()
    {
        return view('seller.rescheduling_order_list');
    }

    public function pendingOrder()
    {
        return view('seller.pending_order_list');
    }

    public function seller_product()
    {
        return view('seller.seller_product');
    }

    // PROFILE METHODS - Added for seller profile management

    /**
     * Display seller profile
     */
    public function profile()
    {
        $seller = Auth::guard('saler')->user();

        // Get statistics
        $stats = [
            'total_orders' => $seller->orders()->count(),
            'pending_orders' => $seller->orders()->where('status', 'pending')->count(),
            'completed_orders' => $seller->orders()->where('status', 'completed')->count(),
            'total_products' => $seller->products()->count(),
            'total_sales' => $seller->total_sales ?? 0,
            'current_deposit' => $seller->deposit ?? 0,
        ];

        return view('seller.profile.profile', compact('seller', 'stats'));
    }

    /**
     * Update seller profile
     */
    public function updateProfile(Request $request)
    {
        $seller = Auth::guard('saler')->user();

        $request->validate([
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

        try {
            // Handle profile image upload
            $imagePath = $seller->img;
            if ($request->hasFile('img')) {
                // Delete old image if exists
                if ($seller->img && \Storage::disk('public')->exists($seller->img)) {
                    \Storage::disk('public')->delete($seller->img);
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

            return redirect()->route('seller.profile')
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
}
