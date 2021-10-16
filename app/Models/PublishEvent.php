<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublishEvent extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function social_publisher()
    {
        return $this->belongsTo(SocialPublisher::class);
    }

    public function social_post_template()
    {
        return $this->belongsTo(SocialPostTemplate::class);
    }

    public function social_post()
    {
        return $this->belongsTo(SocialPost::class);
    }
}
