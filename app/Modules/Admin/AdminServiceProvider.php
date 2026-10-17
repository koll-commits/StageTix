<?php

namespace App\Modules\Admin;

use App\Modules\ModuleServiceProvider;

/**
 * Modul: Admin (alias `admin`).
 * Tanggung jawab: kelola Event Organizer & komisi, venue & gate, verifikasi
 * pencairan dana (FR-ADM-01 s.d. FR-ADM-03). Pemilik route admin.*
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
