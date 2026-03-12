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
use Illuminate\Support\Facades\Storage;

class SellerController extends Controller
{
    public function dashboard()
    {
        return view('seller.dashboard');
    }

    public function shop()
    {
        $products = Product::query()
            ->where('seller_code', 0)
            ->get();
        return view('seller.shop.shop', compact('products'));
    }

    public function index()
    {
        $sellers = Saler::all();
        return view('seller.seller_list', compact('sellers'));
    }
    public function show($id)
    {
        $seller = Saler::findOrFail($id);

        return view('seller.seller_view', compact('seller'));
    }

    public function create()
    {
        $codeGenerate = new CodeGenerator();
        $code = $codeGenerate->newCodeLoad('seller');
        return view('seller.seller_create', compact('code'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:salers,username|unique:users,username',
            'email'    => 'required|email|unique:salers,email|unique:users,email',
            'name'     => 'required|string',
            'password' => 'required|min:6',
        ], [
            'username.unique' => 'The username has already been taken in either the seller or user records.',
            'email.unique'    => 'The email has already been registered in either the seller or user records.',
        ]);

        DB::beginTransaction();

        try {
            // Handle multiple images if they come as an array (due to 'multiple' attribute in form)
            $NIC_front = ImageUploader::uploadImage($request->file('nic_front'), 'seller/nic_front');
            if (is_array($NIC_front)) {
                $NIC_front = $NIC_front[0] ?? null;
            }

            $NIC_back  = ImageUploader::uploadImage($request->file('nic_back'),  'seller/nic_back');
            if (is_array($NIC_back)) {
                $NIC_back = $NIC_back[0] ?? null;
            }

            $codeGenerate = new CodeGenerator;
            $codeGenerate->generateCode('seller');

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
                    'level_01_seller_02' => $request->code,
                    'level_02_seller_02' => $request->code,
                    'level_02_seller_01' => $level_02_seller_01,
                    'level_03_seller_02' => $request->code,
                    'level_03_seller_01' => $level_03_seller_01,
                ]);
            } else {
                Referral::create([
                    'level_01_seller_01' => 0,
                    'level_01_seller_02' => $request->code,
                    'level_02_seller_02' => $request->code,
                    'level_02_seller_01' => 0,
                    'level_03_seller_02' => $request->code,
                    'level_03_seller_01' => 0,
                ]);
            }

            Saler::create([
                'code'          => $request->code,
                'name'          => $request->name,
                'address'       => $request->address,
                'city'          => $request->city ?? '',
                'district'      => $request->district,
                'state'         => $request->state ?? null,
                'country'       => $request->country ?? 'Sri Lanka',
                'postal_code'   => $request->postal_code ?? '',
                'phone'         => $request->phone,
                'whatsapp'      => $request->whatsapp,
                'email'         => $request->email,
                'NIC_no'        => $request->nic_no,
                'NIC_front'     => $NIC_front,
                'NIC_back'      => $NIC_back,
                'cost'          => 0,
                'username'      => $request->username,
                'password'      => Hash::make($request->password),
                'view_password' => $request->password,
                'status'        => 'pending',
                'deposit'       => $request->deposit ?? 0,
                'cal_deposit'   => $request->deposit ?? 0,
                'type'          => $request->type,
                'bank_name'           => $request->bank_name,
                'branch_name'         => $request->branch_name,
                'account_holder_name' => $request->account_holder_name,
                'account_number'      => $request->account_number,
            ]);


            $seller_type = 'retail';
            if ($request->type == 'system_seller') {
                $seller_type = 'system';
            } elseif ($request->type == 'self_seller') {
                $seller_type = 'retail';
            }

            User::create([
                'name'          => $request->name,
                'email'         => $request->email,
                'username'      => $request->username,
                'password'      => Hash::make($request->password),
                'view_password' => $request->password,
                'type'          => 'seller',
                'user_type'     => 'seller',
                'seller_type'   => $seller_type,
            ]);

            DB::commit();
            return redirect()->route('seller.list')->with('success', 'Seller created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $seller = Saler::findOrFail($id);
        return view('seller.seller_update', compact('seller'));
    }

    public function update(Request $request)
    {
        $seller = Saler::findOrFail($request->id);

        $NIC_front = $request->hasFile('nic_front')
            ? ImageUploader::uploadImage($request->file('nic_front'), 'seller/nic_front')
            : $seller->NIC_front;

        $NIC_back = $request->hasFile('nic_back')
            ? ImageUploader::uploadImage($request->file('nic_back'), 'seller/nic_back')
            : $seller->NIC_back;

        $new_cal_deposit = $seller->cal_deposit + ($request->deposit ?? 0);
        $full_deposit    = $seller->deposit    + ($request->deposit ?? 0);

        try {
            $seller->update([
                'code'          => $request->code,
                'name'          => $request->name,
                'address'       => $request->address,
                'city'          => $request->city,
                'postal_code'   => $request->postal_code,
                'district'      => $request->district,
                'state'         => $request->state ?? null,
                'country'       => $request->country ?? 'Sri Lanka',
                'phone'         => $request->phone,
                'whatsapp'      => $request->whatsapp,
                'email'         => $request->email,
                'NIC_no'        => $request->nic_no,
                'NIC_front'     => $NIC_front,
                'NIC_back'      => $NIC_back,
                'deposit'       => $full_deposit,
                'cal_deposit'   => $new_cal_deposit,
                'username'      => $request->username,
                'password'      => $request->filled('password') ? Hash::make($request->password) : $seller->password,
                'view_password' => $request->filled('password') ? $request->password : $seller->view_password,
                'type'          => $request->type ?? $seller->type,
                'bank_name'           => $request->bank_name,
                'branch_name'         => $request->branch_name,
                'account_holder_name' => $request->account_holder_name,
                'account_number'      => $request->account_number,
            ]);

            return redirect()->route('seller.list')->with('success', 'Seller updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function delete($id)
    {
        $seller = Saler::findOrFail($id);

        try {
            if ($seller->NIC_front) {
                Storage::disk('public')->delete($seller->NIC_front);
            }
            if ($seller->NIC_back) {
                Storage::disk('public')->delete($seller->NIC_back);
            }
            if ($seller->img) {
                Storage::disk('public')->delete($seller->img);
            }

            Referral::where('level_01_seller_02', $seller->code)
                ->orWhere('level_02_seller_02', $seller->code)
                ->orWhere('level_03_seller_02', $seller->code)
                ->delete();

            $seller->delete();

            User::where('username', $seller->username)->delete();

            return redirect()->route('seller.list')
                ->with('success', 'Seller deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete seller: ' . $e->getMessage());
        }
    }


    public function new_order()
    {
        $codeGenerate = new CodeGenerator();
        $code = $codeGenerate->newCodeLoad('file');
        $user = auth()->user();
        $import = '0';
        return view('seller.new_order', compact('import', 'user', 'code'));
    }

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

    public function profile()
    {
        $seller = Auth::guard('saler')->user();

        $stats = [
            'total_orders'     => $seller->orders()->count() ?? 0,
            'pending_orders'   => $seller->orders()->where('status', 'pending')->count() ?? 0,
            'completed_orders' => $seller->orders()->where('status', 'completed')->count() ?? 0,
            'total_products'   => $seller->products()->count() ?? 0,
            'total_sales'      => $seller->total_sales ?? 0,
            'current_deposit'  => $seller->deposit ?? 0,
        ];

        return view('seller.profile.profile', compact('seller', 'stats'));
    }

    public function updateProfile(Request $request)
    {
        $seller = Auth::guard('saler')->user();

        $request->validate([
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

        try {
            $imagePath = $seller->img;

            if ($request->hasFile('img')) {
                if ($seller->img && Storage::disk('public')->exists($seller->img)) {
                    Storage::disk('public')->delete($seller->img);
                }

                $image     = $request->file('img');
                $imageName = 'seller_' . $seller->code . '_' . time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('sellers/profiles', $imageName, 'public');
            }

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

            if ($request->ajax()) {
                return response()->json([
                    'success'           => true,
                    'message'           => 'Profile updated successfully!',
                    'profile_image_url' => $imagePath ? Storage::url($imagePath) : null
                ]);
            }

            return redirect()->route('seller.profile')
                ->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update profile: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
