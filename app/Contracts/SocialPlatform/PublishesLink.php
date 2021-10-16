<?php

namespace App\Contracts\SocialPlatform;

use App\Contracts\SocialPlatform\PublishesToSocial;
use Illuminate\Support\Collection;

interface PublishesLink extends PublishesToSocial
{
    public function setLink(string $link): static;

    public function publishLink(): Collection;
}
