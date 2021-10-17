<?php

namespace App\Models;

use App\Jobs\CleanPodcastAudioJob;
use App\Jobs\CreateBrandedLinkJob;
use App\Jobs\CreatePodcastVideoJob;
use App\Jobs\DownloadAndStoreAudioFileJob;
use App\Jobs\GenerateCoverImageJob;
use App\Jobs\PublishEpisodeToPodcastPlatformJob;
use App\Jobs\PublishEpisodeToVideoPlatformJob;
use App\Jobs\SchedulePodcastsSocialPostsJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Foundation\Bus\PendingChain;
use Illuminate\Support\Collection;
use Bus;



class PublishPodcastOrchestrator extends Model
{
    use HasFactory;
    protected $guarded = [];

    public static $jobPipeline = [
        DownloadAndStoreAudioFileJob::class => 'raw_audio_file_is_stored',
        CleanPodcastAudioJob::class => 'audio_cleaned',
        PublishEpisodeToPodcastPlatformJob::class => 'published_on_podcast_platform',
        CreateBrandedLinkJob::class => 'has_branded_link',
        GenerateCoverImageJob::class => 'has_cover_image',
        CreatePodcastVideoJob::class => 'has_podcast_video',
        PublishEpisodeToVideoPlatformJob::class => 'published_on_video_platform',
        SchedulePodcastsSocialPostsJob::class => 'social_posts_scheduled',
    ];

    public function getCompleteJobsAttribute()
    {
        return collect(static::$jobPipeline)
            ->filter(function ($isComplete) {
                return $this[$isComplete];
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
            ->filter(function ($isComplete) {
                return !$this[$isComplete];
            });
    }

    /**
     * Run all incomplete jobs for publishing a podcast
     *
     * @return false|PendingDispatch
     */
    public function publishPodcast()
    {
        return $this->makePublishPodcastJobChain()->dispatch();
    }

    /**
     * Create publish podcast job chain
     *
     * @return false|PendingChain
     */
    public function makePublishPodcastJobChain()
    {
        $jobs = $this->incomplete_jobs->map(fn ($value, $job) => (new $job($this)));
        if ($jobs->isEmpty()) {
            return false;
        }

        $bus = Bus::chain($jobs);

        return $bus;
    }

    /**
     * A PublishPodcastOrchestrator belongs to an
     * ExternalPodcastFolder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function external_podcast_folder()
    {
        return $this->belongsTo(\App\Models\ExternalPodcastFolder::class);
    }

    public function podcast_episode()
    {
        return $this->belongsTo(\App\Models\PodcastEpisode::class);
    }
}
