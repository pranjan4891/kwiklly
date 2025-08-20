<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;

class CouponController extends Controller
{
    public function index()
    {
        $title = 'Coupons | Branch Panel';

        $branch  = Auth::guard('branch')->user();
        if (!$branch ) {
            return redirect()->route('branch.dashboard')->with('error', 'You do not have permission to view this page.');
        }

        $coupons = Coupon::where('is_deleted', 0)->where('is_active', 1)->latest()->get();
        return view('branch.coupon.index', compact('coupons', 'title', 'branch'));
    }

    public function create()
    {
        $title = 'Create Coupon | Branch Panel';
        $branch  = Auth::guard('branch')->user();
        if (!$branch ) {
            return redirect()->route('branch.dashboard')->with('error', 'You do not have permission to create a coupon.');
        }
        $products = Product::where('is_deleted', 0)->where('is_active', 1)->where('vendor_id', $branch ->id)->get(); // Fix: not Product::all()
        $categories = Category::where('is_deleted', 0)->where('is_active', 1)->get(); // main categories
        $subcategories = Subcategory::where('is_deleted', 0)->where('is_active', 1)->get(); // Fix: not Category
        return view('branch.coupon.create', compact('products', 'categories', 'subcategories', 'title', 'branch'));
    }

    public function store(Request $request)
    {
        $branch  = Auth::guard('branch')->user();
        $data = $request->validate([
            'code' => 'required|unique:coupons',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric',
            'min_order_amount' => 'nullable|numeric',
            'max_uses' => 'nullable|integer',
            'max_uses_per_user' => 'nullable|integer',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'applies_to' => 'required|in:all,product,category,attributes',
            'is_active' => 'boolean',
            'product_ids' => 'array',
            'category_ids' => 'array',
            'subcategory_ids' => 'array',
        ]);

        $data['created_by_type'] = $branch ->user_type;
        $data['created_by_id'] = $branch ->id;

        $coupon = Coupon::create($data);

        $coupon->products()->sync($request->product_ids);
        $coupon->categories()->sync($request->category_ids);
        $coupon->subcategories()->sync($request->subcategory_ids);

        return redirect()->route('branch.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function edit(Coupon $coupon)
    {
        $title = 'Edit Coupon | Branch Panel';
        $branch  = Auth::guard('branch')->user();
        if (!$branch ) {
            return redirect()->route('branch.dashboard')->with('error', 'You do not have permission to edit this coupon.');
        }
        $products = Product::where('is_deleted', 0)->where('is_active', 1)->where('vendor_id', $branch ->id)->get();
        $categories = Category::where('is_deleted', 0)->where('is_active', 1)->get();
        $subcategories = Subcategory::where('is_deleted', 0)->where('is_active', 1)->get();
        return view('branch.coupon.edit', compact('title', 'branch', 'coupon', 'products', 'categories', 'subcategories'));
    }

   public function update(Request $request, Coupon $coupon)
    {
        $branch  = Auth::guard('branch')->user();

        $data = $request->validate([
            'code' => 'required|unique:coupons,code,' . $coupon->id,
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric',
            'min_order_amount' => 'nullable|numeric',
            'max_uses' => 'nullable|integer',
            'max_uses_per_user' => 'nullable|integer',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'applies_to' => 'required|in:all,product,category,subcategory',
            'is_active' => 'boolean',
            'product_ids' => 'array',
            'category_ids' => 'array',
            'subcategory_ids' => 'array',
        ]);

        $data['created_by_type'] = $branch ->user_type;
        $data['created_by_id'] = $branch ->id;

        $coupon->update($data);

        // Check if applies_to is set before syncing to avoid null inserts
        $coupon->products()->sync($request->product_ids ?? []);
        $coupon->categories()->sync($request->category_ids ?? []);
        $coupon->subcategories()->sync($request->subcategory_ids ?? []);

        return redirect()->route('branch.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    // deleted coupon
    public function destroy(Request $request, Coupon $coupon)
    {
        $branch  = Auth::guard('branch')->user();

        if (!$branch ) {
            return redirect()->route('branch.dashboard')->with('error', 'You do not have permission to delete this coupon.');
        }

        if ($coupon->usages()->count() > 0) {
            return redirect()->route('branch.coupons.index')->with('error', 'Cannot delete coupon with usages. Please move it to trash instead.');
        }

        // Optional: check if Branch/vendor owns this coupon (if applicable)
        if ($coupon->created_by_id !== $branch->id) {
            return redirect()->route('branch.coupons.index')->with('error', 'Unauthorized action.');
        }

        // Soft-delete: mark as deleted and deactivate
        $coupon->update([
            'is_deleted' => 1,
            'is_active' => 0,
        ]);

        return redirect()->route('branch.coupons.index')->with('success', 'Coupon moved to trash.');
    }

    // listing deleted coupons
    public function deleted()
    {
        $title = 'Deleted Coupons | Branch Panel';
        $branch  = Auth::guard('branch')->user();
        if (!$branch ) {
            return redirect()->route('branch.dashboard')->with('error', 'You do not have permission to view deleted coupons.');
        }
        $coupons = Coupon::where('is_deleted', 1)->get();
        return view('branch.coupon.delete', compact('coupons', 'title', 'branch'));
    }

    // restore deleted coupon
    public function restore($id)
    {
        $branch  = Auth::guard('branch')->user();
        if (!$branch ) {
            return redirect()->route('branch.dashboard')->with('error', 'You do not have permission to restore this coupon.');
        }
        $coupon = Coupon::findOrFail($id);
        // $coupon->restore();
        $coupon->update(['is_deleted' => 0, 'is_active' => 1]);
        return redirect()->route('branch.coupons.deleted')->with('success', 'Coupon restored successfully.');
    }
    // permanently delete coupon
   public function forceDelete($id)
    {
        $branch  = Auth::guard('branch')->user();

        if (!$branch ) {
            return redirect()->route('branch.dashboard')->with('error', 'You do not have permission to permanently delete this coupon.');
        }

        $coupon = Coupon::withTrashed()->findOrFail($id);
        $coupon->forceDelete();

        return redirect()->route('branch.coupons.deleted')->with('success', 'Coupon permanently deleted successfully.');
    }

}

