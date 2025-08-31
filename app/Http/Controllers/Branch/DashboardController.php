<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Order;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\VendorOrder;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $branch= Auth::guard('branch')->user();
        if (!$branch) {
            return redirect()->route('branch.login')->with('error', 'You are not authorized to access this page.');
        }
        $title = 'Admin | Dashboard';
        $totalproduct = Product::where('is_deleted', 0)->where('vendor_id', $branch->id)->count();
        $orderComplete = VendorOrder::where('delivery_status', 'packed')->where('vendor_id', $branch->id)->count();
        $orderPending = VendorOrder::where('delivery_status', 'pending')->where('vendor_id', $branch->id)->count();
        $orderShipped = VendorOrder::where('delivery_status', 'shipped')->where('vendor_id', $branch->id)->count();
        $orderCancelled = VendorOrder::where('delivery_status', 'cancelled')->where('vendor_id', $branch->id)->count();
        $orderDeliered = VendorOrder::where('delivery_status', 'delivered')->where('vendor_id', $branch->id)->count();
        $cartItem = CartItem::where('vendor_id', $branch->id)->count();
        $products = Product::with(['category', 'subcategory', 'vendor', 'featureImage'])->where('is_deleted', 0)->orderBy('id', 'desc')->where('vendor_id', $branch->id)->take(5)->get();

        return view('branch.dashboard',compact('branch','title','totalproduct','orderComplete','orderPending','orderCancelled','orderDeliered','cartItem','orderShipped','products'));
    }



}
