<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Asset;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\FinePayment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserPortalController extends Controller
{
    public function catalog(Request $request): View
    {
        Asset::synchronizeStockAvailability();

        $assets = Asset::with('category')
            ->where(function ($query) use ($request) {
                if ($request->filled('search')) {
                    $search = $request->string('search')->toString();
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%");
                }
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category_id', $request->integer('category'));
            })
            ->where(function ($query) use ($request) {
                if ($request->filled('availability')) {
                    if ($request->string('availability')->toString() === 'available') {
                        $query->where('stock_available', '>', 5);
                    }

                    if ($request->string('availability')->toString() === 'limited') {
                        $query->whereBetween('stock_available', [1, 5]);
                    }
                }
            })
            ->when($request->filled('sort'), function ($query) use ($request) {
                match ($request->string('sort')->toString()) {
                    'price_low' => $query->orderBy('rent_fee'),
                    'price_high' => $query->orderByDesc('rent_fee'),
                    'latest' => $query->latest(),
                    default => $query->latest(),
                };
            }, fn ($query) => $query->latest())
            ->paginate(12)
            ->withQueryString();

        return view('pages.catalog', [
            'assets' => $assets,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function show(Asset $asset): View
    {
        Asset::synchronizeStockAvailability();

        $asset = Asset::with('category')->findOrFail($asset->id);

        $relatedAssets = Asset::with('category')
            ->where('category_id', $asset->category_id)
            ->where('id', '!=', $asset->id)
            ->latest()
            ->limit(4)
            ->get();

        return view('pages.product-detail', [
            'asset' => $asset,
            'relatedAssets' => $relatedAssets,
        ]);
    }

    public function profile(): View
    {
        $user = auth()->user();

        $borrowings = Borrowing::with(['items.asset', 'payment'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $outstandingFines = FinePayment::with('borrowing')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->get();

        $paymentHistory = FinePayment::with('borrowing')
            ->where('user_id', $user->id)
            ->where('status', 'paid')
            ->latest('paid_at')
            ->get();

        $stats = [
            'total_rentals' => $borrowings->count(),
            'active_rentals' => $borrowings->whereIn('status', ['borrowed', 'late'])->count(),
            'on_time' => $borrowings->count() > 0
                ? (string) round(($borrowings->where('status', 'returned')->count() / $borrowings->count()) * 100)
                : '100',
        ];

        return view('pages.profile-user', [
            'stats' => $stats,
            'outstandingFines' => $outstandingFines,
            'paymentHistory' => $paymentHistory,
            'borrowingHistory' => $borrowings,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $user->update($data);

        ActivityLog::create([
            'user_id' => $user->id,
            'module' => 'profile',
            'action' => 'update',
            'description' => 'Profil user diperbarui.',
            'payload' => $data,
        ]);

        return back()->with('status', 'Profil berhasil diperbarui.');
    }

    public function changePassword(): View
    {
        return view('pages.profile-change-password');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = $request->user();
        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'module' => 'auth',
            'action' => 'password_change',
            'description' => 'Kata sandi akun berhasil diperbarui.',
        ]);

        return redirect('/profile')->with('status', 'Password berhasil diperbarui.');
    }

    public function showDeleteAccount(): View
    {
        return view('pages.profile-delete-account');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        DB::transaction(function () use ($user) {
            // Log the deletion
            ActivityLog::create([
                'user_id' => $user->id,
                'module' => 'profile',
                'action' => 'account_deleted',
                'description' => 'Akun telah dihapus secara permanen.',
            ]);

            // Delete related data
            Borrowing::where('user_id', $user->id)->delete();
            FinePayment::where('user_id', $user->id)->delete();
            ActivityLog::where('user_id', $user->id)->delete();

            // Delete the user account
            $user->delete();
        });

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Akun Anda telah dihapus secara permanen.');
    }
}
