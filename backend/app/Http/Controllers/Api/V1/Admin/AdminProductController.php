<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use App\Http\Resources\Products\ProductResource;

class AdminProductController extends Controller
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

        $products = Product::query()
            ->withCategory()
            ->search($searchQuery)
            ->withCategorySlug($categoryQuery)
            ->sortBy($sortField, $sortDirection)
            ->paginate($perPage);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());
        $product->load('category');

        return (new ProductResource($product))
            ->additional(['message' => 'Product created successfully.'])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category');

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        $product->load('category');
        return (new ProductResource($product->fresh()))
            ->additional(['message' => 'Product updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->noContent();
    }
}