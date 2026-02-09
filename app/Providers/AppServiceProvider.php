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
         $this->app->bind(
            \App\Interfaces\AreaInterface::class, \App\Repositories\AreaRepository::class
        );

         $this->app->bind(
            \App\Interfaces\VideoInterface::class, \App\Repositories\VideoRepository::class
        );

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
