<?php

namespace App\Http\Controllers\Vendor;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorAdmin;
use App\Models\Category;
use App\Models\TimeSlot;
use Illuminate\Support\Facades\Log;


class VendorDashboardController extends Controller
{
    public function dashboard()
    {
        $vendor = VendorAdmin::find(Auth::id());
        return view('vendorpanel.dashboard',compact('vendor'));
    }
   public function profile()
    {
        $vendor = VendorAdmin::find(Auth::id());

        $data['vendor'] = $vendor;
        $data['categories'] = Category::where('is_active', '1')->get();
        $data['timeSlots'] = TimeSlot::orderBy('id')->get();

        $storeTimeRaw = $vendor->store_time;

        // If store_time is a JSON string, decode it
        if (is_string($storeTimeRaw)) {
            $storeTimeRaw = json_decode($storeTimeRaw, true);
        }

        // Reindex by day name
        $storetime = [];
        if (is_array($storeTimeRaw)) {
            foreach ($storeTimeRaw as $entry) {
                if (!empty($entry['day_name'])) {
                    $storetime[$entry['day_name']] = $entry;
                }
            }
        }

        $data['storetime'] = $storetime;

        return view('vendorpanel.profile')->with($data);
    }


  public function updateProfile(Request $request)
{
    \Log::info('Update profile request received', $request->all());
    $vendor = VendorAdmin::find(Auth::id());
    if (!$vendor) {
        return back()->with('error', 'Vendor not found');
    }

    // Update fields
    $vendor->display_name = $request->display_name;
    $vendor->business_category = is_array($request->business_category) ? implode(',', $request->business_category) : null;
    $vendor->minimum_order_value = $request->minimum_order_value;
    $vendor->business_description = $request->business_description;
    $vendor->service_offered = $request->service_offered;
    $vendor->business_name = $request->business_name;
    $vendor->gstin = $request->gstin;
    $vendor->landmark = $request->landmark;
    $vendor->business_address = $request->business_address;
    $vendor->email = $request->email;
    $vendor->phone = $request->phone;
    $vendor->account_holder_name = $request->account_holder_name;
    $vendor->account_number = $request->account_number;
    $vendor->ifsc_code = $request->ifsc_code;
    $vendor->bank_name = $request->bank_name;
    $vendor->bank_city = $request->bank_city;
    $vendor->bank_branch = $request->bank_branch;

    // Handle file uploads
    $fileFields = [
        'cancel_cheque_image',
        'pan_image',
        'address_proof_image',
        'tan_image',
        'cin_image',
        'personal_pan_image',
        'personal_address_proof_image'
    ];

    foreach ($fileFields as $field) {
        if ($request->hasFile($field)) {
            $file = $request->file($field);
            $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('vendor_files', $filename, 'public');
            $vendor->$field = $path;
        }
    }

    $vendor->save();

    return back()->with('success', 'Profile updated successfully.');
}

  public function storeTime(Request $request)
{
\Log::info('Store time request received', $request->all());
    $storeSchedule = [];

    for ($i = 1; $i <= 7; $i++) {
        $storeSchedule[] = [
            'day_id'     => $request->input("day_id_$i") ?? null,
            'day_name'   => $request->input("day_name_$i"),
            'status'     => $request->input("day_oc_$i"),
            'startTime'  => $request->input("open_time_$i"),
            'endTime'    => $request->input("closed_time_$i")
        ];
    }

    $vendor = VendorAdmin::find(Auth::id());

    if (!$vendor) {
        return redirect()->back()->with('error', 'Vendor not found');
    }

    $vendor->store_time = json_encode($storeSchedule);
    $vendor->store_time_status = 1;
    $vendor->save();

    return redirect()->back()->with('success', 'Store timings updated successfully.');
}

}
