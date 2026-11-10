# Demo Script — Pertemuan 4

1. **Demonstrasi Isolasi Global Scope & TenantContext**:
   ```bash
   php artisan test --filter=TenantIsolationTest
   ```
   Seluruh 10 skenario pengujian isolasi tenant dieksekusi secara otomatis, memvalidasi fail-closed, scoped binding, dan policy.

2. **Pemeriksaan Route Scoped Binding**:
   ```bash
   php artisan route:list --path=events-api
   ```
   Memperlihatkan parameter `{eo:slug}` terikat model `Tenant` dengan `scopeBindings()` aktif.

3. **Demonstrasi Eksekusi Queue Job Terisolasi**:
   Panggilan `CountTenantEvents` job membersihkan `TenantContext` melalui blok `finally`, mencegah context bleed pada worker daemon.

