<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => ['auth:sanctum'],
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
    }
);
