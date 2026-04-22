<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Teknis Peminjaman Fashion</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; font-size: 12px; line-height: 1.5; }
        h1, h2, h3 { margin: 0 0 10px; }
        .section { margin-bottom: 22px; }
        .box { border: 1px solid #cbd5e1; border-radius: 8px; padding: 12px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; vertical-align: top; }
        th { background: #e2e8f0; }
        .small { font-size: 10px; color: #475569; }
    </style>
</head>
<body>
    <h1>Laporan Teknis Proyek Peminjaman Barang Fashion</h1>
    <p class="small">Dokumen ringkas untuk deliverable UKK. Project stack: Laravel fullstack dengan view dinamis, analytics, dan denda otomatis.</p>

    <div class="section">
        <h2>1. Daftar Fitur Berdasarkan Level Pengguna</h2>
        <div class="box">
            <strong>Admin</strong><br>
            Login/logout, CRUD user, alat, kategori, peminjaman, pengembalian, monitoring log, analytics dashboard, laporan, dan pengelolaan saldo/denda.
        </div>
        <div class="box">
            <strong>Petugas</strong><br>
            Login/logout, melihat alat operasional, menyetujui peminjaman, memantau pengembalian, dan membantu pelaporan.
        </div>
        <div class="box">
            <strong>Peminjam</strong><br>
            Login/logout, melihat daftar alat, mengajukan peminjaman, mengembalikan alat, dan memantau saldo/denda.
        </div>
    </div>

    <div class="section">
        <h2>2. Minimum Aspek Teknis</h2>
        <table>
            <tr><th>Aspek</th><th>Implementasi</th></tr>
            <tr><td>Metode</td><td>Prototype / Waterfall sederhana</td></tr>
            <tr><td>Basis Data</td><td>ERD relasional untuk users, categories, assets, borrowings, borrowing_items, activity_logs, fine_payments.</td></tr>
            <tr><td>SQL</td><td>Menyediakan trigger, stored procedure, function, dan transaksi commit/rollback pada file .sql.</td></tr>
            <tr><td>Optimasi</td><td>Pagination, limit pada query dashboard, dan minimasi looping yang tidak perlu.</td></tr>
            <tr><td>Flow</td><td>Login, peminjaman, pengembalian, serta perhitungan denda didokumentasikan sebagai alur sistem.</td></tr>
        </table>
    </div>

    <div class="section">
        <h2>3. Dashboard Analytics</h2>
        <p>Admin melihat grafik tren peminjaman per bulan dan daftar alat paling sering dipinjam berdasarkan tabel borrowings dan borrowing_items.</p>
    </div>

    <div class="section">
        <h2>4. Manajemen Denda Otomatis & Saldo</h2>
        <p>Jika pengembalian terlambat, sistem menghitung denda otomatis. Saldo virtual user dipakai untuk mockup e-wallet. Status user dapat dikunci jika denda belum lunas.</p>
    </div>

    <div class="section">
        <h2>5. Test Case Minimum</h2>
        <table>
            <tr><th>No</th><th>Skenario</th><th>Hasil yang Diharapkan</th></tr>
            <tr><td>1</td><td>Login user</td><td>User masuk sesuai role dan diarahkan ke dashboard.</td></tr>
            <tr><td>2</td><td>Penambahan data alat</td><td>Data alat tersimpan dan tampil di daftar alat.</td></tr>
            <tr><td>3</td><td>Proses peminjaman</td><td>Permintaan dibuat, disetujui, dan stok berkurang.</td></tr>
            <tr><td>4</td><td>Pengembalian dengan denda</td><td>Denda dihitung otomatis, saldo terpotong, dan status user menyesuaikan.</td></tr>
            <tr><td>5</td><td>Pemeriksaan privilege</td><td>Role tidak berwenang ditolak saat akses halaman khusus.</td></tr>
        </table>
    </div>

    <div class="section">
        <h2>6. Deliverables</h2>
        <p>Kode program tersedia dalam folder proyek Laravel, database disediakan dalam file .sql, dokumentasi teknis dirangkum pada laporan ini, dan hasil evaluasi dapat ditulis sebagai lampiran akhir.</p>
    </div>
</body>
</html>
