<?php

namespace App\Modules\Checkin;

use App\Modules\ModuleServiceProvider;

/**
 * Modul: Checkin (alias `checkin`).
 * Tanggung jawab: Check-in Gate System, validasi QR e-tiket, cegah tiket
 * dipakai ganda, catat waktu & gate (FR-EO-04).
 */
final class CheckinServiceProvider extends ModuleServiceProvider
{
    public function register(): void
    {
        // Binding kontrak -> implementasi ditambahkan saat modul diimplementasikan.
    }

    protected function moduleAlias(): string
    {
        return 'checkin';
    }
}
