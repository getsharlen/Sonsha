@extends('layouts.officer')

@section('content')
<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
    <div class="glass-card rounded-3xl p-5">
        <p class="text-sm text-slate-300">Total Peminjaman</p>
        <h3 class="mt-3 text-3xl font-semibold">{{ $summary['borrowings'] }}</h3>
    </div>
    <div class="glass-card rounded-3xl p-5">
        <p class="text-sm text-slate-300">Peminjaman Aktif</p>
        <h3 class="mt-3 text-3xl font-semibold">{{ $summary['openBorrowings'] }}</h3>
    </div>
    <div class="glass-card rounded-3xl p-5">
        <p class="text-sm text-slate-300">Pengguna Aktif</p>
        <h3 class="mt-3 text-3xl font-semibold">{{ $summary['activeUsers'] }}</h3>
    </div>
</div>

<div class="mt-6 grid gap-6">
    <section class="glass-card rounded-3xl p-5">
        <div class="flex items-center justify-between gap-3">
            <h3 class="text-lg font-semibold">Aktivitas Terbaru</h3>
            <p class="text-xs text-slate-400">Menampilkan {{ $recentLogs->count() }} data terbaru</p>
        </div>
        <div class="mt-4 max-h-[26rem] space-y-3 overflow-y-auto pr-1 text-sm">
            @foreach($recentLogs as $log)
                <div class="rounded-2xl bg-slate-950/60 p-4">
                    <p class="font-medium text-white">{{ $log->module }} - {{ $log->action }}</p>
                    <p class="text-slate-300">{{ $log->description }}</p>
                    <p class="mt-1 text-xs text-slate-400">{{ optional($log->created_at)->format('d M Y H:i') }}</p>
                </div>
            @endforeach
        </div>
    </section>
</div>

<div class="mt-6 rounded-2xl border border-blue-400/20 bg-blue-500/10 p-4 text-sm text-blue-100">
    <i class="fas fa-info-circle mr-2"></i>
    <strong>Info:</strong> Sebagai petugas, Anda hanya dapat mengelola peminjaman dan pengembalian. Untuk manajemen denda, kategori, dan alat, silakan hubungi administrator.
</div>
@endsection
