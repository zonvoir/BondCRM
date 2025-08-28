<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;

class LiveChatSettings extends Model
{
    protected $table = 'live_chat_settings';

    protected $fillable = [
        'type',
        'pusher_app_id',
        'pusher_app_key',
        'pusher_app_secret',
        'pusher_host',
        'pusher_port',
        'pusher_scheme',
        'pusher_app_cluster',
        'ably_key',
        'id',
    ];
}
