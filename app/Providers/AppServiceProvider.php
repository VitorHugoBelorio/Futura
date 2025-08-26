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
        if (env('PHP_MAX_EXECUTION_TIME')) {
            ini_set('max_execution_time', env('PHP_MAX_EXECUTION_TIME')); 
        }
    }
}
