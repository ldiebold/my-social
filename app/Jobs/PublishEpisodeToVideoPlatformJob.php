<?php

namespace App\Jobs;

use App\Models\PodcastEpisode;
use App\Models\PublishPodcastOrchestrator;
use App\Services\YouTube\YouTube;
use Illuminate\Bus\Queueable;
use Exception;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Storage;

class PublishEpisodeToVideoPlatformJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public PublishPodcastOrchestrator $orchestrator;
    public PodcastEpisode $podcastEpisode;
    public YouTube $youtube;

    public string $videoPath;
    public string $coverImagePath;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PublishPodcastOrchestrator $orchestrator)
    {
        if (!$orchestrator) {
            throw new Exception('Orhestrator is required');
        };
        $this->orchestrator = $orchestrator;

        return $this;
    }

    public function setup()
    {
        if (!$this->orchestrator->podcast_episode) {
            throw new Exception('The provided orchestrator is missing a "podcast_episode"');
        };
        $this->orchestrator->fresh();
        $this->podcastEpisode = $this->orchestrator->podcast_episode;

        $podcastDisk = Storage::disk('local-podcasts');

        $this->videoPath = $podcastDisk
            ->path($this->podcastEpisode->video_file_path);

        $this->coverImagePath = $podcastDisk
            ->path($this->podcastEpisode->cover_image_path);

        return $this;
    }

    public function handle(YouTube $youtube)
    {
        $this->youtube = $youtube;

        $this->setup()
            ->publish()
            ->addToPlaylist()
            ->setCoverImage()
            ->updateOrchestratorModel();
    }

    /**
     * Upload the video
     *
     * @return self
     */
    public function publish(): self
    {
        $video = $this->youtube->uploadVideo(
            $this->videoPath,
            $this->getVideoData(),
            $this->podcastEpisode->publish_date
        );

        $this->podcastEpisode->update([
            'video_id' => $video->getId()
        ]);

        return $this;
    }

    public function getVideoData(): array
    {
        return [
            'title' => $this->podcastEpisode->title,
            'description' => $this->podcastEpisode->description
        ];
    }

    public function addToPlaylist(): self
    {
        $this->youtube->addVideoToPlaylist(
            $this->podcastEpisode->video_id,
            env('YOUTUBE_PLAYLIST_ID')
        );

        return $this;
    }

    public function setCoverImage(): self
    {
        $response = $this->youtube->changeVideoCoverImage(
            $this->coverImagePath,
            $this->podcastEpisode->video_id
        );

        return $this;
    }

    public function updateOrchestratorModel(): self
    {
        $this->orchestrator->update([
            'published_on_video_platform' => true
        ]);

        return $this;
    }
}
