<?php

namespace App\Http\Controllers\Api\V1\Products;

use App\Http\Controllers\Controller;
use App\Http\Resources\Products\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categoryQuery = request()->query('category');
        $searchQuery = request()->query('search');
        $perPage = (int) request()->query('per_page', 10);
        $perPage = max(1, min($perPage, 50));
        $sortField = request()->query('sort');
        $sortDirection = request()->query('order');

        $products = Product::active()
            ->withCategory()
            ->with('tax')
            ->search($searchQuery)
            ->withCategorySlug($categoryQuery)
            ->sortBy($sortField, $sortDirection)
            ->paginate($perPage);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        if (!$product->is_active) {
            return response()->json(['message' => __('api.common.not_found')], 404);
        }

        $product->load(['category', 'tax']);

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
