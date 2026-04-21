<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Asset;
use App\Models\Borrowing;
use App\Models\FinePayment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $recentLogs = ActivityLog::with('user')->latest()->limit(8)->get();

        $summary = [
            'users' => User::count(),
            'assets' => Asset::count(),
            'borrowings' => Borrowing::count(),
            'openBorrowings' => Borrowing::whereIn('status', ['requested', 'borrowed', 'late'])->count(),
        ];

        $monthlyBorrowings = Borrowing::select('borrowed_at')
            ->whereNotNull('borrowed_at')
            ->orderBy('borrowed_at')
            ->get()
            ->groupBy(fn ($borrowing) => $borrowing->borrowed_at->format('Y-m'))
            ->map(fn ($items) => $items->count());

        $topAssets = DB::table('borrowing_items')
            ->join('assets', 'borrowing_items.asset_id', '=', 'assets.id')
            ->select('assets.name', DB::raw('SUM(borrowing_items.quantity) as total'))
            ->groupBy('assets.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $pendingFines = FinePayment::where('user_id', $user->id)
            ->where('status', 'pending')
            ->sum('amount');

        return view('dashboard', compact('user', 'summary', 'recentLogs', 'monthlyBorrowings', 'topAssets', 'pendingFines'));
    }

    public function topUp(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1000'],
        ]);

        $user = $request->user();
        $user->balance = $user->balance + $data['amount'];
        $user->save();

        $pendingFines = FinePayment::where('user_id', $user->id)
            ->where('status', 'pending')
            ->orderBy('created_at')
            ->get();

        foreach ($pendingFines as $fine) {
            if ($user->balance < $fine->amount) {
                break;
            }

            $user->balance -= $fine->amount;
            $fine->status = 'paid';
            $fine->paid_at = now();
            $fine->save();
        }

        $user->status = FinePayment::where('user_id', $user->id)->where('status', 'pending')->exists() ? 'locked' : 'active';
        $user->locked_reason = $user->status === 'locked' ? 'Menunggu pelunasan denda.' : null;
        $user->save();

        ActivityLog::create([
            'user_id' => $user->id,
            'module' => 'wallet',
            'action' => 'top_up',
            'description' => 'Saldo virtual ditambahkan.',
            'payload' => ['amount' => $data['amount']],
        ]);

        return back()->with('status', 'Saldo berhasil diperbarui.');
    }
}