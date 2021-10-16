<?php

namespace App\Contracts\SocialPlatform;

use Illuminate\Support\Collection;

interface PublishesPlainText extends PublishesToSocial
{
    public function publishPlainText(): Collection;
}
