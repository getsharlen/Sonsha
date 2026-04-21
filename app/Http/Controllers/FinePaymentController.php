<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\FinePayment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinePaymentController extends Controller
{
    public function pay(Request $request, FinePayment $fine): RedirectResponse
    {
        $user = $fine->relationLoaded('user') ? $fine->user : $fine->user()->first();
        $actor = $request->user();

        abort_unless(
            $actor->id === $fine->user_id || in_array($actor->role, ['admin', 'petugas'], true),
            403
        );

        if ($fine->status === 'paid') {
            return back()->with('status', 'Denda ini sudah lunas.');
        }

        if ($user->balance < $fine->amount) {
            return back()->withErrors([
                'balance' => 'Saldo Anda tidak cukup untuk membayar denda ini.',
            ]);
        }

        DB::transaction(function () use ($request, $fine, $user) {
            $user->decrement('balance', $fine->amount);

            $fine->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            $hasPendingFine = FinePayment::where('user_id', $user->id)
                ->where('status', 'pending')
                ->exists();

            $user->status = $hasPendingFine ? 'locked' : 'active';
            $user->locked_reason = $hasPendingFine ? 'Menunggu pelunasan denda.' : null;
            $user->save();

            ActivityLog::create([
                'user_id' => $request->user()->id,
                'module' => 'fine',
                'action' => 'pay',
                'description' => 'Pembayaran denda berhasil diproses.',
                'payload' => [
                    'fine_id' => $fine->id,
                    'amount' => $fine->amount,
                ],
            ]);
        });

        return back()->with('status', 'Denda berhasil dibayar.');
    }
}
