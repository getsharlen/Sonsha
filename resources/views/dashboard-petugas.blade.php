@extends('layouts.admin')

@section('content')
<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
    <div class="glass-panel rounded-3xl p-5">
        <p class="text-sm text-slate-300">Peminjaman Pending</p>
        <h3 class="mt-3 text-3xl font-semibold">{{ $pendingBorrowings->count() }}</h3>
    </div>
    <div class="glass-panel rounded-3xl p-5">
        <p class="text-sm text-slate-300">Total Alat</p>
        <h3 class="mt-3 text-3xl font-semibold">{{ $assets->count() }}</h3>
    </div>
    <div class="glass-panel rounded-3xl p-5">
        <p class="text-sm text-slate-300">Alat Tersedia</p>
        <h3 class="mt-3 text-3xl font-semibold">{{ $assets->where('stock_available', '>', 0)->count() }}</h3>
    </div>
    <div class="glass-panel rounded-3xl p-5">
        <p class="text-sm text-slate-300">Alat Rusak</p>
        <h3 class="mt-3 text-3xl font-semibold">{{ $assets->where('condition', 'rusak')->count() }}</h3>
    </div>
</div>

<div class="mt-6 grid gap-6 xl:grid-cols-2">
    <section class="glass-panel rounded-3xl p-5">
        <h3 class="text-lg font-semibold">Peminjaman Menunggu Approval</h3>
        <div class="mt-4 space-y-3 text-sm max-h-96 overflow-y-auto">
            @forelse($pendingBorrowings as $borrowing)
                <div class="rounded-2xl bg-slate-950/60 p-4">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="font-medium text-white">{{ $borrowing->borrowing_code }}</p>
                            <p class="text-slate-300">{{ $borrowing->user?->name }}</p>
                        </div>
                        <div class="flex gap-2">
                            <form method="POST" action="/borrowings/{{ $borrowing->id }}/approve" class="inline">
                                @csrf
                                <button class="rounded-xl bg-emerald-500 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-600">Approve</button>
                            </form>
                            <form method="POST" action="/borrowings/{{ $borrowing->id }}/decline" class="inline">
                                @csrf
                                <button class="rounded-xl bg-red-500 px-3 py-2 text-xs font-semibold text-white hover:bg-red-600">Decline</button>
                            </form>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-slate-400">Jatuh tempo: {{ optional($borrowing->due_at)->format('d M Y') }}</p>
                    <p class="mt-2 text-slate-300">Tujuan: {{ $borrowing->purpose }}</p>
                    <div class="mt-2 rounded-xl border border-white/10 bg-slate-900/60 p-3 text-xs text-slate-300">
                        @foreach($borrowing->items as $item)
                            <p>- {{ $item->asset?->name }} | qty {{ $item->quantity }}</p>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-slate-300">Tidak ada peminjaman yang menunggu approval.</p>
            @endforelse
        </div>
    </section>

    <section class="glass-panel rounded-3xl p-5">
        <h3 class="text-lg font-semibold">Kondisi Barang</h3>
        <div class="mt-4 space-y-3 text-sm max-h-96 overflow-y-auto">
            @foreach($assets as $asset)
                <div class="rounded-2xl bg-slate-950/60 p-4">
                    <div class="flex items-center justify-between">
                        <p class="font-medium text-white">{{ $asset->name }}</p>
                        <span class="rounded-full px-2 py-1 text-xs font-semibold
                            @if($asset->condition === 'baik') bg-green-500/20 text-green-400
                            @elseif($asset->condition === 'rusak') bg-red-500/20 text-red-400
                            @else bg-yellow-500/20 text-yellow-400 @endif">
                            {{ ucfirst($asset->condition) }}
                        </span>
                    </div>
                    <p class="text-slate-300">Stok: {{ $asset->stock_available }} / {{ $asset->stock_total }}</p>
                    <p class="text-slate-300">Kategori: {{ $asset->category?->name }}</p>
                </div>
            @endforeach
        </div>
    </section>
</div>

@endsection