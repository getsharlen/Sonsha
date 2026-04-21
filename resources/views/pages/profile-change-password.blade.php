@extends('layouts.user')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="/profile" class="text-slate-400 hover:text-pink-300 text-sm transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Profil
        </a>
    </div>

    <div class="glass-light glass p-8 rounded-2xl">
        <h1 class="text-3xl font-bold mb-2">Ubah Password</h1>
        <p class="text-slate-400 mb-8">Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol agar akun lebih aman.</p>

        <form method="POST" action="/profile/change-password" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="text-sm text-slate-300 mb-2 block">Password Saat Ini</label>
                <input
                    type="password"
                    name="current_password"
                    required
                    autocomplete="current-password"
                    class="w-full rounded-xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white focus:border-pink-400 focus:outline-none"
                >
            </div>

            <div>
                <label class="text-sm text-slate-300 mb-2 block">Password Baru</label>
                <input
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    class="w-full rounded-xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white focus:border-pink-400 focus:outline-none"
                >
            </div>

            <div>
                <label class="text-sm text-slate-300 mb-2 block">Konfirmasi Password Baru</label>
                <input
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="w-full rounded-xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white focus:border-pink-400 focus:outline-none"
                >
            </div>

            <div class="pt-2 flex gap-3">
                <button type="submit" class="btn-luxury bg-gradient-to-r from-pink-500 to-rose-600 px-6 py-3 rounded-lg font-semibold">
                    Simpan Password Baru
                </button>
                <a href="/profile" class="rounded-lg border border-white/15 px-6 py-3 text-sm text-slate-300 hover:bg-white/5 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
