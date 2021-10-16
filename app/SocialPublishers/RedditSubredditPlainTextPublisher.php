<?php

namespace App\SocialPublishers;

use App;
use App\Contracts\SocialPlatform\PublishesImageViaUrl;
use App\Contracts\SocialPlatform\PublishesPlainText;
use App\Contracts\SocialPlatform\PublishesWithBody;
use App\Contracts\SocialPlatform\PublishesWithTitle;
use App\Services\Reddit\Reddit;
use Illuminate\Support\Collection;

class RedditSubredditPublisher implements PublishesPlainText, PublishesWithTitle, PublishesWithBody
{
    public string $title;
    public string $body;
    public string $subreddit;
    public Reddit $reddit;

    public function __construct()
    {
        $this->reddit = App::make(Reddit::class);
    }

    static function getPlatformName(): string
    {
        return 'reddit.subreddit.text';
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

    public function setSubreddit($subreddit): static
    {
        $this->subreddit = $subreddit;
        return $this;
    }

    public function publishPlainText(): Collection
    {
        return $this->reddit->publishTextPost(
            $this->subreddit,
            $this->title,
            $this->body
        );
    }

    public function publish(): Collection
    {
        return $this->publishPlainText();
    }
}
