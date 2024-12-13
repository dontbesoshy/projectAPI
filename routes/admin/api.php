<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => ['auth:sanctum', 'cors'],
        'prefix' => 'ad',
    ],
    function () {
        /*
        |--------------------------------------------------------------------------
        | Logout
        |--------------------------------------------------------------------------
        */
        Route::delete('logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');

        /*
        |--------------------------------------------------------------------------
        | Price list
        |--------------------------------------------------------------------------
        */
        Route::get('priceList', [\App\Http\Controllers\PriceList\AD\PriceListController::class, 'show']);
        Route::post('makeOrder', [\App\Http\Controllers\Order\AD\OrderController::class, 'store']);
        Route::post('generatePdf', [\App\Http\Controllers\Order\AD\OrderController::class, 'generatePdf']);
        Route::post('orders', [\App\Http\Controllers\Order\AD\OrderController::class, 'index']);
        Route::post('orders/{order}', [\App\Http\Controllers\Order\AD\OrderController::class, 'show']);
        Route::post('users/{user}', [\App\Http\Controllers\User\AD\UserController::class, 'setNewPassword']);
        Route::get('users/{user}/getFavoriteParts', [\App\Http\Controllers\User\AD\UserController::class, 'getFavoriteParts']);
        Route::post('users/{user}/syncFavoriteParts', [\App\Http\Controllers\User\AD\UserController::class, 'syncFavoriteParts']);

        Route::post('cart', [\App\Http\Controllers\Cart\AD\CartController::class, 'store']);
        Route::get('cartItems', [\App\Http\Controllers\Cart\AD\CartController::class, 'show']);

        /*
        |--------------------------------------------------------------------------
        | Promotions
        |--------------------------------------------------------------------------
        */
        Route::apiResource('promotions', \App\Http\Controllers\Promotion\AD\PromotionController::class)->only(['index']);

        /*
        |--------------------------------------------------------------------------
        | News
        |--------------------------------------------------------------------------
        */
        Route::apiResource('news', \App\Http\Controllers\News\AD\NewsController::class)->only(['index']);
    }
);
