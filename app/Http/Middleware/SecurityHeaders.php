<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Security Headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        
        // Content Security Policy untuk API
        $csp = "default-src 'none'; " .
               "script-src 'none'; " .
               "style-src 'none'; " .
               "img-src 'none'; " .
               "connect-src 'self'; " .
               "font-src 'none'; " .
               "object-src 'none'; " .
               "media-src 'none'; " .
               "frame-src 'none'; " .
               "frame-ancestors 'none'";
        
        $response->headers->set('Content-Security-Policy', $csp);
        
        // HSTS (hanya jika HTTPS)
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }
        
        // Remove server information that might leak
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');
        
        return $response;
    }
}