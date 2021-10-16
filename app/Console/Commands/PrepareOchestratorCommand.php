<?php

namespace App\Console\Commands;

use App\Models\ExternalPodcastFolder;
use App\Models\PodcastEpisode;
use App\Models\PublishPodcastOrchestrator;
use Illuminate\Console\Command;

class PrepareOchestratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:prepare-orchestrator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $podcastEpisode = PodcastEpisode::first();

        if (!$podcastEpisode) {
            $podcastEpisode = PodcastEpisode::create([
                'title' => 'Example Episode',
                'description' => 'Some description on the episode',
                'episode_number' => 9,
                'raw_audio_file_path' => '/podcasts/1/raw.wav',
                'local_folder_path' => '/podcasts/1',
                'social_post_text' =>
                'An example post, to see if my podcast publisher automation is working!',
            ]);
        }

        $externalPodcastFolderId = ExternalPodcastFolder::create([
            'path' => 'podcasts/1'
        ]);

        $orchestrator = PublishPodcastOrchestrator::first();
        if (!$orchestrator) {
            $orchestrator = PublishPodcastOrchestrator::create([
                'external_podcast_folder_id' => $externalPodcastFolderId->id,
                'podcast_episode_id' => $podcastEpisode->id,
                'raw_audio_file_is_stored' => true,
                'audio_cleaned' => true,
                'published_on_podcast_platform' => true,
                'has_branded_link' => true,
                'has_cover_image' => true,
                'has_podcast_video' => true,
                'published_on_video_platform' => true,
            ]);
        }
        return 0;
    }
}
