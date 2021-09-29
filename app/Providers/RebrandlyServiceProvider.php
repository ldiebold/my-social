<?php

namespace App\Providers;

use App\Services\Rebrandly\Rebrandly;
use Illuminate\Support\ServiceProvider;

class RebrandlyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Rebrandly::class, function ($app) {
            return new Rebrandly();
        });
    }
}
