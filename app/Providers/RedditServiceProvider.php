<?php

namespace App\Providers;

use App\Services\Reddit\Reddit;
use Illuminate\Support\ServiceProvider;

class RedditServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Reddit::class, function ($app) {
            return new Reddit([
                'client_id' => config('reddit.providers.default.client_id'),
                'client_secret' => config('reddit.providers.default.client_secret'),
                'redirect_uri' => config('reddit.providers.default.redirect_uri')
            ]);
        });
    }
}
