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

    public string $hardLimiter;
    public string $secondsOfSilenceAtEnd;

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
        $this->script = base_path('scripts/cleanPodcast.sh');

        $this->podcastEpisode = $this->orchestrator->podcast_episode;
        $this->podcastEpisodeNumer = $this->podcastEpisode->episode_number;
        $this->localProjectPath = $this->podcastEpisode->raw_audio_file_path;

        $this->hardLimiter = '-2';
        $this->secondsOfSilenceAtEnd = '4';

        $this->podcastAudioPath = storage_path("app/podcasts/$this->podcastEpisodeNumer/raw.wav");
        $this->podcastOutputPath = storage_path("app/podcasts/$this->podcastEpisodeNumer/clean.wav");
    }

    /**
     * Using a shell, run the sox script to clean
     * the raw audio file
     *
     * @return string|false|null
     */
    public function runCleanAudioScript()
    {
        $command = "cd scripts && ";
        $command += "$this->script $this->podcastAudioPath $this->podcastOutputPath ";
        $command += "$this->hardLimiter $this->secondsOfSilenceAtEnd";

        return shell_exec($command);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->setup();

        $result = $this->runCleanAudioScript();
    }
}
