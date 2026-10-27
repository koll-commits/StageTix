<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::get('/', function () {
    return redirect()->route('events.index');
});

// Resource route untuk Event Organizer (Tenant Admin)
Route::prefix('tenant')->group(function () {
    Route::resource('events', EventController::class);
});