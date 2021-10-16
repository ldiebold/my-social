<?php

namespace App\SocialPublishers;

use App;
use App\Contracts\SocialPlatform\PublishesImage;
use App\Services\LinkedIn\LinkedIn;
use Illuminate\Support\Collection;

class LinkedInProfileImagePublisher implements PublishesImage
{
    public string $body;
    public string $image;
    public LinkedIn $linkedin;

    public function __construct()
    {
        $this->linkedin = App::make(LinkedIn::class);
    }

    static function getPlatformName(): string
    {
        return 'linkedin.profile';
    }

    public function setTitle($title): static
    {
        $this->title = $title;

        return $this;
    }

    public function setBody($body): static
    {
        $this->body = $body;

        return $this;
    }

    public function getTitleWithBody(): string
    {
        $result = $this->body;
        if (isset($this->title)) {
            $result = $this->title . "\n\n" . $this->body;
        }

        return $result;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function validate(): bool
    {
        return isset($this->body) || isset($this->image);
    }

    public function publishImage(): Collection
    {
        return $this->linkedIn->publishLinkPost(
            $this->image,
            env('FACEBOOK_PAGE_ID'),
            $this->body
        )->throw()->collect();
    }

    public function publish(): Collection
    {
        return $this->publishImage();
    }
}
