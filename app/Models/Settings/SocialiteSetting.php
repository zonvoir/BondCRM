<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class SocialiteSetting extends Model
{
    protected $fillable = [
        'provider',
        'client_id',
        'client_secret',
        'redirect_url',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
