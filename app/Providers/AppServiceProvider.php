<?php

namespace App\Providers;

use App\Duck;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {   
        // Add observer to the Duck Model
        Duck::observe(DuckObserver::class);
    }
}
