<?php

namespace App\Modules\Catalog;

use App\Modules\ModuleServiceProvider;

/**
 
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
