<?php

namespace App\Http\Controllers\Vendor;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorAdmin;
use App\Models\Category;
use App\Models\DeliveryCharge;
use App\Models\TimeSlot;
use App\Models\LocationContact;
use App\Models\DeliveryLocation;
use App\Models\MasterLocation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\LocationSharingMail;


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
        $data['deliveryCharge'] = DeliveryCharge::where('vendor_id', $vendor->id)->first();

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

        // Delivery location (vendor_id wise, like branches)
        $data['deliveryLocation'] = DeliveryLocation::where('vendor_id', $vendor->id)
            ->where('is_deleted', 0)
            ->first();

        // Master location area that contains vendor's lat/long (if set)
        $data['masterLocationArea'] = null;
        if ($vendor->latitude && $vendor->longitude) {
            $locations = MasterLocation::where('is_active', 1)->where('is_deleted', 0)->get();
            foreach ($locations as $loc) {
                $polygon = is_string($loc->lat_long) ? json_decode($loc->lat_long, true) : $loc->lat_long;
                if ($polygon && $this->isPointInPolygon((float) $vendor->latitude, (float) $vendor->longitude, $polygon)) {
                    $data['masterLocationArea'] = ['place' => $loc->place, 'pincode' => $loc->pincode, 'lat_long' => $polygon];
                    break;
                }
            }
        }

        return view('vendorpanel.profile')->with($data);
    }


    public function updateProfile(Request $request)
    {
        //\Log::info('Update profile request received', $request->all());
        $vendor = VendorAdmin::find(Auth::id());
        if (!$vendor) {
            return back()->with('error', 'Vendor not found');
        }

        // Update fields
        $vendor->display_name = $request->display_name;
        $vendor->is_home_request = $request->has('is_home_request') ? 1 : 0;
        $vendor->minimum_order_for_cook = $request->minimum_order_for_cook;
        $vendor->dy_text = $request->dy_text;
        $vendor->business_category = is_array($request->business_category) ? implode(',', $request->business_category) : null;
        $vendor->minimum_order_value = $request->minimum_order_value;
        $vendor->delivery_charge = $request->delivery_charge;
        $vendor->delivery_range = $request->delivery_range;
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
        //\Log::info('Store time request received', $request->all());
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

    public function updateDeliveryCharges(Request $request)
    {
        $vendor = auth()->user();

        $request->validate([
            'delivery_charge' => 'required|numeric|min:0',
            'delivery_range'  => 'required|numeric|min:1',
        ]);

        $delivery_charge = DeliveryCharge::updateOrCreate(
            ['vendor_id' => $vendor->id],
            [
                'delivery_charge' => $request->delivery_charge,
                'delivery_range'  => $request->delivery_range,
                'status' => 0, // reset to pending on update
            ]
        );

        // reload to make sure we have latest DB data
        $delivery_charge->refresh();

        return response()->json([
            'status'  => true,
            'message' => 'Delivery charges updated successfully',
            'data'    => [
                'delivery_charge' => $delivery_charge->delivery_charge,
                'delivery_range'  => $delivery_charge->delivery_range,
                'status'          => $delivery_charge->status,
                'status_text'     => $delivery_charge->status == 1 ? 'Approved' : ($delivery_charge->status == 2 ? 'Rejected' : 'Pending')
            ]
        ]);
    }

    public function saveLocationContact(Request $request)
    {
        $request->validate([
            'contact_method' => 'required|in:email,sms,whatsapp',
            'contact_detail' => 'required|string|max:255'
        ]);

        $vendor = auth()->user();

        // Create or update contact
        LocationContact::updateOrCreate(
            [
                'vendor_id' => $vendor->id,
                'contact_method' => $request->contact_method
            ],
            [
                'contact_detail' => $request->contact_detail
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Contact saved successfully'
        ]);
    }

    public function getLocationContacts()
    {
        $vendor = auth()->user();

        $contacts = LocationContact::where('vendor_id', $vendor->id)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'contact_method', 'contact_detail']);

        return response()->json([
            'contacts' => $contacts
        ]);
    }

    public function getLocation()
    {
        $vendor = auth()->user();

        return response()->json([
            'location' => [
                'latitude' => $vendor->latitude,
                'longitude' => $vendor->longitude
            ]
        ]);
    }

    public function shareLocation($contactId)
    {
        $vendor = auth()->user();

        $contact = LocationContact::where('vendor_id', $vendor->id)
            ->where('id', $contactId)
            ->first();

        if (!$contact) {
            return redirect()->back()->with('error', 'Contact not found');
        }

        if (!$vendor->latitude || !$vendor->longitude) {
            return redirect()->back()->with('error', 'Vendor location not available');
        }

        $locationUrl = "https://www.google.com/maps?q={$vendor->latitude},{$vendor->longitude}";

        switch ($contact->contact_method) {
            case 'whatsapp':
                $cleanContact = preg_replace('/[^0-9]/', '', $contact->contact_detail);
                $url = "https://wa.me/{$cleanContact}?text=" . urlencode("Please collect order from this location: {$locationUrl}");
                return redirect()->away($url);

            case 'email':
                try {
                    Mail::to($contact->contact_detail)->send(new LocationSharingMail($locationUrl, $vendor->business_name));
                    return redirect()->back()->with('success', 'Location shared via email successfully!');
                } catch (\Exception $e) {
                    Log::error('Failed to send location sharing email: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'Failed to send email. Please try again.');
                }

            case 'sms':
                $url = "sms:{$contact->contact_detail}?body=" . urlencode("Delivery location: {$locationUrl}");
                return redirect()->away($url);

            default:
                return redirect()->away($locationUrl);
        }
    }

    public function deleteLocationContact($id)
    {
        $vendor = auth()->user();

        $contact = LocationContact::where('vendor_id', $vendor->id)
            ->where('id', $id)
            ->first();

        if ($contact) {
            $contact->delete();
            return response()->json([
                'success' => true,
                'message' => 'Contact deleted successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Contact not found'
        ], 404);
    }

    /**
     * Get master location areas by pincode (for Delivery location tab - like admin branch)
     */
    public function getArea(Request $request)
    {
        $pincode = $request->input('pincode');
        if (!$pincode) {
            return response()->json(['places' => []]);
        }
        $locations = MasterLocation::where('pincode', $pincode)
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->get(['place', 'lat_long', 'pincode']);
        return response()->json(['places' => $locations]);
    }

    /**
     * Check if lat/lng falls inside any master location polygon (for Delivery location tab)
     */
    public function pointInPolygon(Request $request)
    {
        $lat = (float) $request->query('lat');
        $lng = (float) $request->query('lng');
        $locations = MasterLocation::where('is_active', 1)->where('is_deleted', 0)->get();
        foreach ($locations as $location) {
            $polygon = is_string($location->lat_long) ? json_decode($location->lat_long, true) : $location->lat_long;
            if ($polygon && $this->isPointInPolygon($lat, $lng, $polygon)) {
                return response()->json([
                    'success' => true,
                    'pincode' => $location->pincode,
                    'place'   => $location->place,
                    'lat_long'=> $polygon,
                ]);
            }
        }
        return response()->json([
            'success' => false,
            'message' => 'No service available in your current location.'
        ], 404);
    }

    private function isPointInPolygon($lat, $lng, $polygon)
    {
        $inside = false;
        $x = $lng;
        $y = $lat;
        $points = count($polygon);
        $j = $points - 1;
        for ($i = 0; $i < $points; $i++) {
            $xi = $polygon[$i]['lng'] ?? $polygon[$i][1];
            $yi = $polygon[$i]['lat'] ?? $polygon[$i][0];
            $xj = $polygon[$j]['lng'] ?? $polygon[$j][1];
            $yj = $polygon[$j]['lat'] ?? $polygon[$j][0];
            $intersect = (($yi > $y) != ($yj > $y)) &&
                ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi + 0.0) + $xi);
            if ($intersect) {
                $inside = !$inside;
            }
            $j = $i;
        }
        return $inside;
    }

    /**
     * Save delivery location (polygon) for current vendor - vendor_id wise like branches
     */
    public function saveDeliveryLocation(Request $request)
    {
        $request->validate([
            'service_area' => 'required|json',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
        ]);

        $vendor = auth()->user();
        $latLongArray = json_decode($request->service_area, true);
        if (!is_array($latLongArray) || count($latLongArray) < 3) {
            return response()->json(['success' => false, 'message' => 'Invalid or insufficient polygon points.'], 422);
        }
        $first = $latLongArray[0];
        $last = end($latLongArray);
        if (($first['lat'] ?? $first[0]) != ($last['lat'] ?? $last[0]) || ($first['lng'] ?? $first[1]) != ($last['lng'] ?? $last[1])) {
            $latLongArray[] = $first;
        }

        DeliveryLocation::updateOrCreate(
            ['vendor_id' => $vendor->id],
            [
                'delivery_lat_long' => $latLongArray,
                'is_active'         => 1,
                'is_deleted'        => 0,
            ]
        );

        if ($request->filled('latitude') && $request->filled('longitude')) {
            $vendor->latitude = $request->latitude;
            $vendor->longitude = $request->longitude;
            $vendor->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Delivery location saved successfully.',
        ]);
    }
}
