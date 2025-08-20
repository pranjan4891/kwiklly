<?php

namespace App\Http\Controllers\Website;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\VendorAdmin;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {

// DB::enableQueryLog(); //  Start logging queries

            $data['stores'] = VendorAdmin::where('status', '1')
                ->where('is_active', '1')
                ->whereNull('deleted_at')
                ->limit(6)
                ->get();

            // dd(DB::getQueryLog());

        // $data['stores']= VendorAdmin::where(['status'=>'1','is_active'=>'1','deleted_at'=>''])->get();
        $data['banners']= Banner::where('is_deleted','0')->get();
        $data['categories']= Category::where('is_deleted','0')->get();
         $data['trending_products'] = Product::with('variants')
        ->where('is_deleted', '0')
        ->where('top_selling', '1')
        ->where('is_active', '1')
        ->orderBy('id', 'desc')
        ->limit(10)
        ->get();

        return view('web.index')->with($data);
    }
    public function getProductVariants($id)
{
    $product = Product::with('variants')->findOrFail($id);

    return response()->json([
        'product_name' => $product->title,
        'image' =>  asset('public/' . $product->featureImage->feature_image),
        'variants' => $product->variants
    ]);
}
    public function department()
    {
        $subcategories = SubCategory::where('is_deleted', '0')
        ->get();

        $products = Product::with('variants')
            ->where('vendor_id',1)
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->get();

        return view('web.department',compact('subcategories', 'products'));
    }
    // public function stores($slug='')
    // {
    //     $data['categories']= Category::where('is_deleted','0')->get();
    //     $data['stores'] = VendorAdmin::where('status', '1')
    //     ->where('is_active', '1')
    //     ->whereNull('deleted_at')
    //     ->get();
    //     $data['slug'] = $slug;

    //     return view('web.stores')->with($data);
    // }
public function stores($slug = '')
{
    $data['categories'] = Category::where('is_deleted', '0')->get();
    $data['slug'] = $slug;

    if ($slug) {
        // If a category slug is provided
        $category = Category::where('slug', $slug)->first();

        if ($category) {
            $vendorIds = Product::where('category_id', $category->id)
                ->pluck('vendor_id') // Use vendor_admin_id if needed
                ->unique();

            $data['stores'] = VendorAdmin::whereIn('id', $vendorIds)
                ->where('status', '1')
                ->where('is_active', '1')
                ->whereNull('deleted_at')
                ->get();
        } else {
            $data['stores'] = collect(); // empty collection if slug invalid
        }
    } else {
        // No slug provided: use first category to fetch vendors
        $firstCategory = Category::where('is_deleted', '0')->first();
        $data['slug'] = $firstCategory?->slug ?? '';

        if ($firstCategory) {
            $vendorIds = Product::where('category_id', $firstCategory->id)
                ->pluck('vendor_id')
                ->unique();

            $data['stores'] = VendorAdmin::whereIn('id', $vendorIds)
                ->where('status', '1')
                ->where('is_active', '1')
                ->whereNull('deleted_at')
                ->get();
        } else {
            $data['stores'] = collect();
        }
    }

    $data['banners'] = Banner::where('is_deleted', '0')->get();

    return view('web.stores')->with($data);
}



public function explorestore($vendor_id, $category_slug)
{
    // Get vendor
    $vendor = VendorAdmin::findOrFail($vendor_id);

    // Get selected category by slug
    $category = Category::where('slug', $category_slug)->where('is_deleted', '0')->firstOrFail();

    // Get all subcategories of that category & vendor
    $subcategories = SubCategory::where('category_id', $category->id)
        ->where('is_deleted', '0')
        ->get();

    // If subcategories exist, use the first one to load products
    if ($subcategories->isNotEmpty()) {
        $firstSubcategory = $subcategories->first();

        // Filter products by vendor, category, and first subcategory
        $products = Product::with('variants')
            ->where('vendor_id', $vendor_id)
            ->where('category_id', $category->id)
            ->where('sub_category_id', $firstSubcategory->id)
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->get();
    } else {
        // If no subcategories, fetch products by vendor and category only
        $products = Product::with('variants')
            ->where('vendor_id', $vendor_id)
            ->where('category_id', $category->id)
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->get();
    }
    //dd($subcategories);

    return view('web.explorestore', compact('vendor', 'category', 'subcategories', 'products'));
}


public function subcategoryProducts($vendor_id, $category_id, $subcategory_id)
{
    $vendor = VendorAdmin::findOrFail($vendor_id);
    $category = Category::findOrFail($category_id);
    $subcategory = SubCategory::findOrFail($subcategory_id);

    // Fetch all subcategories for sidebar
    $subcategories = SubCategory::where('category_id', $category_id)
        ->where('is_deleted', '0')
        ->get();

    // Filter products by vendor + category + subcategory
    $products = Product::with('variants')
        ->where('vendor_id', $vendor_id)
        ->where('category_id', $category_id)
        ->where('sub_category_id', $subcategory_id)
        ->where('is_deleted', '0')
        ->where('is_active', '1')
        ->get();

    return view('web.explorestore', compact('vendor', 'category', 'subcategory', 'subcategories', 'products'));
}


    public function productdetails()
    {
        return view('web.productdetails');
    }
    public function searchresults()
    {
        return view('web.searchresults');
    }

    /*------------------------------------------------*/


    public function product()
    {
        return view('vendor.vendor-signin');
    }
    public function privacyPolicy()
    {
        return view('vendor.vendor-signin');
    }
    public function termsConditions()
    {
        return view('vendor.vendor-signin');
    }
    public function returnPolicy()
    {
        return view('vendor.vendor-signin');
    }
    public function aboutUs()
    {
        return view('vendor.vendor-signin');
    }
    public function contactUs()
    {
        return view('vendor.vendor-signin');
    }



}
