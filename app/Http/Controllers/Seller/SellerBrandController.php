<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerBrand;
use App\Models\SellerProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SellerBrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:seller');
    }

    public function index()
    {
        $seller = Auth::guard('seller')->user();

        $brands = SellerBrand::where('seller_id', $seller->id)
            ->withCount('products')
            ->latest()
            ->get();

        $activeBrands = $brands->where('status', 'active')->count();
        $totalProducts = $brands->sum('products_count');
        $topBrands = $brands->where('products_count', '>', 0)->count();

        return view('seller.brands.index', compact(
            'brands',
            'activeBrands',
            'totalProducts',
            'topBrands'
        ));
    }

    public function create()
    {
        $code = 'SBR' . now()->format('YmdHis');

        return view('seller.brands.create', compact('code'));
    }

    public function store(Request $request)
    {
        $seller = Auth::guard('seller')->user();

        $validated = $request->validate([
            'code' => 'required|unique:seller_brands,code',
            'name' => [
                'required',
                'max:255',
                Rule::unique('seller_brands')->where(function ($query) use ($seller) {
                    return $query->where('seller_id', $seller->id);
                }),
            ],
            'description' => 'nullable|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $data = [
            'seller_id' => $seller->id,
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('seller/brands', 'public');
            $data['image'] = $path;
        }

        SellerBrand::create($data);

        return redirect()->route('seller.brands.index')
            ->with('success', 'Brand created successfully!');
    }

    public function edit(SellerBrand $brand)
    {
        $seller = Auth::guard('seller')->user();

        if ($brand->seller_id != $seller->id) {
            return redirect()->route('seller.brands.index')
                ->with('error', 'Unauthorized access.');
        }

        return view('seller.brands.edit', compact('brand'));
    }

    public function update(Request $request, SellerBrand $brand)
    {
        $seller = Auth::guard('seller')->user();

        if ($brand->seller_id != $seller->id) {
            return redirect()->route('seller.brands.index')
                ->with('error', 'Unauthorized access.');
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('seller_brands')
                    ->where(function ($query) use ($seller) {
                        return $query->where('seller_id', $seller->id);
                    })
                    ->ignore($brand->id),
            ],
            'description' => 'nullable|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:active,inactive',
            'remove_current_image' => 'nullable|in:0,1',
        ]);

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
        ];

        // Remove existing image
        if ($request->input('remove_current_image') == '1' && $brand->image) {
            Storage::disk('public')->delete($brand->image);
            $data['image'] = null;
        }

        // Upload new image
        if ($request->hasFile('image')) {
            // delete old image
            if ($brand->image) {
                Storage::disk('public')->delete($brand->image);
            }

            $path = $request->file('image')->store('seller/brands', 'public');
            $data['image'] = $path;
        }

        $brand->update($data);

        return redirect()->route('seller.brands.index')
            ->with('success', 'Brand updated successfully!');
    }

    public function show($id)
    {
        $seller = Auth::guard('seller')->user();

        $brand = SellerBrand::where('id', $id)
            ->where('seller_id', $seller->id)
            ->withCount('products')
            ->firstOrFail();

        $allProducts = SellerProduct::where('brand_id', $brand->id)  // FIXED: changed from seller_brand_id to brand_id
        ->where('seller_id', $seller->id)
            ->latest()
            ->paginate(10);

        $recentProducts = $allProducts->take(5);

        return view('seller.brands.show', compact(
            'brand',
            'allProducts',
            'recentProducts'
        ));
    }

    public function destroy($id)
    {
        $seller = Auth::guard('seller')->user();

        $brand = SellerBrand::where('id', $id)
            ->where('seller_id', $seller->id)
            ->firstOrFail();

        // FIXED: Check products using direct query with correct column name
        $productCount = SellerProduct::where('brand_id', $brand->id)  // Changed from seller_brand_id to brand_id
        ->where('seller_id', $seller->id)
            ->count();

        if ($productCount > 0) {
            return back()->with('error',
                'Cannot delete brand. It is used in ' . $productCount . ' product(s).');
        }

        // Delete brand image
        if ($brand->image) {
            Storage::disk('public')->delete($brand->image);
        }

        $brand->delete();

        return redirect()->route('seller.brands.index')
            ->with('success', 'Brand deleted successfully!');
    }
}
