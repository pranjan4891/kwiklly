<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {

            // Check by URL prefix
            if ($request->is('admin/*')) {
                return route('admin.login');
            }

            if ($request->is('vendor/*')) {
                return route('vendor.login');
            }

            if ($request->is('branch/*')) {
                return route('branch.login');
            }

            // Default fallback for regular users
            return route('login');
        }

        return null;
    }
}
