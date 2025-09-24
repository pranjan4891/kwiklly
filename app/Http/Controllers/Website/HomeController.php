<?php

namespace App\Http\Controllers\Website;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\VendorAdmin;
use App\Models\Product;
use App\Models\MasterLocation;
use App\Models\Page;
use App\Models\AboutUs;
use App\Models\ContactUs;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\MissionVision;
use App\Models\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Coupon;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class HomeController extends Controller
{


    public function index()
    {
        $data['trending_products'] = collect();
        $data['stores'] = collect();
        $data['categorywiseproducts'] = collect();
        $data['banners'] = Banner::where('is_deleted', '0')->get();
        $data['categories'] = Category::where('is_deleted', '0')->limit(9)->get();
        return view('web.index')->with($data);
    }

    public function locationProducts(Request $request)
    {
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');

        // 1. ✅ Find which master polygon user belongs to
        $insideLocation = MasterLocation::where('is_active', 1)
            ->where('is_deleted', 0)
            ->get()
            ->first(function($location) use ($lat, $lng) {
                $polygon = $location->lat_long; // JSON saved in DB
                return $this->pointInPolygon($lat, $lng, $polygon);
            });

        if (!$insideLocation) {
            return response()->json([
                'trending_html'   => '<p>No products available in your area.</p>',
                'stores_html'     => '<p>No stores in your area.</p>',
                'categories_html' => '<p>No categories in your area.</p>',
            ]);
        }

        // 2. ✅ Get all vendors inside this polygon
        $vendors = VendorAdmin::where('status', '1')
            ->where('is_active', '1')
            ->where('user_type', 'vendor')
            ->whereNull('deleted_at')
            ->get()
            ->filter(function ($vendor) use ($insideLocation) {
                return $this->pointInPolygon(
                    $vendor->latitude,
                    $vendor->longitude,
                    $insideLocation->lat_long
                );
            });

        $vendorIds = $vendors->pluck('id');

        // 3. ✅ Fetch vendor products
        $trending_products = Product::with('variants', 'vendor')
            ->whereIn('vendor_id', $vendorIds)
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->where('top_selling', 1)
            ->limit(10)
            ->get();

        $stores = $vendors->map(function ($store) {
            if ($store->business_category) {
                $ids = explode(',', $store->business_category);
                $store->categories = Category::whereIn('id', $ids)->get();
            } else {
                $store->categories = collect();
            }
            return $store;
        });


        $categorywiseproducts = Category::where('is_deleted', '0')
            ->where('is_home', '1')
            //->limit(3)
            ->get()
            ->map(function ($category) use ($vendorIds) {
                $category->products = Product::with('variants', 'vendor')
                    ->where('category_id', $category->id)
                    ->where('is_deleted', 0)
                    ->where('is_active', 1)
                    ->whereIn('vendor_id', $vendorIds)
                    ->limit(16)
                    ->get();
                return $category;
            })
            ->filter(function ($category) {
                // ✅ Only keep categories that actually have products
                return $category->products->count() > 0;
            })
            ->values(); // reset indexes

        // Log::info('Location products request', [
        //     'lat' => $lat,
        //     'lng' => $lng
        // ]);

        // 4. ✅ Return updated sections
        return response()->json([
            'trending_html'   => view('web.partials.trending', compact('trending_products'))->render(),
            'stores_html'     => view('web.partials.stores', compact('stores'))->render(),
            'categories_html' => view('web.partials.categories', compact('categorywiseproducts'))->render(),
        ]);
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
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');



        // ✅ Vendors only inside polygon (admin + branch)
        $branches = collect();
        if ($lat && $lng) {
            $insideLocation = MasterLocation::where('is_active', 1)
                ->where('is_deleted', 0)
                ->get()
                ->first(function ($location) use ($lat, $lng) {
                    return $this->pointInPolygon($lat, $lng, $location->lat_long);
                });

            if ($insideLocation) {
                $branches = VendorAdmin::whereIn('user_type', ['admin', 'branch'])
                    ->where('status', '1')
                    ->where('is_active', '1')
                    ->whereNull('deleted_at')
                    ->get()
                    ->filter(function ($vendor) use ($insideLocation) {
                        return $this->pointInPolygon($vendor->latitude, $vendor->longitude, $insideLocation->lat_long);
                    });
            }
        }

        // ✅ Selected vendor logic
        $selectedBranchId = $request->get('branch');
        $selectedVendor   = null;

        if ($selectedBranchId) {
            // If branch id is provided, select directly
            $selectedVendor = $branches->where('id', (int) $selectedBranchId)->first();

        }

        if (!$selectedVendor) {
            // 👉 Prefer admin first
            $selectedVendor = $branches->where('user_type', 'admin')->first();

            // 👉 If no admin, then pick first branch
            if (!$selectedVendor) {
                $selectedVendor = $branches->where('user_type', 'branch')->first();
            }
        }

        // ✅ Products & Subcategories
        $products = collect();
        $subcategories = collect();

        if ($selectedVendor) {
            $products = Product::with('variants')
                ->where('vendor_id', $selectedVendor->id)
                ->where('is_active', '1')
                ->get();

            // ✅ Get unique sub_category_ids from products
            $subcategoryIds = $products->pluck('sub_category_id')->filter()->unique();

            $subcategories = Subcategory::where('is_deleted', '0')
                ->whereIn('id', $subcategoryIds)
                ->get();

        } else {
            //Log::warning('No selected vendor found', ['branches_count' => $branches->count()]);
        }

        // ✅ Store time
        $currentDay  = now()->format('l');
        $currentTime = null;
        $isOpen      = false;

        if ($selectedVendor && !empty($selectedVendor->store_time)) {
            $storeTimes = is_array($selectedVendor->store_time)
                ? $selectedVendor->store_time
                : json_decode($selectedVendor->store_time, true);

            if ($storeTimes && is_array($storeTimes)) {
                $todaySchedule = collect($storeTimes)->firstWhere('day_name', $currentDay);
                if ($todaySchedule && ($todaySchedule['status'] ?? '0') === '1') {
                    $startTime   = $todaySchedule['startTime'] ?? '';
                    $endTime     = $todaySchedule['endTime'] ?? '';
                    $currentTime = ($startTime && $endTime) ? ($startTime . ' - ' . $endTime) : null;
                    $isOpen      = true;
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
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');
        $branchId = (int) $request->get('branch');

        if (!$branchId || !$lat || !$lng) {
            return response()->json(['success' => false, 'message' => 'Missing branch or location']);
        }

        // ✅ Vendors only inside polygon (admin + branch)
        $branches = collect();
        $insideLocation = MasterLocation::where('is_active', 1)
            ->where('is_deleted', 0)
            ->get()
            ->first(function ($location) use ($lat, $lng) {
                return $this->pointInPolygon($lat, $lng, $location->lat_long);
            });

        if ($insideLocation) {
            $branches = VendorAdmin::whereIn('user_type', ['admin', 'branch'])
                ->where('status', '1')
                ->where('is_active', '1')
                ->whereNull('deleted_at')
                ->get()
                ->filter(function ($vendor) use ($insideLocation) {
                    return $this->pointInPolygon($vendor->latitude, $vendor->longitude, $insideLocation->lat_long);
                });
        }

        $selectedVendor = $branches->where('id', $branchId)->first();
        if (!$selectedVendor) {
            return response()->json(['success' => false, 'message' => 'Vendor not found in your area']);
        }

        // ✅ Products
        $products = Product::with('variants')
            ->where('vendor_id', $selectedVendor->id)
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->get();

        // ✅ Store time
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

        $productsHtml = view('web.partials.products', compact('products', 'selectedVendor'))->render();
        $headerHtml   = view('web.partials.vendor_header', compact('branches', 'selectedVendor', 'currentDay', 'currentTime', 'isOpen'))->render();

        return response()->json([
            'success' => true,
            'html'    => $productsHtml,
            'header'  => $headerHtml,
        ]);
    }

    public function stores(Request $request, $slug = 'all')
    {
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');

      //  Log::info("📍 Stores Request:", ['lat' => $lat, 'lng' => $lng, 'slug' => $slug]);

        // ✅ All categories
        $data['categories'] = Category::where('is_deleted', '0')->get();

        $vendors = collect();

        if ($lat && $lng) {
            // 1. Find inside polygon location
            $insideLocation = MasterLocation::where('is_active', 1)
                ->where('is_deleted', 0)
                ->get()
                ->first(function ($location) use ($lat, $lng) {
                    $inside = $this->pointInPolygon($lat, $lng, $location->lat_long);
                //    Log::info("🔎 Polygon check for location {$location->id}", ['inside' => $inside]);
                    return $inside;
                });

            if (!$insideLocation) {
               // Log::warning("⚠️ No polygon matched for lat/lng", ['lat' => $lat, 'lng' => $lng]);
            } else {
               // Log::info("✅ Inside Location Found:", ['id' => $insideLocation->id]);

                // 2. Get vendors inside that polygon
                $vendors = VendorAdmin::where('status', '1')
                    ->where('is_active', '1')
                    ->where('user_type', 'vendor')
                    ->whereNull('deleted_at')
                    ->with(['products.fcategory']) // ✅ eager load
                    ->get()
                    ->filter(function ($vendor) use ($insideLocation) {
                        return $this->pointInPolygon($vendor->latitude, $vendor->longitude, $insideLocation->lat_long);
                    });
            }
        } else {
          //  Log::warning("⚠️ No lat/lng provided in request");
        }

        // ✅ Filter by category slug if provided
        if ($slug && $slug !== 'all') {
            $category = Category::where('slug', $slug)->first();

            if ($category) {
              //  Log::info("📂 Filtering by category:", ['id' => $category->id, 'name' => $category->name]);

                $vendorIds = Product::where('category_id', $category->id)
                    ->pluck('vendor_id')
                    ->unique();

              //  Log::info("Vendor IDs with products in category {$category->id}:", $vendorIds->toArray());

                $data['stores'] = $vendors->filter(function ($vendor) use ($vendorIds) {
                    return $vendorIds->contains($vendor->id);
                });

              //  Log::info("Stores after category filter:", $data['stores']->pluck('id')->toArray());
            } else {
              //  Log::warning("⚠️ Invalid category slug: " . $slug);
                $data['stores'] = collect();
            }

            $data['slug'] = $slug;
        } else {
            // Show all vendors
            $data['stores'] = $vendors;
            $data['slug'] = 'all';
          //  Log::info("📍 Showing all vendors:", $vendors->pluck('id')->toArray());
        }

        $data['banners'] = Banner::where('banner_cat_id', '4')->where('is_deleted', '0')->get();

        return view('web.stores')->with($data);
    }

    public function explorestore(Request $request, $vendor_id, $cat_id = 0)
    {
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');

        $vendors = collect();
        $insideLocation = null;

        if ($lat && $lng) {
            // 1. Find inside polygon location
            $insideLocation = MasterLocation::where('is_active', 1)
                ->where('is_deleted', 0)
                ->get()
                ->first(function ($location) use ($lat, $lng) {
                    return $this->pointInPolygon($lat, $lng, $location->lat_long);
                });

            if ($insideLocation) {
                // 2. Get vendors inside that polygon
                $vendors = VendorAdmin::where('status', '1')
                    ->where('is_active', '1')
                    ->where('user_type', 'vendor')
                    ->whereNull('deleted_at')
                    ->with(['products.category', 'products.subcategory'])
                    ->get()
                    ->filter(function ($vendor) use ($insideLocation) {
                        return $this->pointInPolygon($vendor->latitude, $vendor->longitude, $insideLocation->lat_long);
                    });
            }
        }

        // ✅ Vendor check
        $vendor = $vendors->firstWhere('id', (int) $vendor_id);
        if (!$vendor) {
            abort(404, 'Vendor not found in your location');
        }

        // ✅ Products logic
        if ($cat_id == 0) {
            // All vendor products
            $category = null;
            $products = $vendor->products;

            // Get all subcategories that have products for this vendor
            $subcategories = SubCategory::whereIn('id', $products->pluck('sub_category_id')->filter())
                ->where('is_deleted', 0)
                ->get();
        } else {
            // Products for one category
            $category = Category::findOrFail($cat_id);
            $products = $vendor->products->where('category_id', $cat_id);

            // Get only subcategories from this category that vendor has products in
            $subcategories = SubCategory::where('category_id', $cat_id)
                ->whereIn('id', $products->pluck('sub_category_id')->filter())
                ->where('is_deleted', 0)
                ->get();
        }
        // ✅ Coupons
        $coupons = Coupon::where('created_by_id', $vendor->id)->where('is_active', 1)->where('is_deleted', 0)->get();


        return view('web.explorestore', compact('vendor', 'category', 'subcategories', 'products', 'coupons'));
    }

    public function subcategoryProducts(Request $request, $vendor_id, $category_id, $subcategory_id)
    {
        $lat = $request->latitude;
        $lng = $request->longitude;
        $insideLocation = null;

        if ($lat && $lng) {
            $insideLocation = MasterLocation::where('is_active', 1)
                ->where('is_deleted', 0)
                ->get()
                ->first(function ($location) use ($lat, $lng) {
                    $inside = $this->pointInPolygon($lat, $lng, $location->lat_long);
                   // Log::info("🔎 Polygon check for location {$location->id}", ['inside' => $inside]);
                    return $inside;
                });

            if (!$insideLocation) {
                Log::warning("⚠️ No polygon matched for lat/lng", ['lat' => $lat, 'lng' => $lng]);
                return view('web.explorestore', [
                    'vendor'        => null,
                    'category'      => null,
                    'subcategory'   => null,
                    'subcategories' => collect(),
                    'products'      => collect(),
                    'error'         => 'No stores available in your location'
                ]);
            }

          //  Log::info("✅ Inside Location Found", ['id' => $insideLocation->id]);
        } else {
          //  Log::warning("⚠️ No lat/lng provided in request");
        }

        // ✅ Vendor
        $vendor = VendorAdmin::where('status', '1')
            ->where('is_active', '1')
            ->where('user_type', 'vendor')
            ->whereNull('deleted_at')
            ->with(['products.category'])
            ->findOrFail($vendor_id);

        // ✅ Category + Subcategory
        $category    = Category::findOrFail($category_id);
        $subcategory = SubCategory::findOrFail($subcategory_id);

        // All subcategories of category
        $subcategories = SubCategory::where('category_id', $category_id)
            ->where('is_deleted', '0')
            ->get();

        // ✅ Products filter
        $products = $vendor->products()
            ->with('variants')
            ->where('category_id', $category_id)
            ->where('sub_category_id', $subcategory_id)
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->get();

        //vendor wise coupons
        $coupons = Coupon::where('created_by_id', $vendor->id)->where('is_active', 1)->where('is_deleted', 0)->get();

        return view('web.explorestore', compact('vendor', 'category', 'subcategory', 'subcategories', 'products', 'coupons'));
    }


    public function allCategoryProducts(Request $request, $category_id)
    {
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');

        $category = Category::findOrFail($category_id);

        $subcategories = SubCategory::where('category_id', $category_id)
            ->where('is_deleted', '0')
            ->get();

        if (!$lat || !$lng) {
          //  Log::warning("No lat/lng received in request");
            return view('web.categorywiseproduct', [
                'category' => $category,
                'subcategories' => $subcategories,
                'products' => collect(),
            ])->with('error', 'Please enable location to see products.');
        }

        $insideLocation = MasterLocation::where('is_active', 1)
            ->where('is_deleted', 0)
            ->get()
            ->first(function ($location) use ($lat, $lng) {
                $inside = $this->pointInPolygon($lat, $lng, $location->lat_long);
                Log::info("Polygon check for location {$location->id}", ['inside' => $inside]);
                return $inside;
            });

        if (!$insideLocation) {
          //  Log::warning("No polygon matched for lat/lng", ['lat' => $lat, 'lng' => $lng]);
            return view('web.categorywiseproduct', [
                'category' => $category,
                'subcategories' => $subcategories,
                'products' => collect(),
            ])->with('error', 'No products available in your area.');
        }

        $vendors = VendorAdmin::where('status', '1')
            ->where('is_active', '1')
            ->whereNull('deleted_at')
            ->get()
            ->filter(function ($vendor) use ($insideLocation) {
                $inside = $this->pointInPolygon($vendor->latitude, $vendor->longitude, $insideLocation->lat_long);
                Log::info("Vendor {$vendor->id} inside polygon?", ['inside' => $inside]);
                return $inside;
            });

        $vendorIds = $vendors->pluck('id');
       // Log::info("Vendors inside polygon", $vendorIds->toArray());

        $products = Product::with(['variants', 'vendor'])
            ->where('category_id', $category_id)
            ->whereIn('vendor_id', $vendorIds)
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->get();

        Log::info("Products found:", ['count' => $products->count()]);

        return view('web.categorywiseproduct', compact('category', 'subcategories', 'products'));
    }

    public function CategoryProducts(Request $request, $category_id, $subcategory_id)
    {
        // ✅ Pick lat/lng either from request (hidden footer fields) or default null
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');

        $category = Category::findOrFail($category_id);
        $subcategory = SubCategory::findOrFail($subcategory_id);

        // Fetch all subcategories for sidebar
        $subcategories = SubCategory::where('category_id', $category_id)
            ->where('is_deleted', '0')
            ->get();

        // If no lat/lng → return empty
        if (!$lat || !$lng) {
            return view('web.categorywiseproduct', [
                'category' => $category,
                'subcategory' => $subcategory,
                'subcategories' => $subcategories,
                'products' => collect(),
            ])->with('error', 'Please enable location to see products.');
        }

        // ✅ Find which master polygon user belongs to
        $insideLocation = MasterLocation::where('is_active', 1)
            ->where('is_deleted', 0)
            ->get()
            ->first(function ($location) use ($lat, $lng) {
                return $this->pointInPolygon($lat, $lng, $location->lat_long);
            });

        if (!$insideLocation) {
            return view('web.categorywiseproduct', [
                'category' => $category,
                'subcategory' => $subcategory,
                'subcategories' => $subcategories,
                'products' => collect(),
            ])->with('error', 'No products available in your area.');
        }

        // ✅ Get vendors inside this polygon
        $vendors = VendorAdmin::where('status', '1')
            ->where('is_active', '1')
            ->whereNull('deleted_at')
            ->get()
            ->filter(function ($vendor) use ($insideLocation) {
                return $this->pointInPolygon($vendor->latitude, $vendor->longitude, $insideLocation->lat_long);
            });

        $vendorIds = $vendors->pluck('id');

        // ✅ Filter products
        $products = Product::with(['variants', 'vendor'])
            ->where('category_id', $category_id)
            ->where('sub_category_id', $subcategory_id)
            ->whereIn('vendor_id', $vendorIds)
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->get();

        return view('web.categorywiseproduct', compact('category', 'subcategory', 'subcategories', 'products'));
    }

    /**
     * Check if a point lies inside a polygon
     */
    private function pointInPolygon($lat, $lng, $polygon)
    {
        $inside = false;
        $x = $lng;
        $y = $lat;
        $points = collect(json_decode($polygon, true));
        $n = $points->count();

        for ($i = 0, $j = $n - 1; $i < $n; $j = $i++) {
            $xi = $points[$i]['lng'];
            $yi = $points[$i]['lat'];
            $xj = $points[$j]['lng'];
            $yj = $points[$j]['lat'];

            $intersect = (($yi > $y) != ($yj > $y))
                && ($x < ($xj - $xi) * ($y - $yi) / (($yj - $yi) ?: 1e-10) + $xi);
            if ($intersect) $inside = !$inside;
        }
        return $inside;
    }
    private function getInsideVendors($lat, $lng)
    {
        // find polygon
        $insideLocation = MasterLocation::where('is_active', 1)
            ->where('is_deleted', 0)
            ->get()
            ->first(function ($location) use ($lat, $lng) {
                return $this->pointInPolygon($lat, $lng, $location->lat_long);
            });

        if (!$insideLocation) {
            return collect(); // no vendors if outside polygon
        }

        // filter vendors inside polygon
        return VendorAdmin::where('status', '1')
            ->where('is_active', '1')
            ->whereNull('deleted_at')
            ->get()
            ->filter(function ($vendor) use ($insideLocation) {
                return $this->pointInPolygon($vendor->latitude, $vendor->longitude, $insideLocation->lat_long);
            });
    }
    public function productdetails()
    {
        return view('web.productdetails');
    }

    public function aboutUs(){
        $about = AboutUs::first();
        $mission = MissionVision::first();
        $stats = Stat::all();
        $features = Feature::all();
        $stats = Stat::all();
        $faqs = Faq::all();
        return view('web.about', compact('about', 'mission', 'stats', 'features', 'faqs'));
    }

    // Frontend - show policy
    public function show($slug) {
        $policy = Page::where('slug', $slug)->firstOrFail();
        return view('web.pages.policy', compact('policy'));
    }

    public function sendEnquiry(Request $request){

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);

        $enquiry = new ContactUs();
        $enquiry->name = $request->name;
        $enquiry->email = $request->email;
        $enquiry->subject = $request->subject;
        $enquiry->message = $request->message;
        $enquiry->save();

        return redirect()->back()->with('success', 'Enquiry sent successfully');
    }


}
