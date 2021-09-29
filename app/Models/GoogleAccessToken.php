<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleAccessToken extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'expires_in'
    ];

    public function getExpiresInAttribute()
    {
        $created = Carbon::createFromTimestamp($this->created);
        return $created->addSeconds(3600)
            ->diffInSeconds(now());
    }
}
