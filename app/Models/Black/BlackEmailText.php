<?php

namespace App\Models\Black;

use Illuminate\Database\Eloquent\Model;

class BlackEmailText extends Model
{
    protected $table = 'black_email_texts';

    protected $fillable = [
        'file',
    ];
}
