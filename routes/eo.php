<?php

use Illuminate\Support\Facades\Route;
Route::get('/dashboard', function (string $eo) { 
    return 'Dashboard for EO: ' . $eo; 
})->name('dashboard');