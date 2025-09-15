<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\PersediaanController;
use App\Http\Controllers\PenyesuaianPersediaanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Barang routes - Owner, Persediaan
    Route::middleware('role:Owner,Persediaan')->group(function () {
        Route::get('barang/{barang}/edit-data', [BarangController::class, 'editData'])->name('barang.edit_data');
        Route::resource('barang', BarangController::class)->except(['show']);
        Route::resource('barang_masuk', BarangMasukController::class)->except(['edit', 'update']);
        Route::resource('penyesuaian_persediaan', PenyesuaianPersediaanController::class)->except(['edit', 'update']);
        Route::get('laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.exportPdf');
        Route::resource('laporan', LaporanController::class);
    });

    // User routes - Owner only
    Route::middleware('role:Owner')->group(function () {
        Route::get('user/{user}/edit-data', [UserController::class, 'editData'])->name('user.edit_data');
        Route::resource('user', UserController::class)->except(['show']);
    });

        // Persediaan routes - Owner, Persediaan, Produksi
    Route::middleware('role:Owner,Persediaan,Produksi')->group(function () {
        Route::get('persediaan/export-stok-menipis', [PersediaanController::class, 'exportStokMenipis'])->name('persediaan.exportStokMenipis');
        Route::resource('persediaan', PersediaanController::class);
    });

    // Pesanan routes - Owner, Produksi
    Route::middleware('role:Owner,Produksi')->group(function () {
        Route::get('pesanan/next-number', [PesananController::class, 'getNextNumber'])->name('pesanan.next_number');
        Route::get('pesanan/{pesanan}/edit-data', [PesananController::class, 'getEditData'])->name('pesanan.edit_data');
        Route::get('pesanan/production-usage-data', [PesananController::class, 'getProductionUsageData'])->name('pesanan.production-usage-data');
        Route::post('pesanan/process-stock', [PesananController::class, 'processStock'])->name('pesanan.process-stock');
        Route::post('pesanan/complete-production', [PesananController::class, 'completeProduction'])->name('pesanan.complete-production');
        Route::patch('pesanan/{pesanan}/update-status', [PesananController::class, 'updateStatus'])->name('pesanan.update-status');
        Route::resource('pesanan', PesananController::class);
        Route::get('api/persediaan/stock-data', [PersediaanController::class, 'getStockData'])->name('api.persediaan.stock-data');
    });

    // Notification routes - Accessible by all authenticated users
    Route::middleware('auth')->group(function () {
        Route::get('api/notifications', [NotificationController::class, 'getNotifications'])->name('api.notifications');
        Route::get('api/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('api.notifications.unread-count');
        Route::post('api/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('api.notifications.mark-read');
        Route::post('api/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('api.notifications.mark-all-read');
        Route::delete('api/notifications/{id}', [NotificationController::class, 'delete'])->name('api.notifications.delete');
    });
});

require __DIR__.'/auth.php';
