<?php

use Illuminate\Support\Facades\Route;

/**
 * Konteks PUBLIK (tanpa login). Prefix: katalog, name: public.*
 * Grup didefinisikan di PortalRoutes::publicSite().
 */
Route::get('/', function () {
    return view('public.home');
})->name('home');
