<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sonsha Fashion Rental' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(217, 119, 182, 0.4);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(217, 119, 182, 0.6);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 25%, #24243e 100%);
            background-attachment: fixed;
            color: #f5f5f5;
            overflow-x: hidden;
        }

        /* Animated background gradient */
        .animated-gradient {
            background: linear-gradient(
                -45deg,
                #d97762,
                #daa520,
                #c41e3a,
                #8b4789
            );
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Glass morphism effect */
        .glass {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
        }

        .glass-light {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .glass-hover {
            transition: all 0.3s ease;
        }

        .glass-hover:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(217, 119, 182, 0.4);
            transform: translateY(-5px);
            box-shadow: 0 10px 40px rgba(217, 119, 182, 0.15);
        }

        /* Luxury fashion card */
        .fashion-card {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            aspect-ratio: 9/12;
        }

        .fashion-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(217, 119, 182, 0) 0%, rgba(217, 119, 182, 0.2) 100%);
            z-index: 1;
        }

        .fashion-card:hover::before {
            background: linear-gradient(135deg, rgba(217, 119, 182, 0.1) 0%, rgba(217, 119, 182, 0.3) 100%);
        }

        .fashion-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .fashion-card:hover img {
            transform: scale(1.1);
        }

        .card-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.8) 100%);
            padding: 20px;
            z-index: 2;
            transform: translateY(30px);
            transition: transform 0.3s ease;
        }

        .fashion-card:hover .card-overlay {
            transform: translateY(0);
        }

        /* Luxury badge */
        .luxury-badge {
            display: inline-block;
            background: linear-gradient(135deg, #d97762 0%, #c41e3a 100%);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Shimmer animation */
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        .shimmer {
            animation: shimmer 2s infinite;
            background-image: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.1) 50%, rgba(255,255,255,0) 100%);
            background-size: 1000px 100%;
        }

        /* Smooth transitions */
        .btn-luxury {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-luxury::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-luxury:hover::before {
            width: 300px;
            height: 300px;
        }

        /* Status badge colors */
        .status-pending {
            background: rgba(217, 119, 182, 0.15);
            color: #d97762;
            border: 1px solid rgba(217, 119, 182, 0.3);
        }

        .status-approved {
            background: rgba(34, 197, 94, 0.15);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .status-returned {
            background: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .status-fine {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        /* Loading skeleton */
        .skeleton {
            background: linear-gradient(90deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.1) 50%, rgba(255,255,255,0.05) 100%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
            border-radius: 10px;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Animated background elements -->
    <div class="fixed inset-0 -z-50 overflow-hidden">
        <div class="absolute -top-1/2 -left-1/4 w-96 h-96 bg-gradient-to-br from-pink-500/10 to-purple-600/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-1/2 -right-1/4 w-96 h-96 bg-gradient-to-br from-purple-600/10 to-pink-500/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Navigation -->
    <nav class="glass sticky top-0 z-40 border-b border-white/10 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-rose-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-gem text-white"></i>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-widest text-pink-300 font-bold">Sonsha</p>
                        <p class="text-sm font-semibold">Fashion Rental</p>
                    </div>
                </div>

                <!-- Menu -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="/" class="text-sm hover:text-pink-300 transition">Beranda</a>
                    <a href="/catalog" class="text-sm hover:text-pink-300 transition">Koleksi</a>
                    <a href="/borrowings" class="text-sm hover:text-pink-300 transition">Peminjaman Saya</a>
                    <a href="/profile" class="text-sm hover:text-pink-300 transition">Profil</a>
                </div>

                <!-- User menu -->
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex flex-col items-end text-xs">
                        <p class="text-slate-300">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-pink-300 font-semibold">Rp {{ number_format(auth()->user()->balance ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="relative group">
                        <button class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center text-white hover:shadow-lg transition">
                            <i class="fas fa-user"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 glass hidden group-hover:block rounded-xl shadow-xl">
                            <a href="/profile" class="block px-4 py-2 text-sm hover:bg-white/10 rounded-t-xl">Profil</a>
                            <a href="/profile#settings" class="block px-4 py-2 text-sm hover:bg-white/10">Pengaturan</a>
                            <form method="POST" action="/logout" class="border-t border-white/10">
                                @csrf
                                <button class="w-full text-left px-4 py-2 text-sm hover:bg-white/10 rounded-b-xl">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <main class="min-h-[calc(100vh-64px)]">
        @if (session('status'))
            <div class="max-w-7xl mx-auto mt-4 px-4">
                <div class="glass border-l-4 border-emerald-400 bg-emerald-500/10 p-4 rounded-xl">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-emerald-400"></i>
                        <p class="text-sm text-emerald-200">{{ session('status') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="max-w-7xl mx-auto mt-4 px-4">
                <div class="glass border-l-4 border-red-400 bg-red-500/10 p-4 rounded-xl">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm text-red-200">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-20 border-t border-white/10 glass">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <p class="text-pink-300 font-bold text-sm uppercase tracking-widest mb-4">Sonsha</p>
                    <p class="text-xs text-slate-400">Platform rental fashion premium dengan koleksi terlengkap dan kualitas terjamin.</p>
                </div>
                <div>
                    <p class="font-semibold text-sm mb-4">Navigasi</p>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="/" class="hover:text-pink-300 transition">Beranda</a></li>
                        <li><a href="/catalog" class="hover:text-pink-300 transition">Koleksi</a></li>
                        <li><a href="/borrowings" class="hover:text-pink-300 transition">Peminjaman</a></li>
                    </ul>
                </div>
                <div>
                    <p class="font-semibold text-sm mb-4">Bantuan</p>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="#" class="hover:text-pink-300 transition">FAQ</a></li>
                        <li><a href="#" class="hover:text-pink-300 transition">Kontak</a></li>
                        <li><a href="#" class="hover:text-pink-300 transition">Kebijakan</a></li>
                    </ul>
                </div>
                <div>
                    <p class="font-semibold text-sm mb-4">Ikuti Kami</p>
                    <div class="flex gap-3">
                        <a href="#" class="w-8 h-8 glass hover:bg-pink-500/20 flex items-center justify-center rounded-lg transition">
                            <i class="fab fa-instagram text-pink-300"></i>
                        </a>
                        <a href="#" class="w-8 h-8 glass hover:bg-pink-500/20 flex items-center justify-center rounded-lg transition">
                            <i class="fab fa-twitter text-pink-300"></i>
                        </a>
                        <a href="#" class="w-8 h-8 glass hover:bg-pink-500/20 flex items-center justify-center rounded-lg transition">
                            <i class="fab fa-facebook text-pink-300"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-white/10 pt-6 flex justify-between items-center text-xs text-slate-400">
                <p>&copy; 2024 Sonsha Fashion Rental. All rights reserved.</p>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-pink-300 transition">Privacy</a>
                    <a href="#" class="hover:text-pink-300 transition">Terms</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</body>
</html>
