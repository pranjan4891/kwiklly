<?php

namespace App\Http\Controllers\Vendor;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Vendor\VendorCategory;
use App\Models\Vendor\VendorSubCategory;


class SubCategoryController extends Controller
{
    
public function index()
{
    $v_id = auth()->user()->id;
    $categories = VendorCategory::where('vendor_id', $v_id)->get();
    $subcategories = VendorSubcategory::with('category')->where('vendor_id', $v_id)->get();
    

    return view('vendorpanel.category.subcategories', compact('categories', 'subcategories', 'v_id'));
    // return view('vendorpanel.category.categories', compact('results'));

}

public function store(Request $request)
{
    $request->validate([
        'cat_id' => 'required|exists:vendor_categories,id',
        'subcategory_name' => 'required|string|max:255',
    ]);
    VendorSubcategory::create([
        'category_id' => $request->cat_id,
        'subcategory_name' => $request->subcategory_name,
        'vendor_id' => Auth::id()
    ]);

    return redirect()->route('vendor.subcategories.index')->with('success', 'Subcategory added successfully!');
}

public function edit($id)
{
    $subcat = VendorSubcategory::findOrFail($id);
    $categories = VendorCategory::all();
    return view('vendor.subcategory.edit', compact('subcat', 'categories'));
}

public function update(Request $request, $id)
{
    $subcat = VendorSubcategory::findOrFail($id);
    $subcat->update([
        'cat_id' => $request->cat_id,
        'subcategory_name' => $request->subcategory_name,
    ]);

    return redirect()->route('vendor.subcategories.index')->with('success', 'Subcategory updated successfully!');
}

public function destroy($id)
{
    VendorSubcategory::destroy($id);
    return redirect()->route('vendor.subcategories.index')->with('success', 'Subcategory deleted successfully!');
}
}