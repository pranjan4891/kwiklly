<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductImages;


class ProductImagesController extends Controller
{
    //show the product images
    public function index()
    {
        $title = 'Vendor | Product Images';
        $vendor = auth()->guard('vendor')->user();
        if (!$vendor) {
            return redirect()->route('vendor.login')->with('error', 'You are not authorized to access this page.');
        }
        $productImages = ProductImages::where('is_deleted', 0)->latest()->get();
        $isDeletedView = false;

        return view('vendorpanel.product_images', compact('title', 'vendor', 'productImages', 'isDeletedView'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'brand_name' => 'required',
            'description' => 'nullable|string',
            'feature_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'product_images' => 'nullable|array',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $productImages = new ProductImages();
        $productImages->product_name = $request->input('product_name');
        $productImages->brand_name = $request->input('brand_name');
        $productImages->description = $request->input('description');
        $productImages->is_active = $request->boolean('is_active');
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
            foreach ($request->file('product_images', []) as $image) {
                if (! $image instanceof \Illuminate\Http\UploadedFile || ! $image->isValid()) {
                    continue;
                }
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
            'description' => 'nullable|string',
            'feature_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'product_images' => 'nullable|array',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $productImages = ProductImages::findOrFail($id);
        $productImages->product_name = $request->input('product_name');
        $productImages->brand_name = $request->input('brand_name');
        if ($request->has('description')) {
            $productImages->description = $request->input('description');
        }
        $productImages->is_active = $request->boolean('is_active');

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
        $existingImages = $productImages->product_images;

        if ($request->hasFile('product_images')) {
            $newImages = [];

            foreach ($request->file('product_images', []) as $image) {
                if (! $image instanceof \Illuminate\Http\UploadedFile || ! $image->isValid()) {
                    continue;
                }
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = 'uploads/product_images';
                $image->move(public_path($imagePath), $imageName);
                $newImages[] = $imagePath . '/' . $imageName;
            }

            if ($newImages !== []) {
                $productImages->product_images = array_merge($existingImages, $newImages);
            }
        }

        if ($productImages->save()) {
            return redirect()->back()->with('success', 'Product images updated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to update product images.');
    }
    public function destroy($id)
    {
        return redirect()->back()->with('error', 'Only the administrator can delete product images.');
    }

    public function softDelete($id)
    {
        return redirect()->back()->with('error', 'Only the administrator can delete product images.');
    }

    public function deleteSingleImage(Request $request)
    {
        return redirect()->back()->with('error', 'Only the administrator can delete product images.');
    }

    //soft delete images show
    public function showDeletedImages()
    {
        $title = 'Vendor | Deleted Product Images';
        $vendor = auth()->guard('vendor')->user();
        if (!$vendor) {
            return redirect()->route('vendor.login')->with('error', 'You are not authorized to access this page.');
        }
        $productImages = ProductImages::where('is_deleted', 1)->get();
        $isDeletedView = true;

        return view('vendorpanel.product_images', compact('title', 'vendor', 'productImages', 'isDeletedView'));
    }

    public function restore($id)
    {
        return redirect()->back()->with('error', 'Only the administrator can restore or permanently delete product images.');
    }

    public function erase($id)
    {
        return redirect()->back()->with('error', 'Only the administrator can restore or permanently delete product images.');
    }

    public function delete($id)
    {
        return redirect()->back()->with('error', 'Only the administrator can delete product images.');
    }

}
