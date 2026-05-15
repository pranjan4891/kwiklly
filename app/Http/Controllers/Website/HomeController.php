<?php

namespace App\Http\Controllers\Website;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\VendorAdmin;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\ProductVariant;
use App\Models\MasterLocation;
use App\Models\DeliveryLocation;
use App\Models\Page;
use App\Models\AboutUs;
use App\Models\ContactUs;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\CartItem;
use App\Models\MissionVision;
use App\Models\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Coupon;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Services\LocationServiceability;
use Illuminate\Support\Collection;

class HomeController extends Controller
{


    public function index(Request $request)
    {
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');

        // Initialize empty collections
        $data['trending_products'] = collect();
        $data['top_selling_products'] = collect();
        $data['best_offers_products'] = collect();
        $data['sponsors_products'] = collect();
        $data['stores'] = collect();
        $data['categorywiseproducts'] = collect();
        $data['banners'] = Banner::where('is_deleted', '0')->get();

        // ✅ Get location-aware categories that have active products
        $data['categories'] = $this->getAvailableCategories($lat, $lng, 9);

        // ✅ If location is available, load products by delivery location (vendor/branch delivery areas)
        if ($lat && $lng) {
            $vendorIds = $this->getVendorIdsByDeliveryLocation($lat, $lng);
            $vendors = collect();
            if ($vendorIds->count() > 0) {
                $vendors = VendorAdmin::whereIn('id', $vendorIds)
                    ->where('status', '1')
                    ->where('is_active', '1')
                    ->where('user_type', 'vendor')
                    ->whereNull('deleted_at')
                    ->orderBy('order_by', 'asc')
                    ->get();
            }

            if ($vendorIds->count() > 0) {
                    $data['trending_products'] = $this->homepageTrendingProducts($vendorIds);
                    $data['top_selling_products'] = $this->homepageTopSellingProducts($vendorIds, $data['trending_products']);
                    $data['best_offers_products'] = $this->homepageBestOffersProducts($vendorIds);
                    $excludeCarouselIds = $data['trending_products']->pluck('id')
                        ->merge($data['top_selling_products']->pluck('id'))
                        ->unique()
                        ->filter()
                        ->values();
                    $data['sponsors_products'] = $this->homepageSponsorProducts($vendorIds, $excludeCarouselIds);

                    // Fetch stores - ensure at least one store renders the section
                    $data['stores'] = $vendors->where('is_home_request', 2)->map(function ($store) {
                        if ($store->business_category) {
                            $ids = explode(',', $store->business_category);
                            $store->categories = Category::whereIn('id', $ids)->get();
                        } else {
                          //  $store->categories = collect();
                        }
                        return $store;
                    })->values(); // Reset keys to ensure proper collection

                    // Fetch category-wise products - sirf delivery location wale vendors ke products (including out of stock)
                    $categoryIdsWithDeliveryProducts = Product::whereIn('vendor_id', $vendorIds)
                        ->where('is_active', 1)
                        ->where('is_deleted', 0)
                        ->distinct()
                        ->pluck('category_id')
                        ->unique()
                        ->filter()
                        ->values();
                    $data['categorywiseproducts'] = Category::whereIn('id', $categoryIdsWithDeliveryProducts)
                        ->where('is_deleted', '0')
                        ->where('is_home', '1')
                        ->get()
                        ->map(function ($category) use ($vendorIds) {
                            $category->products = Product::with(['variants.images', 'featureImage', 'vendor'])
                                ->where('category_id', $category->id)
                                ->where('is_deleted', 0)
                                ->where('is_active', 1)
                                ->whereIn('vendor_id', $vendorIds)
                                ->limit(16)
                                ->get();
                            return $category;
                        })
                        ->filter(function ($category) {
                            return $category->products->count() > 0;
                        })
                        ->values();
            }
        }

        // ✅ Debug logging
        // Log::info('Homepage data', [
        //     'lat' => $lat,
        //     'lng' => $lng,
        //     'categories_count' => $data['categories']->count(),
        //     'trending_count' => $data['trending_products']->count(),
        //     'best_offers_count' => $data['best_offers_products']->count(),
        //     'sponsors_count' => $data['sponsors_products']->count(),
        //     'categorywiseproducts_count' => $data['categorywiseproducts']->count(),
        // ]);

        return view('web.index')->with($data);
    }

    public function locationProducts(Request $request)
    {
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');

        // 1. ✅ Get vendor/branch IDs whose delivery location contains user's point
        $vendorIds = $this->getVendorIdsByDeliveryLocation($lat, $lng);

        if ($vendorIds->isEmpty()) {
            $emptyCategories = collect();
            $footerCategoriesHtml = $this->getFooterCategoriesHtml($lat, $lng);
            return response()->json([
                'trending_html'       => '',
                'top_selling_html'    => '',
                'best_offers_html'    => '',
                'sponsors_html'       => '',
                'stores_html'         => '',
                'categories_html'     => '',
                'homepage_categories_html' => view('web.partials.homepage_categories', ['homepageCategories' => $emptyCategories])->render(),
                'footer_categories_html' => $footerCategoriesHtml,
            ]);
        }

        // 2. ✅ Get vendors/branches for these IDs
        $vendors = VendorAdmin::whereIn('id', $vendorIds)
            ->where('status', '1')
            ->where('is_active', '1')
            ->where('user_type', 'vendor')
            ->whereNull('deleted_at')
            ->orderBy('order_by', 'asc')
            ->get();

        // 3. ✅ Homepage product strips (shared logic with index())
        $trending_products = $this->homepageTrendingProducts($vendorIds);
        $top_selling_products = $this->homepageTopSellingProducts($vendorIds, $trending_products);
        $best_offers_products = $this->homepageBestOffersProducts($vendorIds);
        $excludeCarouselIds = $trending_products->pluck('id')
            ->merge($top_selling_products->pluck('id'))
            ->unique()
            ->filter()
            ->values();
        $sponsors_products = $this->homepageSponsorProducts($vendorIds, $excludeCarouselIds);

        $stores = $vendors->where('is_home_request',2)->map(function ($store) {
            if ($store->business_category) {
                $ids = explode(',', $store->business_category);
                $store->categories = Category::whereIn('id', $ids)->get();
            } else {
              //  $store->categories = collect();
            }
            return $store;
        })->values(); // Reset keys to ensure proper collection

        // Category-wise products - sirf delivery location wale vendors ke (AJAX response, including out of stock)
        $categoryIdsWithDeliveryProducts = Product::whereIn('vendor_id', $vendorIds)
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->distinct()
            ->pluck('category_id')
            ->unique()
            ->filter()
            ->values();
        $categorywiseproducts = Category::whereIn('id', $categoryIdsWithDeliveryProducts)
            ->where('is_deleted', '0')
            ->where('is_home', '1')
            ->get()
            ->map(function ($category) use ($vendorIds) {
                $category->products = Product::with(['variants.images', 'featureImage', 'vendor'])
                    ->where('category_id', $category->id)
                    ->where('is_deleted', 0)
                    ->where('is_active', 1)
                    ->whereIn('vendor_id', $vendorIds)
                    ->limit(16)
                    ->get();
                return $category;
            })
            ->filter(function ($category) {
                return $category->products->count() > 0;
            })
            ->values();



        // Get location-based categories for homepage slider
        $categories = $this->getAvailableCategories($lat, $lng, 9);

        // Get footer categories HTML
        $footerCategoriesHtml = $this->getFooterCategoriesHtml($lat, $lng);

        // 4. ✅ Return updated sections
        return response()->json([
            'trending_html'       => view('web.partials.trending', compact('trending_products'))->render(),
            'top_selling_html'    => view('web.partials.trending', [
                'trending_products' => $top_selling_products,
                'sectionTitle' => 'Top Selling Products',
            ])->render(),
            'best_offers_html'    => view('web.partials.best-offers', compact('best_offers_products'))->render(),
            'sponsors_html'       => view('web.partials.sponsors', compact('sponsors_products'))->render(),
            'stores_html'         => view('web.partials.stores', compact('stores'))->render(),
            'categories_html'     => view('web.partials.categories', compact('categorywiseproducts'))->render(),
            'homepage_categories_html' => view('web.partials.homepage_categories', ['homepageCategories' => $categories])->render(),
            'footer_categories_html' => $footerCategoriesHtml,
        ]);
    }

    public function getProductVariants($id)
    {
        $product = Product::with(['variants.images', 'featureImage'])->findOrFail($id);

        $productFallbackImage = asset('public/assets/website/images/default.png');
        if ($product->featureImage && $product->featureImage->feature_image) {
            $productFallbackImage = asset('public/' . $product->featureImage->feature_image);
        }

        // Per variant: image = uploaded variant image if any, else product feature image
        $variants = $product->variants->map(function ($v) use ($product, $productFallbackImage) {
            $variantImageUrl = $v->displayImageUrlForProduct($product);

            return [
                'id' => $v->id,
                'variant_name' => $v->variant_name ?? '',
                'attributes' => $v->attributes,
                'variant_actual_price' => $v->variant_actual_price,
                'variant_selling_price' => $v->variant_selling_price,
                'stock' => (int) $v->stock,
                'image' => $variantImageUrl,
            ];
        });

        return response()->json([
            'product_name' => $product->title,
            'image' => $productFallbackImage,
            'variants' => $variants,
        ]);
    }

    public function department(Request $request)
    {
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');



        // ✅ Vendors/branches that deliver to user's location (delivery_locations table)
        $branches = collect();
        if ($lat && $lng) {
            $deliveryVendorIds = $this->getVendorIdsByDeliveryLocation($lat, $lng);
            if ($deliveryVendorIds->isNotEmpty()) {
                $branches = VendorAdmin::whereIn('id', $deliveryVendorIds)
                    ->whereIn('user_type', ['admin', 'branch'])
                    ->where('status', '1')
                    ->where('is_active', '1')
                    ->whereNull('deleted_at')
                    ->with('coupons')
                    ->get();
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
            $products = Product::with(['variants.images', 'featureImage'])
                ->where('vendor_id', $selectedVendor->id)
                ->where('is_active', '1')
                ->inStock()
                ->get();

            // ✅ Get unique sub_category_ids from products
            $subcategoryIds = $products->pluck('sub_category_id')->filter()->unique();

            $subcategories = Subcategory::where('is_deleted', '0')
                ->where('is_active', '1')
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

        // ✅ Vendors/branches that deliver to user's location
        $deliveryVendorIds = $this->getVendorIdsByDeliveryLocation($lat, $lng);
        $branches = collect();
        if ($deliveryVendorIds->isNotEmpty()) {
            $branches = VendorAdmin::whereIn('id', $deliveryVendorIds)
                ->whereIn('user_type', ['admin', 'branch'])
                ->where('status', '1')
                ->where('is_active', '1')
                ->whereNull('deleted_at')
                ->with('coupons')
                ->get();
        }

        $selectedVendor = $branches->where('id', $branchId)->first();
        if (!$selectedVendor) {
            return response()->json(['success' => false, 'message' => 'Vendor not found in your area']);
        }

        // ✅ Products (including out of stock)
        $products = Product::with(['variants.images', 'featureImage'])
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

        $vendors = collect();
        if ($lat && $lng) {
            $vendorIds = $this->getVendorIdsByDeliveryLocation($lat, $lng);
            if ($vendorIds->isNotEmpty()) {
                $vendors = VendorAdmin::whereIn('id', $vendorIds)
                    ->where('status', '1')
                    ->where('is_active', '1')
                    ->where('user_type', 'vendor')
                    ->whereNull('deleted_at')
                    ->with(['products.fcategory'])
                    ->get();
            }
        }
        $vendorIds = $vendors->pluck('id');

        // ✅ Get only categories that have active products from these vendors
        if ($vendorIds->count() > 0) {
            $categoryIds = Product::whereIn('vendor_id', $vendorIds)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->pluck('category_id')
                ->unique()
                ->filter();

            $data['categories'] = Category::whereIn('id', $categoryIds)
                ->where('is_deleted', 0)
                ->get();
        } else {
            // No vendors found, return empty categories
            $data['categories'] = collect();
        }

        // ✅ Filter by category slug if provided
        if ($slug && $slug !== 'all') {
            $category = Category::where('slug', $slug)->first();

            if ($category) {
                // Log::info("📂 Filtering by category:", ['id' => $category->id, 'name' => $category->name]);

                $vendorIdsWithProducts = Product::where('category_id', $category->id)
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->pluck('vendor_id')
                    ->unique();

                // Filter vendors to only those that have products in this category AND are in the location
                $data['stores'] = $vendors->filter(function ($vendor) use ($vendorIdsWithProducts) {
                    return $vendorIdsWithProducts->contains($vendor->id);
                });

                // Log::info("Stores after category filter:", $data['stores']->pluck('id')->toArray());
            } else {
                // Log::warning("⚠️ Invalid category slug: " . $slug);
                $data['stores'] = collect();
            }

            $data['slug'] = $slug;
        } else {
            // Show all vendors in the area
            $data['stores'] = $vendors;
            $data['slug'] = 'all';
            // Log::info("📍 Showing all vendors:", $vendors->pluck('id')->toArray());
        }

        $data['banners'] = Banner::where('banner_cat_id', '4')->where('is_deleted', '0')->get();

        return view('web.stores')->with($data);
    }

    public function explorestore(Request $request, $vendor_id, $cat_id = 0)
    {
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');

        $vendors = collect();
        if ($lat && $lng) {
            $vendorIds = $this->getVendorIdsByDeliveryLocation($lat, $lng);
            if ($vendorIds->isNotEmpty()) {
                $vendors = VendorAdmin::whereIn('id', $vendorIds)
                    ->where('status', '1')
                    ->where('is_active', '1')
                    ->where('user_type', 'vendor')
                    ->whereNull('deleted_at')
                    ->with(['products.category', 'products.subcategory'])
                    ->get();
            }
        }

        // ✅ Vendor check - must be in delivery area
        $vendor = $vendors->firstWhere('id', (int) $vendor_id);
        if (!$vendor) {
            abort(404, 'Vendor not found in your location');
        }

        // ✅ Products logic
        if ($cat_id == 0) {
            // All vendor products - find a fallback category for URL generation
            $category = null;

            // Use direct Product query - use integer comparison (including out of stock)
            $products = Product::where('vendor_id', $vendor_id)
                ->where('is_deleted', 0)
                ->where('is_active', 1)
                ->whereNotNull('sub_category_id')
                ->with(['variants.images', 'featureImage'])
                ->get();

            // Debug: Log products found
            Log::info('Products found for vendor (all categories)', [
                'vendor_id' => $vendor_id,
                'count' => $products->count(),
                'product_ids' => $products->pluck('id')->toArray(),
                'sub_category_ids' => $products->pluck('sub_category_id')->filter()->unique()->toArray()
            ]);

            // Get subcategories that have active products for this vendor
            $subcategoryIds = $products->pluck('sub_category_id')
                ->filter(function($id) {
                    return !is_null($id) && $id !== '' && $id !== 0;
                })
                ->unique()
                ->values()
                ->toArray();

            Log::info('Subcategory IDs extracted from products', [
                'vendor_id' => $vendor_id,
                'subcategory_ids' => $subcategoryIds,
                'count' => count($subcategoryIds),
                'products_count' => $products->count()
            ]);

            if (empty($subcategoryIds)) {
                Log::warning('No subcategory IDs found from products', [
                    'vendor_id' => $vendor_id,
                    'products_count' => $products->count()
                ]);
                $subcategories = collect();
            } else {
                // First check all subcategories without filters to debug
                $allSubcategoriesDebug = Subcategory::whereIn('id', $subcategoryIds)->get();
                Log::info('All subcategories found (without filters - all categories)', [
                    'count' => $allSubcategoriesDebug->count(),
                    'ids' => $allSubcategoriesDebug->pluck('id')->toArray(),
                    'names' => $allSubcategoriesDebug->pluck('sub_cat_name')->toArray(),
                    'is_active' => $allSubcategoriesDebug->pluck('is_active')->toArray(),
                    'is_deleted' => $allSubcategoriesDebug->pluck('is_deleted')->toArray(),
                    'category_ids' => $allSubcategoriesDebug->pluck('category_id')->toArray()
                ]);

                // Query subcategories - handle multiple data types for is_active and is_deleted
                // When cat_id == 0, we want ALL subcategories that have products, regardless of category
                $subcategories = Subcategory::whereIn('id', $subcategoryIds)
                    ->where(function($query) {
                        $query->where('is_deleted', 0)
                              ->orWhere('is_deleted', '0')
                              ->orWhereRaw('CAST(is_deleted AS UNSIGNED) = 0');
                    })
                    ->where(function($query) {
                        $query->where('is_active', 1)
                              ->orWhere('is_active', '1')
                              ->orWhereRaw('CAST(is_active AS UNSIGNED) = 1');
                    })
                    ->with('category')
                    ->get();

                // Debug: Check which subcategories were found
                $foundIds = $subcategories->pluck('id')->toArray();
                $missingIds = array_diff($subcategoryIds, $foundIds);

                if (!empty($missingIds)) {
                    Log::warning('Some subcategories were filtered out (all categories)', [
                        'missing_ids' => $missingIds,
                        'found_ids' => $foundIds,
                        'all_ids' => $subcategoryIds
                    ]);

                    // Check the status of missing subcategories
                    foreach ($missingIds as $missingId) {
                        $missingSub = Subcategory::where('id', $missingId)->first();
                        if ($missingSub) {
                            Log::info('Missing subcategory details (all categories)', [
                                'id' => $missingId,
                                'name' => $missingSub->sub_cat_name,
                                'is_active' => $missingSub->is_active,
                                'is_deleted' => $missingSub->is_deleted,
                                'category_id' => $missingSub->category_id
                            ]);
                        }
                    }
                }

                // If still empty, try without active check (for debugging)
                if ($subcategories->isEmpty()) {
                    Log::warning('No subcategories found even with IDs', [
                        'subcategory_ids' => $subcategoryIds,
                        'trying_without_filters' => true
                    ]);
                    // Try without filters to see if subcategories exist
                    $allSubcategories = Subcategory::whereIn('id', $subcategoryIds)->get();
                    Log::info('Subcategories without filters', [
                        'count' => $allSubcategories->count(),
                        'ids' => $allSubcategories->pluck('id')->toArray(),
                        'is_active' => $allSubcategories->pluck('is_active')->toArray(),
                        'is_deleted' => $allSubcategories->pluck('is_deleted')->toArray()
                    ]);
                }

                Log::info('Found subcategories', [
                    'count' => $subcategories->count(),
                    'ids' => $subcategories->pluck('id')->toArray(),
                    'names' => $subcategories->pluck('sub_cat_name')->toArray(),
                    'categories' => $subcategories->pluck('category_id')->toArray()
                ]);
            }
        } else {
            // Products for one category
            $category = Category::findOrFail($cat_id);

            // Use direct Product query - use integer comparison (including out of stock)
            $products = Product::where('vendor_id', $vendor_id)
                ->where('category_id', $cat_id)
                ->where('is_deleted', 0)
                ->where('is_active', 1)
                ->whereNotNull('sub_category_id')
                ->with(['variants.images', 'featureImage'])
                ->get();

            // Debug: Log products found
            Log::info('Products found for vendor (category specific)', [
                'vendor_id' => $vendor_id,
                'category_id' => $cat_id,
                'count' => $products->count(),
                'product_ids' => $products->pluck('id')->toArray(),
                'sub_category_ids' => $products->pluck('sub_category_id')->filter()->unique()->toArray()
            ]);

            // ✅ IMPORTANT: Get ALL subcategories from ALL categories that have products for this vendor
            // This ensures sidebar shows all subcategories, not just from current category (including out of stock)
            $allVendorProducts = Product::where('vendor_id', $vendor_id)
                ->where('is_deleted', 0)
                ->where('is_active', 1)
                ->whereNotNull('sub_category_id')
                ->get();

            // Get all subcategory IDs from all vendor products (across all categories)
            $allSubcategoryIds = $allVendorProducts->pluck('sub_category_id')
                ->filter(function($id) {
                    return !is_null($id) && $id !== '' && $id !== 0;
                })
                ->unique()
                ->values()
                ->toArray();

            Log::info('All subcategory IDs from all vendor products', [
                'vendor_id' => $vendor_id,
                'subcategory_ids' => $allSubcategoryIds,
                'count' => count($allSubcategoryIds),
                'total_products' => $allVendorProducts->count()
            ]);

            if (empty($allSubcategoryIds)) {
                Log::warning('No subcategory IDs found from all vendor products', [
                    'vendor_id' => $vendor_id,
                    'total_products' => $allVendorProducts->count()
                ]);
                $subcategories = collect();
            } else {
                // First, check all subcategories without filters to see what exists
                $allSubcategories = Subcategory::whereIn('id', $allSubcategoryIds)->get();

                Log::info('All subcategories found (without filters - all categories)', [
                    'vendor_id' => $vendor_id,
                    'category_id' => $cat_id,
                    'count' => $allSubcategories->count(),
                    'ids' => $allSubcategories->pluck('id')->toArray(),
                    'names' => $allSubcategories->pluck('sub_cat_name')->toArray(),
                    'category_ids' => $allSubcategories->pluck('category_id')->toArray(),
                    'is_active' => $allSubcategories->pluck('is_active')->toArray(),
                    'is_deleted' => $allSubcategories->pluck('is_deleted')->toArray()
                ]);

                // Query subcategories from ALL categories that have products for this vendor
                // Don't filter by category_id - show all subcategories in sidebar
                $subcategories = Subcategory::whereIn('id', $allSubcategoryIds)
                    ->where(function($query) {
                        // Check both integer and string/boolean values for is_deleted
                        $query->where('is_deleted', 0)
                              ->orWhere('is_deleted', '0')
                              ->orWhereRaw('CAST(is_deleted AS UNSIGNED) = 0');
                    })
                    ->where(function($query) {
                        // Check both integer and string/boolean values for is_active
                        $query->where('is_active', 1)
                              ->orWhere('is_active', '1')
                              ->orWhereRaw('CAST(is_active AS UNSIGNED) = 1');
                    })
                    ->with('category')
                    ->get();

                // Check which subcategories were filtered out
                $foundIds = $subcategories->pluck('id')->toArray();
                $missingIds = array_diff($allSubcategoryIds, $foundIds);

                if (!empty($missingIds)) {
                    Log::warning('Some subcategories were filtered out (all categories)', [
                        'vendor_id' => $vendor_id,
                        'category_id' => $cat_id,
                        'missing_ids' => $missingIds,
                        'found_ids' => $foundIds,
                        'all_ids' => $allSubcategoryIds
                    ]);

                    // Check the status of missing subcategories
                    foreach ($missingIds as $missingId) {
                        $missingSub = Subcategory::where('id', $missingId)->first();
                        if ($missingSub) {
                            Log::info('Missing subcategory details (all categories)', [
                                'id' => $missingId,
                                'name' => $missingSub->sub_cat_name,
                                'is_active' => $missingSub->is_active,
                                'is_deleted' => $missingSub->is_deleted,
                                'category_id' => $missingSub->category_id
                            ]);
                        } else {
                            Log::warning('Subcategory not found in database', ['id' => $missingId]);
                        }
                    }
                }

                Log::info('Found subcategories (all categories for sidebar)', [
                    'vendor_id' => $vendor_id,
                    'current_category_id' => $cat_id,
                    'count' => $subcategories->count(),
                    'ids' => $subcategories->pluck('id')->toArray(),
                    'names' => $subcategories->pluck('sub_cat_name')->toArray(),
                    'category_ids' => $subcategories->pluck('category_id')->toArray()
                ]);
            }
        }
        // ✅ Coupons
        $coupons = Coupon::where('created_by_id', $vendor->id)->where('is_active', 1)->where('is_deleted', 0)->get();

        // Calculate progress values for dynamic display
        // Get vendor minimum order values
        $vendorModel = VendorAdmin::findOrFail($vendor_id);
        $minimumForCook = $vendorModel->minimum_order_value_for_cook ?? ($vendorModel->minimum_order_for_cook ?? 0);
        $minimumForDelivery = $vendorModel->minimum_order_value ?? 0;

        // Initialize cart total to 0 for non-logged in users
        $cartTotal = 0;

        // If user is logged in, get cart total
        if (auth()->check()) {
            $user_id = auth()->id();
            $cartTotal = CartItem::join('products', 'cart_items.product_id', '=', 'products.id')
                ->where('cart_items.user_id', $user_id)
                ->where('products.vendor_id', $vendor_id)
                ->sum(DB::raw('cart_items.quantity * cart_items.price'));
        }

        // Calculate progress values (works for both logged in and non-logged in users)
        $cook_progress = $minimumForCook > 0 ? min(($cartTotal / $minimumForCook) * 100, 100) : 0;
        $cook_amount_needed = max($minimumForCook - $cartTotal, 0);

        $delivery_progress = $minimumForDelivery > 0 ? min(($cartTotal / $minimumForDelivery) * 100, 100) : 0;
        $delivery_amount_needed = max($minimumForDelivery - $cartTotal, 0);

        return view('web.explorestore', compact('vendor', 'category', 'subcategories', 'products', 'coupons', 'cook_amount_needed', 'delivery_amount_needed', 'cook_progress', 'delivery_progress'));
    }

    public function subcategoryProducts(Request $request, $vendor_id, $category_id, $subcategory_id)
    {
        $lat = $request->latitude;
        $lng = $request->longitude;

        if ($lat && $lng) {
            $deliveryVendorIds = $this->getVendorIdsByDeliveryLocation($lat, $lng);
            if ($deliveryVendorIds->isEmpty() || !$deliveryVendorIds->contains((int) $vendor_id)) {
                return view('web.explorestore', [
                    'vendor'        => null,
                    'category'      => null,
                    'subcategory'   => null,
                    'subcategories' => collect(),
                    'products'      => collect(),
                    'error'         => 'No stores available in your location'
                ]);
            }
        }

        // ✅ Vendor (must be in delivery area when location provided)
        $vendor = VendorAdmin::where('status', '1')
            ->where('is_active', '1')
            ->where('user_type', 'vendor')
            ->whereNull('deleted_at')
            ->with(['products.category'])
            ->findOrFail($vendor_id);

        // ✅ Category + Subcategory
        $category    = Category::findOrFail($category_id);
        $subcategory = SubCategory::findOrFail($subcategory_id);

        // ✅ Get ALL subcategories that have active products from this vendor (across all categories, including out of stock)
        // This ensures sidebar menu shows all subcategories even when viewing a specific one
        $allSubcategoryIdsWithProducts = Product::where('vendor_id', $vendor_id)
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->whereNotNull('sub_category_id')
            ->pluck('sub_category_id')
            ->unique()
            ->filter()
            ->values()
            ->toArray();

        Log::info('Subcategory IDs with products (subcategoryProducts method)', [
            'vendor_id' => $vendor_id,
            'category_id' => $category_id,
            'subcategory_id' => $subcategory_id,
            'subcategory_ids' => $allSubcategoryIdsWithProducts,
            'count' => count($allSubcategoryIdsWithProducts)
        ]);

        // Get all subcategories that have products (for sidebar menu) - handle multiple data types
        $subcategories = SubCategory::whereIn('id', $allSubcategoryIdsWithProducts)
            ->where(function($query) {
                $query->where('is_deleted', 0)
                      ->orWhere('is_deleted', '0')
                      ->orWhereRaw('CAST(is_deleted AS UNSIGNED) = 0');
            })
            ->where(function($query) {
                $query->where('is_active', 1)
                      ->orWhere('is_active', '1')
                      ->orWhereRaw('CAST(is_active AS UNSIGNED) = 1');
            })
            ->with('category')
            ->get();

        Log::info('Subcategories found (subcategoryProducts method)', [
            'count' => $subcategories->count(),
            'ids' => $subcategories->pluck('id')->toArray(),
            'names' => $subcategories->pluck('sub_cat_name')->toArray()
        ]);

        // ✅ Products filter - use direct Product query for specific subcategory (including out of stock)
        $products = Product::where('vendor_id', $vendor_id)
            ->where('category_id', $category_id)
            ->where('sub_category_id', $subcategory_id)
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->with(['variants.images', 'featureImage'])
            ->get();

        //vendor wise coupons
        $coupons = Coupon::where('created_by_id', $vendor->id)->where('is_active', 1)->where('is_deleted', 0)->get();

        // Calculate progress values for dynamic display
        // Get vendor minimum order values
        $vendorModel = VendorAdmin::findOrFail($vendor_id);
        $minimumForCook = $vendorModel->minimum_order_value_for_cook ?? ($vendorModel->minimum_order_for_cook ?? 0);
        $minimumForDelivery = $vendorModel->minimum_order_value ?? 0;

        // Initialize cart total to 0 for non-logged in users
        $cartTotal = 0;

        // If user is logged in, get cart total
        if (auth()->check()) {
            $user_id = auth()->id();
            $cartTotal = CartItem::join('products', 'cart_items.product_id', '=', 'products.id')
                ->where('cart_items.user_id', $user_id)
                ->where('products.vendor_id', $vendor_id)
                ->sum(DB::raw('cart_items.quantity * cart_items.price'));
        }

        // Calculate progress values (works for both logged in and non-logged in users)
        $cook_progress = $minimumForCook > 0 ? min(($cartTotal / $minimumForCook) * 100, 100) : 0;
        $cook_amount_needed = max($minimumForCook - $cartTotal, 0);

        $delivery_progress = $minimumForDelivery > 0 ? min(($cartTotal / $minimumForDelivery) * 100, 100) : 0;
        $delivery_amount_needed = max($minimumForDelivery - $cartTotal, 0);

        return view('web.explorestore', compact('vendor', 'category', 'subcategory', 'subcategories', 'products', 'coupons', 'cook_amount_needed', 'delivery_amount_needed', 'cook_progress', 'delivery_progress'));
    }

    public function allCategoryProducts(Request $request, $category_id)
    {
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');

        $category = Category::findOrFail($category_id);

        $subcategories = SubCategory::where('category_id', $category_id)
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->get();

        if (!$lat || !$lng) {
            $subcategories = collect();
            return view('web.categorywiseproduct', [
                'category' => $category,
                'subcategories' => $subcategories,
                'products' => collect(),
            ])->with('error', 'Please enable location to see products.');
        }

        $vendorIds = $this->getVendorIdsByDeliveryLocation($lat, $lng);
        if ($vendorIds->isEmpty()) {
            $subcategories = collect();
            return view('web.categorywiseproduct', [
                'category' => $category,
                'subcategories' => $subcategories,
                'products' => collect(),
            ])->with('error', 'No products available in your area.');
        }

        $products = Product::with(['variants.images', 'featureImage', 'vendor'])
            ->where('category_id', $category_id)
            ->whereIn('vendor_id', $vendorIds)
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->get();

        Log::info("Products found:", ['count' => $products->count()]);

        // ✅ Filter subcategories to only show those that have products
        $subcategoryIdsWithProducts = $products->pluck('sub_category_id')->unique()->filter();
        $subcategories = $subcategories->whereIn('id', $subcategoryIdsWithProducts);

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
            ->where('is_active', '1')
            ->get();

        // If no lat/lng → return empty
        if (!$lat || !$lng) {
            $subcategories = collect();
            return view('web.categorywiseproduct', [
                'category' => $category,
                'subcategory' => $subcategory,
                'subcategories' => $subcategories,
                'products' => collect(),
            ])->with('error', 'Please enable location to see products.');
        }

        $vendorIds = $this->getVendorIdsByDeliveryLocation($lat, $lng);
        if ($vendorIds->isEmpty()) {
            $subcategories = collect();
            return view('web.categorywiseproduct', [
                'category' => $category,
                'subcategory' => $subcategory,
                'subcategories' => $subcategories,
                'products' => collect(),
            ])->with('error', 'No products available in your area.');
        }

        // ✅ Get all products for this category to filter subcategories (including out of stock)
        $allCategoryProducts = Product::with(['variants.images', 'featureImage', 'vendor'])
            ->where('category_id', $category_id)
            ->whereIn('vendor_id', $vendorIds)
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->get();

        // ✅ Filter subcategories to only show those that have products
        $subcategoryIdsWithProducts = $allCategoryProducts->pluck('sub_category_id')->unique()->filter();
        $subcategories = $subcategories->whereIn('id', $subcategoryIdsWithProducts);

        // ✅ Filter products for specific subcategory
        $products = $allCategoryProducts->filter(function($product) use ($subcategory_id) {
            return $product->sub_category_id == $subcategory_id;
        });

        return view('web.categorywiseproduct', compact('category', 'subcategory', 'subcategories', 'products'));
    }

    /**
     * AJAX: store page subcategory — products only, URL unchanged.
     */
    public function ajaxExplorestoreProducts(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|integer',
            'category_id' => 'required|integer',
            'subcategory_id' => 'required|integer',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
        ]);

        $lat = $request->input('latitude');
        $lng = $request->input('longitude');

        if ($lat && $lng) {
            $deliveryVendorIds = $this->getVendorIdsByDeliveryLocation($lat, $lng);
            if ($deliveryVendorIds->isEmpty() || ! $deliveryVendorIds->contains((int) $validated['vendor_id'])) {
                return response()->json([
                    'success' => false,
                    'html' => '',
                    'message' => 'Store not available in your location.',
                ]);
            }
        }

        $vendor_id = (int) $validated['vendor_id'];
        $category_id = (int) $validated['category_id'];
        $subcategory_id = (int) $validated['subcategory_id'];

        $products = Product::where('vendor_id', $vendor_id)
            ->where('category_id', $category_id)
            ->where('sub_category_id', $subcategory_id)
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->with(['variants.images', 'featureImage', 'vendor'])
            ->get();

        $html = view('web.partials.explorestore_products_grid', compact('products'))->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'active_subcategory_id' => $subcategory_id,
        ]);
    }

    /**
     * AJAX: category-wise page subcategory filter — products only, URL unchanged.
     */
    public function ajaxCategorywiseProducts(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|integer',
            'subcategory_id' => 'required|integer',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
        ]);

        $lat = $request->input('latitude');
        $lng = $request->input('longitude');

        if (! $lat || ! $lng) {
            return response()->json([
                'success' => false,
                'html' => '',
                'message' => 'Please enable location.',
            ]);
        }

        $vendorIds = $this->getVendorIdsByDeliveryLocation($lat, $lng);
        if ($vendorIds->isEmpty()) {
            return response()->json([
                'success' => false,
                'html' => '',
                'message' => 'No delivery in your area.',
            ]);
        }

        $category_id = (int) $validated['category_id'];
        $subcategory_id = (int) $validated['subcategory_id'];

        $allCategoryProducts = Product::with(['variants.images', 'featureImage', 'vendor'])
            ->where('category_id', $category_id)
            ->whereIn('vendor_id', $vendorIds)
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->get();

        $products = $allCategoryProducts->filter(function ($product) use ($subcategory_id) {
            return (int) $product->sub_category_id === $subcategory_id;
        })->values();

        $html = view('web.partials.categorywise_products_grid', compact('products'))->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'active_subcategory_id' => $subcategory_id,
        ]);
    }

    public function getVendorProgress($vendor_id)
    {
        $vendor = VendorAdmin::findOrFail($vendor_id);
        $minimumForCook = $vendor->minimum_order_value_for_cook ?? ($vendor->minimum_order_for_cook ?? 0);
        $minimumForDelivery = $vendor->minimum_order_value ?? 0;

        // Initialize cart total to 0 for non-logged in users
        $cartTotal = 0;

        // If user is logged in, get cart total
        if (auth()->check()) {
            $user_id = auth()->id();
            $cartTotal = CartItem::join('products', 'cart_items.product_id', '=', 'products.id')
                ->where('cart_items.user_id', $user_id)
                ->where('products.vendor_id', $vendor_id)
                ->sum(DB::raw('cart_items.quantity * cart_items.price'));
        }

        Log::info('Vendor minimums', ['cook' => $minimumForCook, 'delivery' => $minimumForDelivery, 'cartTotal' => $cartTotal]);

        $cook_progress = $minimumForCook > 0 ? min(($cartTotal / $minimumForCook) * 100, 100) : 0;
        $cook_amount_needed = max($minimumForCook - $cartTotal, 0);

        $delivery_progress = $minimumForDelivery > 0 ? min(($cartTotal / $minimumForDelivery) * 100, 100) : 0;
        $delivery_amount_needed = max($minimumForDelivery - $cartTotal, 0);

        return response()->json([
            'cook_progress' => round($cook_progress, 2),
            'cook_amount_needed' => round($cook_amount_needed, 2),
            'delivery_progress' => round($delivery_progress, 2),
            'delivery_amount_needed' => round($delivery_amount_needed, 2),
        ]);
    }
    // Product details with location-based vendor filtering
    public function productdetails(Request $request, $slug)
    {
        if (!$slug) {
            abort(404, 'Product not found');
        }

        // ✅ Check location data - use delivery_locations (vendor/branch delivery areas)
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');
        $allowedVendorIds = $this->getVendorIdsByDeliveryLocation($lat, $lng);

        // Fetch product with relationships - only if vendor delivers to user's area
        $product = Product::with([
            'vendor',
            'featureImage',
            'variants' => function($query) {
                $query->with('images');
            },
            'category',
            'subcategory'
        ])
        ->where('slug', $slug)
        ->where('is_active', 1)
        ->where('is_deleted', 0)
        ->when($allowedVendorIds->isNotEmpty(), function($query) use ($allowedVendorIds) {
            return $query->whereIn('vendor_id', $allowedVendorIds);
        })
        ->first();

        if (!$product) {
            abort(404, 'Product not found in your area');
        }

        // Get product images
        $productImages = ProductImages::where('id', $product->feature_image_id)
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->first();

        // Get similar products from same subcategory - only from vendors in user's area (in-stock only)
        $similarProducts = Product::with(['featureImage', 'variants.images'])
            ->where('sub_category_id', $product->sub_category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->inStock()
            ->when($allowedVendorIds->isNotEmpty(), function($query) use ($allowedVendorIds) {
                return $query->whereIn('vendor_id', $allowedVendorIds);
            })
            ->inRandomOrder()
            ->limit(8)
            ->get();

        // Get other products by same vendor - in-stock only
        $otherVendorProducts = Product::with(['featureImage', 'variants.images'])
            ->where('vendor_id', $product->vendor_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->inStock()
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return view('web.productdetails', compact(
            'product',
            'productImages',
            'similarProducts',
            'otherVendorProducts'
        ))->with('bodyClass', 'product-details-page');
    }
    /**
     * Get vendor IDs (and branch IDs) for user's location.
     * 1) Prefer delivery_locations: vendors/branches whose delivery polygon contains user point.
     * 2) Fallback: if no delivery location set or user not in any, use master location (vendors in master polygon).
     */
    private function getVendorIdsByDeliveryLocation($lat, $lng)
    {
        if (!$lat || !$lng) {
            return collect();
        }
        $locations = DeliveryLocation::where('is_active', 1)
            ->where('is_deleted', 0)
            ->whereNotNull('delivery_lat_long')
            ->get();
        $vendorIds = collect();
        foreach ($locations as $loc) {
            $polygon = $loc->delivery_lat_long;
            if (empty($polygon)) {
                continue;
            }
            $points = is_array($polygon) ? collect($polygon) : collect(json_decode($polygon, true));
            // Valid polygon = at least 3 points; agar vendor ne area set nahi kiya to skip
            if (!$points || $points->count() < 3) {
                continue;
            }
            if (is_array($polygon)) {
                $polygon = json_encode($polygon);
            }
            if ($this->pointInPolygon($lat, $lng, $polygon)) {
                $vendorIds->push($loc->vendor_id);
            }
        }
        $vendorIds = $vendorIds->unique()->values();
        // Master location fallback commented - ab sirf delivery location se dikhaye
        // if ($vendorIds->isEmpty()) {
        //     return $this->getVendorIdsByMasterLocation($lat, $lng);
        // }
        return $vendorIds;
    }

    // Master location fallback - commented, ab sirf delivery location use ho raha hai
    // private function getVendorIdsByMasterLocation($lat, $lng)
    // {
    //     if (!$lat || !$lng) {
    //         return collect();
    //     }
    //     $insideLocation = MasterLocation::where('is_active', 1)
    //         ->where('is_deleted', 0)
    //         ->get()
    //         ->first(function ($location) use ($lat, $lng) {
    //             return $this->pointInPolygon($lat, $lng, $location->lat_long);
    //         });
    //     if (!$insideLocation) {
    //         return collect();
    //     }
    //     return VendorAdmin::where('status', '1')
    //         ->where('is_active', '1')
    //         ->whereNull('deleted_at')
    //         ->whereNotNull('latitude')
    //         ->whereNotNull('longitude')
    //         ->get()
    //         ->filter(function ($vendor) use ($insideLocation) {
    //             return $this->pointInPolygon($vendor->latitude, $vendor->longitude, $insideLocation->lat_long);
    //         })
    //         ->pluck('id')
    //         ->values();
    // }

    /**
     * Check if a point lies inside a polygon
     */
    private function pointInPolygon($lat, $lng, $polygon)
    {
        if (is_array($polygon)) {
            $polygon = json_encode($polygon);
        }
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

    private function newHomepageProductQuery(Collection $vendorIds): \Illuminate\Database\Eloquent\Builder
    {
        return Product::with(['variants.images', 'featureImage', 'vendor'])
            ->whereIn('vendor_id', $vendorIds)
            ->where('is_deleted', 0)
            ->where('is_active', 1);
    }

    /**
     * "Trending" = recently updated in-stock products in the user’s delivery zone.
     */
    private function homepageTrendingProducts(Collection $vendorIds): Collection
    {
        if ($vendorIds->isEmpty()) {
            return collect();
        }

        return $this->newHomepageProductQuery($vendorIds)
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->take(20)
            ->get();
    }

    /**
     * Admin-flagged top sellers; if none, show latest in-zone products so the block is not empty.
     * Avoids repeating the same fallback items as the Trending strip when possible.
     */
    private function homepageTopSellingProducts(Collection $vendorIds, ?Collection $trendingProducts = null): Collection
    {
        if ($vendorIds->isEmpty()) {
            return collect();
        }

        $flagged = $this->newHomepageProductQuery($vendorIds)
            ->where(function ($w) {
                $w->where('top_selling', 1)
                    ->orWhere('top_selling', '1');
            })
            ->take(20)
            ->get();

        if ($flagged->isNotEmpty()) {
            return $flagged;
        }

        $query = $this->newHomepageProductQuery($vendorIds)->orderByDesc('id');
        if ($trendingProducts instanceof Collection && $trendingProducts->isNotEmpty()) {
            $query->whereNotIn('id', $trendingProducts->pluck('id')->filter()->values()->all());
        }

        $fallback = $query->take(20)->get();

        return $fallback->isNotEmpty()
            ? $fallback
            : $this->newHomepageProductQuery($vendorIds)->orderByDesc('id')->take(20)->get();
    }

    /**
     * Best offers flag, or any in-zone product with a discount on at least one variant.
     */
    private function homepageBestOffersProducts(Collection $vendorIds): Collection
    {
        if ($vendorIds->isEmpty()) {
            return collect();
        }

        $flagged = $this->newHomepageProductQuery($vendorIds)
            ->where(function ($w) {
                $w->where('best_offers', 1)
                    ->orWhere('best_offers', '1');
            })
            ->take(20)
            ->get();

        if ($flagged->isNotEmpty()) {
            return $flagged;
        }

        return $this->newHomepageProductQuery($vendorIds)
            ->whereHas('variants', function ($v) {
                $v->whereColumn('variant_selling_price', '<', 'variant_actual_price');
            })
            ->orderByDesc('id')
            ->take(20)
            ->get();
    }

    /**
     * Sponsored flag, or latest in-zone products if no sponsored items (keeps section visible).
     */
    private function homepageSponsorProducts(Collection $vendorIds, ?Collection $excludeProductIds = null): Collection
    {
        if ($vendorIds->isEmpty()) {
            return collect();
        }

        $flagged = $this->newHomepageProductQuery($vendorIds)
            ->where(function ($w) {
                $w->where('spons_product', 1)
                    ->orWhere('spons_product', '1');
            })
            ->take(20)
            ->get();

        if ($flagged->isNotEmpty()) {
            return $flagged;
        }

        $exclude = $excludeProductIds instanceof Collection ? $excludeProductIds->filter()->values()->all() : [];

        $query = $this->newHomepageProductQuery($vendorIds)->orderByDesc('id');
        if (count($exclude) > 0) {
            $query->whereNotIn('id', $exclude);
        }

        $fallback = $query->take(20)->get();

        return $fallback->isNotEmpty()
            ? $fallback
            : $this->newHomepageProductQuery($vendorIds)->orderByDesc('id')->take(20)->get();
    }

    /**
     * Sirf wahi categories jo vendor delivery location ke andar products rakhte hain.
     * getVendorIdsByDeliveryLocation se vendor IDs aati hain, phir unhi vendors ke products wali categories.
     */
    private function getAvailableCategories($lat, $lng, $limit = 9)
    {
        if (!$lat || !$lng) {
            return collect();
        }
        $vendorIds = $this->getVendorIdsByDeliveryLocation($lat, $lng);
        if ($vendorIds->isEmpty()) {
            return collect();
        }
        $categoryIds = Product::whereIn('vendor_id', $vendorIds)
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->distinct()
            ->pluck('category_id')
            ->unique()
            ->filter()
            ->values()
            ->take($limit);
        return Category::whereIn('id', $categoryIds)
            ->where('is_deleted', 0)
            ->distinct()
            ->orderBy('id', 'asc')
            ->get()
            ->unique('id')
            ->values();
    }

    /**
     * Get footer categories HTML for AJAX updates
     */
    private function getFooterCategoriesHtml($lat, $lng)
    {
        // Get all active categories
        $footerCategories = Category::where('is_active', 1)
            ->where('is_deleted', 0)
            ->orderBy('name')
            ->get();

        // Filter footer categories based on location-vendors with products
        $filteredFooterCategories = $footerCategories;

        if ($lat && $lng) {
            $vendorIds = $this->getVendorIdsByDeliveryLocation($lat, $lng);
            if ($vendorIds->count() > 0) {
                $categoryIdsWithProducts = Product::whereIn('vendor_id', $vendorIds)
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->pluck('category_id')
                    ->unique()
                    ->filter();
                $filteredFooterCategories = $footerCategories->filter(function($category) use ($categoryIdsWithProducts) {
                    return $categoryIdsWithProducts->contains($category->id);
                });
            }
        }

        $totalCategories = $filteredFooterCategories->count();

        // Footer layout: same as index.blade.php with 3-row design
        // First 2 rows: 3 categories each (total 6)
        // Third row: 3 categories, remaining in dropdown
        $maxVisibleFirstTwoRows = 6; // 2 rows × 3 categories
        $maxVisibleThirdRow = 3;    // 3 more categories
        $maxVisible = $maxVisibleFirstTwoRows + $maxVisibleThirdRow; // 9 total

        // If more than 9 categories:
        $visibleCategories = $totalCategories > $maxVisible
            ? $filteredFooterCategories->take($maxVisible - 1)   // show first 8
            : $filteredFooterCategories;           // else show all

        // From 9th onward goes into dropdown
        $moreCategories = $totalCategories > $maxVisible
            ? $filteredFooterCategories->slice($maxVisible - 1)
            : collect();

        // Generate HTML
        $html = '<div class="row w-100">';

        // Visible categories
        foreach($visibleCategories as $category) {
            $html .= '<div class="col-md-4 col-6 footer-links">';
            $html .= '<ul>';
            $html .= '<li>';
            $html .= '<a href="' . route('allcategorywiseproduct', $category->id) . '" onclick="return redirectWithLocation(this.href)">';
            $html .= htmlspecialchars($this->formatCategoryName($category->name));
            $html .= '</a>';
            $html .= '</li>';
            $html .= '</ul>';
            $html .= '</div>';
        }

        // Dropdown in 9th slot if needed
        if ($moreCategories->isNotEmpty()) {
            $html .= '<div class="col-md-4 col-6 footer-links">';
            $html .= '<ul>';
            $html .= '<li>';
            $html .= '<select class="shopmore" onchange="if(this.value) window.location.href=this.value">';
            $html .= '<option>Show More</option>';
            foreach($moreCategories as $category) {
                $html .= '<option value="' . route('allcategorywiseproduct', $category->id) . '">';
                $html .= htmlspecialchars($this->formatCategoryName($category->name));
                $html .= '</option>';
            }
            $html .= '</select>';
            $html .= '</li>';
            $html .= '</ul>';
            $html .= '</div>';
        }

        // Mobile Show More (visible on small screens)
        if ($moreCategories->isNotEmpty()) {
            $html .= '<div class="col-12 d-md-none text-center py-3 shopmore2">';
            $html .= '<select class="shopmore-mobile" onchange="if(this.value) window.location.href=this.value">';
            $html .= '<option>Show More</option>';
            foreach($moreCategories as $category) {
                $html .= '<option value="' . route('allcategorywiseproduct', $category->id) . '">';
                $html .= htmlspecialchars($this->formatCategoryName($category->name));
                $html .= '</option>';
            }
            $html .= '</select>';
            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }

    private function formatCategoryName($name)
    {
        if (empty($name)) return '';
        // Convert to lowercase first to handle ALL CAPS from backend
        $name = mb_strtolower($name, 'UTF-8');
        // Convert to Title Case (First Letter Of Each Word)
        return mb_convert_case($name, MB_CASE_TITLE, 'UTF-8');
    }

    private function getInsideVendors($lat, $lng)
    {
        $vendorIds = $this->getVendorIdsByDeliveryLocation($lat, $lng);
        if ($vendorIds->isEmpty()) {
            return collect();
        }
        return VendorAdmin::whereIn('id', $vendorIds)
            ->where('status', '1')
            ->where('is_active', '1')
            ->whereNull('deleted_at')
            ->get();
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

    /**
     * Check if a location is within any master location area
     */
    public function checkLocationInMaster(Request $request)
    {
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');

        if (!$lat || !$lng) {
            return response()->json([
                'is_in_master_area' => false,
                'message' => 'Invalid location coordinates'
            ]);
        }

        $checker = app(LocationServiceability::class);
        $a = $checker->analyze((float) $lat, (float) $lng);

        return response()->json([
            'is_in_master_area' => $a['is_in_master_area'],
            'is_in_vendor_area' => $a['is_in_vendor_area'],
            'is_valid_for_selection' => $a['is_valid_for_selection'],
            'vendor_count' => $a['vendor_count'],
            'vendor_ids' => $a['vendor_ids'],
            'message' => $a['is_valid_for_selection']
                ? 'Location is serviceable.'
                : 'Sorry we are not providing service this location.'
        ]);
    }


}
