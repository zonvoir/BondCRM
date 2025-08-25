<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class SmtpSetting extends Model
{
    protected $table = 'smtp_settings';

    protected $primaryKey = 'id';

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
    ];
}
