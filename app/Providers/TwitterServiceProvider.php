<?php

namespace App\Providers;

use League\OAuth1\Client\Server\Twitter;
use Illuminate\Support\ServiceProvider;

class TwitterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Twitter::class, function ($app) {
            return new Twitter([
                'identifier' => config('twitter.providers.default.consumer_key'),
                'secret' => config('twitter.providers.default.consumer_secret'),
                'callback_url' => config('twitter.providers.default.callback_url'),
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
