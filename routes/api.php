<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

Route::post('authenticate', [\App\Http\Controllers\Auth\AuthController::class, 'authenticate'])->name('authenticate');

/*
|--------------------------------------------------------------------------
| User - Register
|--------------------------------------------------------------------------
*/

Route::resource('user', \App\Http\Controllers\User\UserController::class)->only('store');

/*
|--------------------------------------------------------------------------
| Application
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::delete('logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');

    Route::apiResource('/users', \App\Http\Controllers\User\UserController::class);
});


