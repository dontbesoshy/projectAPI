<?php

use App\Http\Controllers\CatalogImage\BO\CatalogImageController;
use App\Http\Controllers\Email\BO\EmailController;
use App\Http\Controllers\Image\BO\ImageController;
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
        Route::apiResource('users', UserController::class)->only(['index', 'store', 'destroy']);

        Route::post('users/{user}/setNewPassword', [UserController::class, 'setNewPassword']);
        Route::post('users/{user}/setNewLogin', [UserController::class, 'setNewLogin']);
        Route::post('users/clearCounterLogin', [UserController::class, 'clearCounterLogin']);

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
        | Images
        |--------------------------------------------------------------------------
        */
        Route::apiResource('images', ImageController::class)->only(['index', 'store', 'destroy']);
        Route::delete('images', [ImageController::class, 'destroy']);

        /*
        |--------------------------------------------------------------------------
        | Catalog image
        |--------------------------------------------------------------------------
        */
        Route::apiResource('catalogImages', CatalogImageController::class)->only(['index', 'store', 'destroy']);
        Route::delete('catalogImages', [CatalogImageController::class, 'destroy']);

        /*
        |--------------------------------------------------------------------------
        | Price list
        |--------------------------------------------------------------------------
        */
        Route::apiResource('priceLists', PriceListController::class);
        Route::post('priceLists/{priceList}/attachUser/{user}', [PriceListController::class, 'attachUser']);
    }
);
