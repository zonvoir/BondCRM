<?php

namespace App\Models\Setup;

use App\Traits\CommonTrait;
use Illuminate\Database\Eloquent\Model;

class Sources extends Model
{
    use CommonTrait;

    protected $fillable = [
        'source',
        'user_id',
        'id',
    ];

    public function getCreatedAtAttribute($value): ?string
    {
        return $value ? $this->asDateTime($value)->format($this->getDateFormatSettings()) : null;
    }
}
