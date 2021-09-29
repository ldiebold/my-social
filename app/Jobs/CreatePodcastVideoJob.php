<?php

namespace App\Jobs;

use App\Models\PodcastEpisode;
use App\Models\PublishPodcastOrchestrator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class CreatePodcastVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public PublishPodcastOrchestrator $orchestrator;
    public PodcastEpisode $podcastEpisode;

    public string $scriptFile;
    public array $commandOutput;
    public int $commandResultCode;

    public string $podcastEpisodeNumer;

    public string $audioPath;
    public string $imagePath;
    public string $outputPath;

    /**
     * Create a new job instance.
     *
     * @return self
     */
    public function __construct(PublishPodcastOrchestrator $orchestrator)
    {
        $this->orchestrator = $orchestrator;
    }

    public function setup()
    {
        $this->scriptFile = base_path('scripts/audio-visualizers/ffmpeg1.sh');
        $this->commandOutput = [];
        $this->commandResultCode = 0;

        $this->podcastEpisode = $this->orchestrator->podcast_episode;
        $this->podcastEpisodeNumer = $this->podcastEpisode->episode_number;

        $this->audioPath = storage_path("app/podcasts/$this->podcastEpisodeNumer/clean.mp3");
        $this->imagePath = storage_path("app/podcasts/$this->podcastEpisodeNumer/cover-image.png");
        $this->outputPath = storage_path("app/podcasts/$this->podcastEpisodeNumer/video.mkv");

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
            ->runCreateVideoScript()
            ->updatePodcastEpisode()
            ->updateOrchestrator();
    }

    /**
     * Log Results, indicating if the script failed
     *
     * @param string|false $result
     * @return void
     */
    public function logResult(string|false $result)
    {
        if ($result !== null) {
            Log::info('Audio cleaned: ');
            Log::info(collect($this->commandOutput)->join("\n"));
        } else {
            Log::error('Clean audio script failed: ');
            Log::info(collect($this->commandOutput)->join("\n"));
        }
    }

    /**
     * Using a shell, run the ffmpeg script to
     * create the podcast video using our
     * cover image and cleaned audio
     *
     * @return self
     */
    public function runCreateVideoScript()
    {
        $command = "cd scripts && ";
        $command .= "$this->scriptFile $this->audioPath $this->imagePath $this->outputPath";

        $result = exec($command, $this->commandOutput, $this->commandResultCode);

        $this->logResult($result);

        return $this;
    }

    /**
     * Now that our podcast video has been created,
     * we can update the has_podcast_video on
     * the episodes model
     *
     * @return self
     */
    public function updatePodcastEpisode()
    {
        $this->orchestrator->podcast_episode->update([
            'video_file_path' => "$this->podcastEpisodeNumer/video.mkv"
        ]);

        return $this;
    }

    /**
     * When the audio has been successfully cleaned, we let
     * the orchestrator know
     *
     * @return self
     */
    public function updateOrchestrator()
    {
        $this->orchestrator->update([
            'has_podcast_video' => true
        ]);

        return $this;
    }
}
