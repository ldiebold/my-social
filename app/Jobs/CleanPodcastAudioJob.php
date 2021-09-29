<?php

namespace App\Jobs;

use App\Models\PodcastEpisode;
use App\Models\PublishPodcastOrchestrator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class CleanPodcastAudioJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public PublishPodcastOrchestrator $orchestrator;
    public PodcastEpisode $podcastEpisode;

    public string $podcastEpisodeNumer;
    public string $podcastAudioPath;
    public string $podcastOutputPath;

    public string $script;
    public array $commandOutput;
    public int $commandResultCode;

    public string $hardLimiter;
    public string $secondsOfSilenceAtEnd;

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
        $this->script = base_path('scripts/cleanPodcast.sh');
        $this->commandOutput = [];
        $this->commandResultCode = 0;

        $this->podcastEpisode = $this->orchestrator->podcast_episode;
        $this->podcastEpisodeNumer = $this->podcastEpisode->episode_number;

        $this->hardLimiter = '-2';
        $this->secondsOfSilenceAtEnd = '4';

        $this->podcastAudioPath = storage_path("app/podcasts/$this->podcastEpisodeNumer/raw.wav");
        $this->podcastOutputPath = storage_path("app/podcasts/$this->podcastEpisodeNumer/clean.mp3");

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
            ->runCleanAudioScript()
            ->updateEpisodeCleanedAudioPath()
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
     * Using a shell, run the sox script to clean
     * the raw audio file
     *
     * @return self
     */
    public function runCleanAudioScript()
    {
        $command = "cd scripts && ";
        $command .= "$this->script $this->podcastAudioPath $this->podcastOutputPath ";
        $command .= "$this->hardLimiter $this->secondsOfSilenceAtEnd";

        $result = exec($command, $this->commandOutput, $this->commandResultCode);

        $this->logResult($result);

        return $this;
    }

    /**
     * Now that we have a cleaned version of the audio file,
     * we can update the clean_audio_file_path on
     * the episodes model
     *
     * @return self
     */
    public function updateEpisodeCleanedAudioPath()
    {
        $this->orchestrator->podcast_episode->update([
            'clean_audio_file_path' => "$this->podcastEpisodeNumer/clean.mp3"
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
            'audio_cleaned' => true
        ]);

        return $this;
    }
}
