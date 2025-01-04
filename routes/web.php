<?php

use App\Models\Pelanggan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ODCController;
use App\Http\Controllers\ODPController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\AktivasiController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\TutupBukuController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PerangkatController;

Route::get('/', function () {
    return redirect('login');
});

Auth::routes(['register' => false, 'verify' => true]);

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('paket', PaketController::class)->except(['show', 'destroy']);
    Route::resource('pelanggan', PelangganController::class);
    Route::resource('promo', PromoController::class);
    Route::resource('odc', ODCController::class)->except(['show', 'destroy']);
    Route::resource('odp', ODPController::class)->except(['show', 'destroy']);
    Route::resource('perangkat', PerangkatController::class)->except(['show', 'destroy']);
    
    Route::get('/get-odp/{id}', [PelangganController::class, 'getODP']);
    Route::post('/pelanggan/{id}/deactivate', [PelangganController::class, 'deactivate']);

    Route::get('/aktivasi-layanan', [AktivasiController::class, 'create'])->name('aktivasi.create');
    Route::post('/aktivasi-layanan', [AktivasiController::class, 'store'])->name('aktivasi.store');
    Route::get('/online-pelanggan', [AktivasiController::class, 'pelangganOnline'])->name('pelanggan-online');

    Route::get('/tagihan', [TagihanController::class, 'index'])->name('tagihan.index');
    Route::get('/tagihan-terima', [TagihanController::class, 'create'])->name('tagihan.create');
    Route::post('/tagihan', [TagihanController::class, 'store'])->name('tagihan.store');

    Route::resource('pengeluaran', PengeluaranController::class)->except(['show', 'destroy']);
    Route::resource('pemasukan', PemasukanController::class)->except(['show', 'destroy']);

    Route::get('/report/arus-kas', [ReportController::class, 'arusKas'])->name('report.aruskas');
    Route::get('/report/arus-kas-pdf/{start_date}/{end_date}', [ReportController::class, 'arusKasPDF'])->name('report.aruskaspdf');
    Route::get('/report/promo-pelanggan', [ReportController::class, 'promoPelanggan'])->name('report.promopelanggan');
    Route::get('/report/pelanggan', [ReportController::class, 'pelanggan'])->name('report.pelanggan');

    Route::get('/change-password', [ProfileController::class, 'changePassword']);
    Route::post('/change-password', [ProfileController::class, 'updatePassword'])->name('update-password');

    Route::get('/tutup-buku', [TutupBukuController::class, 'index'])->name('tutupbuku.index');
    Route::get('/tutup-buku/{bulan}/{tahun}', [TutupBukuController::class, 'isPosted'])->name('tutupbuku.posting');

});

Route::get('/send-telegram', [NotifikasiController::class, 'sendTelegram']);

Route::post('/telegram/webhook', [TelegramController::class, 'webhook']);