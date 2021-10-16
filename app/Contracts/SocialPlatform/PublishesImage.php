<?php

namespace App\Contracts\SocialPlatform;

use App\Contracts\SocialPlatform\PublishesToSocial;
use Illuminate\Support\Collection;

interface PublishesImage extends PublishesToSocial
{
    public function setImage(string $image): static;

    public function publishImage(): Collection;
}
