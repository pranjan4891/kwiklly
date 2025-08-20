<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Banner;
use App\Models\Category;


class DashboardController extends Controller
{
    //
    public function index()
    {
        $admin= Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $title = 'Admin | Dashboard';
        return view('admin.dashboard',compact('admin','title'));
    }

    //Banner
    public function banner()
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $title = 'Admin | Banner';
        $banners = Banner::where('is_deleted', 0)->orderBy('position')->get()->groupBy('banner_cat_id');
        $categories = Category::where('is_deleted', 0)->get();

        return view('admin.banner', compact('admin', 'title', 'banners', 'categories'))->with('isDeletedView', false);
    }

    public function deletedbanner()
    {
        $admin = Auth::guard('admin')->user();
        $title = 'Admin | Deleted Banners';
        $banners = Banner::where('is_deleted', 1)->orderBy('position')->get()->groupBy('banner_cat_id');
        $categories = Category::where('is_deleted', 0)->get();

        return view('admin.banner', compact('admin', 'title', 'banners', 'categories'))->with('isDeletedView', true);
    }
    public function bannerstore(Request $request)
    {
        $rules = [
            'banner_cat_id' => 'required|integer',
            'cat_img' => 'required|image',
            'banner_pos' => 'required|integer',
            'banner_url' => 'nullable|url',
        ];

        // Conditionally require mobile image
        if ($request->banner_cat_id == 1) {
            $rules['mob_img'] = 'required|image';
        } else {
            $rules['mob_img'] = 'nullable|image';
        }

        $request->validate($rules);

        // Handle desktop image upload
        $desktopImage = $request->file('cat_img');
        $desktopImageName = time() . '_desktop_' . $desktopImage->getClientOriginalName();
        $desktopImage->move(public_path('uploads/banners'), $desktopImageName);
        $desktopPath = 'uploads/banners/' . $desktopImageName;

        // Handle mobile image upload if exists
        $mobilePath = null;
        if ($request->hasFile('mob_img')) {
            $mobileImage = $request->file('mob_img');
            $mobileImageName = time() . '_mobile_' . $mobileImage->getClientOriginalName();
            $mobileImage->move(public_path('uploads/banners'), $mobileImageName);
            $mobilePath = 'uploads/banners/' . $mobileImageName;
        }

        Banner::create([
            'banner_cat_id' => $request->banner_cat_id,
            'desktop_image' => $desktopPath,
            'mobile_image' => $mobilePath,
            'position' => $request->banner_pos,
            'banner_url' => $request->banner_url,
            'master_category_id' => $request->master_category,
            'is_active' => 1,
            'is_deleted' => 0,
        ]);

        return redirect()->back()->with('success', 'Banner added successfully');
    }

    public function bannerupdate(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        if ($request->hasFile('cat_img')) {
            // Handle desktop image upload
            $desktopImage = $request->file('cat_img');
            $desktopImageName = time() . '_desktop_' . $desktopImage->getClientOriginalName();
            $desktopImage->move(public_path('uploads/banners'), $desktopImageName);
            $banner->desktop_image = 'uploads/banners/' . $desktopImageName;
        }
        // Handle mobile image upload if exists
        if ($request->hasFile('mob_img')) {
            $mobileImage = $request->file('mob_img');
            $mobileImageName = time() . '_mobile_' . $mobileImage->getClientOriginalName();
            $mobileImage->move(public_path('uploads/banners'), $mobileImageName);
            $banner->mobile_image = 'uploads/banners/' . $mobileImageName;
        }
        $banner->banner_cat_id = $request->banner_cat_id;
        $banner->position = $request->banner_pos;
        $banner->banner_url = $request->banner_url;
        $banner->master_category_id = $request->master_category;
        $banner->is_active = $request->status;
        $banner->is_deleted = 0;
        $banner->save();
        return redirect()->back()->with('success', 'Banner updated successfully');
    }

    public function bannerdestroy($id)
    {
        Banner::where('id', $id)->update(['is_active' => 0,'is_deleted' => 1]);
        return redirect()->back()->with('success', 'Banner deleted successfully');
    }

    // Permanent delete
    public function forceDelete($id)
    {
        $banner = Banner::findOrFail($id);

        // Unlink desktop image
        if ($banner->desktop_image && file_exists(public_path($banner->desktop_image))) {
            @unlink(public_path($banner->desktop_image));
        }

        // Unlink mobile image
        if ($banner->mobile_image && file_exists(public_path($banner->mobile_image))) {
            @unlink(public_path($banner->mobile_image));
        }

        $banner->delete(); // or ->forceDelete() if using SoftDeletes trait

        return redirect()->back()->with('success', 'Banner permanently deleted.');
    }

}
