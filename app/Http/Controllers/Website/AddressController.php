<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function store(Request $request)
    {
       // dd($request->all());
        $request->validate([
            'type' => 'required|in:home,work',
            'area' => 'required',
            'flat' => 'required',
            'pincode' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $userId = auth()->id();
        $makeSelected = filter_var($request->input('is_selected', true), FILTER_VALIDATE_BOOLEAN);

        $address = DB::transaction(function () use ($request, $userId, $makeSelected) {
            if ($makeSelected) {
                CustomerAddress::where('user_id', $userId)->update(['is_selected' => false]);
            }

            return CustomerAddress::create([
                'user_id' => $userId,
                'type' => $request->type,
                'area' => $request->area,
                'flat' => $request->flat,
                'landmark' => $request->landmark,
                'pincode' => $request->pincode,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'name' => $request->name,
                'phone' => $request->phone,
                'alt_phone' => $request->alt_phone,
                'full_address' => $request->flat . ', ' . $request->area . ', ' . ($request->landmark ? $request->landmark . ', ' : '') . $request->pincode,
                'is_selected' => $makeSelected,
            ]);
        });

        return response()->json(['success' => true, 'message' => 'Address saved successfully', 'address' => $address]);
    }

    public function update(Request $request, $id)
    {
        $userId = auth()->id();
        $address = CustomerAddress::where('user_id', $userId)->findOrFail($id);
        $payload = $request->only([
            'type', 'area', 'flat', 'landmark', 'pincode', 'latitude', 'longitude', 'name', 'phone', 'alt_phone'
        ]);

        DB::transaction(function () use ($request, $userId, $address, $payload) {
            if ($request->has('is_selected')) {
                $makeSelected = filter_var($request->input('is_selected'), FILTER_VALIDATE_BOOLEAN);

                if ($makeSelected) {
                    CustomerAddress::where('user_id', $userId)
                        ->where('id', '!=', $address->id)
                        ->update(['is_selected' => false]);
                }

                $payload['is_selected'] = $makeSelected;
            }

            $address->update($payload);
        });

        return response()->json(['success' => true, 'message' => 'Address updated']);
    }

    public function delete($id)
    {
        $address = CustomerAddress::where('user_id', auth()->id())->findOrFail($id);
        $address->delete();

        return response()->json(['success' => true, 'message' => 'Address deleted']);
    }



    /** Max distance (km) to consider an address in "current location" */
    const CURRENT_LOCATION_RADIUS_KM = 50;

    public function getAddresses(Request $request)
    {
        $query = CustomerAddress::where('user_id', auth()->id());

        if ($request->has('id')) {
            $address = $query->where('id', $request->id)->firstOrFail();
            return response()->json(['address' => $address]);
        }

        $addresses = $query->whereNull('deleted_at')->orderByDesc('is_selected')->latest()->get();

        // Filter by current location: only addresses within radius of given lat/lng
        if ($request->filled('latitude') && $request->filled('longitude')) {
            $lat = (float) $request->latitude;
            $lng = (float) $request->longitude;
            $addresses = $addresses->filter(function ($addr) use ($lat, $lng) {
                if ($addr->latitude === null || $addr->longitude === null) {
                    return false;
                }
                $km = CustomerAddress::distanceInKm($lat, $lng, (float) $addr->latitude, (float) $addr->longitude);
                return $km !== null && $km <= self::CURRENT_LOCATION_RADIUS_KM;
            })->values();
        }

        return response()->json($addresses);
    }

    public function select($id)
    {
        $userId = auth()->id();
        $address = CustomerAddress::where('user_id', $userId)->findOrFail($id);

        DB::transaction(function () use ($userId, $address) {
            CustomerAddress::where('user_id', $userId)
                ->where('id', '!=', $address->id)
                ->update(['is_selected' => false]);
            $address->is_selected = true;
            $address->save();
        });

        return response()->json(['success' => true, 'message' => 'Address selected successfully']);
    }


    public function getSingleAddress($id)
    {
        $address = CustomerAddress::where('user_id', auth()->id())
            ->findOrFail($id);

        return response()->json(['address' => $address]);
    }


    public function list()
    {
        $addresses = CustomerAddress::where('user_id', auth()->id())->latest()->get();

        $html = '';

        foreach ($addresses as $address) {
            $type = ucfirst($address->type);
            $icon = $address->type === 'home'
                ? 'https://cdn-icons-png.flaticon.com/128/69/69524.png'
                : 'https://cdn-icons-png.flaticon.com/128/609/609803.png';

            $html .= '
            <div class="address-card">
                <div class="address-left">
                    <img src="' . $icon . '" alt="' . $type . '" class="icon">
                    <div>
                        <strong>' . $type . '</strong>
                        <p>' . $address->name . ', ' . $address->flat . ', ' . $address->area . ', ' . $address->landmark . ', ' . $address->pincode . '</p>
                    </div>
                </div>
                <div class="address-right">
                    <span class="check">&#x2714;</span>
                    <div class="dropdown-wrapper">
                        <span class="options" onclick="toggleDropdown(this)">&#8942;</span>
                        <div class="dropdown-menu">
                            <div onclick="editAddress(' . $address->id . ')">Edit</div>
                            <div onclick="deleteAddress(this, ' . $address->id . ')">Delete</div>
                        </div>
                    </div>
                </div>
            </div>';
        }

        return response()->json(['html' => $html]);
    }

}
