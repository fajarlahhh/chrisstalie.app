<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
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
        //
        Carbon::setLocale('id_ID');
        Gate::before(function ($pengguna, $ability) {
            return $pengguna->hasRole('administrator') || $pengguna->getKey() == 'administrator' ? true : null;
        });
    }
}
