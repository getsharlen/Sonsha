@extends('layouts.admin')

@section('content')
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="glass-panel rounded-2xl p-4">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Total Log</p>
        <p class="mt-2 text-2xl font-semibold text-white">{{ number_format($summary['activity_logs']) }}</p>
    </div>
    <div class="glass-panel rounded-2xl p-4">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Total Riwayat</p>
        <p class="mt-2 text-2xl font-semibold text-white">{{ number_format($summary['borrowing_history']) }}</p>
    </div>
    <div class="glass-panel rounded-2xl p-4">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Sedang Aktif</p>
        <p class="mt-2 text-2xl font-semibold text-white">{{ number_format($summary['active_borrowings']) }}</p>
    </div>
    <div class="glass-panel rounded-2xl p-4">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Sudah Selesai</p>
        <p class="mt-2 text-2xl font-semibold text-white">{{ number_format($summary['returned_borrowings']) }}</p>
    </div>
</div>

<div class="mt-6 grid gap-6 xl:grid-cols-2">
    <section class="glass-panel rounded-3xl p-5">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-white/10 pb-4">
            <div>
                <h3 class="text-lg font-semibold">Laporan Log Aktivitas</h3>
                <p class="text-xs text-slate-400">Menampilkan 50 log terbaru aktivitas sistem.</p>
            </div>
            <a href="/reports/technical/excel?type=activity-logs" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 px-3 py-2 text-xs font-semibold text-white transition hover:brightness-110">
                Export Excel
            </a>
        </div>

        <div class="mt-4 max-h-[28rem] overflow-auto rounded-xl border border-white/10">
            <table class="min-w-full text-left text-xs">
                <thead class="bg-slate-950/80 text-slate-300">
                    <tr>
                        <th class="px-3 py-2">Tanggal</th>
                        <th class="px-3 py-2">User</th>
                        <th class="px-3 py-2">Modul</th>
                        <th class="px-3 py-2">Aksi</th>
                        <th class="px-3 py-2">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activityLogs as $log)
                        <tr class="border-t border-white/10 bg-slate-950/35 align-top">
                            <td class="px-3 py-2 text-slate-300">{{ optional($log->created_at)->format('d M Y H:i') }}</td>
                            <td class="px-3 py-2 text-white">{{ $log->user?->name ?? '-' }}</td>
                            <td class="px-3 py-2 text-slate-300">{{ $log->module }}</td>
                            <td class="px-3 py-2 text-pink-300">{{ $log->action }}</td>
                            <td class="px-3 py-2 text-slate-300">{{ $log->description }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-6 text-center text-slate-400">Belum ada data log aktivitas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="glass-panel rounded-3xl p-5">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-white/10 pb-4">
            <div>
                <h3 class="text-lg font-semibold">Laporan History Penyewaan</h3>
                <p class="text-xs text-slate-400">Menampilkan 50 transaksi penyewaan terbaru.</p>
            </div>
            <a href="/reports/technical/excel?type=borrowing-history" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 px-3 py-2 text-xs font-semibold text-white transition hover:brightness-110">
                Export Excel
            </a>
        </div>

        <div class="mt-4 max-h-[28rem] overflow-auto rounded-xl border border-white/10">
            <table class="min-w-full text-left text-xs">
                <thead class="bg-slate-950/80 text-slate-300">
                    <tr>
                        <th class="px-3 py-2">Kode</th>
                        <th class="px-3 py-2">Peminjam</th>
                        <th class="px-3 py-2">Status</th>
                        <th class="px-3 py-2">Jatuh Tempo</th>
                        <th class="px-3 py-2">Kembali</th>
                        <th class="px-3 py-2">Qty</th>
                        <th class="px-3 py-2">Total Harga</th>
                        <th class="px-3 py-2">Durasi</th>
                        <th class="px-3 py-2">Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrowingHistory as $borrowing)
                        <tr class="border-t border-white/10 bg-slate-950/35">
                            <td class="px-3 py-2 text-white">{{ $borrowing->borrowing_code }}</td>
                            <td class="px-3 py-2 text-slate-300">{{ $borrowing->user?->name ?? '-' }}</td>
                            <td class="px-3 py-2 text-pink-300">{{ ucfirst($borrowing->status) }}</td>
                            <td class="px-3 py-2 text-slate-300">{{ optional($borrowing->due_at)->format('d M Y') ?? '-' }}</td>
                            <td class="px-3 py-2 text-slate-300">{{ optional($borrowing->returned_at)->format('d M Y H:i') ?? '-' }}</td>
                            <td class="px-3 py-2 text-slate-300">{{ $borrowing->items->sum('quantity') }}</td>
                            <td class="px-3 py-2 text-emerald-300">Rp {{ number_format($borrowing->items->sum(function($item) { return $item->quantity * $item->unit_fee; }), 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-blue-300">{{ $borrowing->due_at ? $borrowing->created_at->diffInDays($borrowing->due_at) . ' hari' : '-' }}</td>
                            <td class="px-3 py-2 text-slate-300">Rp {{ number_format((float) $borrowing->total_fine, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-3 py-6 text-center text-slate-400">Belum ada history penyewaan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<div class="mt-6 flex flex-wrap gap-3">
    <a href="/reports/technical/pdf" class="rounded-xl border border-white/20 bg-white/5 px-4 py-2 text-sm font-semibold text-slate-100 transition hover:bg-white/10">
        Download Dokumen Teknis (PDF)
    </a>
</div>
@endsection