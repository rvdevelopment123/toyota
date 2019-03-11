<?php

namespace App\Providers;

use App\Services\LicenseService;
use Illuminate\Support\ServiceProvider;

class LicenseServiceProvider extends ServiceProvider
{
    
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('licensor', function ($app) {
            return new LicenseService;
        });
    }

    public function provides () {
        return ['licensor'];
    }
}
