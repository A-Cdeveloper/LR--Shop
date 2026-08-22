<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tax extends Model
{
    protected $fillable = [
        'name',
        'rate',
        'is_default',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:2',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public static function default(): ?self
    {
        return static::query()
            ->active()
            ->where('is_default', true)
            ->first();
    }

    /**
     * Extract the tax amount from a gross amount.
     */
    public static function extractInclusive(float $gross, float $rate): float
    {
        if ($rate <= 0) {
            return 0.0;
        }

        return round($gross * $rate / (100 + $rate), 2);
    }
}
