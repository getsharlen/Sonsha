<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Sonsha</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .login-right-side {
            /* Layer 1: Overlay Gradient (Tetap dipertahankan agar teks tetap terbaca) */
            /* Layer 2: Gambar dari Storage Laravel */
            background: linear-gradient(135deg, rgba(244, 114, 182, 0.4) 0%, rgba(236, 72, 153, 0.3) 25%, rgba(219, 39, 119, 0.2) 50%, rgba(191, 33, 103, 0.15) 75%, #020617 100%),
                        url("{{ asset('storage/assets/fashion.jpg') }}");
               
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body class="min-h-screen bg-[#020617] text-white">
    <div class="grid min-h-screen lg:grid-cols-2">
        <div class="flex items-center justify-center p-6 lg:p-12">
            <div class="w-full max-w-md rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur">
                <p class="text-xs uppercase tracking-[0.35em] text-pink-300">Sonsha</p>
                <h1 class="mt-3 text-3xl font-semibold">Masuk ke sistem</h1>
                <p class="mt-2 text-sm text-slate-300">Login untuk admin, petugas, atau peminjam.</p>

                <form class="mt-8 space-y-4" method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm">Email</label>
                        <input name="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 outline-none focus:border-pink-400" type="email" required>
                        @error('email')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm">Password</label>
                        <input name="password" class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 outline-none focus:border-pink-400" type="password" required>
                    </div>
                    <button class="w-full rounded-2xl bg-gradient-to-r from-pink-500 to-rose-600 px-4 py-3 font-semibold transition hover:brightness-110">Login</button>
                </form>

                <p class="mt-6 text-sm text-slate-300">Belum punya akun? <a class="text-pink-300 underline hover:text-pink-200" href="/register">Daftar</a></p>
                <div class="mt-6 rounded-2xl bg-slate-950/70 p-4 text-sm text-slate-300">
                    <p class="font-medium text-white">Akun demo</p>
                    <p>admin@sonsha.test / password</p>
                    <p>petugas@sonsha.test / password</p>
                    <p>peminjam@sonsha.test / password</p>
                </div>
            </div>
        </div>
        <div class="login-right-side hidden lg:flex lg:flex-col lg:justify-between p-12">
            
        </div>
    </div>
</body>
</html>