<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Asset;
use App\Models\Borrowing;
use App\Models\BorrowingItem;
use App\Models\Category;
use App\Models\FinePayment;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->delete();
        Category::query()->delete();

        $admin = User::create([
            'name' => 'Alya Putri',
            'email' => 'admin@sonsha.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081200000001',
            'balance' => 250000,
        ]);

        $petugas = User::create([
            'name' => 'Raka Pratama',
            'email' => 'petugas@sonsha.test',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'phone' => '081200000002',
            'balance' => 100000,
        ]);

        $peminjam = User::create([
            'name' => 'Nadia Salsabila',
            'email' => 'peminjam@sonsha.test',
            'password' => Hash::make('password'),
            'role' => 'peminjam',
            'phone' => '081200000003',
            'balance' => 40000,
        ]);

        $peminjam2 = User::create([
            'name' => 'Dian Maharani',
            'email' => 'dian@sonsha.test',
            'password' => Hash::make('password'),
            'role' => 'peminjam',
            'phone' => '081200000004',
            'balance' => 120000,
        ]);

        $peminjam3 = User::create([
            'name' => 'Fikri Ramadhan',
            'email' => 'fikri@sonsha.test',
            'password' => Hash::make('password'),
            'role' => 'peminjam',
            'phone' => '081200000005',
            'balance' => 8000,
            'status' => 'locked',
            'locked_reason' => 'Menunggu pelunasan denda.',
        ]);

        $kategoriFashion = Category::create([
            'name' => 'Fashion Studio',
            'slug' => 'fashion-studio',
            'description' => 'Alat pendukung editorial fashion dan styling.',
        ]);

        $kategoriTeknis = Category::create([
            'name' => 'Teknis Produksi',
            'slug' => 'teknis-produksi',
            'description' => 'Peralatan lighting, kamera, dan rig pendukung.',
        ]);

        $camera = Asset::create([
            'category_id' => $kategoriTeknis->id,
            'code' => 'AST-001',
            'name' => 'Mirrorless Sony ZV-E10',
            'brand' => 'Sony',
            'stock_total' => 4,
            'stock_available' => 4,
            'condition' => 'baik',
            'rent_fee' => 75000,
            'description' => 'Kamera utama untuk produksi konten fashion.',
        ]);

        Asset::create([
            'category_id' => $kategoriTeknis->id,
            'code' => 'AST-002',
            'name' => 'Ring Light 18 Inch',
            'brand' => 'Aputure',
            'stock_total' => 8,
            'stock_available' => 8,
            'condition' => 'baik',
            'rent_fee' => 25000,
            'description' => 'Pencahayaan portofolio dan foto produk.',
        ]);

        Asset::create([
            'category_id' => $kategoriFashion->id,
            'code' => 'AST-003',
            'name' => 'Manekin Full Body',
            'brand' => 'Display Pro',
            'stock_total' => 2,
            'stock_available' => 2,
            'condition' => 'baik',
            'rent_fee' => 30000,
            'description' => 'Manekin untuk styling outfit dan katalog.',
        ]);

        $tripod = Asset::create([
            'category_id' => $kategoriTeknis->id,
            'code' => 'AST-004',
            'name' => 'Tripod Carbon Pro',
            'brand' => 'Manfrotto',
            'stock_total' => 5,
            'stock_available' => 5,
            'condition' => 'baik',
            'rent_fee' => 20000,
            'description' => 'Tripod untuk sesi studio dan outdoor fashion.',
        ]);

        $wardrobe = Asset::create([
            'category_id' => $kategoriFashion->id,
            'code' => 'AST-005',
            'name' => 'Portable Wardrobe Rack',
            'brand' => 'Rackline',
            'stock_total' => 3,
            'stock_available' => 3,
            'condition' => 'baik',
            'rent_fee' => 18000,
            'description' => 'Rak wardrobe untuk fitting dan backstage.',
        ]);

        $steamIron = Asset::create([
            'category_id' => $kategoriFashion->id,
            'code' => 'AST-006',
            'name' => 'Garment Steamer',
            'brand' => 'Philips',
            'stock_total' => 4,
            'stock_available' => 4,
            'condition' => 'baik',
            'rent_fee' => 15000,
            'description' => 'Steamer untuk persiapan outfit sebelum sesi foto.',
        ]);

        $borrowingLate = Borrowing::create([
            'borrowing_code' => Borrowing::generateCode(),
            'user_id' => $peminjam->id,
            'approved_by' => $petugas->id,
            'borrowed_at' => now()->subDays(6),
            'due_at' => now()->subDays(1),
            'returned_at' => now()->subDay(),
            'status' => 'late',
            'purpose' => 'Editorial fashion semester project',
            'total_fine' => 25000,
            'notes' => 'Contoh data dashboard analytics.',
        ]);

        BorrowingItem::create([
            'borrowing_id' => $borrowingLate->id,
            'asset_id' => $camera->id,
            'quantity' => 1,
            'unit_fee' => 75000,
            'fine_amount' => 25000,
            'status' => 'returned',
        ]);

        FinePayment::create([
            'user_id' => $peminjam->id,
            'borrowing_id' => $borrowingLate->id,
            'amount' => 25000,
            'status' => 'paid',
            'method' => 'wallet',
            'note' => 'Auto debit saldo mockup',
            'paid_at' => now()->subDay(),
        ]);

        $borrowingBorrowed = Borrowing::create([
            'borrowing_code' => Borrowing::generateCode(),
            'user_id' => $peminjam2->id,
            'approved_by' => $petugas->id,
            'borrowed_at' => now()->subDays(2),
            'due_at' => now()->addDays(3),
            'status' => 'borrowed',
            'purpose' => 'Pemotretan campaign koleksi musim panas',
            'total_fine' => 0,
        ]);

        BorrowingItem::create([
            'borrowing_id' => $borrowingBorrowed->id,
            'asset_id' => $tripod->id,
            'quantity' => 2,
            'unit_fee' => 20000,
            'fine_amount' => 0,
            'status' => 'borrowed',
        ]);

        $borrowingRequested = Borrowing::create([
            'borrowing_code' => Borrowing::generateCode(),
            'user_id' => $peminjam->id,
            'due_at' => now()->addDays(5),
            'status' => 'requested',
            'purpose' => 'Presentasi lookbook kelas produksi fashion',
            'total_fine' => 0,
        ]);

        BorrowingItem::create([
            'borrowing_id' => $borrowingRequested->id,
            'asset_id' => $wardrobe->id,
            'quantity' => 1,
            'unit_fee' => 18000,
            'fine_amount' => 0,
            'status' => 'pending',
        ]);

        $borrowingOld = Borrowing::create([
            'borrowing_code' => Borrowing::generateCode(),
            'user_id' => $peminjam3->id,
            'approved_by' => $petugas->id,
            'borrowed_at' => now()->subMonths(2)->addDays(1),
            'due_at' => now()->subMonths(2)->addDays(4),
            'returned_at' => now()->subMonths(2)->addDays(8),
            'status' => 'late',
            'purpose' => 'Sesi pemotretan produk marketplace',
            'total_fine' => 20000,
        ]);

        BorrowingItem::create([
            'borrowing_id' => $borrowingOld->id,
            'asset_id' => $steamIron->id,
            'quantity' => 1,
            'unit_fee' => 15000,
            'fine_amount' => 20000,
            'status' => 'returned',
        ]);

        FinePayment::create([
            'user_id' => $peminjam3->id,
            'borrowing_id' => $borrowingOld->id,
            'amount' => 12000,
            'status' => 'pending',
            'method' => 'wallet',
            'note' => 'Sisa denda belum terlunasi.',
        ]);

        $borrowingClosed = Borrowing::create([
            'borrowing_code' => Borrowing::generateCode(),
            'user_id' => $peminjam2->id,
            'approved_by' => $petugas->id,
            'borrowed_at' => now()->subMonth()->addDays(3),
            'due_at' => now()->subMonth()->addDays(7),
            'returned_at' => now()->subMonth()->addDays(7),
            'status' => 'returned',
            'purpose' => 'Sesi editorial fashion kampus',
            'total_fine' => 0,
        ]);

        BorrowingItem::create([
            'borrowing_id' => $borrowingClosed->id,
            'asset_id' => $camera->id,
            'quantity' => 1,
            'unit_fee' => 75000,
            'fine_amount' => 0,
            'status' => 'returned',
        ]);

        ActivityLog::create([
            'user_id' => $admin->id,
            'module' => 'seed',
            'action' => 'seed_demo_data',
            'description' => 'Membuat data demo admin, petugas, peminjam, alat, dan peminjaman.',
            'payload' => [
                'users' => 5,
                'assets' => 6,
                'borrowings' => 5,
            ],
        ]);

        ActivityLog::create([
            'user_id' => $petugas->id,
            'module' => 'borrowing',
            'action' => 'seed_approval',
            'description' => 'Data demo approval dan return transaksi dibuat untuk kebutuhan analytics.',
            'payload' => [
                'latest_borrowing' => $borrowingBorrowed->borrowing_code,
            ],
        ]);
    }
}
