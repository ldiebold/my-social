<?php

namespace App\Jobs;

use Storage;
use App\Models\ExternalPodcastFolder;
use App\Models\PublishPodcastOrchestrator;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob as SpatieProcessWebhookJob;
use \Illuminate\Contracts\Filesystem\Filesystem;

class ProcessDropboxWebhookJob extends SpatieProcessWebhookJob
{
    public Filesystem $dropboxDisk;

    public function handle()
    {
        $this->dropboxDisk = Storage::disk('dropbox');

        $directories = collect(
            $this->dropboxDisk->directories(env('PODCASTS_DIRECTORY'))
        );

        $allFolders = ExternalPodcastFolder::pluck('path');

        $newDirectories = $directories->diff($allFolders)
            ->map(function ($item) {
                return ['path' => $item];
            });

        $newDirectories->each(function ($podcastFolder) {
            $externalPodcastFolder =  ExternalPodcastFolder::create($podcastFolder);
            $podcastOrchestrator = PublishPodcastOrchestrator::create([
                'external_podcast_id' => $externalPodcastFolder->id
            ]);
            $podcastOrchestrator->publishPodcast();
        });
    }
}
