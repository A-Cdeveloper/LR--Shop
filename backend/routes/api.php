<?php

use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CartItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::prefix('v1')->group(function () {
    Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);


    Route::get('cart', [CartController::class, 'show']);
    Route::post('cart/items', [CartItemController::class, 'store']);
    Route::patch('cart/items/{cartItem}', [CartItemController::class, 'update']);
    Route::delete('cart/items/{cartItem}', [CartItemController::class, 'destroy']);
});