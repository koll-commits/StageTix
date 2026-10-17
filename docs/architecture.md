# Arsitektur: StageTix Multi-Tenant (Modular Monolith)

Satu aplikasi Laravel, satu database, satu deployment. Batas domain dibuat eksplisit di kode
(`app/Modules/*`), bukan lewat pemisahan proses. Diperkenalkan pada Pertemuan 2 (FR-EO-01, FR-BUY-02).

## Modul dan tanggung jawab

| Modul | Tanggung jawab | Kebutuhan SRS |
|---|---|---|
| `Admin` | Kelola Event Organizer & komisi, venue & gate, verifikasi pencairan dana. Pemilik route `admin.*`. | FR-ADM-01..03 |
| `Catalog` | Katalog event publik, event, kategori tiket, area/denah, tandai sold out. Pemilik route `public.*`. | FR-BUY-01, FR-BUY-04, FR-EO-02, FR-EO-03 |
| `Ordering` | Keranjang lintas EO, area hold, checkout, order induk & event_order, promo, lacak pesanan. Pemilik route `buyer.*`. | FR-BUY-03, 05..08 |
| `Payments` | Kontrak `PaymentGateway`, QRIS dinamis, webhook, settlement, split ledger, notifikasi. | FR-PAY-01..04 |
| `Checkin` | Check-in Gate System, validasi QR e-tiket, anti-duplikasi. | FR-EO-04 |
| `Reporting` | Laporan penjualan & ekspor, rekonsiliasi, penarikan dana. | FR-EO-05..07 |

## Struktur per modul (vertical slice)

```
app/Modules/{Modul}/
├── {Modul}ServiceProvider.php   <- extends App\Modules\ModuleServiceProvider
├── Http/Controllers/
├── Actions/  Data/  Services/
├── routes/{public,buyer,eo,admin}.php   <- dibungkus PortalRoutes::{portal}()
└── resources/views/             <- view('{alias}::...')
    └── livewire/                <- <livewire:{alias}::nama-komponen />
```

`ModuleServiceProvider::boot()` memuat otomatis `routes/*.php`, namespace view `{alias}::`,
dan namespace Livewire `{alias}::`. Provider terdaftar di `bootstrap/providers.php`.

**Shared kernel (lokasi standar Laravel):** model Eloquent `app/Models`, middleware
`app/Http/Middleware`, layout & komponen UI `resources/views/components`, dan `app/Support`.

## Aturan dependency

```
UI (routes, Livewire, Blade) -> Application (Actions/Services) -> Domain/Contracts
                                                                      ^
Infrastructure (Eloquent, HTTP, vendor) ------------------------------|
```

- Modul lain **tidak** mengakses controller/tabel internal modul lain secara langsung.
- Komunikasi lintas modul memakai **event** atau **interface/service** yang disepakati
  (mis. `Payments` memancarkan `OrderPaid`; `Ordering` menerbitkan e-tiket, `Reporting` mencatat).
- Framework tidak menegakkan batas ini; disiplin dijaga lewat review, provider, dan test.

## Routing per konteks

| Konteks | File | Prefix | Name | Middleware |
|---|---|---|---|---|
| Publik (katalog) | `routes/public.php` | `katalog` | `public.*` | `web` |
| Pembeli | `routes/buyer.php` | `akun` | `buyer.*` | `web, auth, verified, role:buyer` |
| Event Organizer | `routes/eo.php` | `eo/{tenant}` | `eo.*` | `web, auth, verified, role:eo` |
| Pengelola platform | `routes/admin.php` | `admin` | `admin.*` | `web, auth, verified, role:admin` |

Grup tiap portal didefinisikan **tunggal** di `App\Support\Routing\PortalRoutes`, dipakai oleh
`bootstrap/app.php` (route inti) dan oleh file route modul. Test `ModuleConventionTest` menjaga
invarian bahwa setiap route portal membawa middleware portalnya.

`{tenant}` saat ini masih string placeholder; model binding, `scopeBindings`, dan global scope
`tenant_id` (NFR-DAT-01..03, NFR-SEC-03) dipasang saat model Event Organizer tersedia.

## Authorization (server-side)

- `auth` menolak tamu; tidak cukup untuk isolasi antar-konteks/tenant.
- Middleware `role:{admin|eo|buyer}` (`EnsureUserHasRole`) menolak role/status yang tidak cocok dengan **403**.
- Menu yang disembunyikan hanya UX; setiap route internal tetap diuji terhadap akses langsung.
- Kontrak role interim: `users.role` + `users.status` + `User::hasRole()`. Policy dan relasi role
  penuh diformalkan pada pertemuan berikutnya.

## Layout dan komponen UI

- `x-layouts.public` (mobile-first, dipakai katalog dan halaman pembeli), `x-layouts.eo`
  (sidebar dapat diciutkan, Alpine), `x-layouts.admin` (tabel responsif `overflow-x-auto`).
- Komponen: `x-button`, `x-input`, `x-status-badge`, `x-empty-state`. Target sentuh >= 44px (`min-h-11`).
- Kontras teks minimal 4,5:1 (NFR-USA-05): gunakan `text-zinc-500` ke atas, hindari `text-zinc-400` di latar terang.

## Konvensi

- Namespace: `App\Modules\{Modul}\...`; alias view/Livewire = nama modul huruf kecil.
- Nama route: `{konteks}.{aksi}`, unik, dipakai controller/redirect/test/Blade.
- Blade `{{ }}` selalu ter-escape; `{!! !!}` hanya untuk HTML tersanitasi.
- Blade/SFC di `app/Modules/*/resources` dikecualikan dari PHPStan (dicakup test).