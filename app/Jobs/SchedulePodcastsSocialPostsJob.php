<?php

namespace App\Jobs;

use App\Models\PodcastEpisode;
use App\Models\PodcastEpisodeCampaignTemplate;
use App\Models\PublishPodcastOrchestrator;
use App\Models\PublishPostEventTemplate;
use App\Models\SocialPost;
use App\Models\SocialPostTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SchedulePodcastsSocialPostsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public PublishPodcastOrchestrator $orchestrator;
    public PodcastEpisodeCampaignTemplate $campaignTemplate;
    public PodcastEpisode $podcastEpisode;
    public SocialPostTemplate $socialPostTemplate;

    public int $podcastEpisodeNumber;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PublishPodcastOrchestrator $orchestrator)
    {
        $this->orchestrator = $orchestrator;
    }

    public function setup()
    {
        $this->podcastEpisode = $this->orchestrator->podcast_episode;
        $this->podcastEpisodeNumber = $this->podcastEpisode->episode_number;
        $this->campaignTemplate = PodcastEpisodeCampaignTemplate::first();
        $this->publishEvents = $this->campaignTemplate->publish_post_event_templates;

        $this->imagePath = storage_path("app/podcasts/$this->podcastEpisodeNumber/cover-image.png");

        return $this;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->setup()
            ->schedulePosts()
            ->updateOrchestrator();
    }

    public function schedulePosts()
    {
        $this->publishEvents->each(function (PublishPostEventTemplate $publishEvent) {
            $publishEvent->schedulePostFor(
                [
                    'twitter',
                    'youtube-community',
                    'reddit',
                    'facebook-group',
                    'facebook-page',
                    'linkedin-group',
                    'linkedin-page'
                ],
                $this->podcastEpisode->publish_date,
                $this->podcastEpisode->social_post_text,
                $this->podcastEpisode->branded_audio_link_url,
                $this->podcastEpisode->cover_image_path
            );
        });

        return $this;
    }

    public function updateOrchestrator()
    {
        $this->orchestrator->update([
            'social_posts_scheduled' => true
        ]);

        return $this;
    }
}
