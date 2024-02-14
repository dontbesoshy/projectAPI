<?php

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
    }
);
