<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

// Make sure these namespaces are correct in your project:
//use App\Helpers\CodeGenerator;
//use App\Helpers\ImageUploader;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::query()
                ->where('seller_code', 0)
                ->get();

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

            return redirect()->back()->with('success', 'Category created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating category: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $category = Category::query()->findOrFail($id);
            return view('product.category_view', compact('category'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function edit($id)
    {
        try {
            $category = Category::query()->findOrFail($id);
            return view('product.category_edit', compact('category'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = Category::query()->findOrFail($id);

            // Determine if this is a wholesaler category
            $isWholesalerCategory = ($category->wholesaler_id !== null) || ($category->add_by === 'wholesaler');

            // Choose column and path based on category type
            $imageColumn = $isWholesalerCategory ? 'image' : 'img';
            $storagePath = $isWholesalerCategory ? 'wholesaler/categories' : 'category';

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

            return redirect()->back()->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating category: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $category = Category::query()->findOrFail($id);

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
}
