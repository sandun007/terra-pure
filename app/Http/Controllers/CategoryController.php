<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SellerCategory;
use Illuminate\Http\Request;

// Make sure these namespaces are correct in your project:
//use App\Helpers\CodeGenerator;
//use App\Helpers\ImageUploader;
use App\Http\Controllers\CodeGenerator;
use App\Http\Controllers\ImageUploader;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            // Get Admin and Wholesaler categories from main table
            $adminCategories = Category::with('wholesaler')->get();

            // Get Seller categories from seller_categories table
            $sellerCategories = SellerCategory::with(['seller', 'wholesaler'])->get();

            // Combine and map them to assign required view attributes explicitly for seller categories
            $categories = $adminCategories->concat($sellerCategories)->map(function ($category) {
                if ($category instanceof SellerCategory) {
                    $status = $category->status ?? 'active';
                    $category->setAttribute('status_badge', $status == 'active' ? 'badge bg-success' : 'badge bg-danger');
                    $category->setAttribute('status_text', ucfirst($status));
                    
                    if ($category->seller_id) {
                        $category->setAttribute('store', 'Seller');
                    } elseif ($category->wholesaler_id) {
                        $category->setAttribute('store', 'Wholesaler');
                    } else {
                        $category->setAttribute('store', 'Admin');
                    }

                    if ($category->seller_id && $category->seller) {
                        $category->setAttribute('creator_name', $category->seller->name . ' (Seller)');
                    } elseif ($category->wholesaler_id && $category->wholesaler) {
                        $category->setAttribute('creator_name', $category->wholesaler->name . ' (Wholesaler)');
                    } elseif ($category->seller_code == 1 && !$category->seller_id) {
                        $category->setAttribute('creator_name', 'Admin (Global Seller)');
                    } else {
                        $category->setAttribute('creator_name', 'Admin');
                    }
                }
                return $category;
            });

            $count = $categories->count();

            return view('product.category_list', compact('categories', 'count'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function create()
    {
        $codeGenerate = new CodeGenerator();
        $code = $codeGenerate->newCodeLoad('category');

        return view('product.category_create', compact('code'));
    }

    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'img'  => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ], [
            'name.required' => 'Category name is required.',
            'img.required'  => 'Category image is required.',
            'img.image'     => 'Category image must be a valid image file.',
            'img.mimes'     => 'Category image must be a JPG, JPEG, PNG, GIF, or WEBP file.',
            'img.max'       => 'Category image must be less than 2MB.',
        ]);

        try {
            // Upload image
            $imagePath = ImageUploader::uploadImage($request->file('img'), 'category');

            // Generate code
            $codeGenerate = new CodeGenerator();
            $code = $codeGenerate->generateCode('category');

            // Create
            Category::query()->create([
                'code'        => $code,
                'name'        => $request->name,
                'img'         => $imagePath,
                'status'      => $request->status ?? 'active',
                'add_by'      => 'admin',
                'update_by'   => 'admin',
                'cancel'      => 0,
                'seller_code' => 0,
            ]);

            return redirect()->route('category.index')->with('success', 'Category created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating category: ' . $e->getMessage());
        }
    }

    public function show(Request $request, $id)
    {
        try {
            if ($request->input('type') === 'seller') {
                $category = SellerCategory::query()->findOrFail($id);
                $status = $category->status ?? 'active';
                $category->setAttribute('status_badge', $status == 'active' ? 'badge bg-success' : 'badge bg-danger');
                $category->setAttribute('status_text', ucfirst($status));
            } else {
                $category = Category::query()->findOrFail($id);
            }
            return view('product.category_view', compact('category'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            if ($request->input('type') === 'seller') {
                $category = SellerCategory::query()->findOrFail($id);
                $status = $category->status ?? 'active';
                $category->setAttribute('status_badge', $status == 'active' ? 'badge bg-success' : 'badge bg-danger');
                $category->setAttribute('status_text', ucfirst($status));
            } else {
                $category = Category::query()->findOrFail($id);
            }
            return view('product.category_edit', compact('category'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if ($request->input('type') === 'seller') {
                $category = SellerCategory::query()->findOrFail($id);
                $isWholesalerCategory = false;
                $imageColumn = 'image';
                $storagePath = 'seller/categories/' . ($category->seller_id ?? 'global');
            } else {
                $category = Category::query()->findOrFail($id);
                // Determine if this is a wholesaler category
                $isWholesalerCategory = ($category->wholesaler_id !== null) || ($category->add_by === 'wholesaler');
                // Choose column and path based on category type
                $imageColumn = $isWholesalerCategory ? 'image' : 'img';
                $storagePath = $isWholesalerCategory ? 'wholesaler/categories' : 'category';
            }

            // Handle image upload if present
            if ($request->hasFile('img')) {
                // Delete old image if exists
                $oldImagePath = $category->{$imageColumn};
                if ($oldImagePath) {
                    ImageUploader::deleteImage($oldImagePath);
                }

                // Upload new image
                $newImagePath = ImageUploader::uploadImage($request->file('img'), $storagePath);
                $category->{$imageColumn} = $newImagePath;
            }

            // Update other fields
            if ($request->filled('name')) {
                $category->name = $request->name;
            }

            if ($request->filled('status')) {
                $category->status = $request->status;
            }

            $category->update_by = 'admin';
            $category->save();

            return redirect()->route('category.index')->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating category: ' . $e->getMessage());
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            if ($request->input('type') === 'seller') {
                $category = SellerCategory::query()->findOrFail($id);
            } else {
                $category = Category::query()->findOrFail($id);
            }

            // Optional: delete image also (safe)
            // If you want to delete both possible columns:
            if (!empty($category->img)) {
                ImageUploader::deleteImage($category->img);
            }
            if (!empty($category->image)) {
                ImageUploader::deleteImage($category->image);
            }

            $category->delete();

            return redirect()->back()->with('success', 'Category deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting category: ' . $e->getMessage());
        }
    }

    public function getCreators(Request $request)
    {
        try {
            $type = $request->input('type');

            if ($type === 'Seller') {
                // Only sellers who have created categories in seller_categories table
                $sellerIds = SellerCategory::whereNotNull('seller_id')
                    ->distinct()
                    ->pluck('seller_id');
                $creators = \App\Models\Saler::whereIn('id', $sellerIds)
                    ->select('id', 'name')
                    ->get();
            } elseif ($type === 'Wholesaler') {
                // Only wholesalers who have created categories in categories table
                $wholesalerIds = \App\Models\Category::whereNotNull('wholesaler_id')
                    ->distinct()
                    ->pluck('wholesaler_id');
                $creators = \App\Models\Wholesaler::whereIn('id', $wholesalerIds)
                    ->select('id', 'name')
                    ->get();
            } else {
                $creators = [];
            }

            return response()->json($creators);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
