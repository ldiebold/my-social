<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PodcastEpisode extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = [
        'publish_date'
    ];

    /**
     * Get a shareable link for the video version of this podcast
     *
     * @return null|string
     */
    public function getVideoShareLinkAttribute()
    {
        if ($this->video_id) {
            return 'https://youtu.be/' . $this->video_id;
        }
        return null;
    }
}
