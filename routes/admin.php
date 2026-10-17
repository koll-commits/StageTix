<?php

use Illuminate\Support\Facades\Route;

/**
 * Konteks PENGELOLA PLATFORM (internal). Prefix: admin, name: admin.*
 * Grup (auth + verified + role:admin) didefinisikan di PortalRoutes::admin().
 */
Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');
