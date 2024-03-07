<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WishlistController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::controller(App\Http\Controllers\HomeController::class)->group(function () {
    Route::get('/','index')->name('landing-page');
    Route::get('/pd/{slug}', 'productDetail')->name('product_detail');
    Route::get('/products', 'products')->name('products');
});

Route::controller(App\Http\Controllers\AuthController::class)->group(function () {
    Route::get('/logout','logout')->name('logout');
    Route::post('/login', 'login')->name('login');
    Route::post('/signup', 'signup')->name('signup');
    Route::post('/forgot', 'forgot')->name('forgot');
    Route::match(['GET','POST'],'/reset', 'reset')->name('reset');
});

Route::controller(App\Http\Controllers\AccountController::class)->group(function () {

    Route::prefix('account')->group(function (){
        Route::get('orders/{id}', 'showOrder')->name('order.show');
        Route::get('address', 'newAddress')->name('address.create');
        Route::post('address', 'newAddress')->name('address.store');
        Route::get('address/{id}', 'editAddress')->name('address.edit');
        Route::put('address/{id}', 'editAddress')->name('address.update');
    });

    Route::get('/account','index')->name('account.index');
    Route::post('/account','index')->name('account.index');
});

Route::controller(App\Http\Controllers\CartController::class)->group(function () {
    Route::get('/cart','index')->name('cart');
    Route::get('/cart/products','apiCartProducts');
    Route::post('/cart/coupon','apiApplyCoupon');
});

Route::controller(App\Http\Controllers\WishlistController::class)->group(function () {
    Route::get('/wishlist', 'index')->name('wishlist');
    Route::post('/wishlist/{id}', 'toggle');
});

Route::get('/about', function () {
    return view('about_us');
})->name('about-us');

Route::get('/privacy-policy', function () {
    return view('privacy_policy');
})->name('privacy-policy');

Route::get('/terms-conditions', function () {
    return view('terms_conditions');
})->name('terms-conditions');