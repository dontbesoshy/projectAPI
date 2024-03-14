<?php

use App\Http\Controllers\MainPhoto\PO\MainPhotoController;
use App\Http\Controllers\User\UserAccountController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Actions
|--------------------------------------------------------------------------
*/
Route::get('verifyToken/{token}', [UserAccountController::class, 'verifyRegisterToken'])->name('verifyToken');

/*
|--------------------------------------------------------------------------
| Main photo
|--------------------------------------------------------------------------
*/
Route::apiResource('mainPhotos', MainPhotoController::class)->only(['index']);
