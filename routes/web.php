<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\AktivasiController;
use App\Http\Controllers\PelangganController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('paket', PaketController::class);
    Route::resource('pelanggan', PelangganController::class);
    Route::resource('promo', PromoController::class);

    Route::get('/aktivasi-layanan', [AktivasiController::class, 'create'])->name('aktivasi.create');
    
});
