<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Sonsha</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                        <input name="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 outline-none focus:border-pink-400" type="email">
                        @error('email')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm">Password</label>
                        <input name="password" class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 outline-none focus:border-pink-400" type="password">
                    </div>
                    <button class="w-full rounded-2xl bg-pink-500 px-4 py-3 font-semibold">Login</button>
                </form>

                <p class="mt-6 text-sm text-slate-300">Belum punya akun? <a class="text-pink-300 underline" href="/register">Daftar</a></p>
                <div class="mt-6 rounded-2xl bg-slate-950/70 p-4 text-sm text-slate-300">
                    <p class="font-medium text-white">Akun demo</p>
                    <p>admin@sonsha.test / password</p>
                    <p>petugas@sonsha.test / password</p>
                    <p>peminjam@sonsha.test / password</p>
                </div>
            </div>
        </div>
        <div class="hidden bg-[radial-gradient(circle_at_top_right,_rgba(244,114,182,0.3),transparent_30%),linear-gradient(135deg,#111827_0%,#0f172a_45%,#020617_100%)] p-12 lg:flex lg:flex-col lg:justify-between">
            <div>
                <h2 class="text-4xl font-semibold leading-tight">Peminjaman fashion dengan analytics, saldo virtual, dan kontrol role.</h2>
                <p class="mt-4 max-w-xl text-slate-300">Project ini disiapkan untuk kebutuhan UKK dengan view dinamis, dashboard grafik, dan dokumen teknis yang bisa diekspor ke PDF.</p>
            </div>
            <div class="grid grid-cols-3 gap-4 text-sm">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-4">3 Role</div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-4">Dashboard Analytics</div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-4">Denda Otomatis</div>
            </div>
        </div>
    </div>
</body>
</html>