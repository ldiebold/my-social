<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookAccessToken extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getExpiresInAttribute()
    {
        $expires = Carbon::createFromTimestamp($this->expires);
        return $expires->diffInSeconds(now(), false);
    }
}
