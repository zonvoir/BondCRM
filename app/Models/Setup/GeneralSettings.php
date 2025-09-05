<?php

namespace App\Models\Setup;

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
        'company_name',
        'allowed_file_types',
        'date_format',
        'time_format',
    ];

    protected $casts = [
        'countries' => 'array',
        'timezones' => 'array',
        'date_format' => 'array',
        'time_format' => 'array',
    ];
}
