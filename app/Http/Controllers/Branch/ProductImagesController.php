<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductImages;


class ProductImagesController extends Controller
{
    //show the product images
    public function index()
    {
        $title = 'Branch | Product Images';
        $branch = auth()->guard('branch')->user();
        if (!$branch) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $productImages = ProductImages::where('is_deleted', 0)->latest()->get();
        $isDeletedView = false;

        return view('branch.product_images', compact('title', 'branch', 'productImages', 'isDeletedView'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'brand_name' => 'required',
            'description' => 'nullable',
            'feature_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $productImages = new ProductImages();
        $productImages->product_name = $request->input('product_name');
        $productImages->brand_name = $request->input('brand_name');
        $productImages->description = $request->input('description');
        $productImages->is_active = 1;
        $productImages->is_deleted = 0;

        // Feature image upload
        if ($request->hasFile('feature_image')) {
            $featureImage = $request->file('feature_image');
            $featureImageName = time() . '_' . $featureImage->getClientOriginalName();
            $featureImagePath = 'uploads/product_images';
            $featureImage->move(public_path($featureImagePath), $featureImageName);
            $productImages->feature_image = $featureImagePath . '/' . $featureImageName;
        }

        // Multiple product images
        $images = [];
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = 'uploads/product_images';
                $image->move(public_path($imagePath), $imageName);
                $images[] = $imagePath . '/' . $imageName;
            }
        }

        $productImages->product_images = $images;

        if ($productImages->save()) {
            return redirect()->back()->with('success', 'Product images uploaded successfully.');
        }

        return redirect()->back()->with('error', 'Failed to upload product images.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required',
            'brand_name' => 'required',
            'description' => 'nullable',
            'feature_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $productImages = ProductImages::findOrFail($id);
        $productImages->product_name = $request->input('product_name');
        $productImages->brand_name = $request->input('brand_name');
        $productImages->description = $request->input('description');

        // ✅ Update feature image (replace old one if exists)
        if ($request->hasFile('feature_image')) {
            if ($productImages->feature_image && file_exists(public_path($productImages->feature_image))) {
                unlink(public_path($productImages->feature_image));
            }

            $featureImage = $request->file('feature_image');
            $featureImageName = time() . '_' . $featureImage->getClientOriginalName();
            $featureImagePath = 'uploads/product_images';
            $featureImage->move(public_path($featureImagePath), $featureImageName);
            $productImages->feature_image = $featureImagePath . '/' . $featureImageName;
        }

        // ✅ Append new product images instead of replacing
        $existingImages = $productImages->product_images ?? [];

        if ($request->hasFile('product_images')) {
            $newImages = [];

            foreach ($request->file('product_images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = 'uploads/product_images';
                $image->move(public_path($imagePath), $imageName);
                $newImages[] = $imagePath . '/' . $imageName;
            }

            $productImages->product_images = array_merge($existingImages, $newImages);
        }

        if ($productImages->save()) {
            return redirect()->back()->with('success', 'Product images updated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to update product images.');
    }
        public function destroy($id)
        {
            $productImages = ProductImages::findOrFail($id);
            if ($productImages->delete()) {
                return redirect()->back()->with('success', 'Product images deleted successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to delete product images.');
            }
        }


    public function softDelete($id)
    {
       $product = ProductImages::findOrFail($id);
        $product->is_deleted = 1;

        if ($product->save()) {
            return redirect()->back()->with('success', 'Product moved to trash.');
        }

        return redirect()->back()->with('error', 'Failed to delete product.');
    }
    public function deleteSingleImage(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'image_index' => 'required|integer',
        ]);

        $product = ProductImages::findOrFail($request->product_id);

        $images = $product->product_images;

        if (is_array($images) && isset($images[$request->image_index])) {
            $imagePath = public_path($images[$request->image_index]);

            // Delete file from public folder
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Remove image from array
            array_splice($images, $request->image_index, 1);

            // Update DB
            $product->product_images = $images;
            $product->save();

            return redirect()->back()->with('success', 'Image deleted successfully.');
        }

        return redirect()->back()->with('error', 'Image not found.');
    }

    //soft delete images show
    public function showDeletedImages()
    {
        $title = 'Branch | Deleted Product Images';
        $branch = auth()->guard('branch')->user();
        if (!$branch) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $productImages = ProductImages::where('is_deleted', 1)->get();
        $isDeletedView = true;

        return view('branch.product_images', compact('title', 'branch', 'productImages', 'isDeletedView'));
    }

    public function restore($id)
    {
        $product = ProductImages::findOrFail($id);
        $product->is_deleted = 0;

        if ($product->save()) {
            return redirect()->back()->with('success', 'Product restored successfully.');
        }

        return redirect()->back()->with('error', 'Failed to restore product.');
    }

    public function erase($id)
    {
        $product = ProductImages::findOrFail($id);

        // Delete feature image
        if ($product->feature_image && file_exists(public_path($product->feature_image))) {
            unlink(public_path($product->feature_image));
        }

        // Delete all product images
        if (!empty($product->product_images)) {
            foreach ($product->product_images as $img) {
                if (file_exists(public_path($img))) {
                    unlink(public_path($img));
                }
            }
        }

        // Permanently delete record
        $product->delete();

        return redirect()->back()->with('success', 'Product permanently deleted.');
    }

    public function delete($id)
    {
        $product = ProductImages::findOrFail($id);
        $product->is_deleted = 1;

        if ($product->save()) {
            return redirect()->back()->with('success', 'Product moved to trash.');
        }

        return redirect()->back()->with('error', 'Failed to delete product.');
    }

}
