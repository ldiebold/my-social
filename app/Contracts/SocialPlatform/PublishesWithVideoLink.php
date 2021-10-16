<?php

namespace App\Contracts\SocialPlatform;

use App\Contracts\SocialPlatform\PublishesToSocial;
use Illuminate\Support\Collection;

interface PublishesWithVideoLink extends PublishesToSocial
{
    public function setVideoLink(string $link): static;

    public function publishVideoLink(): Collection;
}
