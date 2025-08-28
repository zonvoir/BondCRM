<?php

namespace App\Models\Black;

use Illuminate\Database\Eloquent\Model;

class BlackEmail extends Model
{
    //
    protected $fillable = [
        'user_id',
        'email',
    ];
}
