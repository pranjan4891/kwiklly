<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Banner;
use App\Models\Category;


class DashboardController extends Controller
{
    //
    public function index()
    {
        $branch= Auth::guard('branch')->user();
        if (!$branch) {
            return redirect()->route('branch.login')->with('error', 'You are not authorized to access this page.');
        }
        $title = 'Admin | Dashboard';
        return view('branch.dashboard',compact('branch','title'));
    }



}
