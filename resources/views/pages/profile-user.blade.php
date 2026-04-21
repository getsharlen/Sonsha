@extends('layouts.user')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Profile Header -->
    <div class="glass-light glass p-8 md:p-12 rounded-3xl mb-12">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-8">
            <div class="flex items-end gap-6">
                <!-- Profile Picture -->
                <div class="relative">
                    <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center text-white text-4xl">
                        <i class="fas fa-user"></i>
                    </div>
                    <button class="absolute bottom-0 right-0 bg-pink-500 hover:bg-pink-600 w-8 h-8 rounded-full flex items-center justify-center text-white transition">
                        <i class="fas fa-camera text-sm"></i>
                    </button>
                </div>

                <!-- Profile Info -->
                <div>
                    <h1 class="text-3xl font-bold text-white mb-1">{{ auth()->user()->name }}</h1>
                    <p class="text-slate-400 mb-3">{{ auth()->user()->email }}</p>
                    <span class="inline-block luxury-badge">Member {{ auth()->user()->created_at->format('Y') }}</span>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-pink-300">{{ $stats['total_rentals'] ?? 0 }}</p>
                    <p class="text-xs text-slate-400 mt-1">Peminjaman</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-blue-300">{{ $stats['active_rentals'] ?? 0 }}</p>
                    <p class="text-xs text-slate-400 mt-1">Aktif</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-emerald-300">{{ $stats['on_time'] ?? '100' }}%</p>
                    <p class="text-xs text-slate-400 mt-1">Tepat Waktu</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mb-8 border-b border-white/10">
        <div class="flex gap-1 overflow-x-auto">
            <button class="tab-btn active px-6 py-3 border-b-2 border-pink-500 text-pink-300 font-semibold transition" data-tab="info">
                Informasi Akun
            </button>
            <button class="tab-btn px-6 py-3 border-b-2 border-transparent text-slate-400 hover:text-white font-semibold transition" data-tab="payment">
                Pembayaran & Denda
            </button>
            <button class="tab-btn px-6 py-3 border-b-2 border-transparent text-slate-400 hover:text-white font-semibold transition" data-tab="history">
                Riwayat
            </button>
            <button class="tab-btn px-6 py-3 border-b-2 border-transparent text-slate-400 hover:text-white font-semibold transition" data-tab="settings">
                Pengaturan
            </button>
        </div>
    </div>

    <!-- Tab Contents -->

    <!-- Info Tab -->
    <div class="tab-content active" data-tab="info">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <!-- Personal Info -->
            <div class="lg:col-span-2">
                <div class="glass-light glass p-8 rounded-2xl">
                    <h2 class="text-2xl font-bold mb-6">Informasi Pribadi</h2>
                    <form method="POST" action="/profile/update" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm text-slate-400 mb-2 block">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ auth()->user()->name }}" 
                                    class="w-full glass bg-white/10 border-0 text-white py-3 px-4 rounded-lg focus:ring-2 focus:ring-pink-500 transition">
                            </div>
                            <div>
                                <label class="text-sm text-slate-400 mb-2 block">Email</label>
                                <input type="email" name="email" value="{{ auth()->user()->email }}" 
                                    class="w-full glass bg-white/10 border-0 text-white py-3 px-4 rounded-lg focus:ring-2 focus:ring-pink-500 transition">
                            </div>
                        </div>

                        <div>
                            <label class="text-sm text-slate-400 mb-2 block">Nomor Telepon</label>
                            <input type="tel" name="phone" value="{{ auth()->user()->phone }}" placeholder="+62 ..." 
                                class="w-full glass bg-white/10 border-0 text-white py-3 px-4 rounded-lg focus:ring-2 focus:ring-pink-500 transition placeholder-slate-500">
                            <p class="mt-2 text-xs text-slate-500">Nomor ini dipakai untuk notifikasi terkait peminjaman dan denda.</p>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="btn-luxury bg-gradient-to-r from-pink-500 to-rose-600 px-6 py-3 rounded-lg font-semibold transition hover:shadow-lg">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Status -->
            <div>
                <div class="glass-light glass p-8 rounded-2xl mb-6">
                    <h3 class="font-bold text-lg mb-4">Status Akun</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between pb-3 border-b border-white/10">
                            <span class="text-slate-400">Anggota sejak</span>
                            <span class="font-semibold">{{ auth()->user()->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between pb-3 border-b border-white/10">
                            <span class="text-slate-400">Status</span>
                            <span class="font-semibold text-emerald-300">Aktif</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Role</span>
                            <span class="font-semibold text-pink-300">{{ ucfirst(auth()->user()->role) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Saldo -->
                <div class="glass-light glass p-8 rounded-2xl">
                    <h3 class="font-bold text-lg mb-4">Saldo Anda</h3>
                    <p class="text-4xl font-bold text-pink-300 mb-4">
                        Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}
                    </p>
                    <button class="w-full btn-luxury glass-hover glass px-4 py-2 rounded-lg font-semibold transition">
                        <i class="fas fa-plus mr-2"></i> Top Up
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Tab -->
    <div class="tab-content hidden" data-tab="payment">
        <div class="space-y-6 mb-12">
            <!-- Outstanding Fines -->
            @if($outstandingFines && $outstandingFines->count() > 0)
                <div class="glass-light glass p-8 rounded-2xl">
                    <h2 class="text-2xl font-bold mb-6 text-red-300">Denda yang Harus Dibayar</h2>
                    <div class="space-y-4">
                        @foreach($outstandingFines as $fine)
                            <div class="glass bg-white/5 p-4 rounded-lg flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-white mb-1">{{ $fine->borrowing->borrowing_code }}</p>
                                    <p class="text-sm text-slate-400">Terlambat {{ $fine->late_days }} hari</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-red-300">Rp {{ number_format($fine->amount, 0, ',', '.') }}</p>
                                    <form action="/fines/{{ $fine->id }}/pay" method="POST" class="inline mt-2">
                                        @csrf
                                        <button type="submit" class="text-sm btn-luxury bg-pink-500 hover:bg-pink-600 px-3 py-1 rounded-lg transition">
                                            Bayar Sekarang
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="glass-light glass p-8 rounded-2xl text-center">
                    <i class="fas fa-check-circle text-4xl text-emerald-300 mb-4"></i>
                    <p class="text-xl font-bold text-white mb-2">Tidak Ada Denda</p>
                    <p class="text-slate-400">Anda tidak memiliki denda yang tertunda</p>
                </div>
            @endif

            <!-- Payment History -->
            <div class="glass-light glass p-8 rounded-2xl">
                <h2 class="text-2xl font-bold mb-6">Riwayat Pembayaran</h2>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($paymentHistory ?? [] as $payment)
                        <div class="glass bg-white/5 p-4 rounded-lg flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-white">Pembayaran Denda</p>
                                <p class="text-sm text-slate-400">{{ $payment->paid_at->format('d M Y H:i') }}</p>
                            </div>
                            <span class="text-lg font-bold text-emerald-300">+ Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <p class="text-slate-400 text-center py-6">Belum ada riwayat pembayaran</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- History Tab -->
    <div class="tab-content hidden" data-tab="history">
        <div class="glass-light glass p-8 rounded-2xl mb-12">
            <h2 class="text-2xl font-bold mb-6">Riwayat Peminjaman</h2>
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($borrowingHistory ?? [] as $borrowing)
                    <div class="glass bg-white/5 p-4 rounded-lg">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="font-semibold text-white mb-1">{{ $borrowing->borrowing_code }}</p>
                                <p class="text-sm text-slate-400 mb-2">
                                    @foreach($borrowing->items as $item)
                                        {{ $item->asset->name }}@if(!$loop->last), @endif
                                    @endforeach
                                </p>
                                <p class="text-xs text-slate-500">
                                    {{ $borrowing->created_at->format('d M Y') }} - {{ optional($borrowing->returned_at)->format('d M Y') ?? 'Belum dikembalikan' }}
                                </p>
                            </div>
                            <span class="status-{{ strtolower($borrowing->status) }} px-3 py-1 rounded-full text-xs font-bold">
                                {{ ucfirst($borrowing->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-400 text-center py-6">Belum ada riwayat peminjaman</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Settings Tab -->
    <div class="tab-content hidden" data-tab="settings">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Notification Settings -->
            <div class="glass-light glass p-8 rounded-2xl">
                <h3 class="text-xl font-bold mb-6">Notifikasi</h3>
                <div class="space-y-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" checked class="w-4 h-4 rounded">
                        <span class="text-sm">Email untuk pembaruan peminjaman</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" checked class="w-4 h-4 rounded">
                        <span class="text-sm">Pemberitahuan denda yang akan jatuh tempo</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" checked class="w-4 h-4 rounded">
                        <span class="text-sm">Penawaran dan promosi eksklusif</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 rounded">
                        <span class="text-sm">SMS untuk item yang tersedia</span>
                    </label>
                </div>
            </div>

            <!-- Privacy Settings -->
            <div class="glass-light glass p-8 rounded-2xl">
                <h3 class="text-xl font-bold mb-6">Privasi & Keamanan</h3>
                <div class="space-y-4">
                    <a href="/profile/change-password" class="block btn-luxury glass-hover glass px-4 py-2 rounded-lg font-semibold transition">
                        Ubah Password
                    </a>
                    <button class="w-full btn-luxury glass-hover glass px-4 py-2 rounded-lg font-semibold transition text-left">
                        <i class="fas fa-shield-alt mr-2"></i> Verifikasi Dua Faktor
                    </button>
                    <button class="w-full btn-luxury glass-hover glass px-4 py-2 rounded-lg font-semibold transition text-left">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout Semua Perangkat
                    </button>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="glass-light glass border-l-4 border-red-500 p-8 rounded-2xl">
            <h3 class="text-xl font-bold text-red-300 mb-4">Zona Bahaya</h3>
            <p class="text-slate-300 mb-6">Tindakan berikut tidak dapat dibatalkan. Silakan lakukan dengan hati-hati.</p>
            <a href="/profile/delete-account" class="inline-block bg-red-500/20 hover:bg-red-500/30 border border-red-400/30 text-red-300 px-6 py-3 rounded-lg font-semibold transition">
                <i class="fas fa-trash mr-2"></i> Hapus Akun Selamanya
            </a>
        </div>
    </div>
</div>

<script>
    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tab = this.dataset.tab;
            
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('border-pink-500', 'text-pink-300');
                b.classList.add('border-transparent', 'text-slate-400');
            });
            this.classList.add('border-pink-500', 'text-pink-300');
            this.classList.remove('border-transparent', 'text-slate-400');

            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            document.querySelector(`[data-tab="${tab}"]`).classList.remove('hidden');
        });
    });
</script>
@endsection
