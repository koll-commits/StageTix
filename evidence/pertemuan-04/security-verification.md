# Security Verification — Pertemuan 4: Pertahanan Berlapis Multi-Tenant

## Matriks Allow / Deny

| Kasus Uji | Aktor / Konteks | Target Resource | Hasil yang Diharapkan | Status |
|---|---|---|---|---|
| Query Index internal | Operator Tenant A | Daftar event | Hanya menampilkan record dengan `eo_id` = Tenant A | ✅ Lolos (403/200 scoped) |
| Detail Show via URL | Operator Tenant A | Event milik Tenant B | HTTP 404 Not Found (Scoped Route Binding + Global Scope) | ✅ Lolos |
| Update via API | Operator Tenant A | Event milik Tenant B | Policy / Binding menolak aksi (404/403) | ✅ Lolos |
| Policy Check langsung | User Tenant A | Model Tenant B | `EventPolicy::update` mengembalikan `false` | ✅ Lolos |
| Akses Dashboard | Admin / Non-member | Tenant A | HTTP 403 Forbidden | ✅ Lolos |
| Akses Dashboard | Operator suspended | Tenant A | HTTP 403 Forbidden | ✅ Lolos |
| Akses Dashboard | Operator aktif | Tenant A (suspended) | HTTP 403 Forbidden | ✅ Lolos |
| Katalog Publik | Customer / Guest | Venue 1 | Hanya menampilkan event published milik tenant aktif di Venue 1 | ✅ Lolos |
| Background Job Queue | Worker proses | Job Tenant A lalu Tenant B | Context dibersihkan di `finally`; tidak ada kontaminasi antar job | ✅ Lolos |

