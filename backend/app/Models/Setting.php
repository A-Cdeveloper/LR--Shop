<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

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

    protected static function cached(): array
    {
        return Cache::rememberForever('settings', function () {
            return static::query()->known()->pluck('value', 'key')->all();
        });
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return static::cached()[$key] ?? $default;
    }

    public static function flushCache(): void
    {
        Cache::forget('settings');
    }

    public function scopeKnown(Builder $query): Builder
    {
        return $query->whereIn('key', self::KEYS);
    }
}
