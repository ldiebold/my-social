<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledSocialPost extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = [
        'publish_at'
    ];

    public function social_post()
    {
        return $this->belongsTo(SocialPost::class);
    }
}
