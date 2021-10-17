<?php

namespace App\Jobs;

use App;
use Storage;
use App\Models\ExternalPodcastFolder;
use App\Models\PublishPodcastOrchestrator;
use App\Services\Transistor\Transistor;
use Bus;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob as SpatieProcessWebhookJob;
use \Illuminate\Contracts\Filesystem\Filesystem;
use Log;
use Str;

class ProcessDropboxWebhookJob extends SpatieProcessWebhookJob
{
    public Filesystem $dropboxDisk;
    public int $latestEpisodeNumber;
    public Transistor $transistor;

    public function handle()
    {
        $this->transistor = App::make(Transistor::class);
        $this->latestEpisode = $this->transistor->latestEpisodeNumber(env('PODCAST_SHOW_ID'));

        $this->dropboxDisk = Storage::disk('dropbox');

        $directories = collect(
            $this->dropboxDisk->directories(env('PODCASTS_DIRECTORY'))
        );

        $allFolders = ExternalPodcastFolder::all()->pluck('path');

        $newDirectories = $directories->diff($allFolders)
            ->map(function ($item) {
                return $item;
            });

        Log::info($newDirectories);

        $publishPodcastJobChains = $newDirectories->each(function ($podcastFolder) {
            $podcastFolderNumber = Str::replace('podcasts/', '', $podcastFolder);
            Log::info($podcastFolderNumber);
            if (
                (intval($podcastFolderNumber) <= $this->latestEpisode) &&
                $this->dropboxDisk->exists($podcastFolder . '/publish')
            ) {
                return;
            };
            $externalPodcastFolder =  ExternalPodcastFolder::create([
                'path' => $podcastFolder
            ]);
            $podcastOrchestrator = PublishPodcastOrchestrator::create([
                'external_podcast_folder_id' => $externalPodcastFolder->id
            ]);
            return $podcastOrchestrator->publishPodcast();
        });
    }
}
