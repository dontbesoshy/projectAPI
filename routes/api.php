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

Route::resource('user', \App\Http\Controllers\User\PO\UserController::class)->only('store');
Route::get('verifyToken/{token}', [\App\Http\Controllers\User\UserAccountController::class, 'verifyRegisterToken'])->name('verifyToken');

/*
|--------------------------------------------------------------------------
| Application
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::delete('logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');
});


