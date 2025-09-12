<?php

namespace App\Models\Setup;

use App\Models\Lead;
use App\Traits\CommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use CommonTrait;

    protected $fillable = [
        'id',
        'name',
        'color',
        'user_id',
    ];

    public function getCreatedAtAttribute($value): ?string
    {
        return $value ? $this->asDateTime($value)->format($this->getDateFormatSettings()) : null;
    }

    /**
     * A status can have many leads.
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'status_id');
    }
}
