# StageTix

Aplikasi pemesanan tiket acara panggung (konser, teater, pertunjukan) berbasis Laravel.

> Sesuaikan deskripsi, fitur, dan tangkapan layar di bawah ini dengan kondisi proyekmu.

## Fitur

- Daftar acara dan detail acara
- Pemesanan tiket dan pemilihan kursi
- Pencegahan pemesanan ganda pada kursi yang sama
- Autentikasi pengguna (daftar, masuk, keluar)
- Panel admin untuk mengelola acara dan tiket *(rencana)*

## Teknologi

- [Laravel](https://laravel.com) 13
- PHP 8.4+ (cek `composer.json` untuk versi pastinya)
- MySQL / MariaDB
- Composer
- Node.js dan npm (jika memakai Vite untuk aset frontend)

## Prasyarat

Pastikan sudah terpasang:

- PHP dan Composer
- Database MySQL/MariaDB (misalnya lewat ServBay atau XAMPP)
- Git
- Node.js dan npm

## Instalasi

```bash
# 1. Clone repo
git clone https://github.com/USERNAME/StageTix.git
cd StageTix

# 2. Pasang dependensi PHP
composer install

# 3. Salin file environment
cp .env.example .env      # Windows PowerShell: copy .env.example .env

# 4. Buat application key
php artisan key:generate
```

## Konfigurasi Database

1. Buat database kosong, misalnya `stagetix`.
2. Buka file `.env` dan sesuaikan:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stagetix
DB_USERNAME=root
DB_PASSWORD=
```

3. Jalankan migrasi (dan seeder jika tersedia):

```bash
php artisan migrate
php artisan db:seed
```

## Menjalankan Aplikasi

```bash
# Aset frontend (jika ada)
npm install
npm run dev

# Server pengembangan
php artisan serve
```

Buka `http://localhost:8000` di browser.

## Menjalankan Test

```bash
php artisan test
```

## Struktur Singkat

```
app/            Model, controller, dan logika aplikasi
database/       Migration, factory, seeder
resources/      View (Blade) dan aset frontend
routes/         Definisi route (web.php, dll.)
```

## Kontribusi

Kontribusi sangat diterima.

1. Fork repo ini
2. Buat branch fitur: `git checkout -b feat/nama-fitur`
3. Commit perubahan: `git commit -m "feat: deskripsi singkat"`
4. Push ke branch: `git push origin feat/nama-fitur`
5. Buka Pull Request

Gunakan format [Conventional Commits](https://www.conventionalcommits.org/) untuk pesan commit.

## Lisensi

Proyek ini dirilis di bawah lisensi [MIT](LICENSE).
Ganti bagian ini jika kamu memilih lisensi lain.
