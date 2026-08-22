<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{

    use HasFactory;

    protected $fillable = ['category_id', "tax_id", 'name', 'slug', 'description', 'price', 'stock', 'image', 'is_active'];

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
     * Get the tax for the product.
     */
    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // filter active products
    public function scopeFilterActive(Builder $query, ?string $value): Builder
    {
        if ($value === null || $value === '') {
            return $query; // all products
        }

        if ($value === '1' || $value === 'true') {
            return $query->where('is_active', true);
        }

        if ($value === '0' || $value === 'false') {
            return $query->where('is_active', false);
        }

        return $query;
    }


    /**
     * Eager load category relation.
     */
    public function scopeWithCategory(Builder $query): Builder
    {
        return $query->with('category:id,name,slug');
    }

    /**
     * Filter products by category slug.
     */
    public function scopeWithCategorySlug(Builder $query, ?string $category): Builder
    {
        if (blank($category)) {
            return $query;
        }

        return $query->whereHas('category', function (Builder $q) use ($category) {
            $q->where('slug', $category);
        });
    }

    /**
     * Search products by name or description.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        });
    }

    /**
     * Sort products by field and direction.
     */

    public function scopeSortBy(Builder $query, ?string $field, ?string $direction): Builder
    {
        $allowedFields = ['name', 'price', 'created_at'];
        $direction = strtolower($direction ?? 'asc') === 'desc' ? 'desc' : 'asc';

        if (! in_array($field, $allowedFields, true)) {
            return $query->orderBy('name'); // default
        }

        return $query->orderBy($field, $direction);
    }
}
