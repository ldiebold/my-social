<?php

namespace App\Providers;

use App\Services\Facebook\FacebookPage;
use Illuminate\Support\ServiceProvider;

class FacebookPageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FacebookPage::class, function ($app) {
            return new FacebookPage([
                'clientId'          => env('FACEBOOK_CLIENT_ID'),
                'clientSecret'      => env('FACEBOOK_CLIENT_SECRET'),
                'redirectUri'       => env('FACEBOOK_REDIRECT_URI'),
                'graphApiVersion'   => 'v12.0',
            ]);
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
