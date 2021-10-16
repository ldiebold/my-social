<?php

namespace App\SocialPublishers;

use App;
use App\Contracts\SocialPlatform\PublishesLink;
use App\Contracts\SocialPlatform\PublishesWithBody;
use App\Services\Facebook\FacebookPage;
use Illuminate\Support\Collection;

class FacebookPageLinkPublisher implements PublishesLink, PublishesWithBody
{
    public string $body;
    public string $link;
    public FacebookPage $facebookPage;

    public function __construct()
    {
        $this->facebookPage = App::make(FacebookPage::class);
    }

    static function getPlatformName(): string
    {
        return 'facebook.page.link';
    }

    public function setBody($body): static
    {
        $this->body = $body;
        return $this;
    }

    public function setLink($link): static
    {
        $this->link = $link;
        return $this;
    }

    public function publishLink(): Collection
    {
        return $this->facebookPage
            ->usingDefaultPage()
            ->publishLinkPost($this->link, $this->body)
            ->throw()
            ->collect();
    }

    public function publish(): Collection
    {
        return $this->publishLink();
    }
}
