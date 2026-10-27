<?php

namespace App\Modules\Ordering;

use App\Modules\ModuleServiceProvider;

/**
 
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
