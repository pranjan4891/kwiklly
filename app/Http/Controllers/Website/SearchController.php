<?php

namespace App\Http\Controllers\Website;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImages;
use App\Models\DeliveryLocation;
// use App\Models\MasterLocation; // commented with master location fallback
use App\Models\VendorAdmin;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');

        // If location provided, filter by delivery_locations (vendor/branch delivery areas)
        $vendorIds = collect();
        if ($lat && $lng) {
            $vendorIds = $this->getVendorIdsByDeliveryLocation($lat, $lng);
        } else {
            // No location, show no products
            $vendorIds = collect();
        }

        if ($vendorIds->isNotEmpty()) {
            $products = Product::with([
                    'category',
                    'subcategory',
                    'vendor',
                    'featureImage',
                    'variants.images',
                ])
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->whereIn('vendor_id', $vendorIds)
                ->inStock()
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
            $vendorIds = $this->getVendorIdsByDeliveryLocation($lat, $lng);
        }

        if ($vendorIds->isNotEmpty()) {
            $products = Product::with('featureImage')
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->whereIn('vendor_id', $vendorIds)
                ->inStock()
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

    /**
     * Get vendor IDs for user's location: delivery_locations first, fallback to master location.
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
    //         ->whereIn('user_type', ['vendor', 'branch', 'admin'])
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

    // Ray casting algorithm for point in polygon
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
}
