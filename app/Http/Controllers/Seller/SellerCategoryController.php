<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerCategory;
use App\Models\SellerProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SellerCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:seller');
    }

    /**
     * Display a listing of categories for the authenticated seller
     */
    public function index()
    {
        $seller = Auth::guard('seller')->user();

        // Use the scope from the model for cleaner code
        $categories = SellerCategory::sellerCategories($seller->id)
            ->latest()
            ->get();

        $count = $categories->count();

        return view('seller.category.index', compact('categories', 'count'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        // Generate a unique code
        do {
            $code = 'SCT' . now()->format('YmdHis') . rand(10, 99);
        } while (SellerCategory::where('code', $code)->exists());

        return view('seller.category.create', compact('code'));
    }

    /**
     * Store a newly created category in storage
     */
    public function store(Request $request)
    {
        $seller = Auth::guard('seller')->user();

        $request->validate([
            'code' => 'required|unique:seller_categories,code',
            'name' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($seller) {
                    $exists = SellerCategory::where('seller_id', $seller->id)
                        ->where('name', $value)
                        ->exists();

                    if ($exists) {
                        $fail('You already have a category with this name.');
                    }
                },
            ],
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $data = [
            'seller_id' => $seller->id,
            'code' => $request->code,
            'name' => $request->name,
            'status' => $request->status,
            'add_by' => 'seller',
            'update_by' => 'seller',
            'cancel' => 0,
            'seller_code' => 1,
        ];

        // Store image
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('seller/categories/' . $seller->id, 'public');
            $data['image'] = $path;
        }

        SellerCategory::create($data);

        return redirect()->route('seller.category.index')
            ->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified category and its products
     */
    public function show($id)
    {
        $seller = Auth::guard('seller')->user();

        // Get category that belongs to this seller OR is a global category (seller_code=1, seller_id=null)
        $category = SellerCategory::where(function($query) use ($seller, $id) {
            $query->where('id', $id)
                ->where(function($q) use ($seller) {
                    $q->where('seller_id', $seller->id)
                        ->orWhere(function($sub) {
                            $sub->whereNull('seller_id')
                                ->where('seller_code', 1);
                        });
                });
        })
            ->firstOrFail();

        // Get products that belong to this category AND this seller
        $products = SellerProduct::where('category_id', $id)
            ->where('seller_id', $seller->id)
            ->paginate(10);

        return view('seller.category.show', compact('category', 'products'));
    }

    /**
     * Show the form for editing the specified category
     */
    public function edit($id)
    {
        $seller = Auth::guard('seller')->user();

        // Only allow editing of seller's own categories (not global ones)
        $category = SellerCategory::where('seller_id', $seller->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('seller.category.edit', compact('category'));
    }

    /**
     * Update the specified category in storage
     */
    public function update(Request $request, $id)
    {
        $seller = Auth::guard('seller')->user();

        // Only allow updating of seller's own categories
        $category = SellerCategory::where('seller_id', $seller->id)
            ->where('id', $id)
            ->firstOrFail();

        $request->validate([
            'name' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($seller, $category) {
                    $exists = SellerCategory::where('seller_id', $seller->id)
                        ->where('name', $value)
                        ->where('id', '!=', $category->id)
                        ->exists();

                    if ($exists) {
                        $fail('You already have a category with this name.');
                    }
                },
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:active,inactive',
            'remove_current_image' => 'nullable|in:0,1',
        ]);

        $data = [
            'name' => $request->name,
            'status' => $request->status,
            'update_by' => 'seller',
        ];

        // Handle image removal
        if ($request->remove_current_image == '1' && $category->image) {
            Storage::disk('public')->delete($category->image);
            $data['image'] = null;
        }

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            // Store new image in seller-specific folder
            $path = $request->file('image')->store('seller/categories/' . $seller->id, 'public');
            $data['image'] = $path;
        }

        $category->update($data);

        return redirect()->route('seller.category.index')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category from storage
     */
    public function destroy($id)
    {
        $seller = Auth::guard('seller')->user();

        // Only allow deletion of seller's own categories
        $category = SellerCategory::where('seller_id', $seller->id)
            ->where('id', $id)
            ->firstOrFail();

        // Check if category has products
        $productCount = SellerProduct::where('category_id', $id)
            ->where('seller_id', $seller->id)
            ->count();

        if ($productCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete: This category has ' . $productCount . ' product(s) assigned.'
            ], 400);
        }

        // Delete image if exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        // For AJAX requests
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully.'
            ]);
        }

        // For regular form submissions
        return redirect()->route('seller.category.index')
            ->with('success', 'Category deleted successfully.');
    }

    /**
     * Toggle category status
     */
    public function toggleStatus($id)
    {
        $seller = Auth::guard('seller')->user();

        $category = SellerCategory::where('seller_id', $seller->id)
            ->where('id', $id)
            ->firstOrFail();

        $category->status = $category->status === 'active' ? 'inactive' : 'active';
        $category->update_by = 'seller';
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Category status updated successfully.',
            'status' => $category->status
        ]);
    }
}
