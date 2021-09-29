<?php

namespace App\Providers;

use App\Services\Transistor\Transistor;
use Illuminate\Support\ServiceProvider;

class TransistorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Transistor::class, function ($app) {
            return new Transistor();
        });
    }
}
