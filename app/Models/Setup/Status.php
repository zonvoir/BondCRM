<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = [
        'id',
        'name',
        'user_id',
    ];
}
