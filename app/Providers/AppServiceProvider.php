<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Optimize cache configuration
        $this->optimizeCacheConfiguration();
        
        // Add response macros for caching
        $this->addResponseCachingMacros();
    }
    
    /**
     * Optimize cache configuration for better performance
     */
    private function optimizeCacheConfiguration(): void
    {
        // Set default cache TTL based on environment
        if (app()->environment('production')) {
            config(['cache.default_ttl' => 3600]); // 1 hour in production
        } else {
            config(['cache.default_ttl' => 300]); // 5 minutes in development
        }
    }
    
    /**
     * Add response caching macros
     */
    private function addResponseCachingMacros(): void
    {
        Response::macro('cached', function ($content, $ttl = 3600) {
            return response($content)
                ->header('Cache-Control', 'public, max-age=' . $ttl)
                ->header('Expires', gmdate('D, d M Y H:i:s', time() + $ttl) . ' GMT');
        });
    }
}
