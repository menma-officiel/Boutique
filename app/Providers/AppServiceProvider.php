<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Register the admin middleware alias to ensure it's available in tests and runtime
        if (class_exists(\Illuminate\Support\Facades\Route::class)) {
            \Illuminate\Support\Facades\Route::aliasMiddleware('admin', \App\Http\Middleware\EnsureUserIsAdmin::class);
        }
    }
}
