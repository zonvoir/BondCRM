<?php

namespace App\Models\Email;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlackListEmail extends Model
{
    protected $table = 'black_list_emails';

    protected $fillable = [
        'file',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
