@extends('layouts.app')

@section('content')
<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
        <p class="text-sm text-slate-300">Total User</p>
        <h3 class="mt-3 text-3xl font-semibold">{{ $summary['users'] }}</h3>
    </div>
    <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
        <p class="text-sm text-slate-300">Total Alat</p>
        <h3 class="mt-3 text-3xl font-semibold">{{ $summary['assets'] }}</h3>
    </div>
    <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
        <p class="text-sm text-slate-300">Peminjaman</p>
        <h3 class="mt-3 text-3xl font-semibold">{{ $summary['borrowings'] }}</h3>
    </div>
    <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
        <p class="text-sm text-slate-300">Saldo / Denda Pending</p>
        <h3 class="mt-3 text-3xl font-semibold">Rp {{ number_format($pendingFines, 0, ',', '.') }}</h3>
    </div>
</div>

<div class="mt-6 grid gap-6 xl:grid-cols-[1.6fr_1fr]">
    <section class="rounded-3xl border border-white/10 bg-white/5 p-5">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold">Tren Peminjaman per Bulan</h3>
                <p class="text-sm text-slate-300">Grafik diambil dari tabel borrowings.</p>
            </div>
        </div>
        <canvas id="borrowChart" class="mt-6 h-80 w-full"></canvas>
    </section>

    <section class="rounded-3xl border border-white/10 bg-white/5 p-5">
        <h3 class="text-lg font-semibold">Alat Paling Sering Dipinjam</h3>
        <div class="mt-4 space-y-3 text-sm">
            @forelse($topAssets as $asset)
                <div class="rounded-2xl bg-slate-950/60 p-4">
                    <p class="font-medium text-white">{{ $asset->name }}</p>
                    <p class="text-slate-300">Total pinjam: {{ $asset->total }}</p>
                </div>
            @empty
                <p class="text-slate-300">Belum ada data transaksi.</p>
            @endforelse
        </div>
    </section>
</div>

<div class="mt-6 grid gap-6 xl:grid-cols-2">
    <section class="rounded-3xl border border-white/10 bg-white/5 p-5">
        <h3 class="text-lg font-semibold">Aktivitas Terbaru</h3>
        <div class="mt-4 space-y-3 text-sm">
            @foreach($recentLogs as $log)
                <div class="rounded-2xl bg-slate-950/60 p-4">
                    <p class="font-medium text-white">{{ $log->module }} - {{ $log->action }}</p>
                    <p class="text-slate-300">{{ $log->description }}</p>
                    <p class="mt-1 text-xs text-slate-400">{{ optional($log->created_at)->format('d M Y H:i') }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="rounded-3xl border border-white/10 bg-white/5 p-5">
        <h3 class="text-lg font-semibold">Manajemen Saldo</h3>
        <p class="mt-2 text-sm text-slate-300">Saldo user dipakai untuk mockup e-wallet denda otomatis.</p>
        <form class="mt-4 flex gap-3" method="POST" action="/wallet/top-up">
            @csrf
            <input name="amount" type="number" min="1000" step="1000" placeholder="Top up saldo" class="min-w-0 flex-1 rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3">
            <button class="rounded-2xl bg-pink-500 px-4 py-3 font-semibold">Top Up</button>
        </form>
        <div class="mt-5 rounded-2xl border border-white/10 bg-slate-950/60 p-4 text-sm text-slate-300">
            <p>Status akun: <span class="font-semibold text-white">{{ $user->status }}</span></p>
            <p>Locked reason: {{ $user->locked_reason ?? '-' }}</p>
        </div>
    </section>
</div>

<script>
    const borrowLabels = @json(array_keys($monthlyBorrowings->toArray()));
    const borrowValues = @json(array_values($monthlyBorrowings->toArray()));
    const ctx = document.getElementById('borrowChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: borrowLabels.length ? borrowLabels : ['Tidak ada data'],
            datasets: [{
                label: 'Peminjaman',
                data: borrowValues.length ? borrowValues : [0],
                borderColor: '#f472b6',
                backgroundColor: 'rgba(244, 114, 182, 0.2)',
                tension: 0.35,
                fill: true,
            }],
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { color: '#e2e8f0' } } },
            scales: {
                x: { ticks: { color: '#cbd5e1' }, grid: { color: 'rgba(148,163,184,0.12)' } },
                y: { ticks: { color: '#cbd5e1' }, grid: { color: 'rgba(148,163,184,0.12)' } },
            },
        },
    });
</script>
@endsection