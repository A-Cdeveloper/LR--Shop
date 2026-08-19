<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
    ];

    public const KEYS = [
        'shop.name',
        'shop.email',
        'shop.phone',
        'shop.address_line1',
        'shop.address_line2',
        'shop.city',
        'shop.state',
        'shop.zip',
        'shop.country',
        'shop.logo_url',
        'shop.currency',
        'shop.locale',
        'shop.timezone',
        'shop.orders_per_page',
        'shop.products_per_page',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = static::query()->where('key', $key)->value('value');

        return $value ?? $default;
    }

    public function scopeKnown(Builder $query): Builder
    {
        return $query->whereIn('key', self::KEYS);
    }
}