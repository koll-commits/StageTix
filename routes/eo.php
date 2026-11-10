<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function (string $eo) { 
    return view('eo.dashboard', ['eo' => $eo]);
})->name('dashboard');
