<?php

namespace App\Providers;

use App\Services\CustomerService;
use Illuminate\Support\ServiceProvider;

class CustomerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('customer', function ($app) {
            return new CustomerService;
        });
    }

    public function provides () {
        return ['customer'];
    }
}
