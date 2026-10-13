<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ResolveActiveAuthGuard
{
    /**
     * Set the default auth driver dynamically based on who is authenticated.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('admin')->check()) {
            Auth::shouldUse('admin');
        } elseif (Auth::guard('web')->check()) {
            Auth::shouldUse('web');
        }

        return $next($request);
    }
}
