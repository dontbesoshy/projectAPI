<?php

use App\Http\Controllers\Email\BO\EmailController;
use App\Http\Controllers\MainPhoto\BO\MainPhotoController;
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
        | Emails
        |--------------------------------------------------------------------------
        */
        Route::apiResource('emails', EmailController::class)->only(['index', 'store', 'destroy']);

        /*
        |--------------------------------------------------------------------------
        | Main photo
        |--------------------------------------------------------------------------
        */
        Route::apiResource('mainPhotos', MainPhotoController::class)->only(['index', 'store']);

        /*
        |--------------------------------------------------------------------------
        | Price list
        |--------------------------------------------------------------------------
        */
        Route::apiResource('priceLists', PriceListController::class);
        Route::post('priceLists/{priceList}/attachUser/{user}', [PriceListController::class, 'attachUser']);
    }
);
