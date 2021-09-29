<?php

namespace App\Models;

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
