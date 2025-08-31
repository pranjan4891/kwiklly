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


    public function department(Request $request)
    {
        $subcategories = SubCategory::where('is_deleted', '0')->get();

        // Get both admin and branch vendors
        $branches = VendorAdmin::where(function($query) {
                $query->where('user_type', 'branch')
                    ->orWhere('user_type', 'admin');
            })
            ->where('status', '1')
            ->where('is_active', '1')
            ->whereNull('deleted_at')
            ->get();

        // Selected vendor: query param branch, else first admin
        $selectedBranchId = $request->get('branch');
        $selectedVendor = null;

        if ($selectedBranchId) {
            $selectedVendor = $branches->where('id', (int) $selectedBranchId)->first();
        }
        if (!$selectedVendor) {
            $selectedVendor = $branches->where('user_type', 'admin')->first();
        }

        // Products for selected vendor
        $products = collect();
        if ($selectedVendor) {
            $products = Product::with('variants')
                ->where('vendor_id', $selectedVendor->id)
                ->where('is_deleted', '0')
                ->where('is_active', '1')
                ->get();
        }

        // Store time / status
        $currentDay = now()->format('l');
        $currentTime = null;
        $isOpen = false;

        if ($selectedVendor && !empty($selectedVendor->store_time)) {
            $storeTimes = is_array($selectedVendor->store_time)
                ? $selectedVendor->store_time
                : json_decode($selectedVendor->store_time, true);

            if ($storeTimes && is_array($storeTimes)) {
                $todaySchedule = collect($storeTimes)->firstWhere('day_name', $currentDay);
                if ($todaySchedule && ($todaySchedule['status'] ?? '0') === '1') {
                    $startTime = $todaySchedule['startTime'] ?? '';
                    $endTime   = $todaySchedule['endTime'] ?? '';
                    $currentTime = ($startTime && $endTime) ? ($startTime . ' - ' . $endTime) : null;
                    $isOpen = true;
                }
            }
        }

        return view('web.department', compact(
            'subcategories',
            'branches',
            'products',
            'selectedVendor',
            'currentDay',
            'currentTime',
            'isOpen'
        ));
    }

    public function getProducts(Request $request)
    {
        $branchId = (int) $request->get('branch');
        if (!$branchId) {
            return response()->json(['success' => false, 'message' => 'Branch id missing']);
        }

        // Fetch selected vendor
        $selectedVendor = VendorAdmin::find($branchId);
        if (!$selectedVendor) {
            return response()->json(['success' => false, 'message' => 'Vendor not found']);
        }

        // Fetch branches for re-rendering the header dropdown
        $branches = VendorAdmin::where(function($query) {
                $query->where('user_type', 'branch')
                    ->orWhere('user_type', 'admin');
            })
            ->where('status', '1')
            ->where('is_active', '1')
            ->whereNull('deleted_at')
            ->get();

        // Products
        $products = Product::with('variants')
            ->where('vendor_id', $selectedVendor->id)
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->get();

        // Store time / status
        $currentDay = now()->format('l');
        $currentTime = null;
        $isOpen = false;

        if (!empty($selectedVendor->store_time)) {
            $storeTimes = is_array($selectedVendor->store_time)
                ? $selectedVendor->store_time
                : json_decode($selectedVendor->store_time, true);

            if ($storeTimes && is_array($storeTimes)) {
                $todaySchedule = collect($storeTimes)->firstWhere('day_name', $currentDay);
                if ($todaySchedule && ($todaySchedule['status'] ?? '0') === '1') {
                    $startTime = $todaySchedule['startTime'] ?? '';
                    $endTime   = $todaySchedule['endTime'] ?? '';
                    $currentTime = ($startTime && $endTime) ? ($startTime . ' - ' . $endTime) : null;
                    $isOpen = true;
                }
            }
        }

        // Render partials
        $productsHtml = view('web.partials.products', compact('products', 'selectedVendor'))->render();
        $headerHtml   = view('web.partials.vendor_header', compact('branches', 'selectedVendor', 'currentDay', 'currentTime', 'isOpen'))->render();

        return response()->json([
            'success' => true,
            'html'    => $productsHtml,
            'header'  => $headerHtml,
        ]);
    }

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
