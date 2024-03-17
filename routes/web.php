<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Landing page
Route::get('/', [HomeController::class, 'index'])->name('landing-page');

// Product routes
Route::get('/pd/{slug}', [HomeController::class, 'productDetail'])->name('product_detail');
Route::get('/products', [HomeController::class, 'products'])->name('products');

// Authentication routes
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/forgot', [AuthController::class, 'forgot'])->name('forgot');
Route::match(['GET', 'POST'], '/reset', [AuthController::class, 'reset'])->name('reset');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Account routes
Route::group(['prefix' => 'account'], function () {
    Route::get('/', [AccountController::class, 'index'])->name('account.index');
    Route::post('/', [AccountController::class, 'index'])->name('account.index');
    Route::get('orders/{id}', [AccountController::class, 'showOrder'])->name('order.show');
    Route::get('address', [AccountController::class, 'newAddress'])->name('address.create');
    Route::post('address', [AccountController::class, 'newAddress'])->name('address.store');
    Route::get('address/{id}', [AccountController::class, 'editAddress'])->name('address.edit');
    Route::put('address/{id}', [AccountController::class, 'editAddress'])->name('address.update');
});

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/cart/products', [CartController::class, 'apiCartProducts']);
Route::post('/cart/coupon', [CartController::class, 'apiApplyCoupon']);

// Wishlist routes
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
Route::post('/wishlist/{id}', [WishlistController::class, 'toggle']);

// Static pages routes
Route::view('/about', 'about_us')->name('about-us');
Route::view('/privacy-policy', 'privacy_policy')->name('privacy-policy');
Route::view('/terms-conditions', 'terms_conditions')->name('terms-conditions');

// Payment routes
Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
Route::post('/payment/create', [PaymentController::class, 'create'])->name('payment.create');

// Account deletion route
Route::delete('/account/delete', [AccountController::class, 'deleteAccount'])->name('account.delete');
// Address deletion route
Route::delete('/account/address/{id}', [AccountController::class, 'deleteAddress'])->name('address.delete');

Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('cart.add');

