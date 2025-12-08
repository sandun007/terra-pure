<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::query()
                ->where('seller_code', 0)
                ->get();

            $count = $products->count();
            return view('product.product_list', compact('products', 'count'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $brands = Brand::query()
                ->where('seller_code', 0)
                ->get();
            $categories = Category::query()
                ->where('seller_code', 0)
                ->get();
            $codeGenerate = new CodeGenerator();
            $code = $codeGenerate->newCodeLoad('product');

            return view('product.product_create', compact('brands', 'categories', 'code'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $brands = Brand::query()
                ->where('seller_code', 0)
                ->get();
            $categories = Category::query()
                ->where('seller_code', 0)
                ->get();
            $product = Product::query()->findOrFail($id);

            return view('product.product_update', compact('brands', 'categories', 'product'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'qty' => 'required|numeric|min:0',
                'min_qty' => 'required|numeric|min:0',
                'stock_price' => 'required|numeric|min:0',
                'display_price' => 'required|numeric|min:0',
            ]);

            $imagePaths = [];
            if ($request->hasFile('img')) {
                foreach ($request->file('img') as $image) {
                    if ($image->isValid()) {
                        $imagePath = ImageUploader::uploadSingleImage($image, 'products');
                        if ($imagePath) {
                            $imagePaths[] = $imagePath;
                        }
                    }
                }
            }

            $codeGenerate = new CodeGenerator();

            $product = new Product();
            $product->code = $request->code; // Use the code from778rm
            $product->name = $request->name;
            $product->barcode = $request->barcode;
            $product->brand = $request->brand;
            $product->category = $request->category;
            $product->description = $request->description;
            $product->stock_price = $request->stock_price;
            $product->display_price = $request->display_price;
            $product->discount = $request->discount ?? 0;
            $product->qty = $request->qty;
            $product->min_qty = $request->min_qty;
            $product->packing_cost = $request->packing_cost ?? 0;
            $product->type = 'stock_item';
            $product->add_by = 'admin';
            $product->update_by = 'admin';
            $product->status = 'active';
            $product->cancel = 0;
            $product->seller_code = 0;

            // Store images as JSON if multiple images, or single image path
            if (!empty($imagePaths)) {
                if (count($imagePaths) === 1) {
                    $product->img = $imagePaths[0];
                } else {
                    $product->img = json_encode($imagePaths);
                }
            }

            $product->save();
            $codeGenerate->generateCode('product');
//            dd($imagePaths);

            return redirect()->route('product.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'qty' => 'required|numeric|min:0',
                'min_qty' => 'required|numeric|min:0',
                'stock_price' => 'required|numeric|min:0',
                'display_price' => 'required|numeric|min:0',
            ]);

            $product = Product::findOrFail($id);

            $imagePaths = [];

            // Handle new image uploads
            if ($request->hasFile('img')) {
                // Delete old images if they exist
                if ($product->img) {
                    if (is_json($product->img)) {
                        $oldImages = json_decode($product->img, true);
                        foreach ($oldImages as $oldImage) {
                            ImageUploader::deleteImage($oldImage);
                        }
                    } else {
                        ImageUploader::deleteImage($product->img);
                    }
                }

                // Upload new images
                foreach ($request->file('img') as $image) {
                    if ($image->isValid()) {
                        $imagePath = ImageUploader::uploadSingleImage($image, 'products');
                        if ($imagePath) {
                            $imagePaths[] = $imagePath;
                        }
                    }
                }
            }

            $product->name = $request->name;
            $product->barcode = $request->barcode;
            $product->brand = $request->brand;
            $product->category = $request->category;
            $product->description = $request->description;
            $product->stock_price = $request->stock_price;
            $product->display_price = $request->display_price;
            $product->discount = $request->discount ?? 0;
            $product->qty = $request->qty;
            $product->min_qty = $request->min_qty;
            $product->packing_cost = $request->packing_cost ?? 0;
            $product->update_by = 'admin';

            // Update images only if new images were uploaded
            if (!empty($imagePaths)) {
                if (count($imagePaths) === 1) {
                    $product->img = $imagePaths[0];
                } else {
                    $product->img = json_encode($imagePaths);
                }
            }

            $product->save();

            return redirect()->route('product.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function product_view($id)
    {
        try {
            $product = Product::query()->findOrFail($id);
            return view('product.product_view', compact('product'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $product = Product::findOrFail($id);

            // Delete associated images
            if ($product->img) {
                if (is_json($product->img)) {
                    $images = json_decode($product->img, true);
                    foreach ($images as $image) {
                        ImageUploader::deleteImage($image);
                    }
                } else {
                    ImageUploader::deleteImage($product->img);
                }
            }

            $product->delete();

            return redirect()->route('product.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}

// Helper function to check if string is JSON
if (!function_exists('is_json')) {
    function is_json($string) {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
