@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header Section -->
    <div class="mb-8">
        <a href="/borrowings" class="text-slate-400 hover:text-blue-400 font-semibold transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Borrowing Header -->
            <div class="bg-slate-800 border border-slate-700 p-8 rounded-2xl">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">{{ $borrowing->borrowing_code }}</h1>
                        <p class="text-slate-400">{{ $borrowing->user->name }} ({{ $borrowing->user->email }})</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-block px-4 py-2 rounded-full font-semibold text-sm @switch($borrowing->status)
                            @case('requested')
                                bg-yellow-500/20 text-yellow-300 border border-yellow-500/30
                            @break
                            @case('approved')
                                bg-blue-500/20 text-blue-300 border border-blue-500/30
                            @break
                            @case('borrowed')
                                bg-purple-500/20 text-purple-300 border border-purple-500/30
                            @break
                            @case('late')
                                bg-red-500/20 text-red-300 border border-red-500/30
                            @break
                            @case('returned')
                                bg-green-500/20 text-green-300 border border-green-500/30
                            @break
                            @case('rejected')
                                bg-gray-500/20 text-gray-300 border border-gray-500/30
                            @break
                            @default
                                bg-slate-500/20 text-slate-300 border border-slate-500/30
                        @endswitch">
                            {{ ucfirst($borrowing->status) }}
                        </span>
                    </div>
                </div>

                <!-- Key Dates -->
                <div class="grid grid-cols-3 gap-4 pt-6 border-t border-slate-700">
                    <div>
                        <p class="text-slate-500 text-sm mb-1">Tanggal Permintaan</p>
                        <p class="text-white font-semibold">{{ $borrowing->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 text-sm mb-1">Tanggal Jatuh Tempo</p>
                        <p class="text-white font-semibold">{{ $borrowing->due_at->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 text-sm mb-1">Tanggal Pengembalian</p>
                        <p class="text-white font-semibold">{{ $borrowing->returned_at ? $borrowing->returned_at->format('d M Y') : '—' }}</p>
                    </div>
                </div>
            </div>

            <!-- Items Section -->
            <div class="bg-slate-800 border border-slate-700 p-8 rounded-2xl">
                <h2 class="text-2xl font-bold text-white mb-6">Item yang Dipinjam</h2>
                <div class="space-y-4">
                    @forelse($borrowing->items as $item)
                        <div class="bg-slate-700/50 p-4 rounded-lg border border-slate-600">
                            <div class="flex gap-4">
                                <!-- Item Image -->
                                <div class="flex-shrink-0">
                                    <img 
                                        src="{{ $item->asset->image_url ?? '/placeholder.jpg' }}" 
                                        alt="{{ $item->asset->name }}"
                                        class="w-20 h-20 rounded-lg object-cover"
                                    >
                                </div>

                                <!-- Item Details -->
                                <div class="flex-1">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h3 class="text-lg font-bold text-white">{{ $item->asset->name }}</h3>
                                            <p class="text-slate-400 text-sm">{{ $item->asset->category->name ?? 'N/A' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-slate-300 text-sm">Qty: {{ $item->quantity }}</p>
                                            <p class="text-blue-300 font-semibold">Rp {{ number_format($item->asset->rent_fee, 0, ',', '.') }}/hari</p>
                                        </div>
                                    </div>
                                    <p class="text-slate-400 text-sm">Brand: {{ $item->asset->brand ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-400 text-center py-6">Tidak ada item</p>
                    @endforelse
                </div>
            </div>

            <!-- Purpose Section -->
            @if($borrowing->purpose)
                <div class="bg-slate-800 border border-slate-700 p-8 rounded-2xl">
                    <h2 class="text-lg font-bold text-white mb-4">Tujuan Peminjaman</h2>
                    <p class="text-slate-300">{{ $borrowing->purpose }}</p>
                </div>
            @endif

            <!-- Action Section -->
            <div class="bg-slate-800 border border-slate-700 p-8 rounded-2xl">
                <h2 class="text-lg font-bold text-white mb-6">Tindakan</h2>
                <div class="space-y-3">
                    @if($borrowing->status === 'requested')
                        <form method="POST" action="/borrowings/{{ $borrowing->id }}/approve" class="inline-block w-full">
                            @csrf
                            <button class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                                <i class="fas fa-check mr-2"></i> Setujui Peminjaman
                            </button>
                        </form>
                    @endif

                    @if(in_array($borrowing->status, ['borrowed', 'late']))
                        <form method="POST" action="/borrowings/{{ $borrowing->id }}/return" class="inline-block w-full">
                            @csrf
                            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                                <i class="fas fa-undo mr-2"></i> Proses Pengembalian
                            </button>
                        </form>
                    @endif

                    @if($borrowing->status === 'requested')
                        <form method="POST" action="/borrowings/{{ $borrowing->id }}" class="inline-block w-full" onsubmit="return confirm('Tolak peminjaman ini?');">
                            @csrf
                            @method('DELETE')
                            <button class="w-full bg-red-600/20 hover:bg-red-600/30 border border-red-500/30 text-red-300 px-6 py-3 rounded-lg font-semibold transition">
                                <i class="fas fa-times mr-2"></i> Tolak Peminjaman
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Activity Log -->
            <div class="bg-slate-800 border border-slate-700 p-8 rounded-2xl">
                <h2 class="text-lg font-bold text-white mb-6">Log Aktivitas</h2>
                <div class="space-y-3">
                    @forelse($borrowing->activities ?? [] as $activity)
                        <div class="flex items-start gap-3 pb-3 border-b border-slate-700 last:border-0">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center">
                                <i class="fas fa-history text-slate-400 text-xs"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-white font-semibold">{{ $activity->action ?? 'Aktivitas' }}</p>
                                <p class="text-slate-400 text-sm">{{ $activity->description ?? $activity->module ?? '' }}</p>
                                <p class="text-slate-500 text-xs mt-1">{{ $activity->created_at?->format('d M Y H:i') ?? '' }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-400 text-center py-6">Belum ada aktivitas tercatat</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Peminjam Info -->
            <div class="bg-slate-800 border border-slate-700 p-6 rounded-2xl">
                <h3 class="text-lg font-bold text-white mb-4">Informasi Peminjam</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-slate-500 text-sm mb-1">Nama</p>
                        <p class="text-white font-semibold">{{ $borrowing->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 text-sm mb-1">Email</p>
                        <p class="text-blue-300">{{ $borrowing->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 text-sm mb-1">Telepon</p>
                        <p class="text-white">{{ $borrowing->user->phone ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 text-sm mb-1">Status Akun</p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold @if($borrowing->user->status === 'active')
                            bg-green-500/20 text-green-300 border border-green-500/30
                        @else
                            bg-red-500/20 text-red-300 border border-red-500/30
                        @endif">
                            {{ ucfirst($borrowing->user->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Peninjau Info -->
            @if($borrowing->approver)
                <div class="bg-slate-800 border border-slate-700 p-6 rounded-2xl">
                    <h3 class="text-lg font-bold text-white mb-4">Disetujui Oleh</h3>
                    <div class="space-y-3">
                        <p class="text-white font-semibold">{{ $borrowing->approver->name }}</p>
                        <p class="text-slate-400 text-sm">{{ $borrowing->approver->email }}</p>
                        <p class="text-slate-500 text-xs">{{ $borrowing->approved_at?->format('d M Y H:i') ?? '' }}</p>
                    </div>
                </div>
            @endif

            <!-- Financial Summary -->
            <div class="bg-slate-800 border border-slate-700 p-6 rounded-2xl">
                <h3 class="text-lg font-bold text-white mb-4">Ringkasan Keuangan</h3>
                <div class="space-y-3 border-b border-slate-700 pb-4 mb-4">
                    @php
                        $totalCost = 0;
                        $days = $borrowing->returned_at 
                            ? $borrowing->returned_at->diffInDays($borrowing->created_at)
                            : $borrowing->due_at->diffInDays($borrowing->created_at);
                        foreach ($borrowing->items as $item) {
                            $totalCost += $item->asset->rent_fee * $item->quantity * max($days, 1);
                        }
                    @endphp
                    <div class="flex justify-between">
                        <span class="text-slate-400">Durasi Peminjaman</span>
                        <span class="text-white font-semibold">{{ max($days, 1) }} hari</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Total Biaya</span>
                        <span class="text-white font-semibold">Rp {{ number_format($totalCost, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if($borrowing->payment)
                    <div class="space-y-2">
                        <p class="text-slate-400 text-sm font-semibold">Status Pembayaran</p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold @if($borrowing->payment->status === 'paid')
                            bg-green-500/20 text-green-300 border border-green-500/30
                        @elseif($borrowing->payment->status === 'pending')
                            bg-yellow-500/20 text-yellow-300 border border-yellow-500/30
                        @else
                            bg-gray-500/20 text-gray-300 border border-gray-500/30
                        @endif">
                            {{ ucfirst($borrowing->payment->status) }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
