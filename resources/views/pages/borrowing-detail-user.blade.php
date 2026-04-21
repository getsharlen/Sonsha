@extends('layouts.user')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6">
        <a href="/borrowings" class="text-slate-400 hover:text-pink-300 text-sm transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Peminjaman Saya
        </a>
    </div>

    <div class="glass-light glass p-6 rounded-2xl mb-6">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <p class="text-xs text-slate-400">Kode Peminjaman</p>
                <h1 class="text-2xl font-bold text-white">{{ $borrowing->borrowing_code }}</h1>
                <p class="text-sm text-slate-300 mt-2">Dibuat pada {{ $borrowing->created_at->format('d M Y H:i') }}</p>
            </div>
            <div>
                @if($borrowing->status === 'requested')
                    <span class="status-pending px-3 py-1 rounded-full text-xs font-semibold">Menunggu Approval</span>
                @elseif(in_array($borrowing->status, ['borrowed', 'late', 'approved'], true))
                    <span class="status-borrowed px-3 py-1 rounded-full text-xs font-semibold">Sedang Dipinjam</span>
                @elseif($borrowing->status === 'returned')
                    <span class="status-returned px-3 py-1 rounded-full text-xs font-semibold">Dikembalikan</span>
                @else
                    <span class="status-fine px-3 py-1 rounded-full text-xs font-semibold">{{ ucfirst($borrowing->status) }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 glass p-6 rounded-2xl">
            <h2 class="text-lg font-semibold mb-4">Item Dipinjam</h2>
            <div class="space-y-3">
                @foreach($borrowing->items as $item)
                    <div class="rounded-xl border border-white/10 bg-slate-900/50 p-4 flex items-center gap-3">
                        @if($item->asset?->image_url)
                            <img src="{{ $item->asset->image_url }}" alt="{{ $item->asset?->name }}" class="w-14 h-14 rounded-lg object-cover">
                        @else
                            <div class="w-14 h-14 rounded-lg bg-slate-800 flex items-center justify-center text-slate-500">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-semibold text-white">{{ $item->asset?->name ?? 'Item tidak ditemukan' }}</p>
                            <p class="text-xs text-slate-400">Kategori: {{ $item->asset?->category?->name ?? '-' }}</p>
                            <p class="text-xs text-slate-400">Qty: {{ $item->quantity }} | Status item: {{ $item->status }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-400">Tarif/Hari</p>
                            <p class="font-semibold text-pink-300">Rp {{ number_format($item->unit_fee, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="space-y-6">
            <div class="glass p-6 rounded-2xl">
                <h3 class="text-lg font-semibold mb-4">Ringkasan</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Jatuh tempo</span>
                        <span>{{ optional($borrowing->due_at)->format('d M Y') ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Dikembalikan</span>
                        <span>{{ optional($borrowing->returned_at)->format('d M Y H:i') ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Total denda</span>
                        <span>Rp {{ number_format($borrowing->total_fine ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Denda pending</span>
                        <span class="text-pink-300">Rp {{ number_format($borrowing->pending_fine ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="glass p-6 rounded-2xl">
                <h3 class="text-lg font-semibold mb-3">Tujuan Peminjaman</h3>
                <p class="text-sm text-slate-300">{{ $borrowing->purpose }}</p>
            </div>

            <div class="glass p-6 rounded-2xl">
                <div class="flex flex-wrap gap-2">
                    @if($borrowing->status === 'requested')
                        <form action="/borrowings/{{ $borrowing->id }}/cancel" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="rounded-lg bg-red-500/20 border border-red-400/30 text-red-300 px-4 py-2 text-sm font-semibold">
                                Batalkan
                            </button>
                        </form>
                    @endif

                    @if(in_array($borrowing->status, ['borrowed', 'late', 'approved'], true) && !$borrowing->returned_at)
                        <form action="/borrowings/{{ $borrowing->id }}/return" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="rounded-lg bg-blue-500/20 border border-blue-400/30 text-blue-300 px-4 py-2 text-sm font-semibold">
                                Kembalikan Sekarang
                            </button>
                        </form>
                    @endif

                    @if(($borrowing->pending_fine ?? 0) > 0 && $borrowing->payment)
                        <form action="/fines/{{ $borrowing->payment->id }}/pay" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="rounded-lg bg-pink-500/20 border border-pink-400/30 text-pink-300 px-4 py-2 text-sm font-semibold">
                                Bayar Denda
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
