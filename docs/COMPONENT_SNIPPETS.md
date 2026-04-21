{{-- Komponen Stats Card --}}
@component('components.stats-card', [
    'title' => 'Total Peminjaman',
    'value' => 24,
    'icon' => 'fas fa-shopping-bag',
    'color' => 'pink' // pink, blue, emerald, yellow
])
@endcomponent

<!-- Contoh HTML jika tidak menggunakan component: -->
<div class="glass-light glass p-6 rounded-2xl">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-slate-400 mb-1">Total Peminjaman</p>
            <p class="text-3xl font-bold">24</p>
        </div>
        <i class="fas fa-shopping-bag text-pink-300 text-3xl opacity-30"></i>
    </div>
</div>

---

{{-- Komponen Status Badge --}}
@component('components.status-badge', [
    'status' => 'approved', // pending, approved, returned, fine
    'label' => 'Disetujui'
])
@endcomponent

<!-- HTML: -->
<span class="status-approved px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
    <i class="fas fa-check"></i> Disetujui
</span>

---

{{-- Komponen Product Card --}}
@component('components.product-card', [
    'asset' => $asset,
    'showOverlay' => true
])
@endcomponent

<!-- HTML: -->
<div class="group">
    <div class="fashion-card glass-hover relative mb-3">
        <img src="{{ $asset->image_url }}" alt="{{ $asset->name }}" class="w-full h-full object-cover">
        @if($asset->stock_available > 5)
            <div class="absolute top-3 right-3 luxury-badge">In Stock</div>
        @endif
        <div class="card-overlay">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-white">{{ $asset->name }}</h3>
                    <p class="text-sm text-slate-300">{{ $asset->brand }}</p>
                </div>
                <a href="/catalog/{{ $asset->id }}" class="btn-luxury bg-pink-500 px-4 py-2 rounded-lg">
                    Detail
                </a>
            </div>
        </div>
    </div>
    <p class="font-bold text-white line-clamp-2">{{ $asset->name }}</p>
    <p class="text-lg font-bold text-pink-300">Rp {{ number_format($asset->rent_fee, 0, ',', '.') }}</p>
</div>

---

{{-- Komponen Glass Card dengan Title --}}
<div class="glass-light glass p-8 rounded-2xl">
    <h3 class="text-xl font-bold mb-6">Judul Card</h3>
    <!-- Content di sini -->
</div>

---

{{-- Komponen Form Input Glass --}}
<div>
    <label class="text-sm text-slate-400 mb-2 block">Label</label>
    <input type="text" placeholder="Placeholder text"
        class="w-full glass bg-white/10 border-0 text-white py-3 px-4 rounded-lg focus:ring-2 focus:ring-pink-500 transition">
</div>

---

{{-- Komponen Tab Navigation --}}
<div class="border-b border-white/10 mb-8">
    <div class="flex gap-1 overflow-x-auto">
        <button class="tab-btn active px-6 py-3 border-b-2 border-pink-500 text-pink-300 font-semibold transition" data-tab="all">
            Semua
        </button>
        <button class="tab-btn px-6 py-3 border-b-2 border-transparent text-slate-400 hover:text-white font-semibold transition" data-tab="active">
            Aktif
        </button>
    </div>
</div>

---

{{-- Komponen Alert/Notification --}}
<!-- Success -->
<div class="glass border-l-4 border-emerald-400 bg-emerald-500/10 p-4 rounded-xl">
    <div class="flex items-center gap-3">
        <i class="fas fa-check-circle text-emerald-400"></i>
        <p class="text-sm text-emerald-200">Berhasil!</p>
    </div>
</div>

<!-- Error -->
<div class="glass border-l-4 border-red-400 bg-red-500/10 p-4 rounded-xl">
    <div class="flex items-center gap-3">
        <i class="fas fa-exclamation-circle text-red-400"></i>
        <p class="text-sm text-red-200">Terjadi kesalahan!</p>
    </div>
</div>

<!-- Warning -->
<div class="glass border-l-4 border-yellow-400 bg-yellow-500/10 p-4 rounded-xl">
    <div class="flex items-center gap-3">
        <i class="fas fa-info-circle text-yellow-400"></i>
        <p class="text-sm text-yellow-200">Perhatian!</p>
    </div>
</div>

---

{{-- Komponen Button Variants --}}
<!-- Primary Button -->
<button class="btn-luxury bg-gradient-to-r from-pink-500 to-rose-600 hover:shadow-lg hover:shadow-pink-500/50 px-8 py-3 rounded-full font-semibold text-white transition">
    Primary Button
</button>

<!-- Secondary Button (Glass) -->
<button class="btn-luxury glass-hover glass px-8 py-3 rounded-full font-semibold transition">
    Secondary Button
</button>

<!-- Danger Button -->
<button class="btn-luxury bg-red-500/20 hover:bg-red-500/30 border border-red-400/30 text-red-300 px-8 py-3 rounded-full font-semibold transition">
    Danger Button
</button>

<!-- Success Button -->
<button class="btn-luxury bg-emerald-500/20 hover:bg-emerald-500/30 border border-emerald-400/30 text-emerald-300 px-8 py-3 rounded-full font-semibold transition">
    Success Button
</button>

---

{{-- Komponen Luxury Badge --}}
<span class="luxury-badge">Premium Collection 2024</span>

---

{{-- Komponen Loading Skeleton --}}
<div class="space-y-3">
    @for($i = 0; $i < 3; $i++)
        <div class="skeleton h-20 rounded-lg"></div>
    @endfor
</div>

---

{{-- Komponen Empty State --}}
<div class="text-center py-20">
    <i class="fas fa-inbox text-6xl text-slate-500 mb-4"></i>
    <h3 class="text-2xl font-bold text-slate-300 mb-2">Tidak Ada Data</h3>
    <p class="text-slate-400 mb-8">Belum ada item yang tersedia</p>
    <a href="/catalog" class="btn-luxury bg-gradient-to-r from-pink-500 to-rose-600 px-8 py-3 rounded-full font-semibold inline-block">
        Kembali ke Katalog
    </a>
</div>

---

{{-- Komponen Modal/Dialog --}}
<div id="modalExample" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal()"></div>
    
    <!-- Modal -->
    <div class="glass-light glass relative max-w-2xl w-full max-h-[90vh] overflow-y-auto rounded-3xl p-8">
        <!-- Close Button -->
        <button onclick="closeModal()" class="absolute top-4 right-4 text-slate-400 hover:text-white text-2xl">
            <i class="fas fa-times"></i>
        </button>
        
        <!-- Content -->
        <h2 class="text-2xl font-bold mb-4">Modal Title</h2>
        <p class="text-slate-300">Modal content goes here...</p>
    </div>
</div>

<!-- Script untuk modal: -->
<script>
function closeModal() {
    document.getElementById('modalExample').classList.add('hidden');
}

function openModal() {
    document.getElementById('modalExample').classList.remove('hidden');
}
</script>

---

{{-- Komponen Pagination Links --}}
<div class="mt-12 flex justify-center gap-2">
    {{ $items->links('pagination::tailwind') }}
</div>

---

{{-- Komponen Breadcrumb --}}
<div class="mb-8 flex items-center gap-3 text-sm">
    <a href="/" class="text-slate-400 hover:text-pink-300 transition">
        <i class="fas fa-home mr-2"></i>Beranda
    </a>
    <span class="text-slate-600">•</span>
    <a href="/catalog" class="text-slate-400 hover:text-pink-300 transition">
        Katalog
    </a>
    <span class="text-slate-600">•</span>
    <span class="text-pink-300">{{ $asset->name }}</span>
</div>

---

{{-- Komponen Avatar --}}
<div class="relative">
    <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center text-white text-4xl">
        <i class="fas fa-user"></i>
    </div>
    <button class="absolute bottom-0 right-0 bg-pink-500 hover:bg-pink-600 w-8 h-8 rounded-full flex items-center justify-center text-white transition">
        <i class="fas fa-camera text-sm"></i>
    </button>
</div>

---

{{-- Komponen Floating Action Button --}}
<button class="fixed bottom-8 right-8 w-14 h-14 bg-gradient-to-r from-pink-500 to-rose-600 rounded-full flex items-center justify-center text-white shadow-lg hover:shadow-xl transition hover:scale-110">
    <i class="fas fa-plus text-2xl"></i>
</button>

---

{{-- Komponen Hero Section --}}
<div class="relative h-[500px] overflow-hidden mb-20">
    <!-- Background -->
    <div class="absolute inset-0 animated-gradient opacity-40"></div>
    
    <!-- Content -->
    <div class="relative z-10 h-full flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="max-w-2xl">
                <div class="mb-4 flex items-center gap-2">
                    <span class="luxury-badge">Premium Collection 2024</span>
                </div>
                <h1 class="text-5xl md:text-6xl font-black mb-4 leading-tight">
                    <span class="bg-gradient-to-r from-pink-300 via-pink-200 to-rose-300 bg-clip-text text-transparent">
                        Gaya Mewah
                    </span>
                </h1>
                <p class="text-lg text-slate-300 mb-8 max-w-xl">
                    Deskripsi hero section Anda di sini
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#" class="btn-luxury bg-gradient-to-r from-pink-500 to-rose-600 px-8 py-3 rounded-full font-semibold inline-flex items-center gap-2 transition">
                        Call to Action
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Floating elements -->
    <div class="absolute top-10 right-10 w-40 h-40 bg-pink-500/20 rounded-full blur-3xl animate-pulse"></div>
</div>

---

## 📌 Tips Menggunakan Komponen

1. **Konsistensi**: Gunakan komponen yang sama di berbagai halaman
2. **Reusability**: Jangan duplicate code, buat component blade jika diperlukan
3. **Customization**: Modify classes sesuai kebutuhan dengan Tailwind utilities
4. **Responsiveness**: Selalu test di mobile, tablet, desktop
5. **Performance**: Lazy load images, minimize HTTP requests

## 🎨 Color Variants

Untuk mengubah warna button/badge:
- Replace `pink` dengan `blue`, `emerald`, `yellow`, `red` sesuai kebutuhan
- Use Tailwind color scale (50-950)
- Contoh: `from-blue-500 to-blue-600`

## 📝 Template Checklist

Ketika membuat halaman baru:
- [ ] Extend correct layout (user/admin)
- [ ] Add page title & subtitle
- [ ] Include proper navigation breadcrumb
- [ ] Use glass components untuk cards
- [ ] Add status badges jika diperlukan
- [ ] Include empty state handler
- [ ] Test responsive design
- [ ] Add error/success alerts
- [ ] Optimize images
- [ ] Test di berbagai browsers
