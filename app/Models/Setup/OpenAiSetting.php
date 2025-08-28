<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;

class OpenAiSetting extends Model
{
    protected $fillable = [
        'id',
        'assistant_name',
        'assistant_id',
        'api_key',
        'prompt',
    ];
}
