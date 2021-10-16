<?php

namespace App\SocialPublishers;

use App;
use App\Contracts\SocialPlatform\PublishesPlainText;
use App\Contracts\SocialPlatform\PublishesWithBody;
use App\Services\Facebook\FacebookPage;
use Illuminate\Support\Collection;

class FacebookPagePlainTextPublisher implements PublishesPlainText, PublishesWithBody
{
    public string $body;
    public FacebookPage $facebookPage;

    public function __construct()
    {
        $this->facebookPage = App::make(FacebookPage::class);
    }

    static function getPlatformName(): string
    {
        return 'facebook.page.text';
    }

    public function setBody($body): static
    {
        $this->body = $body;
        return $this;
    }

    public function publishPlainText(): Collection
    {
        return $this->facebookPage
            ->usingDefaultPage()
            ->publishTextPost($this->body)
            ->throw()
            ->collect();
    }

    public function publish(): Collection
    {
        return $this->publishPlainText();
    }
}
