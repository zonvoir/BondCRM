<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;

class Sources extends Model
{
    protected $fillable = [
        'source',
        'user_id',
        'id',
    ];
}
