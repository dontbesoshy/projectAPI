<?php

use App\Http\Controllers\PriceList\BO\PriceListController;
use App\Http\Controllers\User\BO\UserController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => ['auth:sanctum', 'verified'],
        'prefix' => 'bo',
    ],
    function () {
        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */
        Route::apiResource('users', UserController::class)->only(['index', 'store']);

        /*
        |--------------------------------------------------------------------------
        | Price list
        |--------------------------------------------------------------------------
        */
        Route::apiResource('priceLists', PriceListController::class)->only(['index', 'store', 'destroy']);
        Route::post('priceLists/{priceList}/attachUser/{user}', [PriceListController::class, 'attachUser']);
    }
);
