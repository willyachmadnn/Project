<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $maxAge = '3600'): Response
    {
        $response = $next($request);

        // Only apply caching headers to successful responses
        if ($response->getStatusCode() === 200) {
            // Set cache control headers
            $response->headers->set('Cache-Control', 'public, max-age=' . $maxAge);
            
            // Set ETag for better cache validation
            $etag = md5($response->getContent());
            $response->headers->set('ETag', '"' . $etag . '"');
            
            // Set Last-Modified header
            $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT');
            
            // Check if client has cached version
            if ($request->header('If-None-Match') === '"' . $etag . '"') {
                return response('', 304);
            }
        }

        return $response;
    }
}