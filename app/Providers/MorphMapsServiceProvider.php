<?php

namespace App\Providers;

use App\Models\PodcastEpisodeCampaignTemplate;
use App\Models\PublishEvent;
use App\Models\PublishPostEventTemplate;
use App\Models\SocialPost;
use App\Models\SocialPostTemplate;
use App\Models\SocialPublisher;
use App\Models\User;
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
            'users' => User::class,
            'podcast_episode_campaign_templates' => PodcastEpisodeCampaignTemplate::class,
            'publish_post_event_template' => PublishPostEventTemplate::class,
            'social_post_templates' => SocialPostTemplate::class,
            'social_publishers' => SocialPublisher::class,
            'publish_evnets' => PublishEvent::class,
            'social_posts' => SocialPost::class,
        ]);
    }
}
