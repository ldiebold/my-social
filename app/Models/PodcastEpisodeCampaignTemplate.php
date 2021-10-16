<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PodcastEpisodeCampaignTemplate extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function publish_post_event_templates()
    {
        return $this->morphMany(
            PublishPostEventTemplate::class,
            'publish_post_event_templateable'
        );
    }

    public function scheduled_social_posts()
    {
        return $this->hasMany(ScheduledSocialPost::class);
    }

    public function createPostsFor(PodcastEpisode $podcastEpisode)
    {
        return $this->publish_post_event_templates->map(
            function (
                PublishPostEventTemplate $eventTemplate
            ) use ($podcastEpisode) {
                $eventTemplate->scheduleAllPostsForPodcastEpisode($podcastEpisode);
            }
        );
    }
}
