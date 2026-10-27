<?php

use Illuminate\Support\Facades\Route;

//Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');
Route::get('/dashboard', function () { 
    return 'Admin Platform Dashboard'; 
    })->name('dashboard');