# Known Issues & Architectural Notes — Pertemuan 4

## Aturan Bypass Global Scope
- Pemanggilan `withoutGlobalScope('tenant')` hanya diizinkan di dalam class repositori/query service terisolasi (contoh: `App\Modules\Catalog\Services\PublicCatalogQuery`).
- Setiap bypass scope **wajib** menyertakan filter pengganti eksplisit (misal filter status tenant aktif, status publikasi event, dan relasi venue).
- Dilarang keras menggunakan `withoutGlobalScope()` di dalam controller tenant atau aksi Livewire.

## Audit Logging Akses Lintas Tenant
- Akses lintas-tenant oleh admin platform (misal pada dashboard laporan konsolidasi) akan dicatat ke dalam tabel `audit_logs` oleh service yang dibangun pada Modul 5.

