<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{

    use HasFactory;

    protected $fillable = ['category_id', 'name', 'slug', 'description', 'price', 'stock', 'image', 'is_active'];

/**
 * Get the route key for the model.
 */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

/**
 * Get the category for the product.
 */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

/**
 * Scope a query to only include active products.
 */
    public function scopeActive(Builder $query) : Builder
    {
    return $query->where('is_active', true);
    }



/**
 * Scope a query to only include products by category.
 */
public function scopeWithCategory(Builder $query): Builder
{
    return $query->with('category:id,name,slug');
}

/**
 * Scope a query to only include products by category slug.
 */
public function scopeWithCategorySlug(Builder $query, string $category): Builder
{
    return $query->whereHas('category', function (Builder $q) use ($category) {
        $q->where('slug', $category);
    });
}



}