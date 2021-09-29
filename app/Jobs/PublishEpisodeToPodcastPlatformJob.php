<?php

namespace App\Jobs;

use App\Models\PodcastEpisode;
use App\Models\PublishPodcastOrchestrator;
use App\Services\Transistor\Transistor;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Storage;

class PublishEpisodeToPodcastPlatformJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public PublishPodcastOrchestrator $orchestrator;

    protected Transistor $transistor;
    protected PodcastEpisode $podcastEpisode;
    protected Carbon $publishDate;

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
    }

    public function setup()
    {
        if (!$this->orchestrator->podcast_episode) {
            throw new Exception('The provided orchestrator is missing a "podcast_episode"');
        };
        $this->orchestrator->fresh();

        $this->podcastEpisode = $this->orchestrator->podcast_episode;

        return $this;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Transistor $transistor)
    {
        $this->transistor = $transistor;

        $this->setup()
            ->ensurePodcastCanBeUploaded()
            ->guessPublishDate()
            ->publish()
            ->updateOrchestratorModel();
    }

    public function ensurePodcastCanBeUploaded(): self
    {
        $validator = Validator::make($this->podcastEpisode->toArray(), [
            'title' => 'required',
            'description' => 'required',
            'clean_audio_file_path' => 'required'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this;
    }

    public function guessPublishDate(): self
    {
        $latestKnownPublishDate = $this->transistor
            ->getLatestKnownEpisodeDate(env('PODCAST_SHOW_ID'));

        $this->publishDate = $latestKnownPublishDate
            ->addDays(env('DAYS_BETWEEN_EPISODES'));

        return $this;
    }

    public function publish(): self
    {
        $podcastFile = Storage::disk('local-podcasts')
            ->get($this->orchestrator->podcast_episode->clean_audio_file_path);

        $response = $this->transistor->createEpisodeWithAudio(
            $podcastFile,
            'clean.mp3',
            env('PODCAST_SHOW_ID'),
            $this->podcastEpisode->title,
            [
                'episode[summary]' => $this->podcastEpisode->description
            ]
        );

        ray($response->collect());

        $this->podcastEpisode->update([
            'provider_id' => $response->collect()['data']['id'],
            'publish_date' => $this->publishDate->toDateTimeString(),
            'audio_share_url' => $response->collect()['data']['attributes']['share_url']
        ]);

        $episodeId = $response->collect()['data']['id'];

        $publishResponse = $this->transistor->patch("episodes/$episodeId/publish", [
            'episode[status]' => 'scheduled',
            'episode[published_at]' => $this->publishDate->toDateTimeString()
        ])->throw();

        ray($publishResponse->collect());

        return $this;
    }

    public function updateOrchestratorModel()
    {
        $this->orchestrator->update([
            'published_on_podcast_platform' => true
        ]);
    }
}
