<?php

namespace App\Contracts\SocialPlatform;

interface PublishesWithBody
{
    public function setBody(string $body): static;
}
