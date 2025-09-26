<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StoreIntendedUrl
{
    public function handle(Request $request, Closure $next)
    {
        // Only save if not login/signup and only for GET requests
        if (
            $request->isMethod('GET') &&
            ! $request->is('login') &&
            ! $request->is('loginphone') &&
            ! $request->is('signup') &&
            ! $request->ajax()
        ) {
            session(['url.intended' => $request->fullUrl()]);
        }

        return $next($request);
    }
}
