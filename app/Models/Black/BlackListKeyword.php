<?php

namespace App\Models\Black;

use Illuminate\Database\Eloquent\Model;

class BlackListKeyword extends Model
{
    //
    protected $fillable = [
        'user_id',
        'keyword',
    ];
}
