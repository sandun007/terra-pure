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

    // Index - List all categories
    public function index()
    {
        try {
            $seller = Auth::guard('seller')->user();

            $categories = SellerCategory::where('seller_id', $seller->id)
                ->orWhere(function($query) {
                    $query->whereNull('seller_id')
                        ->where('seller_code', 1); // Default seller categories
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $count = $categories->count();

            return view('seller.category.index', compact('categories', 'count'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load categories: ' . $e->getMessage());
        }
    }

    // Create - Show create form
    public function create()
    {
        try {
            // Generate category code
            $code = 'SCT' . date('Ymd') . strtoupper(Str::random(6));

            return view('seller.category.create', compact('code'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load form: ' . $e->getMessage());
        }
    }

    // Store - Save new category
    public function store(Request $request)
    {
        try {
            $seller = Auth::guard('seller')->user();

            $request->validate([
                'code' => 'required|string|unique:seller_categories,code',
                'name' => 'required|string|max:255|unique:seller_categories,name,NULL,id,seller_id,' . $seller->id,
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'status' => 'required|in:active,inactive',
            ]);

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('seller/categories', 'public');
            }

            // Create category
            SellerCategory::create([
                'seller_id' => $seller->id,
                'code' => $request->code,
                'name' => $request->name,
                'image' => $imagePath,
                'status' => $request->status,
                'add_by' => 'seller',
                'update_by' => 'seller',
                'cancel' => 0,
                'seller_code' => 1,
            ]);

            return redirect()->route('seller.category.index')
                ->with('success', 'Category created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create category: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Show - View single category
    public function show($id)
    {
        try {
            $seller = Auth::guard('seller')->user();

            $category = SellerCategory::where(function($query) use ($seller) {
                $query->where('seller_id', $seller->id)
                    ->orWhere(function($q) {
                        $q->whereNull('seller_id')
                            ->where('seller_code', 1);
                    });
            })
                ->findOrFail($id);

            $products = SellerProduct::where('seller_id', $seller->id)
                ->where('category_id', $category->id)
                ->with('brand')
                ->paginate(10);

            return view('seller.category.show', compact('category', 'products'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Category not found: ' . $e->getMessage());
        }
    }

    // Edit - Show edit form
    public function edit($id)
    {
        try {
            $seller = Auth::guard('seller')->user();
            $category = SellerCategory::where('seller_id', $seller->id)
                ->findOrFail($id);

            return view('seller.category.edit', compact('category'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Category not found: ' . $e->getMessage());
        }
    }

    // Update - Update category
    public function update(Request $request, $id)
    {
        try {
            $seller = Auth::guard('seller')->user();
            $category = SellerCategory::where('seller_id', $seller->id)
                ->findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:255|unique:seller_categories,name,' . $id . ',id,seller_id,' . $seller->id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'status' => 'required|in:active,inactive',
            ]);

            // Handle image upload
            $data = [
                'name' => $request->name,
                'status' => $request->status,
                'update_by' => 'seller',
            ];

            // Check if user wants to remove current image
            if ($request->has('remove_current_image') && $request->remove_current_image == '1') {
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                $data['image'] = null;
            }

            if ($request->hasFile('image')) {
                // Delete old image
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                $data['image'] = $request->file('image')->store('seller/categories', 'public');
            }

            // Update category
            $category->update($data);

            return redirect()->route('seller.category.index')
                ->with('success', 'Category updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update category: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Destroy - Delete category
    public function destroy($id)
    {
        try {
            $seller = Auth::guard('seller')->user();
            $category = SellerCategory::where('seller_id', $seller->id)->findOrFail($id);

            // Check if category has products
            $productCount = SellerProduct::where('category_id', $id)
                ->where('seller_id', $seller->id)
                ->count();

            if ($productCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category because it has ' . $productCount . ' product(s) assigned to it.'
                ], 400);
            }

            // Delete image if exists
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting category: ' . $e->getMessage()
            ], 500);
        }
    }
}
