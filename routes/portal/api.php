<?php

use App\Http\Controllers\Config\PO\ConfigController;
use App\Http\Controllers\MainPhoto\PO\MainPhotoController;
use App\Http\Controllers\User\UserAccountController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Actions
|--------------------------------------------------------------------------
*/
Route::get('verifyToken/{token}', [UserAccountController::class, 'verifyRegisterToken'])->name('verifyToken');
Route::post('forgotPassword', [UserAccountController::class, 'forgotPassword'])->name('forgotPassword');

/*
|--------------------------------------------------------------------------
| Main photo
|--------------------------------------------------------------------------
*/
Route::apiResource('mainPhotos', MainPhotoController::class)->only(['index']);

/*
|--------------------------------------------------------------------------
| Config
|--------------------------------------------------------------------------
*/
Route::apiResource('configs', ConfigController::class)->only(['index']);
