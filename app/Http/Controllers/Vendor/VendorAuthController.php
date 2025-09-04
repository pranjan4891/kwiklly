<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorAdmin;



class VendorAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $check = VendorAdmin::where('email', $credentials['email'])->where('user_type','admin')->first();
        if ($check) {
            return redirect()->route('admin.login');
        }

        if (Auth::guard('vendor')->attempt($credentials)) {
            $request->session()->regenerate();

             // Update store_time_status to 1 after login
            /** @var \App\Models\VendorAdmin $vendor */

            $vendor = Auth::guard('vendor')->user();
            $vendor->store_time_status = 1;
            $vendor->save();

            return redirect()->intended('/vendor/dashboard'); // Change this to your dashboard route
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    public function logout(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        /** @var \App\Models\VendorAdmin $vendor */
        if ($vendor) {
            $vendor->store_time_status = 0;
            $vendor->save();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Auth::guard('vendor')->logout();

        return redirect(route('vendor.login')); // Redirect to your vendor login route
    }


}
