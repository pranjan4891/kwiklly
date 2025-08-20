<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckVendorApproval
{
    public function handle(Request $request, Closure $next)
    {
        $vendor = Auth::guard('vendor')->user();

        if ($vendor->status == 0) {
            return response()->view('vendorpanel.auth.pending-approval');
        }

        if ($vendor->status == 2) {
            return response()->view('vendorpanel.auth.rejected');
        }

        return $next($request);
    }
}
