<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedditAccessToken extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function isStale()
    {
        $minutesUntilExpired = ($this->expires_in * 60) - 30;

        $expirationTime = Carbon::createFromTimestampUTC($this->date_retrieved)
            ->addMinutes($minutesUntilExpired);

        return now()->greaterThan($expirationTime);
    }
}
