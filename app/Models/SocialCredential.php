<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialCredential extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'provider_user_id',
        'credentials',
        'access_token',
        'refresh_token',
        'token_expires_at',
    ];

    protected $casts = [
        'credentials' => 'json',
    ];
}
