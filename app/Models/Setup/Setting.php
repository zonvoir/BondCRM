<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['group', 'key', 'type', 'value'];

    public function getCastedValueAttribute()
    {
        return match ($this->type) {
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $this->value,
            'json' => json_decode($this->value, true),
            default => $this->value,
        };
    }

    public function setRawValue($value): static
    {
        if (is_bool($value)) {
            $this->type = 'boolean';
            $this->value = $value ? 1 : 0;
        } elseif (is_int($value)) {
            $this->type = 'integer';
            $this->value = (string) $value;
        } elseif (is_array($value) || is_object($value)) {
            $this->type = 'json';
            $this->value = json_encode($value);
        } else {
            $this->type = 'string';
            $this->value = (string) $value;
        }

        return $this;
    }
}
