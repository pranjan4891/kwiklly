<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\Subcategory;
use App\Models\AttributeValue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

use App\Exports\CategoriesExport;
use App\Exports\SubcategoriesExport;
use Maatwebsite\Excel\Facades\Excel;


class CategoryController extends Controller
{
    //
    public function index(){

        $title = 'Admin | Categories';
        $categories = Category::where('is_deleted', 0)->latest()->get();
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        return view('admin.category',compact('title','admin','categories'));
    }

     // Show form to create a new category
    //  public function create()
    //  {
    //      return view('admin.category.create'); // You may separate this if needed
    //  }

     // Store a new category
     public function store(Request $request)
     {
         $request->validate([
             'category_name' => 'required|string|max:255',
             'cat_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|dimensions:min_width=120,min_height=120'
         ]);

         $imagePath = null;
         if ($request->hasFile('cat_img')) {
             $image = $request->file('cat_img');
             $imageName = time() . '_' . $image->getClientOriginalName();
             $image->move(public_path('uploads/categories'), $imageName);
             $imagePath = 'uploads/categories/' . $imageName;
         }

         Category::create([
             'va_id' => 1, // replace or dynamically get this
             'name' => strtoupper($request->category_name),
             'slug' => Str::slug($request->category_name),
             'image' => $imagePath,
             'is_active' => 1,
             'is_deleted' => 0,
         ]);

         return redirect()->route('admin.categories')->with('success', 'Category added successfully.');
     }

      // is home toggle
    public function toggleHome(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->is_home = $request->has('is_home') ? 1 : 0;
        $category->save();

        return redirect()->route('admin.categories')->with('success', 'Category updated successfully.');
    }
     // Show edit form
     public function edit($id)
     {
         $category = Category::findOrFail($id);
         return view('admin.category.edit', compact('category'));
     }

     // Update category
     public function update(Request $request, $id)
     {
         $category = Category::findOrFail($id);

         $request->validate([
             'category_name' => 'required|string|max:255',
             'cat_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
         ]);

         $category->name = strtoupper($request->category_name);
         $category->slug = Str::slug($request->category_name);

         if ($request->hasFile('cat_img')) {
             $image = $request->file('cat_img');
             $imageName = time() . '_' . $image->getClientOriginalName();
             $image->move(public_path('uploads/categories'), $imageName);
             $category->image = 'uploads/categories/' . $imageName;
         }

         $category->save();

         return redirect()->route('admin.categories')->with('success', 'Category updated successfully.');
     }



     // Soft delete category
     public function destroy($id)
     {
         $category = Category::findOrFail($id);
         $category->is_deleted = 1;
         $category->save();

         return redirect()->route('admin.categories')->with('success', 'Category deleted successfully.');
     }

     //Show All Subcategories
    public function indexSubcategory()
    {
        $title = 'Admin | Subcategories';
        $categories = Category::where('is_deleted', 0)->latest()->get();
        $attributes = Attribute::where('is_deleted', 0)->latest()->get();
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }

        $subcategories = Subcategory::with(['category', 'attributes'])->where('is_deleted', 0)->latest()->get();

        return view('admin.subcategory', compact('title', 'admin', 'categories', 'attributes', 'subcategories'));
    }


    // For AJAX request to fetch attribute values
    public function getAttributeValues(Request $request)
    {
        $values = AttributeValue::where('is_deleted', 0)
            ->where('attribute_id', $request->attribute_id)
            ->get();

        return response()->json($values);
    }

    // Store a new subcategory
    public function storeSubcategory(Request $request)
    {
        // Validate the request including webp support
        $validator = Validator::make($request->all(), [
            'cat_id' => 'required|exists:categories,id',
            'subcategory_name' => 'required|string|max:255',
            'attribute' => 'required|array',
            'attribute.*' => 'exists:attributes,id',
            'status' => 'required|in:0,1',
            'subcat_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Handle image upload
        $imageName = null;
        if ($request->hasFile('subcat_img')) {
            $image = $request->file('subcat_img');
            $extension = $image->getClientOriginalExtension();
            $imageName = time() . '_' . Str::slug($request->subcategory_name) . '.' . $extension;
            $image->move(public_path('uploads/subcategories'), $imageName);
        }

        $subcategory = Subcategory::create([
            'va_id' => Auth::guard('admin')->user()->id,
            'category_id' => $request->cat_id,
            'sub_cat_name' => $request->subcategory_name,
            'sub_cat_slug' => Str::slug($request->subcategory_name),
            'image' => $imageName,
            'is_active' => $request->status,
            'is_deleted' => 0,
        ]);

        // Attach multiple attributes
        $subcategory->attributes()->attach($request->attribute);

        return redirect()->back()->with('success', 'Subcategory created successfully.');
    }

    // Update subcategory
    public function updateSubcategory(Request $request, $id)
    {
        $subcategory = Subcategory::findOrFail($id);

        $request->validate([
            'cat_id' => 'required|exists:categories,id',
            'subcategory_name' => 'required|string|max:255',
            'attribute' => 'required|array',
            'attribute.*' => 'exists:attributes,id',
            'status' => 'required|in:0,1',
            'subcat_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('subcat_img')) {
            $image = $request->file('subcat_img');
            $imageName = time() . '_' . Str::slug($request->subcategory_name) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/subcategories'), $imageName);
            $subcategory->image = 'uploads/subcategories/' . $imageName;
        }

        $subcategory->category_id = $request->cat_id;
        $subcategory->sub_cat_name = $request->subcategory_name;
        $subcategory->sub_cat_slug = Str::slug($request->subcategory_name);
        $subcategory->is_active = $request->status;
        $subcategory->save();

        // Sync multiple attributes
        $subcategory->attributes()->sync($request->attribute);

        return redirect()->route('admin.subcategories')->with('success', 'Subcategory updated successfully.');
    }



    public function softDeleteSubcategory($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $subcategory->is_deleted = 1;
        $subcategory->save();

        return redirect()->route('admin.subcategories')->with('success', 'Subcategory deleted successfully.');
    }

    public function exportCategories()
    {
        return Excel::download(new CategoriesExport, 'categories.xlsx');
    }

    // Export subcategories and their attributes
    public function exportSubcategories()
    {
        return Excel::download(new SubcategoriesExport, 'subcategories.xlsx');
    }
}
