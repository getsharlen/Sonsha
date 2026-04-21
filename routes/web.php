<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TechnicalReportController;
use App\Http\Controllers\WalletController;
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

    Route::get('/categories', [CategoryController::class, 'index'])->middleware('role:admin,petugas');
    Route::post('/categories', [CategoryController::class, 'store'])->middleware('role:admin,petugas');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->middleware('role:admin,petugas');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->middleware('role:admin,petugas');

    Route::get('/assets', [AssetController::class, 'index'])->middleware('role:admin,petugas');
    Route::post('/assets', [AssetController::class, 'store'])->middleware('role:admin,petugas');
    Route::put('/assets/{asset}', [AssetController::class, 'update'])->middleware('role:admin,petugas');
    Route::delete('/assets/{asset}', [AssetController::class, 'destroy'])->middleware('role:admin,petugas');

    Route::get('/borrowings', [BorrowingController::class, 'index']);
    Route::post('/borrowings', [BorrowingController::class, 'store']);
    Route::post('/borrowings/{borrowing}/approve', [BorrowingController::class, 'approve'])->middleware('role:admin,petugas');
    Route::post('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnBorrowing']);

    Route::get('/reports/technical', [TechnicalReportController::class, 'preview'])->middleware('role:admin,petugas');
    Route::get('/reports/technical/pdf', [TechnicalReportController::class, 'download'])->middleware('role:admin,petugas');
});
