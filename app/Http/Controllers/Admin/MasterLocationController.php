<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterLocation;
use App\Models\State;
use App\Models\City;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class MasterLocationController extends Controller
{
    //
   public function index()
    {
        $title = 'Master Locations';
        $admin = Auth::guard('admin')->user();

        // Eager load relationships to avoid N+1 problem
        $locations = MasterLocation::with(['state', 'city'])->where('is_deleted', 0)->where('is_active', 1)->get();
        if($admin){
            return view('admin.location.index', compact('title', 'admin', 'locations'));
        }else{
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }

    }

    public function createLocation()
    {
        $title = 'Create Master Location';
        $admin = Auth::guard('admin')->user();
        // Fetch necessary data for the form, e.g., countries, states
        $countries = Country::all(); // Fetch all countries
        $states = State::where('is_active', 1)->get(); // Fetch all states
        return view('admin.location.create', compact('title', 'admin', 'countries', 'states'));
    }
    public function storeLocation(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'pincode' => 'required|string|size:6',
            'lat_long' => 'required|string', // Storing as a JSON/text string
            'place' => 'required|string|max:255', // Optional field
        ],
        [
            'state_id.required' => 'State is required.',
            'city_id.required' => 'City is required.',
            'pincode.required' => 'Pincode is required and must be exactly 6 characters.',
            'lat_long.required' => 'Latitude and Longitude are required.',
        ]);

        // Decode polygon and close it if needed
        $latLongArray = json_decode($request->lat_long, true);

        if (is_array($latLongArray) && count($latLongArray) > 2) {
            $first = $latLongArray[0];
            $last = end($latLongArray);

            // Auto-close the polygon if not already closed
            if ($first['lat'] != $last['lat'] || $first['lng'] != $last['lng']) {
                $latLongArray[] = $first;
            }
        }

        // Save the data
        MasterLocation::create([
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'pincode' => $request->pincode,
            'place' => $request->place ?? null, // Optional field
            'lat_long' => json_encode($latLongArray),
        ]);

        return redirect()->route('admin.location')->with('success', 'Master Location created successfully.');
    }


    public function editLocation($id)
    {
        $title = 'Edit Master Location';
        $admin = Auth::guard('admin')->user();
        $location = MasterLocation::findOrFail($id);
        $countries = Country::all(); // Fetch all countries
        $states = State::where('is_active', 1)->get(); // Fetch all states
        $cities = City::where('state_id', $location->state_id)->get(); // Fetch cities based on the selected state
        if($admin){
            return view('admin.location.edit', compact('title', 'admin', 'location', 'countries', 'states', 'cities'));

        }else{
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
    }

   public function updateLocation(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'pincode' => 'required|string|size:6',
            'place' => 'required|string|max:255',
            'lat_long' => 'required|string', // Storing as a JSON/text string
        ],
        [
            'state_id.required' => 'State is required.',
            'city_id.required' => 'City is required.',
            'pincode.required' => 'Pincode is required and must be exactly 6 characters.',
            'lat_long.required' => 'Latitude and Longitude are required.',
        ]);

        // Decode polygon and auto-close it if needed
        $latLongArray = json_decode($request->lat_long, true);

        if (is_array($latLongArray) && count($latLongArray) > 2) {
            $first = $latLongArray[0];
            $last = end($latLongArray);

            if ($first['lat'] != $last['lat'] || $first['lng'] != $last['lng']) {
                $latLongArray[] = $first; // Close the polygon
            }
        }

        // Find and update the location
        $location = MasterLocation::findOrFail($id);
        $location->update([
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'pincode' => $request->pincode,
            'place' => $request->place,
            'lat_long' => json_encode($latLongArray),
        ]);

        return redirect()->route('admin.location')->with('success', 'Master Location updated successfully.');
    }


    // soft delete functionality
    public function deleteLocation($id)
    {
        $location = MasterLocation::findOrFail($id);
        $location->update([
            'is_deleted' => true,
            'is_active' => false, // Optionally set to inactive
        ]);
        return redirect()->route('admin.location')->with('success', 'Master Location deleted successfully.');
    }

    public function deletedLocations()
    {
        $title = 'Deleted Master Locations';
        $admin = Auth::guard('admin')->user();
        // Fetch only deleted locations
        $locations = MasterLocation::where('is_deleted', true)->with(['state', 'city'])->get();
        if(!$admin){
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        // Return the view with deleted locations
        return view('admin.location.delete', compact('title', 'admin', 'locations'));
    }

    public function restoreLocation($locationId)
    {
        $location = MasterLocation::findOrFail($locationId);
        $location->update([
            'is_deleted' => false,
            'is_active' => true, // Optionally set to active
        ]);
        return redirect()->route('admin.location')->with('success', 'Master Location restored successfully.');
    }
    public function destroyLocation($id)
    {
        MasterLocation::destroy($id);
        return redirect()->route('admin.location')->with('success', 'Master Location deleted successfully.');
    }
}
