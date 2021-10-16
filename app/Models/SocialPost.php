<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialPost extends Model
{
    use HasFactory;

    const primaryKey = 'id';

    protected $guarded = [];

    public function social_publisher()
    {
        return $this->belongsTo(SocialPublisher::class);
    }
}
