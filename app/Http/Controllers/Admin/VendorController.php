<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorAdmin;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;
use App\Mail\VendorApprovedMail;
use App\Mail\VendorRejectedMail;

class VendorController extends Controller
{
    //
    public function approve()
    {
        $title = 'Admin | Approve Vendor';
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $vendors = VendorAdmin::where('status', 1)->where('parent_id',0)->with(['state', 'city'])->latest()->get();
        return view('admin.vendor.approve', compact('title', 'admin', 'vendors'));
    }

    public function pending()
    {
        $title = 'Admin | Pending Vendor';
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $vendors = VendorAdmin::where('status', 0)->where('parent_id',0)->with(['state', 'city'])->latest()->get();
        return view('admin.vendor.pending', compact('title', 'admin', 'vendors'));
    }
    public function rejected()
    {
        $title = 'Admin | Rejected Vendor';
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $vendors = VendorAdmin::where('status', 2)->where('parent_id',0)->with(['state', 'city'])->latest()->get();
        return view('admin.vendor.rejected', compact('title', 'admin', 'vendors'));
    }
   // Show vendor details by UUID
    public function show($uuid)
    {
        $title = 'Admin | Vendor Details';
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $vendor = VendorAdmin::where('uuid', $uuid)->firstOrFail();

        return view('admin.vendor.show', compact('title', 'admin', 'vendor'));
    }

    // Update status and comment


    public function updateStatus(Request $request, $uuid)
    {
        $request->validate([
            'status' => 'required|in:0,1,2',
            'comment' => 'nullable|string|max:1000',
        ]);

        $vendor = VendorAdmin::where('uuid', $uuid)->firstOrFail();
        $vendor->status = $request->status;

        $vendor->admin_comments = $request->comment;

        $vendor->save();

        // Send email notification
        if ($request->status == 1) {
            Mail::to($vendor->email)->send(new VendorApprovedMail($vendor));
            return redirect()->route('admin.vendor.approve')->with('success', 'Vendor approved successfully.');
        } elseif ($request->status == 0) {
            return redirect()->route('admin.vendor.pending')->with('success', 'Vendor status updated to pending.');
        } else {
            Mail::to($vendor->email)->send(new VendorRejectedMail($vendor));
            return redirect()->route('admin.vendor.rejected')->with('success', 'Vendor rejected successfully.');
        }
    }

    // Delete vendor
    public function destroy($uuid)
    {
        $vendor = VendorAdmin::where('uuid', $uuid)->firstOrFail();
        $vendor->delete();

        return redirect()->back()->with('success', 'Vendor deleted successfully.');
    }

}
