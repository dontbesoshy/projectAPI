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
        Route::post('users/{user}', [\App\Http\Controllers\User\AD\UserController::class, 'setNewPassword']);
    }
);
