<?php

namespace App\SocialPublishers;

use App;
use App\Contracts\SocialPlatform\PublishesLink;
use App\Contracts\SocialPlatform\PublishesWithBody;
use Illuminate\Support\Collection;
use App\Services\Twitter\Twitter;

class TwitterLinkPublisher implements PublishesLink, PublishesWithBody
{
    protected string $body;
    protected string $link;

    protected Twitter $twitter;

    static function getPlatformName(): string
    {
        return 'twitter.link';
    }

    public function __construct()
    {
        $this->twitter = App::make(Twitter::class);
    }

    public function setLink(string $link): static
    {
        $this->link = $link;
        return $this;
    }

    public function setBody(string $body): static
    {
        $this->body = $body;
        return $this;
    }

    public function publishLink(): Collection
    {
        return $this->twitter->updateStatus($this->body . "\n" . $this->link);
    }

    public function publish(): Collection
    {
        return $this->publishLink();
    }
}
