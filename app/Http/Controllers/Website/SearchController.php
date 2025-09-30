<?php

namespace App\Http\Controllers\Website;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImages;
use App\Models\MasterLocation;
use App\Models\VendorAdmin;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');

        // If location provided, filter by same MasterLocation
        $vendorIds = collect();
        if ($lat && $lng) {
            $insideLocation = $this->findUserLocation($lat, $lng);
            if ($insideLocation) {
                $vendorIds = $this->getVendorsInLocation($insideLocation);
            }
        } else {
            // No location, show no products
            $vendorIds = collect();
        }

        if ($vendorIds->isNotEmpty()) {
            $products = Product::with([
                    'category',
                    'subcategory',
                    'vendor',
                    'featureImage'
                ])
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->whereIn('vendor_id', $vendorIds)
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                    ->orWhere('sub_title', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    // ✅ Search in Category Name
                    ->orWhereHas('category', function ($cat) use ($query) {
                        $cat->where('name', 'LIKE', "%{$query}%");
                    })
                    // ✅ Search in Subcategory Name
                    ->orWhereHas('subcategory', function ($subcat) use ($query) {
                        $subcat->where('sub_cat_name', 'LIKE', "%{$query}%");
                    });
                })
                ->get();
        } else {
            $products = collect(); // No products if no location or no vendors
        }

        // ✅ Count how many vendors (stores) matched
        $storeCount = $products->groupBy('vendor_id')->count();

        return view('web.searchresults', compact('products', 'query', 'storeCount'));
    }

    public function suggestions(Request $request)
    {
        $query = $request->input('q');
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');

        if (!$query) {
            return response()->json([]);
        }

        $vendorIds = collect();
        if ($lat && $lng) {
            $insideLocation = $this->findUserLocation($lat, $lng);
            if ($insideLocation) {
                $vendorIds = $this->getVendorsInLocation($insideLocation);
            }
        }

        if ($vendorIds->isNotEmpty()) {
            $products = Product::with('featureImage')
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->whereIn('vendor_id', $vendorIds)
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                    ->orWhere('sub_title', 'LIKE', "%{$query}%");
                })
                ->limit(5)
                ->get()
                ->map(function ($product) {
                    return [
                        'id'    => $product->id,
                        'title' => $product->title,
                        'image' => $product->featureImage ? asset('public/' . optional($product->featureImage)->feature_image) : asset('public/marker.png'),
                    ];
                });
        } else {
            $products = collect();
        }

        return response()->json($products);
    }

    private function findUserLocation($lat, $lng)
    {
        return MasterLocation::where('is_active', 1)
            ->where('is_deleted', 0)
            ->get()
            ->first(function($location) use ($lat, $lng) {
                return $this->pointInPolygon($lat, $lng, $location->lat_long);
            });
    }

    private function getVendorsInLocation($location)
    {
        return VendorAdmin::where('status', '1')
            ->where('is_active', '1')
            ->where('user_type', 'vendor')
            ->whereNull('deleted_at')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->filter(function ($vendor) use ($location) {
                return $this->pointInPolygon(
                    $vendor->latitude,
                    $vendor->longitude,
                    $location->lat_long
                );
            })->pluck('id');
    }

    // Ray casting algorithm for point in polygon
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
}
