<?php

namespace App\Models;

use Arr;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Str;

class PublishPostEventTemplate extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function social_publishers()
    {
        return $this->belongsToMany(
            SocialPublisher::class,
            'publish_post_event_template_social_publishers'
        );
    }

    public function social_post_template()
    {
        return $this->belongsTo(SocialPostTemplate::class);
    }

    public function publish_post_event_templateable()
    {
        return $this->morphTo();
    }

    public function scheduleAllPostsForPodcastEpisode(PodcastEpisode $podcastEpisode)
    {
        $this->social_publishers->map(
            function (SocialPublisher $socialPublisher)
            use ($podcastEpisode) {
                $socialPost = SocialPost::make(
                    Arr::except($this->social_post_template->toArray(), ['id'])
                )->social_publisher()->associate($socialPublisher)
                    ->save();

                return PublishEvent::make([
                    'publish_at' => $this->getPublishDateFromReleaseDate($podcastEpisode->publish_date)
                ])->social_publisher()->associate($socialPublisher)
                    ->social_post_template()->associate($this->social_post_template)
                    ->social_post()->associate($socialPost)
                    ->save();
            }
        );
    }

    public function getPublishDateFromReleaseDate(Carbon $releaseDate)
    {
        return $releaseDate->setTime(0, 0, 0, 0)
            ->addDays($this->days_after_release)
            ->setTimeFromTimeString($this->release_time);
    }

    public function schedulePostFor(
        array|Collection|string $platforms,
        Carbon $releaseDate,
        string $postBody,
        string $link = null,
        string $image_path = null
    ): self {
        $publishDate = $releaseDate->setTime(0, 0, 0, 0)
            ->addDays($this->days_after_release)
            ->setTimeFromTimeString($this->release_time);

        $platformsArray = (new Collection($platforms))->toArray();

        foreach ($platformsArray as $platform) {
            $body = Str::of($postBody);
            if ($link) {
                $body->append("\n\n $link");
            }

            $socialPost = SocialPost::create([
                'social_channel' => $platform,
                'body' => (string) $body,
                'image_path' => $image_path
            ]);

            ScheduledSocialPost::create([
                'social_post_id' => $socialPost->id,
                'publish_at' => $publishDate
            ]);
        }

        return $this;
    }
}
