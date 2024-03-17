<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\WishlistController;
use App\Http\Controllers\API\CartController;

// Public routes
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/signup', [UserController::class, 'signup'])->name('signup');

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/add', [CartController::class, 'store']);
Route::put('/cart/update/{id}', [CartController::class, 'update']);
Route::delete('/cart/remove/{id}', [CartController::class, 'destroy']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User management
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [UserController::class, 'logout']);
    Route::put('/user/profile', [UserController::class, 'updateProfile']);
    Route::delete('/user/profile', [UserController::class, 'deleteProfile']);

    Route::post('/user/addresses', [UserController::class, 'createAddress']);
    Route::get('/user/addresses', [UserController::class, 'getAddresses']);
    Route::put('/user/addresses/{id}', [UserController::class, 'updateAddress']);
    Route::delete('/user/addresses/{id}', [UserController::class, 'deleteAddress']);

    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist/toggle/{id}', [WishlistController::class, 'toggle']);

});