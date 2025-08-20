<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\VendorAdmin;
use App\Models\State;
use App\Models\City;
use App\Models\TimeSlot;
use App\Models\DeliveryLocation;
use App\Models\MasterLocation;
use Illuminate\Support\Str;

class BranchController extends Controller
{
     // Show login form
     public function index()
     {
        $title = 'Department | Login';
         return view('branch.login',compact('title'));
     }

     // Handle login request
    public function loginCheck(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Check if user exists and is an admin
        $user = VendorAdmin::where('email', $request->email)->first();

        if (!$user || $user->user_type !== 'branch') {
            return back()->withErrors(['email' => 'Access denied. Only adminstrators can login.'])->withInput();
        }

        // Now attempt login only if user_type is admin
        if (Auth::guard('branch')->attempt([
            'email' => $request->email,
            'password' => $request->password,
            'user_type' => 'branch'
        ])) {
            return redirect()->route('branch.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function profile()
    {
        $branch = Auth::guard('branch')->user();
        if (!$branch) {
            return redirect()->route('branch.login')
                ->with('error', 'You are not authorized to access this page.');
        }

        // Fetch all active states
        $states = State::where('is_active', 1)->get();

        // Fetch cities for the branch's saved state
        $cities = [];
        if (!empty($branch->state_id)) {
            $cities = City::where('state_id', $branch->state_id)->get();
        }

        // Fetch time slots
        $timeSlots = TimeSlot::orderBy('id')->get();

        // Decode store_time JSON into an array keyed by day_name
        $storetime = [];
        if (!empty($branch->store_time)) {
            $decoded = json_decode($branch->store_time, true);
            if (is_array($decoded)) {
                foreach ($decoded as $day) {
                    $storetime[$day['day_name']] = $day; // Key by day_name
                }
            }
        }

        $title = 'Department | Profile';

        return view('branch.profile', compact(
            'branch',
            'title',
            'states',
            'cities',
            'timeSlots',
            'storetime'
        ));
    }


   public function updateProfile(Request $request)
    {
        $branch = Auth::guard('branch')->user();

        if (!$branch) {
            return redirect()->route('branch.login')
                ->withErrors(['error' => 'Unauthorized access.']);
        }

        // Validation
        $request->validate([
            'business_name' => 'nullable|string|max:255',
            'business_category' => 'nullable|string|max:255',
            'business_description' => 'nullable|string',
            'business_address' => 'nullable|string',
            'state' => 'nullable|integer|exists:states,id',
            'city' => 'nullable|integer|exists:cities,id',
            'postal_code' => 'nullable|string|max:10',
            'area' => 'nullable|string',
            'bank_name' => 'nullable|string|max:255',
            'bank_branch' => 'nullable|string|max:255',
            'account_holder_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:20',
            'ifsc_code' => 'nullable|string|max:20',
            'business_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Reload branch from DB
        $branch = VendorAdmin::find($branch->id);

        if (!$branch) {
            return redirect()->route('branch.profile')
                ->withErrors(['error' => 'Branch not found.']);
        }

        // Build store schedule array
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

        // Save store time JSON
        $branch->store_time = json_encode($storeSchedule);
        $branch->store_time_status = 1;

        // Update profile fields
        $branch->fill($request->except(['business_logo', 'state', 'city']));
        $branch->state_id = $request->state;
        $branch->city_id = $request->city;

        // Handle image upload
        if ($request->hasFile('business_logo')) {
            $imagePath = $request->file('business_logo')->store('uploads/business_logos', 'public');
            $branch->business_logo = $imagePath;
        }

        $branch->save();

        return redirect()->route('branch.profile')->with('success', 'Profile updated successfully.');
    }


    public function showChangePasswordForm()
    {
        $branch= Auth::guard('branch')->user();
        if (!$branch) {
            return redirect()->route('branch.login')->with('error', 'You are not authorized to access this page.');
        }
        $title = 'Department | Change Password';
        return view('branch.change_password', compact('branch','title'));
    }

    public function updatePassword(Request $request)
    {
        $branch= Auth::guard('branch')->user();
        if (!$branch) {
            return redirect()->route('branch.login')->with('error', 'You are not authorized to access this page.');
        }

        // Validate inputs
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $branch=  VendorAdmin::find($branch->id);

        // Check if old password is correct
        if (!Hash::check($request->old_password, $branch->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        // Update password
        $branch->password = Hash::make($request->new_password);
        $branch->save();

        return back()->with('success', 'Password updated successfully.');
    }

    // Logout function
    public function logout()
    {
        Auth::guard('branch')->logout();
        return redirect()->route('branch.login');
    }
}
