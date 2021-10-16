<?php

namespace App\Contracts\SocialPlatform;

use Illuminate\Support\Collection;

interface PublishesToSocial
{
    static function getPlatformName(): string;

    public function publish(): Collection;
}
