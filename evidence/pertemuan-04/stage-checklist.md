# Stage Checklist — Pertemuan 4: Isolasi Multi-Tenant

| Checkpoint | Status | Keterangan |
|---|---|---|
| **Tahap 1** Definisikan konteks dan aturan akses | ✅ Selesai | Setiap bypass mempunyai filter pengganti dan alasan eksplisit. |
| **Tahap 2** Implementasikan TenantContext request-scoped | ✅ Selesai | `TenantContext` didaftarkan `scoped()` di `AppServiceProvider`, job queue membawa `tenantId`. |
| **Tahap 3** Middleware resolver dan role check | ✅ Selesai | `SetTenantContext` memeriksa slug `{eo:slug}`, validasi status aktif, verifikasi keanggotaan `UserTenantRole`, fail-closed 403/404. |
| **Tahap 4** Global scope dan model integration | ✅ Selesai | Trait `BelongsToTenant` menambahkan global scope `eo_id` dan auto-fill saat `creating`. Model tenant-owned memuat trait ini. |
| **Tahap 5** Policy dan scoped route binding | ✅ Selesai | `EventPolicy`, `EventOrderPolicy`, `WithdrawalPolicy`, `scopeBindings()` pada `PortalRoutes::eo()`, controller `TenantEventController`. |
| **Tahap 6** Public query dan bypass terkontrol | ✅ Selesai | `PublicCatalogQuery` melakukan `withoutGlobalScope('tenant')` secara eksplisit dan menggantinya dengan filter venue & active tenant. |
| **Tahap 7** Security tests dan commit | ✅ Selesai | `TenantIsolationTest` (10 passed) dan seluruh test suite lulus (43 passed, 171 assertions). |

