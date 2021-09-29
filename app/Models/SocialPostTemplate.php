<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialPostTemplate extends Model
{
    use HasFactory;

    protected $guarded = [];

    static function createFromPodcast(PodcastEpisode $podcastEpisode): SocialPostTemplate
    {
        return static::create([
            'body' => $podcastEpisode->social_post_text,
            'image_path' => $podcastEpisode->cover_image_path
        ]);
    }
}
