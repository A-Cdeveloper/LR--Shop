<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Http\Resources\Products\CategoryResource;
use App\Models\Category;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = (int) request()->query('per_page', 10);
        $perPage = max(1, min($perPage, 50));
        $sortField = request()->query('sort');
        $sortDirection = request()->query('order');

        $categories = Category::query()
            ->withCount('products')
            ->sortBy($sortField, $sortDirection)
            ->paginate($perPage);

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return (new CategoryResource($category->loadCount('products')))
            ->additional(['message' => __('api.admin.category_created')])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new CategoryResource($category->loadCount('products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return (new CategoryResource($category->fresh()->loadCount('products')))
            ->additional(['message' => __('api.admin.category_updated')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return response()->json([
                'message' => __('api.admin.category_delete_has_products'),
            ], 422);
        }

        $category->delete();

        return response()->noContent();
    }
}
