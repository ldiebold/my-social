<?php

namespace App\Providers;

use App\Services\LinkedIn\LinkedIn;
use Illuminate\Support\ServiceProvider;

class LinkedInServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LinkedIn::class, function ($app) {
            return new LinkedIn([
                'client_id' => config('linkedin.providers.default.client_id'),
                'client_secret' => config('linkedin.providers.default.client_secret'),
                'redirect_uri' => config('linkedin.providers.default.redirect_uri'),
                'user_id' => config('linkedin.providers.default.user_id')
            ]);
        });
    }
}
