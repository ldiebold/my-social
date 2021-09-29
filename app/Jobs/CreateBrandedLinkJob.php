<?php

namespace App\Jobs;

use App\Models\PodcastEpisode;
use App\Models\PublishPodcastOrchestrator;
use App\Services\Rebrandly\Rebrandly;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class CreateBrandedLinkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public PublishPodcastOrchestrator $orchestrator;
    public Rebrandly $rebrandly;
    public PodcastEpisode $podcastEpisode;

    public string $brandedLink;
    public Collection $rebrandlyResponseData;

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

        return $this;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Rebrandly $rebrandly)
    {
        $this->rebrandly = $rebrandly;

        $this->setup()
            ->createBrandedLink()
            ->upatePodcastModel()
            ->updateOrchestratorModel();
    }

    public function createBrandedLink(): self
    {
        $this->rebrandlyResponseData = $this->rebrandly->post('links', [
            'domain' => [
                "id" => env('REBRANDLY_DOMAIN_ID')
            ],
            'destination' => $this->podcastEpisode->audio_share_url,
            'slashtag' => $this->makeLinkSlashtag(),
            'title' => $this->getLinkTitle()
        ])->throw()->collect();

        return $this;
    }

    public function makeLinkSlashtag()
    {
        return env('REBRANDLY_PREFIX') . $this->podcastEpisode->episode_number;
    }

    public function getLinkTitle()
    {
        return env('SHOW_NAME') . ' Episode ' . $this->podcastEpisode->episode_number;
    }

    public function upatePodcastModel(): self
    {
        $this->podcastEpisode->update([
            'branded_audio_link_id' => $this->rebrandlyResponseData['id'],
            'branded_audio_link_url' => $this->rebrandlyResponseData['shortUrl']
        ]);

        return $this;
    }

    public function updateOrchestratorModel()
    {
        $this->orchestrator->update([
            'has_branded_link' => true
        ]);
    }
}
