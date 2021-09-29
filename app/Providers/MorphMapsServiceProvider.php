<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class MorphMapsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::enforceMorphMap([
            'users' => 'App\Models\User',
            'podcast_episode_campaign_templates' => 'App\Models\PodcastEpisodeCampaignTemplate',
        ]);
    }
}
