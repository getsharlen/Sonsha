@extends('layouts.user')

@section('content')
<!-- Page Header -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-8">
    <div class="mb-12">
        <h1 class="text-5xl font-bold mb-3">
            <span class="bg-gradient-to-r from-pink-300 to-rose-300 bg-clip-text text-transparent">
                Koleksi Lengkap
            </span>
        </h1>
        <p class="text-slate-400 text-lg">Temukan fashion item impian Anda dari berbagai kategori eksklusif</p>
    </div>

    <!-- Filters -->
    <div class="glass-light glass p-6 rounded-2xl mb-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4" id="filterForm">
            <!-- Search -->
            <div class="relative">
                <input type="text" name="search" placeholder="Cari item..." value="{{ request('search') }}" 
                    class="w-full glass bg-white/10 border-0 text-white placeholder-slate-400 pl-10 pr-4 py-3 rounded-lg focus:ring-2 focus:ring-pink-500 focus:bg-white/15 transition">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
            </div>

            <!-- Category Filter -->
            <div>
                <select name="category" class="w-full glass bg-white/10 border-0 text-white py-3 px-4 rounded-lg focus:ring-2 focus:ring-pink-500 transition">
                    <option value="">Semua Kategori</option>
                    @foreach($categories ?? [] as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Price Range -->
            <div>
                <select name="price_range" class="w-full glass bg-white/10 border-0 text-white py-3 px-4 rounded-lg focus:ring-2 focus:ring-pink-500 transition">
                    <option value="">Semua Harga</option>
                    <option value="0-50000" {{ request('price_range') == '0-50000' ? 'selected' : '' }}>Rp 0 - 50K</option>
                    <option value="50000-100000" {{ request('price_range') == '50000-100000' ? 'selected' : '' }}>Rp 50K - 100K</option>
                    <option value="100000-300000" {{ request('price_range') == '100000-300000' ? 'selected' : '' }}>Rp 100K - 300K</option>
                    <option value="300000" {{ request('price_range') == '300000' ? 'selected' : '' }}>Rp 300K+</option>
                </select>
            </div>

            <!-- Availability -->
            <div>
                <select name="availability" class="w-full glass bg-white/10 border-0 text-white py-3 px-4 rounded-lg focus:ring-2 focus:ring-pink-500 transition">
                    <option value="">Semua Status</option>
                    <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="limited" {{ request('availability') == 'limited' ? 'selected' : '' }}>Stok Terbatas</option>
                </select>
            </div>

            <!-- Search Button -->
            <button type="submit" class="btn-luxury bg-gradient-to-r from-pink-500 to-rose-600 hover:shadow-lg px-6 py-3 rounded-lg font-semibold transition col-span-1">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
        </form>
    </div>
</div>

<!-- Results Info -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
    <div class="flex items-center justify-between">
        <p class="text-slate-400">
            Menampilkan <span class="text-pink-300 font-bold">{{ ($assets ?? collect())->count() }}</span> item
        </p>
        <div class="flex gap-2">
            <select name="sort" class="glass bg-white/10 border-0 text-white py-2 px-3 rounded-lg text-sm">
                <option value="latest">Terbaru</option>
                <option value="popular">Paling Dicari</option>
                <option value="price_low">Harga Terendah</option>
                <option value="price_high">Harga Tertinggi</option>
                <option value="rating">Rating Tertinggi</option>
            </select>
        </div>
    </div>
</div>

<!-- Products Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
    @if(($assets ?? collect())->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($assets ?? [] as $asset)
                <div class="group" data-asset-id="{{ $asset->id }}">
                    <!-- Product Card -->
                    <div class="relative">
                        <!-- Fashion Card Image -->
                        <div class="fashion-card glass-hover relative mb-3">
                            @if($asset->image_url)
                                <img src="{{ $asset->image_url }}" alt="{{ $asset->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center">
                                    <i class="fas fa-image text-slate-500 text-4xl"></i>
                                </div>
                            @endif

                            <!-- Stock Badge -->
                            @if($asset->stock_available > 5)
                                <div class="absolute top-3 right-3 luxury-badge">In Stock</div>
                            @elseif($asset->stock_available > 0)
                                <div class="absolute top-3 right-3 bg-yellow-500/20 border border-yellow-400 text-yellow-300 px-3 py-1 rounded-full text-xs font-bold">
                                    Terbatas
                                </div>
                            @else
                                <div class="absolute top-3 right-3 bg-red-500/20 border border-red-400 text-red-300 px-3 py-1 rounded-full text-xs font-bold">
                                    Habis
                                </div>
                            @endif

                            <!-- Brand Badge -->
                            <div class="absolute top-3 left-3 text-xs font-bold text-pink-200 bg-black/30 px-3 py-1 rounded-full">
                                {{ $asset->brand }}
                            </div>

                            <!-- Wishlist Button -->
                            <button class="absolute bottom-3 right-3 w-10 h-10 bg-white/20 hover:bg-pink-500/40 backdrop-blur rounded-full flex items-center justify-center transition z-30 wishlist-btn">
                                <i class="fas fa-heart text-white"></i>
                            </button>

                            <!-- Card Overlay -->
                            <div class="card-overlay">
                                <div class="space-y-3">
                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-star text-yellow-300 text-sm"></i>
                                            <span class="text-sm text-yellow-300 font-bold">4.8</span>
                                            <span class="text-xs text-slate-400">(142 ulasan)</span>
                                        </div>
                                        <p class="text-xs text-slate-300 line-clamp-2">{{ $asset->description }}</p>
                                    </div>
                                    <div class="pt-3 border-t border-white/20 flex gap-2">
                                        <a href="/catalog/{{ $asset->id }}" class="flex-1 btn-luxury bg-pink-500 hover:bg-pink-600 px-3 py-2 rounded-lg text-sm font-semibold text-center transition">
                                            Detail
                                        </a>
                                        <form action="/borrowings" method="POST" class="flex-1">
                                            @csrf
                                            <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="w-full btn-luxury bg-white/20 hover:bg-white/30 px-3 py-2 rounded-lg text-sm font-semibold transition">
                                                <i class="fas fa-shopping-bag"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div>
                            <h3 class="font-bold text-white group-hover:text-pink-300 transition line-clamp-2 mb-1">
                                {{ $asset->name }}
                            </h3>
                            <p class="text-xs text-slate-400 mb-2">{{ $asset->brand }}</p>

                            <!-- Price and Rating -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-slate-400">Mulai dari</p>
                                    <p class="text-lg font-bold text-pink-300">
                                        Rp {{ number_format($asset->rent_fee, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-slate-400">Stok</p>
                                    <p class="text-sm font-semibold text-white">{{ $asset->stock_available }}/{{ $asset->stock_total }}</p>
                                </div>
                            </div>

                            <!-- Condition Badge -->
                            <div class="mt-2 pt-2 border-t border-white/10">
                                @if($asset->condition == 'baik')
                                    <span class="inline-block text-xs px-2 py-1 bg-emerald-500/20 text-emerald-300 rounded-full">
                                        <i class="fas fa-check-circle"></i> Kondisi Baik
                                    </span>
                                @elseif($asset->condition == 'maintenance')
                                    <span class="inline-block text-xs px-2 py-1 bg-yellow-500/20 text-yellow-300 rounded-full">
                                        <i class="fas fa-wrench"></i> Maintenance
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-16 flex justify-center gap-2">
            {{ $assets->links('pagination::tailwind') }}
        </div>
    @else
        <div class="text-center py-20">
            <i class="fas fa-inbox text-5xl text-slate-500 mb-4"></i>
            <h3 class="text-xl font-bold text-slate-300 mb-2">Tidak Ada Item Ditemukan</h3>
            <p class="text-slate-400 mb-6">Coba ubah filter atau kategori pilihan Anda</p>
            <a href="/catalog" class="btn-luxury bg-gradient-to-r from-pink-500 to-rose-600 px-6 py-2 rounded-full font-semibold inline-block">
                Lihat Semua
            </a>
        </div>
    @endif
</div>

<!-- Quick View Modal -->
<div id="quickViewModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="glass-light glass relative max-w-2xl w-full max-h-[90vh] overflow-y-auto rounded-3xl p-8">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-slate-400 hover:text-white text-2xl">
            <i class="fas fa-times"></i>
        </button>
        <div id="modalContent"></div>
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    function closeModal() {
        document.getElementById('quickViewModal').classList.add('hidden');
    }

    // Wishlist functionality
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            this.classList.toggle('text-pink-500');
            this.classList.toggle('bg-pink-500/40');
        });
    });

    // Sort functionality
    document.querySelector('select[name="sort"]').addEventListener('change', function() {
        const params = new URLSearchParams(window.location.search);
        params.set('sort', this.value);
        window.location.search = params.toString();
    });
</script>
@endsection
