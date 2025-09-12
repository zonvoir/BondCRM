<?php

namespace App\Models\Black;

use App\Traits\CommonTrait;
use Illuminate\Database\Eloquent\Model;

class BlackListKeyword extends Model
{
    use CommonTrait;

    protected $fillable = [
        'user_id',
        'keyword',
    ];

    public function getCreatedAtAttribute($value): ?string
    {
        return $value ? $this->asDateTime($value)->format($this->getDateFormatSettings()) : null;
    }
}
