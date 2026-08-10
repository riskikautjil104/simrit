<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Gate: any admin role (superadmin or admin)
        Gate::define('access-admin', function (User $user) {
            return in_array($user->role, ['superadmin', 'admin']) && $user->is_active;
        });

        // Gate: superadmin only
        Gate::define('superadmin-only', function (User $user) {
            return $user->role === 'superadmin' && $user->is_active;
        });
    }
}
