<?php

namespace App\Jobs;

use App\Models\PodcastEpisode;
use App\Models\PublishPodcastOrchestrator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Spatie\Browsershot\Browsershot;

class GenerateCoverImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public PublishPodcastOrchestrator $orchestrator;
    public PodcastEpisode $podcastEpisode;

    public string $podcastEpisodeNumer;
    public string $coverImagePath;

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
        $this->podcastEpisodeNumer = $this->podcastEpisode->episode_number;

        $this->coverImagePath = "$this->podcastEpisodeNumer/cover-image.png";

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
            ->generateAndStoreCoverImage()
            ->updatePodcastModel()
            ->updateOrchestrator();
    }

    /**
     * This is where the magic happens!
     * We generate and store the cover image
     *
     * @return self
     */
    public function generateAndStoreCoverImage(): self
    {
        ray($this->getCoverImageUrlWithQueryString());
        Browsershot::url($this->getCoverImageUrlWithQueryString())
            ->noSandbox()
            ->windowSize(1920, 1080)
            ->save(storage_path("app/podcasts/$this->coverImagePath"));

        return $this;
    }

    /**
     * Details for generating the cover image are sent via a query string
     *
     * @return string
     */
    public function getCoverImageQueryString(): string
    {
        return Arr::query([
            'description' => $this->podcastEpisode->description,
            'episode' => $this->podcastEpisodeNumer
        ]);
    }

    /**
     * Update the podcast model when the cover image has been created
     *
     * @return self
     */
    public function updatePodcastModel(): self
    {
        $this->podcastEpisode->update([
            'cover_image_path' => $this->coverImagePath
        ]);

        return $this;
    }

    /**
     * When the cover image has been successfully generated
     * and created, we let the orchestrator know
     *
     * @return self
     */
    public function updateOrchestrator(): self
    {
        $this->orchestrator->update([
            'has_cover_image' => true
        ]);

        return $this;
    }

    /**
     * Get the url for creating this cover image
     *
     * @return string
     */
    public function getCoverImageUrl(): string
    {
        return env('APP_URL') . '/templates/cover-image';
    }

    /**
     * Here we join the cover image Url and query string
     * to create a convenient function
     *
     * @return string
     */
    public function getCoverImageUrlWithQueryString(): string
    {
        return $this->getCoverImageUrl() . '?' . $this->getCoverImageQueryString();
    }
}
