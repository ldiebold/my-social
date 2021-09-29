<?php

namespace App\Console\Commands;

use App\Models\PublishPodcastOrchestrator;
use App\Services\Rebrandly\Rebrandly;
use Artisan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ResetDevStateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform commands that reset the dev start';

    protected Rebrandly $rebrandly;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Rebrandly $rebrandly)
    {
        $this->rebrandly = $rebrandly;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orchestrator = PublishPodcastOrchestrator::first();

        if ($orchestrator) {
            $podcastEpisode = $orchestrator->podcast_episode;

            if ($podcastEpisode) {
                $getLinkRequest = $this->rebrandly->get("links/$podcastEpisode->branded_audio_link_id");
                if ($getLinkRequest->successful()) {
                    $this->rebrandly->delete("links/$podcastEpisode->branded_audio_link_id")
                        ->throw();
                }
            }
        }

        Artisan::call('db:wipe');
        Artisan::call('migrate');
        Artisan::call('horizon:terminate');
        Storage::disk('local-podcasts')->deleteDirectory('1');
        return 0;
    }
}
