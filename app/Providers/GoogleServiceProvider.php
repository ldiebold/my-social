<?php

namespace App\Providers;

use Google\Client;
use Illuminate\Support\ServiceProvider;

class GoogleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $credentialsPath = storage_path(env('GOOGLE_CREDENTIALS_PATH'));
        $client = new Client();
        $client->setAuthConfig($credentialsPath);
        $client->setAccessType('offline');
        $client->setApprovalPrompt("consent");
        $client->setIncludeGrantedScopes(true);

        $redirect_uri = env('APP_URL') . '/youtube/callback';

        $client->setRedirectUri($redirect_uri);

        $this->app->singleton(Client::class, function ($app) use ($client) {
            return $client;
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
