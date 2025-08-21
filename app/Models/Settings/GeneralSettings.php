<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class GeneralSettings extends Model
{
    protected $table = 'general_settings';

    protected $primaryKey = 'id';

    protected $fillable = [
        'footer_text',
        'icon_logo_dark',
        'icon_logo_white',
        'logo_dark',
        'logo_white',
        'favicon',
        'countries',
        'timezones',
        'app_name',
        'app_description',
        'app_logo',
        'theme_color',
        'id',
    ];

    protected $casts = [
        'countries' => 'array',
        'timezones' => 'array',
    ];
}
