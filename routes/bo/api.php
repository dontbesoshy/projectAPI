<?php

use App\Http\Controllers\User\BO\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Users
|--------------------------------------------------------------------------
*/
Route::group(
    [
        'middleware' => ['auth:sanctum', 'verified'],
        'prefix' => 'bo',
    ],
    function () {
        Route::apiResource('users', UserController::class)->only(['index']);
});
