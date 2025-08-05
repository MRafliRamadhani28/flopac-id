<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\PersediaanController;
use App\Http\Controllers\PenyesuaianPersediaanController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Barang routes - Owner, Persediaan
    Route::middleware('role:Owner,Persediaan')->group(function () {
        Route::resource('barang', BarangController::class)->except(['show']);
        Route::resource('barang_masuk', BarangMasukController::class)->except(['edit', 'update']);
        Route::resource('penyesuaian_persediaan', PenyesuaianPersediaanController::class)->except(['edit', 'update']);
    });

    // User routes - Owner only
    Route::middleware('role:Owner')->group(function () {
        Route::resource('user', UserController::class)->except(['show']);
        Route::get('user-roles', [UserController::class, 'roles'])->name('user.roles');
        Route::resource('laporan', LaporanController::class);
    });

    // Persediaan routes - Owner, Persediaan, Produksi
    Route::middleware('role:Owner,Persediaan,Produksi')->group(function () {
        Route::resource('persediaan', PersediaanController::class);
    });

    // Pesanan routes - Owner, Produksi
    Route::middleware('role:Owner,Produksi')->group(function () {
        Route::get('pesanan/next-number', [PesananController::class, 'getNextNumber'])->name('pesanan.next_number');
        Route::get('pesanan/production-usage-data', [PesananController::class, 'getProductionUsageData'])->name('pesanan.production-usage-data');
        Route::post('pesanan/process-stock', [PesananController::class, 'processStock'])->name('pesanan.process-stock');
        Route::post('pesanan/complete-production', [PesananController::class, 'completeProduction'])->name('pesanan.complete-production');
        Route::patch('pesanan/{pesanan}/update-status', [PesananController::class, 'updateStatus'])->name('pesanan.update-status');
        Route::resource('pesanan', PesananController::class)->except(['edit']);
        Route::get('api/persediaan/stock-data', [PersediaanController::class, 'getStockData'])->name('api.persediaan.stock-data');
    });
});

require __DIR__.'/auth.php';
