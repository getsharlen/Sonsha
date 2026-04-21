@extends('layouts.admin')

@section('content')
<div class="grid gap-6 xl:grid-cols-[1.05fr_1.35fr]">
    <section class="rounded-3xl border border-white/10 bg-white/5 p-5">
        <h3 class="text-lg font-semibold">Tambah Alat</h3>
        <form class="mt-4 grid gap-3" method="POST" action="/assets">
            @csrf
            <select name="category_id" class="rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            <input name="name" placeholder="Nama alat" class="rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3">
            <input name="brand" placeholder="Brand" class="rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3">
            <div class="grid grid-cols-2 gap-3">
                <input name="stock_total" type="number" placeholder="Stok total" class="rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3">
                <input name="stock_available" type="number" placeholder="Stok tersedia" class="rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <input name="rent_fee" type="number" placeholder="Tarif sewa" class="rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3">
                <select name="condition" class="rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3">
                    <option value="baik">Baik</option>
                    <option value="rusak">Rusak</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            <textarea name="description" placeholder="Deskripsi" class="rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3"></textarea>
            <input name="image_url" placeholder="URL gambar" class="rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3">
            <button class="rounded-2xl bg-pink-500 px-4 py-3 font-semibold">Simpan</button>
        </form>
    </section>

    <section class="rounded-3xl border border-white/10 bg-white/5 p-5">
        <h3 class="text-lg font-semibold">Daftar Alat</h3>
        <div class="mt-4 overflow-hidden rounded-2xl border border-white/10">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-950/80 text-slate-300">
                    <tr>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Stok</th>
                        <th class="px-4 py-3">Sewa</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assets as $asset)
                        <tr class="border-t border-white/10 bg-slate-950/40">
                            <td class="px-4 py-3">
                                <p class="font-medium text-white">{{ $asset->name }}</p>
                                <p class="text-xs text-slate-400">{{ $asset->category?->name }}</p>
                            </td>
                            <td class="px-4 py-3">{{ $asset->stock_available }}/{{ $asset->stock_total }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($asset->rent_fee, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <button type="button" onclick="document.getElementById('asset-detail-{{ $asset->id }}').classList.toggle('hidden')" class="rounded-lg bg-indigo-500 px-2 py-1 text-xs">Detail</button>
                                    <button type="button" onclick="document.getElementById('asset-edit-{{ $asset->id }}').classList.toggle('hidden')" class="rounded-lg bg-sky-500 px-2 py-1 text-xs">Edit</button>
                                    <form method="POST" action="/assets/{{ $asset->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rounded-lg bg-rose-500 px-2 py-1 text-xs">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <tr id="asset-detail-{{ $asset->id }}" class="hidden border-t border-white/10 bg-slate-900/50 text-xs text-slate-300">
                            <td colspan="4" class="px-4 py-3">
                                <p>Kode: {{ $asset->code }} | Kondisi: {{ $asset->condition }} | Brand: {{ $asset->brand ?: '-' }}</p>
                                <p class="mt-1">Deskripsi: {{ $asset->description ?: '-' }}</p>
                                <p class="mt-1">Gambar: {{ $asset->image_url ?: '-' }}</p>
                            </td>
                        </tr>
                        <tr id="asset-edit-{{ $asset->id }}" class="hidden border-t border-white/10 bg-slate-900/60">
                            <td colspan="4" class="px-4 py-3">
                                <form class="grid gap-2 md:grid-cols-2" method="POST" action="/assets/{{ $asset->id }}">
                                    @csrf
                                    @method('PUT')
                                    <select name="category_id" class="rounded-xl border border-white/10 bg-slate-950/80 px-3 py-2 text-xs">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" @selected($asset->category_id === $category->id)>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <input name="name" value="{{ $asset->name }}" class="rounded-xl border border-white/10 bg-slate-950/80 px-3 py-2 text-xs">
                                    <input name="brand" value="{{ $asset->brand }}" class="rounded-xl border border-white/10 bg-slate-950/80 px-3 py-2 text-xs">
                                    <input name="rent_fee" type="number" value="{{ $asset->rent_fee }}" class="rounded-xl border border-white/10 bg-slate-950/80 px-3 py-2 text-xs">
                                    <input name="stock_total" type="number" value="{{ $asset->stock_total }}" class="rounded-xl border border-white/10 bg-slate-950/80 px-3 py-2 text-xs">
                                    <input name="stock_available" type="number" value="{{ $asset->stock_available }}" class="rounded-xl border border-white/10 bg-slate-950/80 px-3 py-2 text-xs">
                                    <select name="condition" class="rounded-xl border border-white/10 bg-slate-950/80 px-3 py-2 text-xs">
                                        <option value="baik" @selected($asset->condition === 'baik')>Baik</option>
                                        <option value="rusak" @selected($asset->condition === 'rusak')>Rusak</option>
                                        <option value="maintenance" @selected($asset->condition === 'maintenance')>Maintenance</option>
                                    </select>
                                    <input name="image_url" value="{{ $asset->image_url }}" class="rounded-xl border border-white/10 bg-slate-950/80 px-3 py-2 text-xs">
                                    <textarea name="description" class="rounded-xl border border-white/10 bg-slate-950/80 px-3 py-2 text-xs md:col-span-2">{{ $asset->description }}</textarea>
                                    <button class="rounded-xl bg-sky-500 px-3 py-2 text-xs font-semibold md:col-span-2">Simpan Perubahan</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $assets->links() }}</div>
    </section>
</div>
@endsection