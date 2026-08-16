<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\ChangePasswordController;
use App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V1\Auth\ProfileController;
use App\Http\Controllers\Api\V1\Auth\ResetPasswordController;
use App\Http\Controllers\Api\V1\Cart\CartController;
use App\Http\Controllers\Api\V1\Cart\CartItemController;
use App\Http\Controllers\Api\V1\Products\CategoryController;
use App\Http\Controllers\Api\V1\Products\ProductController;
use App\Http\Controllers\Api\V1\Orders\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\AdminCategoryController;
use App\Http\Controllers\Api\V1\Admin\AdminProductController;
use App\Http\Controllers\Api\V1\Admin\AdminOrderController;

// Public
Route::prefix('v1')->group(function () {
    Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);

    Route::get('cart', [CartController::class, 'show']);
    Route::delete('cart', [CartController::class, 'destroy']);
    Route::post('cart/items', [CartItemController::class, 'store']);
    Route::patch('cart/items/{cartItem}', [CartItemController::class, 'update']);
    Route::delete('cart/items/{cartItem}', [CartItemController::class, 'destroy']);

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [ForgotPasswordController::class, 'store'])->name('forgot-password');
    Route::post('reset-password', [ResetPasswordController::class, 'update'])->name('reset-password');
});

// Protected (Bearer token required)
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {


    Route::prefix('v1')->group(function () {

        // Profile
        Route::get('profile', [ProfileController::class, 'show']);
        Route::patch('profile', [ProfileController::class, 'update']);
        Route::delete('profile', [ProfileController::class, 'destroy']);

        // Auth
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('change-password', [ChangePasswordController::class, 'update'])->name('change-password');


        // Orders
        Route::post('orders', [OrderController::class, 'store']);
        Route::get('orders', [OrderController::class, 'index']);
        Route::get('orders/{order}', [OrderController::class, 'show']);
    });
});



// admin
Route::middleware(['auth:sanctum', 'admin', 'throttle:api'])
    ->prefix('v1/admin')
    ->group(function () {
        Route::apiResource('categories', AdminCategoryController::class);
        Route::apiResource('products', AdminProductController::class);
        Route::apiResource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);
    });
