<?php

use Illuminate\Support\Facades\Route;

/**
 * Konteks EVENT ORGANIZER (internal). Prefix: eo/{tenant}, name: eo.*
 * Grup (auth + verified + role:eo) didefinisikan di PortalRoutes::eo().
 */
Route::get('/dashboard', function (string $tenant) {
    return view('eo.dashboard', ['tenant' => $tenant]);
})->name('dashboard');
