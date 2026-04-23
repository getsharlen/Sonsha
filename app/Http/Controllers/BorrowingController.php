<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Asset;
use App\Models\Borrowing;
use App\Models\BorrowingItem;
use App\Models\FinePayment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    private function syncAssetStock(Asset $asset): void
    {
        $borrowedQty = BorrowingItem::query()
            ->where('asset_id', $asset->id)
            ->where('status', 'borrowed')
            ->sum('quantity');

        $available = max(0, (int) $asset->stock_total - (int) $borrowedQty);
        if ((int) $asset->stock_available !== $available) {
            $asset->update(['stock_available' => $available]);
        }
    }

    public function index(): View
    {
        Asset::synchronizeStockAvailability();

        $query = Borrowing::with(['user', 'items.asset', 'approver'])->latest();
        $user = Auth::user();

        if ($user->role === 'peminjam') {
            $query->where('user_id', Auth::id());
        }

        $borrowings = $query->paginate(3);
        $assets = Asset::where('stock_available', '>', 0)->orderBy('name')->get();
        $users = User::where('role', 'peminjam')->orderBy('name')->get();

        if ($user->role === 'peminjam') {
            $summary = [
                'total' => Borrowing::where('user_id', $user->id)->count(),
                'active' => Borrowing::where('user_id', $user->id)
                    ->whereIn('status', ['borrowed', 'late'])
                    ->count(),
                'pending' => Borrowing::where('user_id', $user->id)
                    ->where('status', 'requested')
                    ->count(),
                'fines' => FinePayment::where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->sum('amount'),
            ];

            return view('pages.borrowings-user', compact('borrowings', 'summary'));
        }

        return view('borrowings.index', compact('borrowings', 'assets', 'users'));
    }

    public function show(Borrowing $borrowing): View
    {
        $user = Auth::user();

        abort_unless(
            $user->id === $borrowing->user_id || in_array($user->role, ['admin', 'petugas'], true),
            403
        );

        $borrowing->load(['user', 'items.asset.category', 'approver', 'payment']);

        if ($user->role === 'peminjam') {
            return view('pages.borrowing-detail-user', compact('borrowing'));
        }

        // Load activity logs for admin view
        $borrowing->activities = ActivityLog::where('user_id', $borrowing->user_id)
            ->latest()
            ->limit(20)
            ->get();

        return view('borrowings.detail-admin', compact('borrowing'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'purpose' => ['required', 'string'],
            'due_at' => ['required', 'date', 'after:today'],
            'asset_id' => ['required', 'exists:assets,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $requestUser = $request->user();
        $borrowerId = in_array($requestUser->role, ['admin', 'petugas'], true)
            ? ($data['user_id'] ?? $requestUser->id)
            : $requestUser->id;

        $borrower = User::findOrFail($borrowerId);
        if ($borrower->status === 'locked') {
            throw ValidationException::withMessages([
                'user_id' => 'Akun peminjam masih terkunci karena denda belum lunas.',
            ]);
        }

        $borrowing = DB::transaction(function () use ($data, $borrowerId, $requestUser) {
            $borrowing = Borrowing::create([
                'borrowing_code' => Borrowing::generateCode(),
                'user_id' => $borrowerId,
                'purpose' => $data['purpose'],
                'due_at' => $data['due_at'],
                'status' => 'requested',
            ]);

            $asset = Asset::query()->lockForUpdate()->findOrFail($data['asset_id']);
            $this->syncAssetStock($asset);
            $asset->refresh();
            if ($asset->stock_available < $data['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => 'Stok alat tidak mencukupi untuk jumlah pinjam ini.',
                ]);
            }

            BorrowingItem::create([
                'borrowing_id' => $borrowing->id,
                'asset_id' => $asset->id,
                'quantity' => $data['quantity'],
                'unit_fee' => $asset->rent_fee,
                'status' => 'pending',
            ]);

            ActivityLog::create([
                'user_id' => $requestUser->id,
                'module' => 'borrowing',
                'action' => 'request',
                'description' => 'Pengajuan peminjaman baru dibuat.',
                'payload' => ['borrowing_code' => $borrowing->borrowing_code],
            ]);

            return $borrowing;
        });

        return back()->with('status', 'Pengajuan peminjaman berhasil dibuat dengan kode '.$borrowing->borrowing_code.'.');
    }

    public function approve(Borrowing $borrowing): RedirectResponse
    {
        abort_unless(in_array(Auth::user()->role, ['admin', 'petugas'], true), 403);
        if ($borrowing->status !== 'requested') {
            return back()->with('status', 'Peminjaman ini tidak dapat disetujui lagi.');
        }

        DB::transaction(function () use ($borrowing) {
            $borrowing->load('items.asset');

            foreach ($borrowing->items as $item) {
                $asset = Asset::query()->lockForUpdate()->findOrFail($item->asset_id);
                $this->syncAssetStock($asset);
                $asset->refresh();

                if ($asset->stock_available < $item->quantity) {
                    abort(422, 'Stok alat '.$item->asset->name.' tidak mencukupi.');
                }

                $item->status = 'borrowed';
                $item->save();
                $this->syncAssetStock($asset);
            }

            $borrowing->update([
                'approved_by' => Auth::id(),
                'borrowed_at' => now(),
                'status' => 'borrowed',
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'module' => 'borrowing',
                'action' => 'approve',
                'description' => 'Peminjaman disetujui petugas/admin.',
                'payload' => ['borrowing_code' => $borrowing->borrowing_code],
            ]);
        });

        return back()->with('status', 'Peminjaman disetujui.');
    }

    public function decline(Borrowing $borrowing): RedirectResponse
    {
        abort_unless(in_array(Auth::user()->role, ['admin', 'petugas'], true), 403);
        if ($borrowing->status !== 'requested') {
            return back()->with('status', 'Peminjaman ini tidak dapat ditolak lagi.');
        }

        DB::transaction(function () use ($borrowing) {
            $borrowing->update(['status' => 'declined']);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'module' => 'borrowing',
                'action' => 'decline',
                'description' => 'Peminjaman ditolak petugas/admin.',
                'payload' => ['borrowing_code' => $borrowing->borrowing_code],
            ]);
        });

        return back()->with('status', 'Peminjaman ditolak.');
    }

    public function returnBorrowing(Borrowing $borrowing): RedirectResponse
    {
        abort_unless(Auth::user()->id === $borrowing->user_id || in_array(Auth::user()->role, ['admin', 'petugas'], true), 403);
        if (in_array($borrowing->status, ['returned', 'paid', 'late'], true) && $borrowing->returned_at) {
            return back()->with('status', 'Peminjaman ini sudah dikembalikan sebelumnya.');
        }

        DB::transaction(function () use ($borrowing) {
            $borrowing->load('items.asset', 'user');
            $returnedAt = now();
            $daysLate = $borrowing->due_at && $returnedAt->greaterThan($borrowing->due_at)
                ? $borrowing->due_at->startOfDay()->diffInDays($returnedAt->startOfDay())
                : 0;

            $fineAmount = $daysLate * 5000;

            foreach ($borrowing->items as $item) {
                $item->status = 'returned';
                $item->fine_amount = $fineAmount;
                $item->save();
                $this->syncAssetStock($item->asset);
            }

            if ($fineAmount > 0) {
                $paidFromBalance = min((float) $borrowing->user->balance, $fineAmount);
                $remainingFine = $fineAmount - $paidFromBalance;

                if ($paidFromBalance > 0) {
                    $borrowing->user->decrement('balance', $paidFromBalance);
                }

                FinePayment::create([
                    'user_id' => $borrowing->user_id,
                    'borrowing_id' => $borrowing->id,
                    'amount' => $remainingFine > 0 ? $remainingFine : $fineAmount,
                    'status' => $remainingFine > 0 ? 'pending' : 'paid',
                    'method' => 'wallet',
                    'note' => $remainingFine > 0 ? 'Saldo belum cukup. Akun dikunci sampai top up.' : 'Denda dibayar otomatis dari saldo.',
                    'paid_at' => $remainingFine > 0 ? null : now(),
                ]);

                $borrowing->user->status = $remainingFine > 0 ? 'locked' : 'active';
                $borrowing->user->locked_reason = $remainingFine > 0 ? 'Menunggu pelunasan denda.' : null;
                $borrowing->user->save();
            }

            $borrowing->update([
                'returned_by' => Auth::id(),
                'returned_at' => $returnedAt,
                'status' => $fineAmount > 0 ? 'late' : 'returned',
                'total_fine' => $fineAmount,
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'module' => 'borrowing',
                'action' => 'return',
                'description' => 'Pengembalian alat diproses otomatis.',
                'payload' => ['borrowing_code' => $borrowing->borrowing_code, 'fine' => $fineAmount],
            ]);
        });

        return back()->with('status', 'Pengembalian berhasil diproses.');
    }

    public function cancel(Borrowing $borrowing): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user->id === $borrowing->user_id, 403);

        if ($borrowing->status !== 'requested') {
            return back()->with('status', 'Peminjaman ini tidak bisa dibatalkan.');
        }

        $borrowing->update([
            'status' => 'rejected',
            'notes' => 'Dibatalkan oleh peminjam.',
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'module' => 'borrowing',
            'action' => 'cancel',
            'description' => 'Pengajuan peminjaman dibatalkan oleh peminjam.',
            'payload' => ['borrowing_code' => $borrowing->borrowing_code],
        ]);

        return redirect('/borrowings')->with('status', 'Pengajuan peminjaman berhasil dibatalkan.');
    }
}