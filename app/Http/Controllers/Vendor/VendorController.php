<?php

namespace App\Http\Controllers\Vendor;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


use App\Models\VendorAdmin;

class VendorController extends Controller
{
    public function vendorSignIn()
    {
        return view('web.vendor.vendorLogin');
    }

    public function vendorRegistration()
    {
        return view('web.vendor.vendorRegistration');
    }


    public function submit(Request $request)
    {
        try {
            // ✅ Validate input
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:vendor_admins,email',
                'phone' => 'required|digits_between:7,15|unique:vendor_admins,phone',
                'password' => 'required|min:6|confirmed', // added confirmed for confirmation check
                'company_name' => 'required|string|max:255',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'postal_code' => 'nullable|string|max:20',
                'place_name' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $uuid = (string) Str::uuid();
            $vendor = new VendorAdmin;

            // Upload cancelled cheque
            if ($request->hasFile('cancelled_cheque')) {
                $cheque = $request->file('cancelled_cheque');
                $chequeName = time() . '_' . $cheque->getClientOriginalName();
                $destinationPath = public_path('uploads/cheques');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $cheque->move($destinationPath, $chequeName);
                $vendor->cancel_cheque_image = 'uploads/cheques/' . $chequeName;
            }

            // Fill vendor data
            $vendor->user_type = 'vendor';
            $vendor->uuid = $uuid;
            $vendor->landmark = $request->landmark;
            $vendor->email = $request->email;
            $vendor->phone = $request->phone;
            $vendor->password = Hash::make($request->password);
            $vendor->business_name = $request->company_name;
            $vendor->gstin = $request->gst_number;
            $vendor->business_address = $request->company_address;
            $vendor->account_holder_name = $request->account_holder;
            $vendor->account_number = $request->account_number;
            $vendor->ifsc_code = $request->ifsc_code;
            $vendor->latitude = $request->latitude;
            $vendor->longitude = $request->longitude;
            $vendor->postal_code = $request->postal_code;
            $vendor->area = $request->place_name;
            $vendor->save();

            // Login (only if guard exists)
            if (Auth::guard('vendor')) {
                Auth::guard('vendor')->login($vendor);
            }

            // Send verification mail only if model supports it
            if (in_array(\Illuminate\Contracts\Auth\MustVerifyEmail::class, class_implements($vendor))) {
                $vendor->sendEmailVerificationNotification();
            }

            return response()->json([
                'success' => true,
                'message' => 'Registration successful! Please verify your email address.',
                'redirect' => route('vendor.dashboard'),
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function updateImage(Request $request)
    {
        $vendor = auth()->user(); // Or fetch from DB using vendor ID

        if ($request->hasFile('business_logo')) {
            $file = $request->file('business_logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/vendorsBannerLogo/'), $filename);
            $vendor->business_logo = 'uploads/vendorsBannerLogo/'.$filename;
            /** @var \App\Models\VendorAdmin $vendor */
            $vendor->save();
            return response()->json(['status' => true, 'path' => asset('public/uploads/vendorsBannerLogo/' . $filename)]);
        }

        if ($request->hasFile('business_banner')) {
            $file = $request->file('business_banner');
            $filename = 'banner_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/vendorsBannerLogo/'), $filename);
            $vendor->business_banner = 'uploads/vendorsBannerLogo/'.$filename;
            /** @var \App\Models\VendorAdmin $vendor */
            $vendor->save();
            return response()->json(['status' => true, 'path' => asset('public/uploads/vendorsBannerLogo/' . $filename)]);
        }

        return response()->json(['status' => false]);
    }



}
