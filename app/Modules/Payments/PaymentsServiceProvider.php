<?php

namespace App\Modules\Payments;

use App\Modules\ModuleServiceProvider;

/**
 * Modul: Payments (alias `payments`).
 * Tanggung jawab: kontrak PaymentGateway, QRIS dinamis, webhook, settlement,
 * split payment/ledger, notifikasi (FR-PAY-01 s.d. FR-PAY-04).
 */
final class PaymentsServiceProvider extends ModuleServiceProvider
{
    public function register(): void
    {
        // Binding kontrak -> implementasi ditambahkan saat modul diimplementasikan.
    }

    protected function moduleAlias(): string
    {
        return 'payments';
    }
}
