<?php

namespace App\Models;

use App\Models\Setup\Sources;
use App\Models\Setup\Status;
use App\Traits\CommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Lead extends Model
{
    use CommonTrait;

    protected $fillable = [
        'user_id',
        'name',
        'sources_id',
        'status_id',
        'address',
        'position',
        'city',
        'email',
        'state',
        'website',
        'country_id',
        'phone',
        'zip_code',
        'lead_value',
        'company',
        'description',
        'date_contacted',
        'public',
        'is_date_contacted',
        'id',
    ];

    protected $casts = [
        'phone' => 'integer',
        'zip_code' => 'integer',
        'public' => 'boolean',
        'is_date_contacted' => 'boolean',
        'date_contacted' => 'datetime',
    ];

    protected $with = [
        'tags',
    ];

    public function getCreatedAtAttribute($value): ?string
    {
        return $value ? $this->asDateTime($value)->format($this->getDateFormatSettings()) : null;
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Lead belongs to a Source
     */
    public function source(): BelongsTo
    {
        return $this->belongsTo(Sources::class, 'sources_id');
    }

    /**
     * Lead belongs to a Status
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Lead belongs to a Country
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
