<?php

use App\Models\Pelanggan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ODCController;
use App\Http\Controllers\ODPController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\AktivasiController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PengeluaranController;

Route::get('/', function () {
    return redirect('login');
});

Auth::routes(['register' => false, 'verify' => true]);

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('paket', PaketController::class);
    Route::resource('pelanggan', PelangganController::class);
    Route::resource('promo', PromoController::class);
    Route::resource('odc', ODCController::class);
    Route::resource('odp', ODPController::class);

    Route::get('/get-odp/{id}', [PelangganController::class, 'getODP']);

    Route::get('/aktivasi-layanan', [AktivasiController::class, 'create'])->name('aktivasi.create');
    Route::post('/aktivasi-layanan', [AktivasiController::class, 'store'])->name('aktivasi.store');
    Route::get('/online-pelanggan', [AktivasiController::class, 'pelangganOnline'])->name('pelanggan-online');

    Route::get('/tagihan', [TagihanController::class, 'index'])->name('tagihan.index');
    Route::get('/tagihan-terima', [TagihanController::class, 'create'])->name('tagihan.create');
    Route::post('/tagihan', [TagihanController::class, 'store'])->name('tagihan.store');

    Route::get('/pemasukan', [PemasukanController::class, 'index'])->name('pemasukan.index');
    Route::get('/pemasukan-tambah', [PemasukanController::class, 'create'])->name('pemasukan.create');
    Route::post('/pemasukan', [PemasukanController::class, 'store'])->name('pemasukan.store');

    Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
    Route::get('/pengeluaran-tambah', [PengeluaranController::class, 'create'])->name('pengeluaran.create');
    Route::post('/pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.store');

    Route::get('/report/arus-kas', [ReportController::class, 'arusKas'])->name('report.aruskas');
});

Route::get('/send-telegram', [NotifikasiController::class, 'sendTelegram']);