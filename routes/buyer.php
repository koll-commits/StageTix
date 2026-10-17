<?php

use Illuminate\Support\Facades\Route;

/**
 * Konteks PEMBELI (login wajib). Prefix: akun, name: buyer.*
 * Grup (auth + verified + role:buyer) didefinisikan di PortalRoutes::buyer().
 */
Route::get('/pesanan', function () {
    return view('buyer.orders');
})->name('orders.index');
