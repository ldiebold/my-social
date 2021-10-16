<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwitterTokenCredentials extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = [
        'secret'
    ];

    public function getSecret(): string
    {
        return $this->secret;
    }
}
