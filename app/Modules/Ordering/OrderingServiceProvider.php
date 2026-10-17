<?php

namespace App\Modules\Ordering;

use App\Modules\ModuleServiceProvider;

/**
 * Modul: Ordering (alias `ordering`).
 * Tanggung jawab: keranjang lintas EO, area hold, checkout, order induk &
 * event_order, promo, lacak pesanan & e-tiket (FR-BUY-03, 05, 06, 07, 08).
 * Pemilik route buyer.*
 */
final class OrderingServiceProvider extends ModuleServiceProvider
{
    public function register(): void
    {
        // Binding kontrak -> implementasi ditambahkan saat modul diimplementasikan.
    }

    protected function moduleAlias(): string
    {
        return 'ordering';
    }
}
