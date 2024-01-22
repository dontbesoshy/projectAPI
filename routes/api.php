<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
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

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('verifyToken/{token}', [\App\Http\Controllers\User\UserAccount\UserAccountController::class, 'verifyUserEmail'])
    ->name('verification.notice');

/*
|--------------------------------------------------------------------------
| Application
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::delete('logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');

    Route::apiResource('/users', \App\Http\Controllers\User\UserController::class);
});


