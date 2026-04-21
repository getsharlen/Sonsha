<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register Sonsha</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-950 text-white">
    <div class="flex min-h-screen items-center justify-center p-6">
        <div class="w-full max-w-lg rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur">
            <h1 class="text-3xl font-semibold">Daftar akun peminjam</h1>
            <form class="mt-8 grid gap-4" method="POST" action="{{ route('register.submit') }}">
                @csrf
                <input name="name" placeholder="Nama lengkap" class="rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3">
                <input name="email" placeholder="Email" type="email" class="rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3">
                <input name="phone" placeholder="Nomor HP" class="rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3">
                <input name="password" placeholder="Password" type="password" class="rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3">
                <input name="password_confirmation" placeholder="Konfirmasi password" type="password" class="rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3">
                <button class="rounded-2xl bg-pink-500 px-4 py-3 font-semibold">Buat Akun</button>
            </form>
            <p class="mt-6 text-sm text-slate-300"><a class="text-pink-300 underline" href="/login">Kembali ke login</a></p>
        </div>
    </div>
</body>
</html>