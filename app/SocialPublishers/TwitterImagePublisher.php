<?php

namespace App\SocialPublishers;

use App;
use App\Contracts\SocialPlatform\PublishesImage;
use App\Contracts\SocialPlatform\PublishesWithBody;
use Illuminate\Support\Collection;
use App\Services\Twitter\Twitter;

class TwitterImagePublisher implements PublishesImage, PublishesWithBody
{
    protected string $image;
    protected string $body;

    protected Twitter $twitter;

    static function getPlatformName(): string
    {
        return 'twitter.image';
    }

    public function __construct()
    {
        $this->twitter = App::make(Twitter::class);
    }

    public function setBody(string $body): static
    {
        $this->body = $body;
        return $this;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function publishImage(): Collection
    {
        return $this->twitter->updateStatusWithImage(
            $this->title,
            $this->body
        );
    }

    public function publish(): Collection
    {
        return $this->publishImage();
    }
}
