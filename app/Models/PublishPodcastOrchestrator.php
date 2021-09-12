<?php

namespace App\Models;

use Bus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class PublishPodcastOrchestrator extends Model
{
    use HasFactory;
    protected $guarded = [];

    public static $jobPipeline = [
        \App\Jobs\DownloadAndStoreAudioFileJob::class => [
            'complete_if' => 'raw_audio_file_is_stored'
        ],
        \App\Jobs\CleanPodcastAudioJob::class => [
            'complete_if' => 'audio_cleaned'
        ]
    ];

    public function getCompleteJobsAttribute()
    {
        return collect(static::$jobPipeline)
            ->filter(function ($job) {
                return $this[$job['complete_if']];
            });
    }

    /**
     * Get a collection of jobs that haven't
     * been successfully completed
     *
     * @return Collection
     */
    public function getIncompleteJobsAttribute()
    {
        return Collection::make(static::$jobPipeline)
            ->filter(function ($job) {
                return !$this[$job['complete_if']];
            });
    }

    /**
     * Run all incomplete jobs for publishing a podcast
     *
     * @return \Illuminate\Foundation\Bus\PendingDispatch
     */
    public function publishPodcast()
    {
        $bus = Bus::chain(
            $this->incomplete_jobs->map(fn ($value, $job) => (new $job($this)))
        );

        $bus->dispatch();
    }

    public function external_podcast_folder()
    {
        return $this->belongsTo(\App\Models\ExternalPodcastFolder::class);
    }

    public function podcast_episode()
    {
        return $this->belongsTo(\App\Models\PodcastEpisode::class);
    }
}
