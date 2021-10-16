<?php

namespace App\SocialPublishers;

use App;
use App\Contracts\SocialPlatform\PublishesLink;
use App\Contracts\SocialPlatform\PublishesWithBody;
use App\Contracts\SocialPlatform\PublishesWithTitle;
use App\Services\Reddit\Reddit;
use Illuminate\Support\Collection;

class RedditSubredditLinkPublisher implements PublishesLink, PublishesWithTitle, PublishesWithBody
{
    public string $title;
    public string $body;
    public string $link;
    public string $subreddit;

    public Reddit $reddit;

    public function __construct()
    {
        $this->reddit = App::make(Reddit::class);
    }

    static function getPlatformName(): string
    {
        return 'reddit.subreddit.link';
    }

    public function setBody($body): static
    {
        $this->body = $body;
        return $this;
    }

    public function setTitle($title): static
    {
        $this->title = $title;
        return $this;
    }

    public function setLink($link): static
    {
        $this->link = $link;
        return $this;
    }

    public function setSubreddit($subreddit): static
    {
        $this->subreddit = $subreddit;
        return $this;
    }

    public function publishLink(): Collection
    {
        return $this->reddit->usingDefaultAccount()
            ->publishLinkPost(
                $this->subreddit,
                $this->link,
                $this->title,
                $this->body
            );
    }

    public function publish(): Collection
    {
        return $this->publishLink();
    }
}
