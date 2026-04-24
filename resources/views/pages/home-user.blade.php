@extends('layouts.user')

@section('content')
<!-- Hero Section -->
<div class="relative h-[500px] overflow-hidden mb-20">
    <!-- Animated gradient background -->
    <div class="absolute inset-0 animated-gradient opacity-40"></div>

    <!-- Content -->
    <div class="relative z-10 h-full flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="max-w-2xl">
                <div class="mb-4 flex items-center gap-2">
                    <span class="luxury-badge">Premium Collection 2026</span>
                </div>
                <h1 class="text-5xl md:text-6xl font-black mb-4 leading-tight">
                    <span class="bg-gradient-to-r from-pink-300 via-pink-200 to-rose-300 bg-clip-text text-transparent">
                        Gaya Mewah
                    </span>
                    <br>
                    Terjangkau
                </h1>
                <p class="text-lg text-slate-300 mb-8 max-w-xl">
                    Kumpulkan koleksi fashion premium dari designer ternama. Sewa dengan harga terjangkau, tampil percaya diri setiap hari.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="/catalog" class="btn-luxury bg-gradient-to-r from-pink-500 to-rose-600 hover:shadow-lg hover:shadow-pink-500/50 px-8 py-3 rounded-full font-semibold inline-flex items-center gap-2 transition">
                        Jelajahi Koleksi
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <button class="glass-hover glass px-8 py-3 rounded-full font-semibold inline-flex items-center gap-2">
                        <i class="fas fa-play"></i>
                        Tonton Video
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating elements -->
    <div class="absolute top-10 right-10 w-40 h-40 bg-pink-500/20 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-10 left-1/4 w-32 h-32 bg-rose-600/20 rounded-full blur-3xl animate-pulse delay-1000"></div>
</div>

<!-- Featured Collections -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-20">
    <div class="mb-12">
        <div class="inline-block mb-4">
            <span class="luxury-badge">Featured</span>
        </div>
        <h2 class="text-4xl font-bold mb-2">Koleksi Pilihan</h2>
        <p class="text-slate-400">Item fashion terpopuler yang sedang trending sekarang</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($featuredAssets ?? [] as $asset)
            <div class="group">
                <div class="fashion-card glass-hover relative">
                    @if($asset->image_source)
                        <img src="{{ $asset->image_source }}" alt="{{ $asset->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center">
                            <i class="fas fa-image text-slate-500 text-4xl"></i>
                        </div>
                    @endif

                    <div class="card-overlay">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="font-bold text-white text-lg">{{ $asset->name }}</h3>
                                <p class="text-sm text-slate-300">{{ $asset->brand }}</p>
                            </div>
                            <span class="text-xs font-bold text-yellow-300">
                                <i class="fas fa-star"></i> 4.8
                            </span>
                        </div>
                        <p class="text-sm text-slate-300 mb-3">{{ $asset->description }}</p>
                        <div class="flex items-center justify-between pt-3 border-t border-white/20">
                            <div>
                                <p class="text-xs text-slate-400">per hari</p>
                                <p class="text-lg font-bold text-pink-300">Rp {{ number_format($asset->rent_fee, 0, ',', '.') }}</p>
                            </div>
                            <a href="/catalog/{{ $asset->id }}" class="btn-luxury bg-pink-500 hover:bg-pink-600 px-4 py-2 rounded-lg text-sm font-semibold transition">
                                Pesan
                            </a>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="text-xs text-slate-400">Stok: {{ $asset->stock_available }}/{{ $asset->stock_total }}</p>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-20">
                <i class="fas fa-box-open text-4xl text-slate-500 mb-4"></i>
                <p class="text-slate-400">Belum ada koleksi yang tersedia</p>
            </div>
        @endforelse
    </div>
</section>

<!-- Categories Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-20">
    <div class="mb-12">
        <h2 class="text-4xl font-bold mb-2">Jelajahi Kategori</h2>
        <p class="text-slate-400">Temukan gaya yang sempurna untuk setiap kesempatan</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($categories ?? [] as $category)
            <a href="/catalog?category={{ $category->id }}" class="group">
                <div class="glass-hover glass h-40 flex flex-col items-center justify-center text-center p-6 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-pink-500/0 to-rose-600/0 group-hover:from-pink-500/10 group-hover:to-rose-600/10 transition"></div>
                    <div class="relative z-10">
                        <i class="fas fa-shopping-bag text-3xl text-pink-300 mb-3"></i>
                        <h3 class="font-bold text-lg">{{ $category->name }}</h3>
                        <p class="text-xs text-slate-400 mt-1">Explore</p>
                    </div>
                </div>
            </a>
        @empty
            <p class="col-span-full text-center text-slate-400 py-10">Belum ada kategori</p>
        @endforelse
    </div>
</section>

<!-- Stats Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-20">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass-light glass p-8 text-center">
            <p class="text-4xl font-bold text-pink-300 mb-2">{{ $stats['total_items'] ?? 0 }}</p>
            <p class="text-slate-300">Item Fashion</p>
            <p class="text-xs text-slate-400 mt-2">Koleksi premium siap sewa</p>
        </div>
        <div class="glass-light glass p-8 text-center">
            <p class="text-4xl font-bold text-pink-300 mb-2">{{ $stats['active_users'] ?? 0 }}+</p>
            <p class="text-slate-300">Member Aktif</p>
            <p class="text-xs text-slate-400 mt-2">Bergabung dengan komunitas kami</p>
        </div>
        <div class="glass-light glass p-8 text-center">
            <p class="text-4xl font-bold text-pink-300 mb-2">{{ $stats['total_rentals'] ?? 0 }}+</p>
            <p class="text-slate-300">Transaksi Berhasil</p>
            <p class="text-xs text-slate-400 mt-2">Kepercayaan pelanggan</p>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-20">
    <div class="relative overflow-hidden rounded-3xl p-12 md:p-20">
        <div class="absolute inset-0 animated-gradient opacity-40"></div>
        <div class="relative z-10 text-center max-w-2xl mx-auto">
            <h2 class="text-4xl font-bold mb-4">Siap Tampil Memukau?</h2>
            <p class="text-slate-300 mb-8">Dapatkan akses eksklusif ke koleksi terbaru dan penawaran spesial member.</p>
            <a href="/catalog" class="btn-luxury bg-white text-pink-600 hover:bg-slate-100 hover:shadow-xl px-8 py-3 rounded-full font-bold inline-block transition">
                Mulai Sewa Sekarang
            </a>
        </div>
    </div>
</section>

<script>
    // Intersection Observer untuk animasi on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('[data-animate]').forEach(el => {
        observer.observe(el);
    });
</script>
@endsection
