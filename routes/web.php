<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\PengeluarannController;
use App\Http\Controllers\SupplierController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth')->group(function () {
    Route::resource('/', DashboardController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('produk', ProdukController::class);
    Route::resource('supplier', SupplierController::class);
    Route::resource('penerimaan', PenerimaanController::class);
    Route::resource('pengeluaran', PengeluarannController::class);
    Route::patch('/pengeluaran/{id}/approve', [PengeluarannController::class, 'approve'])->name('pengeluaran.approve');
});