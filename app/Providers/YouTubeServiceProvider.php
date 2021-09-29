<?php

namespace App\Providers;

use App\Services\YouTube\YouTube;
use Google\Client;
use Illuminate\Support\ServiceProvider;

class YouTubeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(YouTube::class, function ($app) {
            return new YouTube($app->make(Client::class));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
