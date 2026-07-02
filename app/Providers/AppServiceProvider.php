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
        // Memastikan asset publik dibaca dengan benar di dalam environment Vercel
        if (env('VERCEL_JOB') || isset($_ENV['VERCEL'])) {
            $this->app->bind('path.public', function () {
                return base_path('public');
            });
        }
    }
}
