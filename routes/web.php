<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return 'login page';
})->name('login');


Route::prefix('tenant')->group(function () {
    Route::resource('events', EventController::class);
});