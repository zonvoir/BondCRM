<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;

class SmtpUser extends Model
{
    protected $fillable = [
        'mail_driver',
        'mail_host',
        'mail_port',
        'mail',
        'password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        'id',
        'user_id',
    ];
}
