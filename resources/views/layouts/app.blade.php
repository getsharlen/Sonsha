<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="min-h-screen bg-[#0f172a] text-slate-100">
    <div class="fixed inset-0 -z-10 bg-[radial-gradient(circle_at_top_left,_rgba(236,72,153,0.18),transparent_35%),radial-gradient(circle_at_top_right,_rgba(59,130,246,0.16),transparent_30%),linear-gradient(180deg,#111827_0%,#0f172a_65%,#020617_100%)]"></div>
    <div class="mx-auto flex min-h-screen max-w-7xl">
        <aside class="hidden w-80 shrink-0 border-r border-white/10 bg-white/5 p-6 backdrop-blur xl:block">
            <div class="mb-8">
                <p class="text-xs uppercase tracking-[0.35em] text-pink-300">Sonsha</p>
                <h1 class="mt-2 text-2xl font-semibold">Fashion Rental System</h1>
                <p class="mt-2 text-sm text-slate-300">Peminjaman barang fashion dan teknis dengan analytics serta denda otomatis.</p>
            </div>
            <nav class="space-y-2 text-sm">
                <a class="block rounded-xl bg-white/10 px-4 py-3" href="/dashboard">Dashboard</a>
                <a class="block rounded-xl px-4 py-3 hover:bg-white/10" href="/borrowings">Peminjaman</a>
                <a class="block rounded-xl px-4 py-3 hover:bg-white/10" href="/assets">Alat</a>
                <a class="block rounded-xl px-4 py-3 hover:bg-white/10" href="/categories">Kategori</a>
                <a class="block rounded-xl px-4 py-3 hover:bg-white/10" href="/reports/technical">Laporan Teknis</a>
            </nav>
            <div class="mt-10 rounded-2xl border border-white/10 bg-slate-950/60 p-4 text-sm text-slate-300">
                <p class="font-medium text-white">Role aktif</p>
                <p>{{ auth()->user()?->role ?? '-' }}</p>
                <p class="mt-4 font-medium text-white">Saldo</p>
                <p>Rp {{ number_format(auth()->user()?->balance ?? 0, 0, ',', '.') }}</p>
            </div>
        </aside>

        <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-5 py-4 backdrop-blur">
                <div>
                    <p class="text-sm text-slate-300">{{ now()->format('d F Y') }}</p>
                    <h2 class="text-xl font-semibold">{{ $pageTitle ?? 'Dashboard' }}</h2>
                </div>
                <form method="POST" action="/logout">
                    @csrf
                    <button class="rounded-xl bg-pink-500 px-4 py-2 text-sm font-semibold text-white">Logout</button>
                </form>
            </div>

            @if (session('status'))
                <div class="mb-5 rounded-xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">{{ session('status') }}</div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>