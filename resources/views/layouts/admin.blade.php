<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Dashboard - Sonsha Fashion Rental' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); }

        ::-webkit-scrollbar-thumb {
            background: rgba(217, 119, 182, 0.4);
            border-radius: 10px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.14);
        }

        .nav-item-active {
            background: linear-gradient(135deg, rgba(236, 72, 153, 0.28), rgba(225, 29, 72, 0.2));
            border: 1px solid rgba(236, 72, 153, 0.35);
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="fixed inset-0 -z-20 bg-[radial-gradient(circle_at_top_left,_rgba(217,119,182,0.14),transparent_35%),radial-gradient(circle_at_bottom_right,_rgba(236,72,153,0.12),transparent_35%),linear-gradient(180deg,#111827_0%,#0f172a_60%,#020617_100%)]"></div>

    <div class="mx-auto flex min-h-screen max-w-7xl gap-4 px-4 py-4 sm:px-6">
        <aside class="glass-card hidden w-72 shrink-0 rounded-2xl p-4 xl:block">
            <div class="mb-6 border-b border-white/10 pb-4">
                <p class="text-xs uppercase tracking-[0.35em] text-pink-300">Sonsha Admin</p>
                <h1 class="mt-2 text-xl font-semibold">Fashion Rental System</h1>
                <p class="mt-1 text-xs text-slate-300">Panel Administrasi</p>
            </div>

            <nav class="space-y-2 text-sm">
                <a href="/dashboard" class="block rounded-xl px-4 py-3 transition {{ request()->is('dashboard*') ? 'nav-item-active' : 'hover:bg-white/10' }}">
                    <i class="fas fa-chart-line mr-2"></i> Dashboard
                </a>
                <a href="/assets" class="block rounded-xl px-4 py-3 transition {{ request()->is('assets*') ? 'nav-item-active' : 'hover:bg-white/10' }}">
                    <i class="fas fa-box mr-2"></i> Manajemen Alat
                </a>
                <a href="/borrowings" class="block rounded-xl px-4 py-3 transition {{ request()->is('borrowings*') ? 'nav-item-active' : 'hover:bg-white/10' }}">
                    <i class="fas fa-handshake mr-2"></i> Peminjaman
                </a>
                <a href="/categories" class="block rounded-xl px-4 py-3 transition {{ request()->is('categories*') ? 'nav-item-active' : 'hover:bg-white/10' }}">
                    <i class="fas fa-tags mr-2"></i> Kategori
                </a>
                <a href="/reports/technical" class="block rounded-xl px-4 py-3 transition {{ request()->is('reports/technical*') ? 'nav-item-active' : 'hover:bg-white/10' }}">
                    <i class="fas fa-file-alt mr-2"></i> Laporan
                </a>
            </nav>

            <div class="mt-6 rounded-2xl border border-white/10 bg-slate-950/55 p-4 text-sm text-slate-300">
                <p class="font-medium text-white">Admin Info</p>
                <p class="mt-2">{{ auth()->user()->name ?? 'Administrator' }}</p>
                <p class="text-xs">{{ auth()->user()->email }}</p>
                <p class="mt-3 text-xs uppercase tracking-[0.2em] text-slate-400">Role</p>
                <p>{{ ucfirst(auth()->user()->role) }}</p>
            </div>
        </aside>

        <main class="min-w-0 flex-1">
            <div class="glass-card mb-4 rounded-2xl px-4 py-3 sm:px-5 sm:py-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-slate-300">{{ now()->format('d F Y') }}</p>
                        <h2 class="text-xl font-semibold">{{ $pageTitle ?? 'Dashboard' }}</h2>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="/dashboard" class="rounded-xl border border-white/15 bg-white/5 px-3 py-2 text-xs text-slate-200 transition hover:bg-white/10 xl:hidden">
                            <i class="fas fa-grid-2 mr-1"></i> Menu
                        </a>
                        <form method="POST" action="/logout">
                            @csrf
                            <button class="rounded-xl bg-gradient-to-r from-pink-500 to-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:brightness-110">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            @if (session('status'))
                <div class="glass-card mb-4 rounded-xl border-emerald-400/35 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="glass-card mb-4 rounded-xl border-red-400/35 bg-red-500/10 px-4 py-3 text-sm text-red-100">
                    @foreach ($errors->all() as $error)
                        <p><i class="fas fa-exclamation-circle mr-2"></i> {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
