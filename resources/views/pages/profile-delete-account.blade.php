@extends('layouts.user')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <div class="mb-8">
        <a href="/profile" class="text-slate-400 hover:text-pink-300 font-semibold transition">
            <i class="fas fa-chevron-left mr-2"></i> Kembali ke Profil
        </a>
    </div>

    <!-- Warning Card -->
    <div class="glass-light glass border-l-4 border-red-500 p-8 rounded-3xl mb-8">
        <div class="flex gap-6">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-4xl text-red-400"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-red-300 mb-2">Hapus Akun</h1>
                <p class="text-slate-300 mb-4">Tindakan ini akan menghapus akun Anda secara permanen. Data berikut akan dihapus:</p>
                <ul class="space-y-2 text-slate-400">
                    <li><i class="fas fa-times-circle mr-2 text-red-400"></i> Profil dan informasi pribadi Anda</li>
                    <li><i class="fas fa-times-circle mr-2 text-red-400"></i> Riwayat peminjaman</li>
                    <li><i class="fas fa-times-circle mr-2 text-red-400"></i> Riwayat pembayaran</li>
                    <li><i class="fas fa-times-circle mr-2 text-red-400"></i> Data aktivitas</li>
                    <li><i class="fas fa-times-circle mr-2 text-red-400"></i> Semua data yang terkait dengan akun</li>
                </ul>
                <p class="text-red-300 font-semibold mt-4">⚠️ Tindakan ini TIDAK DAPAT DIBATALKAN dan PERMANEN</p>
            </div>
        </div>
    </div>

    <!-- Confirmation Form -->
    <div class="glass-light glass p-8 rounded-3xl">
        <h2 class="text-2xl font-bold mb-8">Konfirmasi Penghapusan Akun</h2>

        <form action="/profile/delete-account" method="POST" id="deleteForm" class="space-y-6">
            @csrf
            @method('DELETE')

            <!-- Password Confirmation -->
            <div>
                <label class="text-sm text-slate-400 mb-3 block font-semibold">Masukkan Password Anda untuk Konfirmasi</label>
                <input 
                    type="password" 
                    name="password" 
                    placeholder="••••••••"
                    class="w-full glass bg-white/10 border-0 text-white py-3 px-4 rounded-lg focus:ring-2 focus:ring-red-500 transition placeholder-slate-500"
                    required
                >
                @error('password')
                    <p class="text-red-300 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Understanding Checkbox -->
            <div class="glass bg-white/5 p-4 rounded-lg">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input 
                        type="checkbox" 
                        name="understand"
                        id="understand"
                        class="w-5 h-5 rounded mt-1 cursor-pointer"
                        required
                    >
                    <span class="text-sm text-slate-300 leading-relaxed">
                        Saya memahami bahwa penghapusan akun adalah tindakan permanen dan tidak dapat dipulihkan. Semua data saya akan dihapus selamanya.
                    </span>
                </label>
            </div>

            <!-- Permanent Checkbox -->
            <div class="glass bg-white/5 p-4 rounded-lg">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input 
                        type="checkbox" 
                        name="permanent"
                        id="permanent"
                        class="w-5 h-5 rounded mt-1 cursor-pointer"
                        required
                    >
                    <span class="text-sm text-slate-300 leading-relaxed">
                        Saya setuju untuk menghapus akun saya secara permanen tanpa kemungkinan pemulihan.
                    </span>
                </label>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 pt-6">
                <a 
                    href="/profile" 
                    class="flex-1 text-center btn-luxury glass px-6 py-3 rounded-lg font-semibold hover:bg-slate-600 transition"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="flex-1 bg-red-500/20 hover:bg-red-500/30 border border-red-400/30 text-red-300 px-6 py-3 rounded-lg font-semibold transition disabled:opacity-50 disabled:cursor-not-allowed"
                    id="deleteBtn"
                    disabled
                >
                    <i class="fas fa-trash mr-2"></i> Hapus Akun Selamanya
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Enable delete button only when both checkboxes are checked
    const understandCheckbox = document.getElementById('understand');
    const permanentCheckbox = document.getElementById('permanent');
    const deleteBtn = document.getElementById('deleteBtn');

    function updateButtonState() {
        deleteBtn.disabled = !(understandCheckbox.checked && permanentCheckbox.checked);
    }

    understandCheckbox.addEventListener('change', updateButtonState);
    permanentCheckbox.addEventListener('change', updateButtonState);

    // Prevent form submission if password is empty
    document.getElementById('deleteForm').addEventListener('submit', function(e) {
        const password = document.querySelector('input[name="password"]').value;
        if (!password.trim()) {
            e.preventDefault();
            alert('Silakan masukkan password Anda');
        }
    });
</script>
@endsection
