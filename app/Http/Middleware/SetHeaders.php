<?php
namespace App\Http\Middleware;

use Closure;

class SetHeaders
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Set Cache-Control for static assets
        if ($request->is('css/*') || $request->is('js/*') || $request->is('fonts/*')) {
            $response->header('Cache-Control', 'public, max-age=31536000, immutable');
        }

        // Remove deprecated headers
        $response->headers->remove('X-XSS-Protection');
        $response->headers->remove('Expires');

        // Set Content-Security-Policy instead of X-Frame-Options
        $response->header('Content-Security-Policy', "frame-ancestors 'self'");

        return $response;
    }
}