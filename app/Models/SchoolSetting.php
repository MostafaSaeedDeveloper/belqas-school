<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'group',
        'value',
        'type',
    ];

    /**
     * Get the setting value in a normalized way.
     */
    public function getNormalizedValueAttribute()
    {
        $rawValue = $this->value;

        return match ($this->type) {
            'boolean' => filter_var($rawValue, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false,
            'integer' => (int) $rawValue,
            'array' => json_decode($rawValue ?? '[]', true) ?? [],
            default => $rawValue,
        };
    }

    /**
     * Update or create a setting value.
     */
    public static function setValue(string $key, $value, string $group = 'general', string $type = 'string'): self
    {
        $storedValue = $value;

        if ($type === 'array') {
            $storedValue = json_encode($value ?? []);
        }

        return static::updateOrCreate(
            ['key' => $key],
            [
                'group' => $group,
                'value' => $storedValue,
                'type' => $type,
            ]
        );
    }

    /**
     * Retrieve a setting value.
     */
    public static function getValue(string $key, $default = null)
    {
        return optional(static::query()->where('key', $key)->first())->normalized_value ?? $default;
    }
}
