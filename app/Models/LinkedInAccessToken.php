<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $other
 */
class LinkedInAccessToken extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getExpiresInAttribute()
    {
        $created = Carbon::createFromTimestamp($this->created);
        return $created->addSeconds($this->expires_in)
            ->diffInSeconds(now());
    }
}
