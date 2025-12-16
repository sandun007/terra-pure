<?php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\WholesalerProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class WholesalerCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:wholesaler');
    }

    // Index - List all categories
    public function index()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $categories = Category::where('wholesaler_id', $wholesaler->id)
                ->orWhere(function($query) use ($wholesaler) {
                    $query->where('wholesaler_id', null)
                        ->where('seller_code', 0);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $count = $categories->count();

            return view('wholesaler.categories.index', compact('categories', 'count'));
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
            $code = 'WCT' . date('Ymd') . strtoupper(Str::random(6));

            return view('wholesaler.categories.create', compact('code'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load form: ' . $e->getMessage());
        }
    }

    // Store - Save new category
    public function store(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $request->validate([
                'code' => 'required|string|unique:categories,code',
                'name' => 'required|string|max:255|unique:categories,name,NULL,id,wholesaler_id,' . $wholesaler->id,
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'status' => 'required|in:active,inactive',
            ]);

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('wholesaler/categories', 'public');
            }

            // Create category
            Category::create([
                'wholesaler_id' => $wholesaler->id,
                'code' => $request->code,
                'name' => $request->name,
                'image' => $imagePath,
                'status' => $request->status,
                'add_by' => 'wholesaler',
                'update_by' => 'wholesaler',
                'cancel' => 0,
                'seller_code' => 0,
            ]);

            return redirect()->route('wholesaler.categories.index')
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
            $wholesaler = Auth::guard('wholesaler')->user();
            $category = Category::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            $products = WholesalerProduct::where('wholesaler_id', $wholesaler->id)
                ->where('category', $category->name)
                ->with('brand')
                ->paginate(10);

            return view('wholesaler.categories.show', compact('category', 'products'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Category not found: ' . $e->getMessage());
        }
    }

    // Edit - Show edit form
    public function edit($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $category = Category::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            return view('wholesaler.categories.edit', compact('category'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Category not found: ' . $e->getMessage());
        }
    }

    // Update - Update category
    public function update(Request $request, $id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $category = Category::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $id . ',id,wholesaler_id,' . $wholesaler->id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'status' => 'required|in:active,inactive',
            ]);

            // Handle image upload
            $data = [
                'name' => $request->name,
                'status' => $request->status,
                'update_by' => 'wholesaler',
            ];

            if ($request->hasFile('image')) {
                // Delete old image
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                $data['image'] = $request->file('image')->store('wholesaler/categories', 'public');
            }

            // Update category
            $category->update($data);

            return redirect()->route('wholesaler.categories.index')
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
            $category = Category::findOrFail($id);

            // Check if category has products
            $productCount = WholesalerProduct::where('category_id', $id)
                ->where('wholesaler_id', Auth::guard('wholesaler')->id())
                ->count();

            if ($productCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category because it has products assigned to it.'
                ], 400);
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
