<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\VendorAdmin;
use App\Models\State;
use App\Models\City;
use App\Models\DeliveryLocation;
use App\Models\MasterLocation;
use Illuminate\Support\Str;

class AdminController extends Controller
{
     // Show login form
     public function index()
     {
        $title = 'Admin | Login';
         return view('admin.login',compact('title'));
     }

     // Handle login request
    public function loginCheck(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Check if user exists and is an admin
        $user = \App\Models\VendorAdmin::where('email', $request->email)->first();

        if (!$user || $user->user_type !== 'admin') {
            return back()->withErrors(['email' => 'Access denied. Only adminstrators can login.'])->withInput();
        }

        // Now attempt login only if user_type is admin
        if (Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password,
            'user_type' => 'admin'
        ])) {
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }



     public function profile()
     {
         $admin = Auth::guard('admin')->user();
         if (!$admin) {
             return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
         }
         $title = 'Admin | Profile';
         return view('admin.profile', compact('admin','title'));
     }


    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        // Debugging: Check if $admin is null
        if (!$admin) {
            return redirect()->route('admin.login')->withErrors(['error' => 'Unauthorized access.']);
        }

        // Validate inputs
        $request->validate([
            'business_name' => 'nullable|string|max:255',
            'business_category' => 'nullable|string|max:255',
            'business_description' => 'nullable|string',
            'business_address' => 'nullable|string',
            'state' => 'nullable|string',
            'city' => 'nullable|string',
            'postal_code' => 'nullable|string|max:10',
            'area' => 'nullable|string',
            'bank_name' => 'nullable|string|max:255',
            'bank_branch' => 'nullable|string|max:255',
            'account_holder_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:20',
            'ifsc_code' => 'nullable|string|max:20',
            'business_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Ensure admin is an instance of VendorAdmin
        $admin = VendorAdmin::find($admin->id);

        if (!$admin) {
            return redirect()->route('admin.profile')->withErrors(['error' => 'Admin not found.']);
        }

        // Update profile details
        $admin->fill($request->except('business_logo'));

        // Handle image upload
        if ($request->hasFile('business_logo')) {
            $imagePath = $request->file('business_logo')->store('uploads/business_logos', 'public');
            $admin->business_logo = $imagePath;
        }

        $admin->save(); // Save the changes

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully.');
    }

    public function showChangePasswordForm()
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $title = 'Admin | Change Password';
        return view('admin.change_password', compact('admin','title'));
    }

    public function updatePassword(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }

        // Validate inputs
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $admin =  VendorAdmin::find($admin->id);

        // Check if old password is correct
        if (!Hash::check($request->old_password, $admin->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        // Update password
        $admin->password = Hash::make($request->new_password);
        $admin->save();

        return back()->with('success', 'Password updated successfully.');
    }



    // Show add branch form
    public function createBranch()
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }

        $title = 'Admin | Add Branch';

        // Fetch all states from `states` table
        $states = State::where('is_active', 1)->get();

        return view('admin.branch.create', compact('title', 'admin', 'states'));
    }

    // Handle branch creation
    public function storeBranch(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }

        $request->validate([
            'branch_name'            => 'required|string|max:255',
            'phone'                  => 'required|string|max:15',
            'email'                  => 'nullable|email|unique:vendor_admins,email',
            'state'                  => 'required|exists:states,id',
            'city'                   => 'required|exists:cities,id',
            'postal_code'            => 'required|string|max:6',
            'place'                  => 'nullable|string',
            'latitude'               => 'nullable|numeric',
            'longitude'              => 'nullable|numeric',
            'branch_address'         => 'required|string',
            'landmark'               => 'nullable|string',
            'service_area'           => 'required|json',
        ]);

        // Upload files if available
        $panImage = null;
        $cancelChequeImage = null;

        if ($request->hasFile('pan_image')) {
            $panImage = $request->file('pan_image')->store('uploads/pan_images', 'public');
        }

        if ($request->hasFile('cancel_cheque_image')) {
            $cancelChequeImage = $request->file('cancel_cheque_image')->store('uploads/cancel_cheques', 'public');
        }

        $branch = VendorAdmin::create([
            'name'                  => $request->branch_name,
            'business_name'         => $request->branch_name,
            'uuid'                  => Str::uuid(),
            'phone'                 => $request->phone,
            'email'                 => $request->email,
            'password'              => Hash::make('123456'),
            'gstin'                 => $request->gstin,
            'pan_number'            => $request->pan_number,
            'pan_image'             => $panImage,
            'bank_name'             => $request->bank_name,
            'bank_branch'           => $request->bank_branch,
            'account_holder_name'   => $request->account_holder_name,
            'account_number'        => $request->account_number,
            'ifsc_code'             => $request->ifsc_code,
            'cancel_cheque_image'   => $cancelChequeImage,
            'state_id'              => $request->state,
            'city_id'               => $request->city,
            'postal_code'           => $request->postal_code,
            'area'                  => $request->place_name,
            'latitude'              => $request->latitude,
            'longitude'             => $request->longitude,
            'business_address'      => $request->branch_address,
            'landmark'              => $request->landmark,
            'user_type'             => 'branch',
            'parent_id'             => $admin->id,
            'status'                => 1,
            'is_active'             => 1,
        ]);
         $latLongArray = json_decode($request->service_area, true);
        // Ensure the service area is an array and has at least 3 points to form a polygon
        if (is_array($latLongArray) && count($latLongArray) > 2) {
            $first = $latLongArray[0];
            $last = end($latLongArray);

            // Auto-close the polygon if not already closed
            if ($first['lat'] != $last['lat'] || $first['lng'] != $last['lng']) {
                $latLongArray[] = $first;
            }
        }
        if ($branch) {
            // Create delivery location for the branch
            DeliveryLocation::create([
                'vendor_id' => $branch->id,
                'delivery_lat_long' => json_encode($latLongArray),
                'is_active' => 1,
                'is_deleted' => 0
            ]);
        } else {
            return redirect()->back()->with('error', 'Failed to create branch. Please try again.');
        }
        return redirect()->route('admin.branches')->with('success', 'Branch created successfully.');
    }

    // show branches
    public function showBranches()
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $title = 'Admin | Branches';
        // Fetch all branches where parent_id is the admin's id
        $branches = VendorAdmin::where('user_type', 'branch')
            ->where('parent_id', $admin->id)
            ->where('is_deleted', 0)->with(['state', 'city'])
            ->get();

        return view('admin.branch.index', compact('title', 'admin', 'branches'));
    }

    public function getArea(Request $request)
    {
        $pincode = $request->input('pincode');

        $locations = MasterLocation::where('pincode', $pincode)
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->get(['place', 'lat_long']);

        return response()->json([
            'places' => $locations
        ]);
    }

    // Function to deleted branches
    public function deleteBranch(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $branch = VendorAdmin::find($id);
        if ($branch) {
            $branch->is_deleted = 1;
            $branch->is_active = 0; // Optionally set status to inactive
            $branch->save();
            return redirect()->back()->with('success', 'Branch deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Branch not found.');
        }
    }
    // Show deleted branches
    public function deletedBranches()
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $title = 'Admin | Deleted Branches';
        // Fetch all deleted branches where parent_id is the admin's id
        $branches = VendorAdmin::where('user_type', 'branch')
            ->where('parent_id', $admin->id)
            ->where('is_deleted', 1)
            ->with(['state', 'city'])
            ->get();
        return view('admin.branch.delete', compact('title', 'admin', 'branches'));
    }
    // Restore deleted branch
    public function restoreBranch(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $branch = VendorAdmin::find($id);
        if ($branch) {
            $branch->is_deleted = 0;
            $branch->is_active = 1; // Optionally set status to active
            $branch->save();
            return redirect()->back()->with('success', 'Branch restored successfully.');
        } else {
            return redirect()->back()->with('error', 'Branch not found.');
        }
    }

    //destroyBranch
    public function destroyBranch(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $branch = VendorAdmin::find($id);
        if ($branch) {
            // Delete the branch and its associated delivery location
            $branch->delete();
            DeliveryLocation::where('vendor_id', $id)->delete();
            return redirect()->back()->with('success', 'Branch deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Branch not found.');
        }
    }
     // Logout function
     public function logout()
     {
         Auth::guard('admin')->logout();
         return redirect()->route('admin.login');
     }
}
