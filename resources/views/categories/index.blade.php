@extends('layouts.admin')

@section('content')
<div class="grid gap-6 xl:grid-cols-2">
    <section class="glass-panel rounded-3xl p-5">
        <h3 class="text-lg font-semibold">Tambah Kategori</h3>
        <form class="mt-4 grid gap-3" method="POST" action="/categories">
            @csrf
            <input name="name" placeholder="Nama kategori" class="field-input" required>
            <textarea name="description" placeholder="Deskripsi" class="field-input min-h-24"></textarea>
            <button class="rounded-2xl bg-gradient-to-r from-pink-500 to-rose-600 px-4 py-3 font-semibold text-white transition hover:brightness-110">Simpan</button>
        </form>
    </section>

    <section class="glass-panel rounded-3xl p-5">
        <h3 class="text-lg font-semibold">Daftar Kategori</h3>
        <div class="mt-4 space-y-3 text-sm">
            @foreach($categories as $category)
                <div class="rounded-2xl bg-slate-950/60 p-4">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="font-medium text-white">{{ $category->name }}</p>
                            <p class="text-xs text-slate-400">Total alat: {{ $category->assets_count }}</p>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" onclick="document.getElementById('edit-category-{{ $category->id }}').classList.toggle('hidden')" class="rounded-xl bg-sky-500 px-3 py-2 text-xs font-semibold text-white">Edit</button>
                            <form method="POST" action="/categories/{{ $category->id }}" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-xl bg-rose-500 px-3 py-2 text-xs font-semibold text-white">Hapus</button>
                            </form>
                        </div>
                    </div>
                    <p class="mt-2 text-slate-300">{{ $category->description ?: '-' }}</p>
                    <div id="edit-category-{{ $category->id }}" class="mt-3 hidden rounded-xl border border-white/10 bg-slate-900/50 p-3">
                        <form class="grid gap-2" method="POST" action="/categories/{{ $category->id }}">
                            @csrf
                            @method('PUT')
                            <input name="name" value="{{ $category->name }}" class="field-input" required>
                            <textarea name="description" class="field-input">{{ $category->description }}</textarea>
                            <button class="rounded-xl bg-sky-500 px-3 py-2 text-xs font-semibold text-white">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection