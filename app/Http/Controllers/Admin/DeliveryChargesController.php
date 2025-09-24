<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryCharge;
use App\Models\VendorAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryChargesController extends Controller
{
    //
    public function index()
    {
        $title = 'Admin | Delivery Charges';
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $devileryCharge = DeliveryCharge::orderBy('updated_at')->get();

        return view('admin.delivery_charges',compact('title','admin','devileryCharge'));
    }
    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }

        $deliveryCharge = DeliveryCharge::findOrFail($request->id);
        $deliveryCharge->status = $request->status;
        $deliveryCharge->save();

        // ✅ If approved, sync data with vendor_admins table
        if ($request->status == 1) {
            VendorAdmin::where('id', $deliveryCharge->vendor_id)
                ->update([
                    'delivery_charge'        => $deliveryCharge->delivery_charge,
                    'delivery_range'         => $deliveryCharge->delivery_range,
                    'delivery_charge_status' => 1, // approved
                    'updated_at'             => now(),
                ]);
        }

        return redirect()->back()->with('success', 'Delivery Charge Updated Successfully');
    }

}
