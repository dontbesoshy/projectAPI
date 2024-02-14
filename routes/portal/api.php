<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Actions
|--------------------------------------------------------------------------
*/
Route::get('verifyToken/{token}', [\App\Http\Controllers\User\UserAccountController::class, 'verifyRegisterToken'])->name('verifyToken');
