<?php

namespace App\Contracts\SocialPlatform;

use App\Contracts\SocialPlatform\PublishesToSocial;
use Illuminate\Support\Collection;

interface PublishesImageViaUrl extends PublishesToSocial
{
    public function setImageUrl(string $imageUrl): static;

    public function publishImageViaUrl(): Collection;
}
