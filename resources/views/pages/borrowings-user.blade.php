@extends('layouts.user')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Page Header -->
    <div class="mb-12">
        <h1 class="text-4xl font-bold mb-2">
            <span class="bg-gradient-to-r from-pink-300 to-rose-300 bg-clip-text text-transparent">
                Peminjaman Saya
            </span>
        </h1>
        <p class="text-slate-400">Kelola semua peminjaman fashion Anda dengan mudah</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <div class="glass-light glass p-6 rounded-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400 mb-1">Total Peminjaman</p>
                    <p class="text-3xl font-bold">{{ $summary['total'] ?? 0 }}</p>
                </div>
                <i class="fas fa-shopping-bag text-pink-300 text-3xl opacity-30"></i>
            </div>
        </div>

        <div class="glass-light glass p-6 rounded-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400 mb-1">Sedang Dipinjam</p>
                    <p class="text-3xl font-bold text-yellow-300">{{ $summary['active'] ?? 0 }}</p>
                </div>
                <i class="fas fa-hourglass-half text-yellow-300 text-3xl opacity-30"></i>
            </div>
        </div>

        <div class="glass-light glass p-6 rounded-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400 mb-1">Menunggu Approval</p>
                    <p class="text-3xl font-bold text-blue-300">{{ $summary['pending'] ?? 0 }}</p>
                </div>
                <i class="fas fa-clock text-blue-300 text-3xl opacity-30"></i>
            </div>
        </div>

        <div class="glass-light glass p-6 rounded-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400 mb-1">Denda Pending</p>
                    <p class="text-3xl font-bold text-red-300">Rp {{ number_format($summary['fines'] ?? 0, 0, ',', '.') }}</p>
                </div>
                <i class="fas fa-exclamation-triangle text-red-300 text-3xl opacity-30"></i>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mb-8 border-b border-white/10">
        <div class="flex gap-1 overflow-x-auto">
            <button class="tab-btn active px-6 py-3 border-b-2 border-pink-500 text-pink-300 font-semibold transition" data-tab="all">
                Semua
            </button>
            <button class="tab-btn px-6 py-3 border-b-2 border-transparent text-slate-400 hover:text-white font-semibold transition" data-tab="active">
                Aktif
            </button>
            <button class="tab-btn px-6 py-3 border-b-2 border-transparent text-slate-400 hover:text-white font-semibold transition" data-tab="pending">
                Menunggu
            </button>
            <button class="tab-btn px-6 py-3 border-b-2 border-transparent text-slate-400 hover:text-white font-semibold transition" data-tab="returned">
                Dikembalikan
            </button>
        </div>
    </div>

    <!-- Borrowings List -->
    @if(($borrowings ?? collect())->count() > 0)
        <div class="space-y-6" id="borrowingsList">
            @foreach($borrowings ?? [] as $borrowing)
                <div class="glass-hover glass p-6 rounded-2xl group" data-status="{{ in_array($borrowing->status, ['borrowed', 'late', 'approved'], true) ? 'active' : ($borrowing->status === 'requested' ? 'pending' : ($borrowing->status === 'returned' ? 'returned' : 'other')) }}">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <!-- Booking Code & Date -->
                        <div>
                            <p class="text-xs text-slate-400 mb-1">Kode Peminjaman</p>
                            <p class="font-bold text-white">{{ $borrowing->borrowing_code }}</p>
                            <p class="text-xs text-slate-400 mt-2">{{ $borrowing->created_at->format('d M Y') }}</p>
                        </div>

                        <!-- Item Details -->
                        <div>
                            <p class="text-xs text-slate-400 mb-1">Item</p>
                            <div class="space-y-1">
                                @foreach($borrowing->items as $item)
                                    <p class="font-semibold text-white text-sm">{{ $item->asset->name }}</p>
                                    <p class="text-xs text-slate-400">qty: {{ $item->quantity }}</p>
                                @endforeach
                            </div>
                        </div>

                        <!-- Duration -->
                        <div>
                            <p class="text-xs text-slate-400 mb-1">Durasi</p>
                            <p class="font-semibold text-white">
                                {{ $borrowing->created_at->format('d M') }} - {{ optional($borrowing->due_at)->format('d M Y') ?? '-' }}
                            </p>
                            @if($borrowing->returned_at)
                                <p class="text-xs text-slate-400 mt-2">Dikembalikan: {{ $borrowing->returned_at->format('d M Y H:i') }}</p>
                            @endif
                        </div>

                        <!-- Status & Amount -->
                        <div class="flex flex-col justify-between">
                            <div>
                                <p class="text-xs text-slate-400 mb-1">Status</p>
                                <div class="flex items-center gap-2">
                                    @if($borrowing->status === 'requested')
                                        <div class="status-pending px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                                            <i class="fas fa-clock"></i> Pending
                                        </div>
                                    @elseif(in_array($borrowing->status, ['borrowed', 'late'], true))
                                        <div class="status-borrowed px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                                            <i class="fas fa-handshake"></i> Dipinjam
                                        </div>
                                    @elseif($borrowing->status === 'returned')
                                        <div class="status-returned px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                                            <i class="fas fa-undo"></i> Dikembalikan
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-slate-400">Total</p>
                                <p class="text-lg font-bold text-pink-300">
                                    Rp {{ number_format($borrowing->total_amount ?? 0, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Items Details -->
                    <div class="border-t border-white/10 pt-4 mb-4">
                        <p class="text-xs text-slate-400 mb-3 font-semibold">Detail Item</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                            @foreach($borrowing->items as $item)
                                <div class="glass bg-white/5 p-3 rounded-lg">
                                    <div class="flex items-start gap-3">
                                        @if($item->asset->image_source)
                                            <img src="{{ $item->asset->image_source }}" alt="{{ $item->asset->name }}" class="w-12 h-12 rounded-lg object-cover">
                                        @else
                                            <div class="w-12 h-12 bg-gradient-to-br from-slate-700 to-slate-900 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-image text-slate-500 text-xs"></i>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <p class="text-xs font-semibold text-white line-clamp-2">{{ $item->asset->name }}</p>
                                            <p class="text-xs text-slate-400 mt-1">Status: <span class="font-bold">{{ $item->status }}</span></p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Purpose -->
                    <div class="border-t border-white/10 pt-4 mb-4">
                        <p class="text-xs text-slate-400 mb-1">Tujuan Penggunaan</p>
                        <p class="text-sm text-white">{{ $borrowing->purpose }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-wrap gap-2">
                        <a href="/borrowings/{{ $borrowing->id }}" class="btn-luxury glass-hover glass px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-2">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>

                        @if($borrowing->status === 'requested')
                            <form action="/borrowings/{{ $borrowing->id }}/cancel" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn-luxury bg-red-500/20 hover:bg-red-500/30 border border-red-400/30 text-red-300 px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-2">
                                    <i class="fas fa-times"></i> Batalkan
                                </button>
                            </form>
                        @elseif(in_array($borrowing->status, ['borrowed', 'late'], true) && !$borrowing->returned_at)
                            <form action="/borrowings/{{ $borrowing->id }}/return" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn-luxury bg-blue-500/20 hover:bg-blue-500/30 border border-blue-400/30 text-blue-300 px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-2">
                                    <i class="fas fa-undo"></i> Kembalikan
                                </button>
                            </form>
                        @endif

                        @if($borrowing->pending_fine && $borrowing->pending_fine > 0)
                            <form action="/fines/{{ $borrowing->payment->id }}/pay" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn-luxury bg-pink-500/20 hover:bg-pink-500/30 border border-pink-400/30 text-pink-300 px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-2">
                                    <i class="fas fa-money-bill"></i> Bayar Denda Rp {{ number_format($borrowing->pending_fine, 0, ',', '.') }}
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Late Notice -->
                    @if(in_array($borrowing->status, ['borrowed', 'late'], true) && $borrowing->due_at && $borrowing->due_at < now())
                        <div class="mt-4 bg-red-500/10 border border-red-400/30 rounded-lg p-3 flex items-start gap-3">
                            <i class="fas fa-exclamation-circle text-red-300 flex-shrink-0 mt-0.5"></i>
                            <div>
                                <p class="text-sm font-semibold text-red-300">Terlambat!</p>
                                <p class="text-xs text-red-200">Item seharusnya dikembalikan {{ $borrowing->due_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12 flex justify-center gap-2">
            {{ $borrowings->links('pagination::tailwind') }}
        </div>
    @else
        <div class="text-center py-20">
            <i class="fas fa-inbox text-6xl text-slate-500 mb-4"></i>
            <h3 class="text-2xl font-bold text-slate-300 mb-2">Belum Ada Peminjaman</h3>
            <p class="text-slate-400 mb-8">Mulai jelajahi koleksi fashion kami sekarang</p>
            <a href="/catalog" class="btn-luxury bg-gradient-to-r from-pink-500 to-rose-600 px-8 py-3 rounded-full font-semibold inline-block transition">
                Lihat Katalog
            </a>
        </div>
    @endif
</div>

<script>
    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('border-pink-500', 'text-pink-300');
                b.classList.add('border-transparent', 'text-slate-400');
            });
            this.classList.add('border-pink-500', 'text-pink-300');
            this.classList.remove('border-transparent', 'text-slate-400');

            const tab = this.dataset.tab;
            const items = document.querySelectorAll('#borrowingsList > div');

            items.forEach(item => {
                if (tab === 'all') {
                    item.style.display = 'block';
                } else {
                    item.style.display = item.dataset.status === tab ? 'block' : 'none';
                }
            });
        });
    });
</script>
@endsection
