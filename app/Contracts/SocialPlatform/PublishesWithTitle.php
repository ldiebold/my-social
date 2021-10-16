<?php

namespace App\Contracts\SocialPlatform;

interface PublishesWithTitle
{
    public function setTitle(string $body): static;
}
