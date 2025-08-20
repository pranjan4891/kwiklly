<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerAddress;

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
            'phone' => 'required'
        ]);

        $address = CustomerAddress::create([
            'user_id' => auth()->id(),
            'type' => $request->type,
            'area' => $request->area,
            'flat' => $request->flat,
            'landmark' => $request->landmark,
            'pincode' => $request->pincode,
            'name' => $request->name,
            'phone' => $request->phone,
            'alt_phone' => $request->alt_phone,
            'full_address' => $request->flat . ', ' . $request->area . ', ' . $request->landmark
        ]);

        return response()->json(['success' => true, 'message' => 'Address saved successfully']);
    }

    public function update(Request $request, $id)
    {
        $address = CustomerAddress::where('user_id', auth()->id())->findOrFail($id);

        $address->update($request->only([
            'type', 'area', 'flat', 'landmark', 'pincode', 'name', 'phone', 'alt_phone'
        ]));

        return response()->json(['success' => true, 'message' => 'Address updated']);
    }

    public function delete($id)
    {
        $address = CustomerAddress::where('user_id', auth()->id())->findOrFail($id);
        $address->delete();

        return response()->json(['success' => true, 'message' => 'Address deleted']);
    }

  
  
public function getAddresses(Request $request)
{
    $query = CustomerAddress::where('user_id', auth()->id());

    if ($request->has('id')) {
        $address = $query->where('id', $request->id)->firstOrFail();
        return response()->json(['address' => $address]);
    }

    $addresses = $query->whereNull('deleted_at')->latest()->get();
    return response()->json($addresses);
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


