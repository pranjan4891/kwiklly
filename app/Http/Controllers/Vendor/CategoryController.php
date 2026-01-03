<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Vendor\VendorCategory;

class CategoryController extends Controller
{
    public function categories()
    {
        $results = VendorCategory::where('vendor_id', Auth::id())->get();
        return view('vendorpanel.category.categories', compact('results'));
    }

    public function edit($id)
    {
        $results = VendorCategory::where('vendor_id', Auth::id())->get();
        $edit = VendorCategory::findOrFail($id);
        return view('vendorpanel.category.categories', compact('results', 'edit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'cat_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('cat_img')) {
            $image = $request->file('cat_img');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('uploads/vendorCategories');

            // Create directory if not exists
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);
            $imagePath = 'uploads/vendorCategories/' . $imageName;
        }

        VendorCategory::create([
            'vendor_id' => Auth::id(),
            'category_name' => $request->category_name,
            'category_image' => $imagePath,
        ]);

        return redirect()->route('vendor.categories')->with('success', 'Category added successfully!');
    }

    public function update(Request $request, $id)
    {
        $cat = VendorCategory::findOrFail($id);

        $request->validate([
            'category_name' => 'required|string|max:255',
            'cat_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('cat_img')) {
            // Delete old image if exists
            if ($cat->category_image && File::exists(public_path($cat->category_image))) {
                File::delete(public_path($cat->category_image));
            }

            $image = $request->file('cat_img');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('uploads/vendorCategories');

            // Create directory if not exists
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);
            $cat->category_image = 'uploads/vendorCategories/' . $imageName;
        }

        $cat->category_name = $request->category_name;
        $cat->save();

        return redirect()->route('vendor.categories')->with('success', 'Category updated successfully!');
    }


    public function destroy($id)
    {
        $category = VendorCategory::where('vendor_id', Auth::id())->findOrFail($id);
        $category->delete();
        return redirect()->route('vendor.categories')->with('success', 'Category deleted successfully!');
    }
}
