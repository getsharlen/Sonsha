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
        body {
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 25%, #24243e 100%);
            background-attachment: fixed;
        }
        
        .glass {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(217, 119, 182, 0.4);
            border-radius: 10px;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="fixed inset-0 -z-10 bg-[radial-gradient(circle_at_top_left,_rgba(236,72,153,0.18),transparent_35%),radial-gradient(circle_at_top_right,_rgba(59,130,246,0.16),transparent_30%),linear-gradient(180deg,#111827_0%,#0f172a_65%,#020617_100%)]"></div>
    
    <div class="mx-auto flex min-h-screen max-w-7xl">
        <!-- Sidebar -->
        <aside class="hidden w-80 shrink-0 border-r border-white/10 bg-white/5 p-6 backdrop-blur xl:block overflow-y-auto fixed h-screen">
            <div class="mb-8">
                <p class="text-xs uppercase tracking-[0.35em] text-pink-300">Sonsha Admin</p>
                <h1 class="mt-2 text-2xl font-semibold">Fashion Rental System</h1>
                <p class="mt-2 text-sm text-slate-300">Panel Administrasi & Manajemen</p>
            </div>
            
            <nav class="space-y-2 text-sm">
                <a href="/dashboard" class="block rounded-xl bg-white/10 px-4 py-3 {{ request()->is('dashboard*') ? 'bg-white/10' : 'hover:bg-white/10' }}">
                    <i class="fas fa-chart-line mr-2"></i> Dashboard
                </a>
                <a href="/assets" class="block rounded-xl px-4 py-3 hover:bg-white/10 {{ request()->is('assets*') ? 'bg-white/10' : '' }}">
                    <i class="fas fa-box mr-2"></i> Manajemen Alat
                </a>
                <a href="/borrowings" class="block rounded-xl px-4 py-3 hover:bg-white/10 {{ request()->is('borrowings*') ? 'bg-white/10' : '' }}">
                    <i class="fas fa-handshake mr-2"></i> Peminjaman
                </a>
                <a href="/categories" class="block rounded-xl px-4 py-3 hover:bg-white/10 {{ request()->is('categories*') ? 'bg-white/10' : '' }}">
                    <i class="fas fa-tags mr-2"></i> Kategori
                </a>
                <a href="/reports/technical" class="block rounded-xl px-4 py-3 hover:bg-white/10 {{ request()->is('reports/technical*') ? 'bg-white/10' : '' }}">
                    <i class="fas fa-file-alt mr-2"></i> Laporan
                </a>
            </nav>

            <div class="mt-10 rounded-2xl border border-white/10 bg-slate-950/60 p-4 text-sm text-slate-300">
                <p class="font-medium text-white">Admin Info</p>
                <p class="mt-2">{{ auth()->user()->name ?? 'Administrator' }}</p>
                <p class="text-xs">{{ auth()->user()->email }}</p>
                <p class="mt-3 font-medium text-white">Role</p>
                <p>{{ ucfirst(auth()->user()->role) }}</p>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8 xl:ml-80">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-5 py-4 backdrop-blur">
                <div>
                    <p class="text-sm text-slate-300">{{ now()->format('d F Y') }}</p>
                    <h2 class="text-xl font-semibold">{{ $pageTitle ?? 'Dashboard' }}</h2>
                </div>
                <form method="POST" action="/logout">
                    @csrf
                    <button class="rounded-xl bg-pink-500 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-600 transition">
                        Logout
                    </button>
                </form>
            </div>

            <!-- Alerts -->
            @if (session('status'))
                <div class="mb-5 rounded-xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-400/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">
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
