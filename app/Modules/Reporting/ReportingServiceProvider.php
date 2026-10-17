<?php

namespace App\Modules\Reporting;

use App\Modules\ModuleServiceProvider;

/**
 * Modul: Reporting (alias `reporting`).
 * Tanggung jawab: laporan penjualan & ekspor, rekonsiliasi bagi hasil,
 * pengajuan penarikan dana (FR-EO-05, FR-EO-06, FR-EO-07).
 */
final class ReportingServiceProvider extends ModuleServiceProvider
{
    public function register(): void
    {
        // Binding kontrak -> implementasi ditambahkan saat modul diimplementasikan.
    }

    protected function moduleAlias(): string
    {
        return 'reporting';
    }
}
