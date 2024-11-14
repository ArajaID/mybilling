<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaketController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('paket', PaketController::class);
});
