<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin', function($user) {
            return $user->role === 'admin';
        });

        Gate::define('resepsionis', function($user) {
            return $user->role === 'resepsionis';
        });

        Gate::define('tamu', function($user) {
            return $user->role === 'tamu';
        });
    }
}
