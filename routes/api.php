<?php

use Illuminate\Support\Facades\Route;

require __DIR__ . '/bo/api.php';
require __DIR__ . '/admin/api.php';
require __DIR__ . '/portal/api.php';

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

Route::apiResource('users', \App\Http\Controllers\User\PO\UserController::class)->only('store');
