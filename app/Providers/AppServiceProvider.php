<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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

        Gate::define('view-reports', fn (User $user) => in_array($user->role?->name, ['Owner', 'Admin']));
        Gate::define('manage-users', fn (User $user) => in_array($user->role?->name, ['Owner', 'Admin']));
        Gate::define('manage-stock', fn (User $user) => in_array($user->role?->name, ['Owner', 'Admin', 'Gudang']));
        Gate::define('manage-promotions', fn (User $user) => in_array($user->role?->name, ['Owner', 'Admin']));
    }
}
