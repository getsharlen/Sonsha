@extends('layouts.admin')

@section('content')
<div class="grid gap-6 xl:grid-cols-[1.1fr_1.3fr]">
    <section class="glass-panel rounded-3xl p-5">
        <h3 class="text-lg font-semibold">Ajukan Peminjaman</h3>
        <form class="mt-4 grid gap-3" method="POST" action="/borrowings">
            @csrf
            @if(in_array(auth()->user()->role, ['admin', 'petugas'], true))
                <select name="user_id" class="field-input" required>
                    @foreach($users->where('role', 'peminjam') as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            @endif
            <select name="asset_id" class="field-input" required>
                @foreach($assets as $asset)
                    <option value="{{ $asset->id }}">{{ $asset->name }} - stok {{ $asset->stock_available }}</option>
                @endforeach
            </select>
            <input name="quantity" type="number" min="1" value="1" class="field-input" required>
            <textarea name="purpose" placeholder="Tujuan peminjaman" class="field-input min-h-24"></textarea>
            <input name="due_at" type="date" class="field-input" required>
            <button class="rounded-2xl bg-gradient-to-r from-pink-500 to-rose-600 px-4 py-3 font-semibold text-white transition hover:brightness-110">Buat Pengajuan</button>
        </form>
    </section>

    <section class="glass-panel rounded-3xl p-5">
        <h3 class="text-lg font-semibold">Daftar Peminjaman</h3>
        <div class="mt-4 space-y-3 text-sm">
            @foreach($borrowings as $borrowing)
                <div class="rounded-2xl bg-slate-950/60 p-4">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="font-medium text-white">{{ $borrowing->borrowing_code }}</p>
                            <p class="text-slate-300">{{ $borrowing->user?->name }} - {{ $borrowing->status }}</p>
                        </div>
                        @if(in_array(auth()->user()->role, ['admin', 'petugas'], true) && $borrowing->status === 'requested')
                            <form method="POST" action="/borrowings/{{ $borrowing->id }}/approve">
                                @csrf
                                <button class="rounded-xl bg-emerald-500 px-3 py-2 text-xs font-semibold text-white">Approve</button>
                            </form>
                        @endif
                    </div>
                    <p class="mt-1 text-xs text-slate-400">Jatuh tempo: {{ optional($borrowing->due_at)->format('d M Y') ?? '-' }} | Dikembalikan: {{ optional($borrowing->returned_at)->format('d M Y H:i') ?? '-' }}</p>
                    <p class="mt-2 text-slate-300">Tujuan: {{ $borrowing->purpose }}</p>
                    <div class="mt-2 rounded-xl border border-white/10 bg-slate-900/60 p-3 text-xs text-slate-300">
                        <p class="font-semibold text-white">Detail item</p>
                        @foreach($borrowing->items as $item)
                            <p>- {{ $item->asset?->name }} | qty {{ $item->quantity }} | status {{ $item->status }}</p>
                        @endforeach
                    </div>
                    <p class="mt-1 text-slate-400">Denda: Rp {{ number_format($borrowing->total_fine, 0, ',', '.') }}</p>
                    @if(in_array($borrowing->status, ['approved', 'borrowed'], true))
                        <form class="mt-3" method="POST" action="/borrowings/{{ $borrowing->id }}/return">
                            @csrf
                            <button class="rounded-xl bg-pink-500 px-3 py-2 text-xs font-semibold text-white">Proses Pengembalian</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $borrowings->links() }}</div>
    </section>
</div>
@endsection