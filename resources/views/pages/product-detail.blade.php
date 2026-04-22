@extends('layouts.user')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <div class="mb-8 flex items-center gap-3 text-sm">
        <a href="/catalog" class="text-slate-400 hover:text-pink-300 transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
        <span class="text-slate-600">•</span>
        <span class="text-slate-400">{{ $asset->category->name ?? 'Kategori' }}</span>
        <span class="text-slate-600">•</span>
        <span class="text-pink-300">{{ $asset->name }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-12">
        <!-- Product Image -->
        <div>
            <div class="fashion-card glass-hover mb-6 aspect-square">
                @if($asset->image_source)
                    <img src="{{ $asset->image_source }}" alt="{{ $asset->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center">
                        <i class="fas fa-image text-slate-500 text-6xl"></i>
                    </div>
                @endif
            </div>

            <!-- Image Gallery Thumbnails -->
            <div class="flex gap-3">
                @for($i = 0; $i < 4; $i++)
                    <div class="glass-hover glass w-20 h-20 rounded-xl overflow-hidden cursor-pointer hover:border-pink-400">
                        @if($asset->image_source)
                            <img src="{{ $asset->image_source }}" alt="Gallery" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center">
                                <i class="fas fa-image text-slate-500"></i>
                            </div>
                        @endif
                    </div>
                @endfor
            </div>
        </div>

        <!-- Product Info -->
        <div>
            <!-- Badges -->
            <div class="mb-6 flex flex-wrap gap-3">
                @if($asset->stock_available > 5)
                    <span class="luxury-badge">In Stock</span>
                @elseif($asset->stock_available > 0)
                    <span class="inline-block bg-yellow-500/20 border border-yellow-400 text-yellow-300 px-4 py-1 rounded-full text-sm font-bold">
                        Stok Terbatas
                    </span>
                @else
                    <span class="inline-block bg-red-500/20 border border-red-400 text-red-300 px-4 py-1 rounded-full text-sm font-bold">
                        Habis
                    </span>
                @endif

                <span class="glass px-4 py-1 rounded-full text-sm">{{ $asset->category->name }}</span>
            </div>

            <!-- Title and Brand -->
            <h1 class="text-5xl font-bold mb-2 bg-gradient-to-r from-pink-300 to-rose-300 bg-clip-text text-transparent">
                {{ $asset->name }}
            </h1>
            <p class="text-2xl font-light text-slate-300 mb-6">{{ $asset->brand }}</p>

            <!-- Rating -->
            <div class="flex items-center gap-4 mb-8 pb-8 border-b border-white/10">
                <div class="flex items-center gap-1">
                    @for($i = 0; $i < 5; $i++)
                        <i class="fas fa-star {{ $i < 4 ? 'text-yellow-400' : 'text-slate-600' }}"></i>
                    @endfor
                </div>
                <span class="text-sm text-slate-400">(142 ulasan)</span>
                <a href="#reviews" class="text-pink-300 hover:text-pink-200 transition">Lihat ulasan</a>
            </div>

            <!-- Price Section -->
            <div class="mb-8">
                <p class="text-sm text-slate-400 mb-2">Harga Sewa Per Hari</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-5xl font-bold text-pink-300">
                        Rp {{ number_format($asset->rent_fee, 0, ',', '.') }}
                    </span>
                    <span class="text-slate-400">/hari</span>
                </div>

                <!-- Rental Duration Calculator -->
                <div class="mt-6 glass p-6 rounded-2xl">
                    <p class="text-sm font-semibold mb-4">Hitung Total Sewa</p>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs text-slate-400 mb-1 block">Durasi (hari)</label>
                            <input type="number" id="rentalDays" value="1" min="1" max="30" 
                                class="w-full glass bg-white/10 border-0 text-white py-2 px-3 rounded-lg focus:ring-2 focus:ring-pink-500 transition">
                        </div>
                        <div class="pt-3 border-t border-white/10">
                            <div class="flex justify-between mb-2">
                                <span class="text-sm text-slate-400">Subtotal:</span>
                                <span class="text-sm font-semibold" id="subtotal">Rp {{ number_format($asset->rent_fee, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold text-pink-300">Total:</span>
                                <span class="text-2xl font-bold text-pink-300" id="total">Rp {{ number_format($asset->rent_fee, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-8">
                <h3 class="font-bold text-lg mb-3">Deskripsi</h3>
                <p class="text-slate-300 leading-relaxed">{{ $asset->description }}</p>
            </div>

            <!-- Details -->
            <div class="mb-8 glass p-6 rounded-2xl">
                <h3 class="font-bold text-lg mb-4">Spesifikasi</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between pb-3 border-b border-white/10">
                        <span class="text-slate-400">Brand</span>
                        <span class="font-semibold">{{ $asset->brand }}</span>
                    </div>
                    <div class="flex items-center justify-between pb-3 border-b border-white/10">
                        <span class="text-slate-400">Kategori</span>
                        <span class="font-semibold">{{ $asset->category->name }}</span>
                    </div>
                    <div class="flex items-center justify-between pb-3 border-b border-white/10">
                        <span class="text-slate-400">Kondisi</span>
                        <span class="font-semibold">
                            @if($asset->condition == 'baik')
                                <i class="fas fa-check-circle text-emerald-400"></i> Baik
                            @elseif($asset->condition == 'maintenance')
                                <i class="fas fa-wrench text-yellow-400"></i> Maintenance
                            @else
                                {{ $asset->condition }}
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Stok Tersedia</span>
                        <span class="font-semibold text-pink-300">{{ $asset->stock_available }}/{{ $asset->stock_total }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            @if($asset->stock_available > 0)
                <form action="/borrowings" method="POST" class="space-y-3 mb-8">
                    @csrf
                    <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                    <div>
                        <label class="text-sm text-slate-400 mb-2 block">Jumlah Rental</label>
                        <input type="number" name="quantity" value="1" min="1" max="{{ $asset->stock_available }}" 
                            class="w-full glass bg-white/10 border-0 text-white py-3 px-4 rounded-lg focus:ring-2 focus:ring-pink-500 transition">
                    </div>
                    <div>
                        <label class="text-sm text-slate-400 mb-2 block">Tanggal Jatuh Tempo</label>
                        <input type="date" name="due_at" required
                            class="w-full glass bg-white/10 border-0 text-white py-3 px-4 rounded-lg focus:ring-2 focus:ring-pink-500 transition">
                    </div>
                    <div>
                        <label class="text-sm text-slate-400 mb-2 block">Tujuan Penggunaan</label>
                        <textarea name="purpose" rows="3" placeholder="Jelaskan tujuan peminjaman item ini..."
                            class="w-full glass bg-white/10 border-0 text-white py-3 px-4 rounded-lg focus:ring-2 focus:ring-pink-500 transition resize-none placeholder-slate-500"></textarea>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="submit" class="flex-1 btn-luxury bg-gradient-to-r from-pink-500 to-rose-600 hover:shadow-lg hover:shadow-pink-500/50 py-3 rounded-lg font-semibold transition flex items-center justify-center gap-2">
                            <i class="fas fa-shopping-bag"></i>
                            Pesan Sekarang
                        </button>
                        <button type="button" class="btn-luxury glass-hover glass px-6 py-3 rounded-lg font-semibold transition flex items-center justify-center gap-2">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </form>
            @else
                <div class="bg-red-500/10 border border-red-400/30 rounded-xl p-4 text-red-300 text-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Item ini saat ini tidak tersedia
                </div>
            @endif

            <!-- Additional Info -->
            <div class="glass p-6 rounded-2xl">
                <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-pink-300"></i>
                    Informasi Penting
                </h3>
                <ul class="space-y-3 text-sm text-slate-300">
                    <li class="flex gap-3">
                        <i class="fas fa-check text-pink-300 mt-1 flex-shrink-0"></i>
                        <span>Harga sudah termasuk asuransi dasar</span>
                    </li>
                    <li class="flex gap-3">
                        <i class="fas fa-check text-pink-300 mt-1 flex-shrink-0"></i>
                        <span>Pengiriman gratis untuk pesanan di atas Rp 500K</span>
                    </li>
                    <li class="flex gap-3">
                        <i class="fas fa-check text-pink-300 mt-1 flex-shrink-0"></i>
                        <span>Pengembalian dalam 48 jam sebelum batas waktu</span>
                    </li>
                    <li class="flex gap-3">
                        <i class="fas fa-check text-pink-300 mt-1 flex-shrink-0"></i>
                        <span>Perlindungan pembeli 100% terjamin</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="border-t border-white/10 pt-16">
        <h2 class="text-3xl font-bold mb-8">Produk Serupa</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($relatedAssets ?? [] as $related)
                <div class="group">
                    <div class="fashion-card glass-hover relative mb-3">
                        @if($related->image_source)
                            <img src="{{ $related->image_source }}" alt="{{ $related->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center">
                                <i class="fas fa-image text-slate-500 text-3xl"></i>
                            </div>
                        @endif
                        <div class="card-overlay">
                            <div class="flex items-center justify-between">
                                <a href="/catalog/{{ $related->id }}" class="btn-luxury bg-pink-500 hover:bg-pink-600 px-4 py-2 rounded-lg text-sm font-semibold transition">
                                    Lihat
                                </a>
                                <p class="font-bold text-pink-300">Rp {{ number_format($related->rent_fee, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    <h3 class="font-bold text-white line-clamp-2">{{ $related->name }}</h3>
                    <p class="text-xs text-slate-400">{{ $related->brand }}</p>
                </div>
            @empty
                <p class="col-span-full text-center text-slate-400">Tidak ada produk serupa</p>
            @endforelse
        </div>
    </div>
</div>

<script>
    // Calculate total price
    document.getElementById('rentalDays')?.addEventListener('input', function() {
        const days = parseInt(this.value) || 1;
        const pricePerDay = {{ $asset->rent_fee }};
        const subtotal = days * pricePerDay;
        
        document.getElementById('subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('total').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    });

    // Set minimum date to today
    const dateInput = document.querySelector('input[name="due_at"]');
    if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.min = today;
    }
</script>
@endsection
