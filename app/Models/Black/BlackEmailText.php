<?php

namespace App\Models\Black;

use App\Traits\CommonTrait;
use Illuminate\Database\Eloquent\Model;

class BlackEmailText extends Model
{
    use CommonTrait;

    protected $table = 'black_email_texts';

    protected $fillable = [
        'file',
    ];

    public function getCreatedAtAttribute($value): ?string
    {
        return $value ? $this->asDateTime($value)->format($this->getDateFormatSettings()) : null;
    }
}
