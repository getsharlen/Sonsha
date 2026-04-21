# Panduan Lengkap Per Alur Fitur

Dokumen ini menjelaskan alur operasional aplikasi Sonsha dari sisi pengguna, endpoint, aksi sistem, dan hasil yang diharapkan.

## 1. Alur Login dan Logout

### Tujuan
Memastikan autentikasi berjalan sesuai role user.

### Langkah
1. Buka halaman login: /login.
2. Masukkan email dan password.
3. Klik Login.
4. Sistem validasi kredensial.
5. Jika valid, user diarahkan ke /dashboard.
6. Jika tidak valid, tampil error di form.
7. Logout dilakukan melalui tombol Logout di layout utama.

### Dampak Sistem
1. Membuat log aktivitas module auth action login/logout.
2. Menyimpan sesi login user.

### Endpoint
1. GET /login
2. POST /login
3. POST /logout

## 2. Alur Manajemen Kategori (Admin/Petugas)

### Tujuan
Mengelola klasifikasi alat agar data terstruktur.

### Langkah
1. Masuk ke halaman /categories.
2. Tambah kategori baru melalui form.
3. Lihat daftar kategori pada panel kanan.
4. Klik Edit untuk ubah nama/deskripsi kategori.
5. Klik Hapus untuk menghapus kategori tanpa relasi alat.

### Validasi Penting
1. Nama kategori wajib diisi.
2. Kategori yang masih punya data alat tidak bisa dihapus.

### Endpoint
1. GET /categories
2. POST /categories
3. PUT /categories/{category}
4. DELETE /categories/{category}

## 3. Alur Manajemen Alat (Admin/Petugas)

### Tujuan
Mengelola inventaris alat fashion dan teknis.

### Langkah
1. Buka halaman /assets.
2. Isi form tambah alat: kategori, nama, stok, tarif, kondisi.
3. Simpan data alat.
4. Pada tabel daftar alat:
5. Gunakan tombol Detail untuk melihat informasi lengkap.
6. Gunakan tombol Edit untuk memperbarui data.
7. Gunakan tombol Hapus untuk menghapus alat yang belum punya histori peminjaman.

### Validasi Penting
1. Stok dan tarif tidak boleh negatif.
2. Alat yang sudah punya histori borrowing_items tidak bisa dihapus.

### Endpoint
1. GET /assets
2. POST /assets
3. PUT /assets/{asset}
4. DELETE /assets/{asset}

## 4. Alur Pengajuan Peminjaman

### Tujuan
Mencatat permintaan peminjaman dari peminjam.

### Aktor
1. Peminjam.
2. Admin/Petugas (boleh membuatkan untuk peminjam lain).

### Langkah
1. Buka halaman /borrowings.
2. Pilih peminjam (khusus admin/petugas).
3. Pilih alat, isi quantity, purpose, due date.
4. Klik Buat Pengajuan.
5. Sistem membuat transaksi status requested.

### Validasi Penting
1. Akun peminjam status locked tidak boleh mengajukan.
2. Quantity tidak boleh melebihi stok tersedia.

### Endpoint
1. GET /borrowings
2. POST /borrowings

## 5. Alur Persetujuan Peminjaman

### Tujuan
Menyetujui pengajuan dan memotong stok alat.

### Aktor
1. Admin.
2. Petugas.

### Langkah
1. Pada daftar peminjaman, temukan status requested.
2. Klik Approve.
3. Sistem memeriksa stok setiap item.
4. Stok tersedia dikurangi sesuai quantity.
5. Status berubah menjadi borrowed, borrowed_at terisi.

### Endpoint
1. POST /borrowings/{borrowing}/approve

## 6. Alur Pengembalian dan Denda Otomatis

### Tujuan
Memproses pengembalian alat dan otomatisasi denda.

### Langkah
1. Pada transaksi status approved/borrowed, klik Proses Pengembalian.
2. Sistem hitung keterlambatan berdasarkan due_at.
3. Rumus denda saat ini: hari terlambat x Rp5.000.
4. Sistem menambah kembali stok alat.
5. Jika terlambat:
6. Sistem memotong saldo user jika cukup.
7. Jika saldo tidak cukup, membuat fine_payments status pending.
8. Status user berubah locked sampai denda lunas.

### Endpoint
1. POST /borrowings/{borrowing}/return

## 7. Alur Top Up Saldo (E-Wallet Mockup)

### Tujuan
Melunasi denda pending dan membuka akun terkunci.

### Langkah
1. Pada dashboard, isi nominal top up.
2. Klik Top Up.
3. Sistem menambah saldo user.
4. Sistem mencoba membayar fine_payments pending sesuai urutan.
5. Jika pending tersisa, akun tetap locked.
6. Jika seluruh denda lunas, akun active kembali.

### Endpoint
1. POST /wallet/top-up

## 8. Alur Dashboard Analytics (Admin)

### Tujuan
Memberikan insight operasional peminjaman.

### Data yang Ditampilkan
1. Ringkasan total user, alat, transaksi, open borrowing.
2. Grafik tren peminjaman per bulan.
3. Top 5 alat paling sering dipinjam.
4. Aktivitas terbaru sistem.

### Query Kunci
1. Agregasi monthly dari kolom borrowed_at.
2. SUM quantity di borrowing_items per asset.

### Endpoint
1. GET /dashboard

## 9. Alur Laporan Teknis PDF

### Tujuan
Menyediakan dokumen penilaian UKK siap unduh.

### Langkah
1. Buka /reports/technical untuk preview.
2. Buka /reports/technical/pdf untuk download PDF.

### Endpoint
1. GET /reports/technical
2. GET /reports/technical/pdf

## 10. Checklist Uji Coba Minimal 5 Skenario

### Skenario 1 - Login User
1. Input kredensial valid.
2. Hasil: masuk dashboard.

### Skenario 2 - Tambah Data Alat
1. Tambah alat baru di /assets.
2. Hasil: data muncul di tabel.

### Skenario 3 - Proses Peminjaman
1. Buat pengajuan di /borrowings.
2. Approve sebagai petugas/admin.
3. Hasil: status borrowed, stok berkurang.

### Skenario 4 - Pengembalian Dengan Denda
1. Ubah due_at agar lewat waktu atau gunakan data demo terlambat.
2. Klik Proses Pengembalian.
3. Hasil: denda terhitung, fine payment tercatat.

### Skenario 5 - Hak Akses User
1. Login sebagai peminjam.
2. Coba akses /assets atau /categories.
3. Hasil: akses ditolak (403) karena middleware role.

## 11. Ringkasan Query SQL Penting

1. Function: fn_calculate_fine.
2. Procedure: sp_create_borrowing.
3. Procedure: sp_return_borrowing.
4. Trigger: trg_borrowings_lock_user_after_update.
5. Transaksi: START TRANSACTION, COMMIT, ROLLBACK.

File SQL lengkap: database/sonsha_fashion_rental.sql
