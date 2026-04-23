<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinePaymentController;
use App\Http\Controllers\TechnicalReportController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\UserPortalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/wallet/top-up', [WalletController::class, 'topUp'])->name('wallet.topup');
    Route::get('/catalog', [UserPortalController::class, 'catalog'])->name('catalog');
    Route::get('/catalog/{asset}', [UserPortalController::class, 'show'])->name('catalog.show');
    Route::get('/profile', [UserPortalController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [UserPortalController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [UserPortalController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/profile/change-password', [UserPortalController::class, 'updatePassword'])->name('profile.change-password.update');
    Route::get('/profile/delete-account', [UserPortalController::class, 'showDeleteAccount'])->name('profile.delete-account.show');
    Route::delete('/profile/delete-account', [UserPortalController::class, 'destroy'])->name('profile.destroy');
    Route::post('/fines/{fine}/pay', [FinePaymentController::class, 'pay'])->name('fines.pay');

    Route::get('/categories', [CategoryController::class, 'index'])->middleware('role:admin');
    Route::post('/categories', [CategoryController::class, 'store'])->middleware('role:admin');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->middleware('role:admin');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->middleware('role:admin');

    Route::get('/assets', [AssetController::class, 'index'])->middleware('role:admin');
    Route::post('/assets', [AssetController::class, 'store'])->middleware('role:admin');
    Route::put('/assets/{asset}', [AssetController::class, 'update'])->middleware('role:admin');
    Route::delete('/assets/{asset}', [AssetController::class, 'destroy'])->middleware('role:admin');

    Route::get('/borrowings', [BorrowingController::class, 'index']);
    Route::get('/borrowings/{borrowing}', [BorrowingController::class, 'show']);
    Route::post('/borrowings', [BorrowingController::class, 'store']);
    Route::post('/borrowings/{borrowing}/cancel', [BorrowingController::class, 'cancel']);
    Route::post('/borrowings/{borrowing}/approve', [BorrowingController::class, 'approve'])->middleware('role:admin,petugas');
    Route::post('/borrowings/{borrowing}/decline', [BorrowingController::class, 'decline'])->middleware('role:admin,petugas');
    Route::post('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnBorrowing'])->middleware('role:admin,petugas');

    Route::get('/reports/technical', [TechnicalReportController::class, 'preview'])->middleware('role:admin,petugas');
    Route::get('/reports/technical/excel', [TechnicalReportController::class, 'exportExcel'])->middleware('role:admin,petugas');
    Route::get('/reports/technical/pdf', [TechnicalReportController::class, 'download'])->middleware('role:admin,petugas');
});
