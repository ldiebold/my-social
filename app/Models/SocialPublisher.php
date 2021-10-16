<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialPublisher extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function publish_post_event_templates()
    {
        return $this->belongsToMany(
            PublishPostEventTemplate::class,
            'publish_post_event_template_social_publishers'
        );
    }
}
