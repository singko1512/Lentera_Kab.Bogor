<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // HTTP Strict Transport Security (HSTS)
        // Memberi tahu browser agar selalu mengakses situs dengan HTTPS selama 1 tahun (31536000 detik)
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        // Header keamanan tambahan standar OWASP & Acunetix
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        // X-XSS-Protection diset ke '0' sesuai rekomendasi OWASP & peramban modern (Chrome, Firefox)
        // karena legacy auditor '1; mode=block' sudah deprecated dan dapat memicu celah di browser lama.
        // Mitigasi XSS modern sepenuhnya dikelola oleh Content-Security-Policy (CSP) di bawah.
        $response->headers->set('X-XSS-Protection', '0');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Content Security Policy (CSP) untuk mitigasi XSS & data injection
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://challenges.cloudflare.com; " .
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
               "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net data:; " .
               "img-src 'self' data: blob: https:; " .
               "connect-src 'self' https://nominatim.openstreetmap.org https://*.openstreetmap.org https://challenges.cloudflare.com; " .
               "frame-src 'self' https://www.google.com https://challenges.cloudflare.com; " .
               "frame-ancestors 'self'; " .
               "object-src 'none'; " .
               "base-uri 'self';";
        $response->headers->set('Content-Security-Policy', $csp);

        // Permissions-Policy untuk mengontrol akses fitur sensitif browser
        // Kamera dan Geolocation diizinkan untuk origin sendiri (self) untuk kebutuhan absensi selfie & lokasi GPS
        // Fitur yang tidak dipakai (microphone, payment, usb, sensor) dimatikan total ()
        $permissionsPolicy = "camera=(self), geolocation=(self), microphone=(), payment=(), usb=(), magnetometer=(), accelerometer=(), gyroscope=()";
        $response->headers->set('Permissions-Policy', $permissionsPolicy);

        return $response;
    }
}
