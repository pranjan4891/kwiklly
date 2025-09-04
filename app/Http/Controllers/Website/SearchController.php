<?php

namespace App\Http\Controllers\Website;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImages;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        $products = Product::with([
                'category',
                'subcategory',
                'vendor',
                'featureImage'
            ])
            ->where('is_active', 1)
            ->where('is_deleted', 0)
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

        // ✅ Count how many vendors (stores) matched
        $storeCount = $products->groupBy('vendor_id')->count();

        return view('web.searchresults', compact('products', 'query', 'storeCount'));
    }


}
