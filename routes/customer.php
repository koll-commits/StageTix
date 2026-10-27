<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Customer Portal StageTix';
})->name('home');