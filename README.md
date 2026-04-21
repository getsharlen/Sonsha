# Sonsha - Fashion Item Rental System

Aplikasi peminjaman barang bertema fashion untuk kebutuhan UKK, dibangun dengan Laravel fullstack dan tampilan dinamis.

## Fitur Utama

1. Multi role: admin, petugas, peminjam.
2. Autentikasi: login, register, logout.
3. CRUD data master: kategori dan alat.
4. Operasional peminjaman: ajukan, approve, pengembalian.
5. Dashboard analytics admin: tren bulanan dan alat paling sering dipinjam.
6. Denda otomatis + saldo virtual user (mockup e-wallet).
7. Laporan teknis dapat dipreview dan diunduh PDF.

## Struktur Deliverable

1. Kode aplikasi Laravel: seluruh folder proyek.
2. SQL database lengkap: [database/sonsha_fashion_rental.sql](database/sonsha_fashion_rental.sql).
3. Laporan teknis (Blade): [resources/views/reports/technical.blade.php](resources/views/reports/technical.blade.php).
4. PDF laporan teknis hasil generate: [storage/app/public/laporan-teknis-peminjaman-fashion.pdf](storage/app/public/laporan-teknis-peminjaman-fashion.pdf).
5. Panduan alur fitur lengkap: [docs/PANDUAN_ALUR_FITUR.md](docs/PANDUAN_ALUR_FITUR.md).

## Persiapan Environment

1. PHP 8.2+.
2. Composer.
3. MySQL/MariaDB aktif (disarankan Laragon).
4. Node.js opsional, karena UI saat ini memakai CDN Tailwind dan Chart.js.

## Instalasi Cepat

1. Install dependency:

```bash
composer install
```

2. Siapkan file env:

```bash
copy .env.example .env
php artisan key:generate
```

3. Sesuaikan koneksi database di [.env](.env):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sonsha_fashion_rental
DB_USERNAME=root
DB_PASSWORD=
```

4. Jalankan migrasi dan seed:

```bash
php artisan migrate:fresh --seed
```

5. Jalankan server:

```bash
php artisan serve
```

## Akun Demo

1. admin@sonsha.test / password
2. petugas@sonsha.test / password
3. peminjam@sonsha.test / password

## Alur Fitur (Ringkas)

1. Login berdasarkan role, lalu diarahkan ke dashboard.
2. Admin/Petugas mengelola kategori dan alat.
3. Peminjam mengajukan peminjaman.
4. Petugas/Admin menyetujui pengajuan.
5. Pengembalian memicu perhitungan denda otomatis.
6. Jika saldo kurang, status user terkunci hingga top up dan pelunasan.
7. Admin melihat analytics dan mengekspor laporan teknis PDF.

Panduan rinci per langkah ada di [docs/PANDUAN_ALUR_FITUR.md](docs/PANDUAN_ALUR_FITUR.md).

## Pengujian Manual Minimal (5 Skenario)

1. Login user berhasil dan gagal.
2. Tambah data alat.
3. Pengajuan dan approval peminjaman.
4. Pengembalian dengan simulasi denda.
5. Verifikasi privilege antar role.

Checklist detail pengujian tersedia di [docs/PANDUAN_ALUR_FITUR.md](docs/PANDUAN_ALUR_FITUR.md).
