<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'slug', 'description', 'price', 'stock', 'image', 'is_active'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function scopeActive(Builder $query) : Builder
{
    return $query->where('is_active', true);
}
}