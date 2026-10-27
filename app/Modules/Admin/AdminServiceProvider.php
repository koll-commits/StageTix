<?php

namespace App\Modules\Admin;

use App\Modules\ModuleServiceProvider;

/**
 
 */
final class AdminServiceProvider extends ModuleServiceProvider
{
    public function register(): void
    {
        // Binding kontrak -> implementasi ditambahkan saat modul diimplementasikan.
    }

    protected function moduleAlias(): string
    {
        return 'admin';
    }
}
