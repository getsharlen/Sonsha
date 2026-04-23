@extends('layouts.admin')

@section('content')
<div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(0,1.45fr)]">
    <section class="glass-panel rounded-3xl p-5">
        <h3 class="text-lg font-semibold text-white">Tambah Alat</h3>
        <p class="mt-1 text-sm text-slate-400">Upload gambar via drag-and-drop atau pakai URL eksternal.</p>

        <form class="mt-4 grid gap-3" method="POST" action="/assets" enctype="multipart/form-data">
            @csrf

            <select name="category_id" class="field-input" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>

            <input name="name" value="{{ old('name') }}" placeholder="Nama alat" class="field-input" required>
            <input name="brand" value="{{ old('brand') }}" placeholder="Brand" class="field-input">

            <div class="grid grid-cols-2 gap-3">
                <input name="stock_total" value="{{ old('stock_total') }}" type="number" min="0" placeholder="Stok total" class="field-input" required>
                <input name="stock_available" value="{{ old('stock_available') }}" type="number" min="0" placeholder="Stok tersedia" class="field-input" required>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <input name="rent_fee" value="{{ old('rent_fee') }}" type="number" min="0" placeholder="Tarif sewa" class="field-input" required>
                <select name="condition" class="field-input" required>
                    <option value="baik" @selected(old('condition') === 'baik')>Baik</option>
                    <option value="rusak" @selected(old('condition') === 'rusak')>Rusak</option>
                    <option value="maintenance" @selected(old('condition') === 'maintenance')>Maintenance</option>
                </select>
            </div>

            <textarea name="description" placeholder="Deskripsi" class="field-input min-h-24">{{ old('description') }}</textarea>

            <div id="asset-dropzone" class="group cursor-pointer rounded-2xl border border-dashed border-pink-300/40 bg-slate-900/40 px-4 py-6 transition hover:border-pink-300 hover:bg-slate-900/60">
                <input id="asset-image-file" name="image_file" type="file" accept="image/png,image/jpeg,image/jpg,image/webp" class="hidden">
                <div class="flex flex-col items-center justify-center gap-2 text-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-pink-500/20 text-pink-200">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <p class="text-sm font-medium text-white">Drop file gambar di sini</p>
                    <p class="text-xs text-slate-400">atau klik untuk pilih file (JPG, PNG, WEBP, maks 2MB)</p>
                    <p id="asset-image-filename" class="text-xs text-pink-200"></p>
                </div>
            </div>

            <button class="rounded-2xl bg-gradient-to-r from-pink-500 to-rose-600 px-4 py-3 font-semibold text-white transition hover:brightness-110">Simpan</button>
        </form>
    </section>

    <section class="glass-panel rounded-3xl p-5">
        <h3 class="text-lg font-semibold text-white">Daftar Alat</h3>
        <div class="mt-4 overflow-x-auto rounded-2xl border border-white/10">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-950/80 text-slate-300">
                    <tr>
                        <th class="px-4 py-3">Item</th>
                        <th class="px-4 py-3">Stok</th>
                        <th class="px-4 py-3">Sewa</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assets as $asset)
                        <tr class="border-t border-white/10 bg-slate-950/35">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($asset->image_source)
                                        <img src="{{ $asset->image_source }}" alt="{{ $asset->name }}" class="h-12 w-12 rounded-lg object-cover">
                                    @else
                                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-slate-800 text-slate-500">
                                            <i class="fas fa-image text-xs"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-white">{{ $asset->name }}</p>
                                        <p class="text-xs text-slate-400">{{ $asset->category?->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $asset->stock_available }}/{{ $asset->stock_total }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($asset->rent_fee, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" onclick="document.getElementById('asset-detail-{{ $asset->id }}').classList.toggle('hidden')" class="rounded-lg bg-white/10 px-3 py-1 text-xs hover:bg-white/20">Detail</button>
                                    <button type="button" onclick="document.getElementById('asset-edit-{{ $asset->id }}').classList.toggle('hidden')" class="rounded-lg bg-sky-500/80 px-3 py-1 text-xs text-white hover:bg-sky-500">Edit</button>
                                    <form method="POST" action="/assets/{{ $asset->id }}" class="confirm-action" data-confirm="Hapus data alat ini?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rounded-lg bg-rose-500/80 px-3 py-1 text-xs text-white hover:bg-rose-500">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <tr id="asset-detail-{{ $asset->id }}" class="hidden border-t border-white/10 bg-slate-900/45 text-xs text-slate-300">
                            <td colspan="4" class="px-4 py-3">
                                <div class="grid gap-3 md:grid-cols-[120px_1fr]">
                                    <div class="overflow-hidden rounded-xl border border-white/10 bg-slate-900">
                                        @if($asset->image_source)
                                            <img src="{{ $asset->image_source }}" alt="{{ $asset->name }}" class="h-24 w-full object-cover">
                                        @else
                                            <div class="flex h-24 items-center justify-center text-slate-500">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p>Kode: {{ $asset->code }} | Kondisi: {{ $asset->condition }} | Brand: {{ $asset->brand ?: '-' }}</p>
                                        <p class="mt-1">Deskripsi: {{ $asset->description ?: '-' }}</p>
                                        <p class="mt-1">Gambar upload: {{ $asset->image_source ? 'Tersedia' : '-' }}</p>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr id="asset-edit-{{ $asset->id }}" class="hidden border-t border-white/10 bg-slate-900/60">
                            <td colspan="4" class="px-4 py-3">
                                <form class="grid gap-2 md:grid-cols-2" method="POST" action="/assets/{{ $asset->id }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <select name="category_id" class="field-input text-xs" required>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" @selected($asset->category_id === $category->id)>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <input name="name" value="{{ $asset->name }}" class="field-input text-xs" required>
                                    <input name="brand" value="{{ $asset->brand }}" class="field-input text-xs">
                                    <input name="rent_fee" type="number" min="0" value="{{ $asset->rent_fee }}" class="field-input text-xs" required>
                                    <input name="stock_total" type="number" min="0" value="{{ $asset->stock_total }}" class="field-input text-xs" required>
                                    <input name="stock_available" type="number" min="0" value="{{ $asset->stock_available }}" class="field-input text-xs" required>

                                    <select name="condition" class="field-input text-xs" required>
                                        <option value="baik" @selected($asset->condition === 'baik')>Baik</option>
                                        <option value="rusak" @selected($asset->condition === 'rusak')>Rusak</option>
                                        <option value="maintenance" @selected($asset->condition === 'maintenance')>Maintenance</option>
                                    </select>

                                    <div class="asset-edit-dropzone cursor-pointer rounded-xl border border-dashed border-pink-300/35 bg-slate-900/35 px-3 py-4 md:col-span-2" data-target="asset-image-file-edit-{{ $asset->id }}" data-filename="asset-image-filename-edit-{{ $asset->id }}">
                                        <input id="asset-image-file-edit-{{ $asset->id }}" name="image_file" type="file" accept="image/png,image/jpeg,image/jpg,image/webp" class="hidden">
                                        <div class="flex items-center justify-between gap-2">
                                            <div class="flex items-center gap-2 text-xs text-slate-300">
                                                <i class="fas fa-upload text-pink-300"></i>
                                                <span>Drop/klik untuk ganti gambar</span>
                                            </div>
                                            <span id="asset-image-filename-edit-{{ $asset->id }}" class="text-xs text-pink-200"></span>
                                        </div>
                                    </div>

                                    <label class="inline-flex items-center gap-2 text-xs text-slate-300 md:col-span-2">
                                        <input type="checkbox" name="remove_image" value="1" class="rounded border-white/20 bg-slate-900/60">
                                        Hapus gambar saat ini
                                    </label>

                                    <textarea name="description" class="field-input text-xs md:col-span-2">{{ $asset->description }}</textarea>
                                    <button class="rounded-xl bg-sky-500 px-3 py-2 text-xs font-semibold text-white transition hover:bg-sky-400 md:col-span-2">Simpan Perubahan</button>
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

<script>
    (function () {
        const wireDropzone = (dropzone, input, fileName, withLabel = false) => {
            if (!dropzone || !input || !fileName) {
                return;
            }

            const updateFileName = (file) => {
                if (!file) {
                    fileName.textContent = '';
                    return;
                }

                fileName.textContent = withLabel ? `File dipilih: ${file.name}` : file.name;
            };

            dropzone.addEventListener('click', () => input.click());

            input.addEventListener('change', () => {
                updateFileName(input.files && input.files[0] ? input.files[0] : null);
            });

            ['dragenter', 'dragover'].forEach((eventName) => {
                dropzone.addEventListener(eventName, (event) => {
                    event.preventDefault();
                    dropzone.classList.add('ring-2', 'ring-pink-400');
                });
            });

            ['dragleave', 'drop'].forEach((eventName) => {
                dropzone.addEventListener(eventName, (event) => {
                    event.preventDefault();
                    dropzone.classList.remove('ring-2', 'ring-pink-400');
                });
            });

            dropzone.addEventListener('drop', (event) => {
                if (!event.dataTransfer || !event.dataTransfer.files.length) {
                    return;
                }

                input.files = event.dataTransfer.files;
                updateFileName(event.dataTransfer.files[0]);
            });
        };

        wireDropzone(
            document.getElementById('asset-dropzone'),
            document.getElementById('asset-image-file'),
            document.getElementById('asset-image-filename'),
            true
        );

        document.querySelectorAll('.asset-edit-dropzone').forEach((dropzone) => {
            const input = document.getElementById(dropzone.dataset.target);
            const fileName = document.getElementById(dropzone.dataset.filename);
            wireDropzone(dropzone, input, fileName, false);
        });
    })();
</script>
@endsection