<?php

namespace App\SocialPublishers;

use App;
use App\Contracts\SocialPlatform\PublishesImage;
use App\Contracts\SocialPlatform\PublishesWithBody;
use App\Services\Facebook\FacebookPage;
use Illuminate\Support\Collection;

class FacebookPageImagePublisher implements PublishesImage, PublishesWithBody
{
    public string $body;
    public string $image;
    public FacebookPage $facebookPage;

    public function __construct()
    {
        $this->facebookPage = App::make(FacebookPage::class);
    }

    static function getPlatformName(): string
    {
        return 'facebook.page.image';
    }

    public function setBody($body): static
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
        return $this->facebookPage
            ->usingDefaultPage()
            ->uploadPicturePost($this->image, $this->body)
            ->throw()
            ->collect();
    }

    public function publish(): Collection
    {
        return $this->publishImage();
    }
}
