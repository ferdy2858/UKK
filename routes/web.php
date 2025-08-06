<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\PengeluarannController;
use App\Http\Controllers\SupplierController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['web', 'auth', 'role:admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('kategori', KategoriController::class);
    Route::resource('produk', ProdukController::class);
    Route::resource('supplier', SupplierController::class);
    Route::resource('penerimaan', PenerimaanController::class);
    Route::resource('pengeluaran', PengeluarannController::class);
    Route::patch('/pengeluaran/{id}/approve', [PengeluarannController::class, 'approve'])->name('pengeluaran.approve');
});

/*
|--------------------------------------------------------------------------
| MANAJER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['web', 'auth','role:manajer'])->prefix('manajer')->name('manajer.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::get('supplier', [SupplierController::class, 'index'])->name('supplier.index');
    Route::get('supplier/{id}', [SupplierController::class, 'show'])->name('supplier.show');
    Route::get('penerimaan', [PenerimaanController::class, 'index'])->name('penerimaan.index');
    Route::get('penerimaan/{id}', [PenerimaanController::class, 'show'])->name('penerimaan.show');
    Route::get('pengeluaran', [PengeluarannController::class, 'index'])->name('pengeluaran.index');
    Route::get('pengeluaran/{id}', [PengeluarannController::class, 'show'])->name('pengeluaran.show');
    Route::patch('pengeluaran/{id}/approve', [PengeluarannController::class, 'approve'])->name('pengeluaran.approve');
});
/*
|--------------------------------------------------------------------------
| Staf Gudang
|--------------------------------------------------------------------------
*/
Route::middleware(['web', 'auth', 'role:staf gudang'])->prefix('gudang')->name('gudang.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('penerimaan', PenerimaanController::class)->except(['destroy']);
    Route::resource('pengeluaran', PengeluarannController::class)->except(['destroy', 'approve']);
    Route::get('produk', [ProdukController::class, 'index'])->name('produk.index');
});