<?php

namespace App\Models\Settings;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Imap extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'type',
        'imap_server',
        'imap_user_name',
        'imap_port',
        'folder',
        'imap_encryption',
        'password',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
