<?php

namespace App\Modules\Catalog;

use App\Modules\ModuleServiceProvider;

/**
 * Modul: Catalog (alias `catalog`).
 * Tanggung jawab: katalog event publik, event, kategori tiket, area/denah,
 * tandai sold out (FR-BUY-01, FR-BUY-04, FR-EO-02, FR-EO-03).
 */
final class CatalogServiceProvider extends ModuleServiceProvider
{
    public function register(): void
    {
        // Binding kontrak -> implementasi ditambahkan saat modul diimplementasikan.
    }

    protected function moduleAlias(): string
    {
        return 'catalog';
    }
}
