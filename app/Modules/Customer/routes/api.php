<?php

use App\Modules\Customer\Http\Controllers\Auth\LoginController;
use App\Modules\Customer\Http\Controllers\Auth\LogoutController;
use App\Modules\Customer\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterController::class)->middleware('throttle:20,1');
Route::post('/login', LoginController::class)->middleware('throttle:20,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', LogoutController::class);
});
