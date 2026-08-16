<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'image'];


    public function getRouteKeyName(): string
    {
        return 'slug';
    }


    public function products()
    {
        return $this->hasMany(Product::class);
    }




    public function scopeSortBy(Builder $query, ?string $field, ?string $direction): Builder
    {
        $allowedFields = ['name', 'products_count'];
        $direction = strtolower($direction ?? 'asc') === 'desc' ? 'desc' : 'asc';

        if (! in_array($field, $allowedFields, true)) {
            return $query->orderBy('name');
        }

        return $query->orderBy($field, $direction);
    }
}
