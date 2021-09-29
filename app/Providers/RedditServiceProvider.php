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
                'client_id' => env('REDDIT_CLIENT_ID'),
                'client_secret' => env('REDDIT_CLIENT_SECRET'),
                'redirect_uri' => env('APP_URL') . '/reddit/callback'
            ]);
        });
    }
}
