<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureDinasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || !($user->isDinas() || $user->isAdmin())) {
            abort(403, 'Akses ditolak. Halaman ini hanya dapat diakses oleh Instansi / Dinas atau Administrator.');
        }

        return $next($request);
    }
}
