<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{

    use HasFactory;

    protected $fillable = ['category_id', 'name', 'slug', 'description', 'price', 'stock', 'image', 'is_active'];


    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function scopeActive(Builder $query) : Builder
    {
    return $query->where('is_active', true);
    }



}