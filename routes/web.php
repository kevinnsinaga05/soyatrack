<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StokBahanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\PenyesuaianStokController;
use App\Http\Controllers\OpnameController;
use App\Http\Controllers\RiwayatStokController;

Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

    Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
    Route::get('/produk/create', [ProductController::class, 'create'])->name('produk.create');
    Route::post('/produk', [ProductController::class, 'store'])->name('produk.store');
    // EDIT & UPDATE
    Route::get('/produk/{product}/edit', [ProductController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{product}', [ProductController::class, 'update'])->name('produk.update');

    // HAPUS
    Route::delete('/produk/{product}', [ProductController::class, 'destroy'])->name('produk.destroy');

    Route::get('/stok-bahan', [StokBahanController::class, 'index'])->name('stok.index');
    Route::get('/stok-bahan/create', [StokBahanController::class, 'create'])->name('stok.create');
    Route::post('/stok-bahan', [StokBahanController::class, 'store'])->name('stok.store');

    Route::get('/stok-bahan/{stokBahan}/edit', [StokBahanController::class, 'edit'])->name('stok.edit');
    Route::put('/stok-bahan/{stokBahan}', [StokBahanController::class, 'update'])->name('stok.update');

    Route::delete('/stok-bahan/{stokBahan}', [StokBahanController::class, 'destroy'])->name('stok.destroy');

    Route::get('/update-stok', [App\Http\Controllers\UpdateStokController::class, 'index'])->name('update.stok');

    Route::get('/update-stok/create', [App\Http\Controllers\UpdateStokController::class, 'create'])->name('update.stok.create');
    Route::post('/update-stok', [App\Http\Controllers\UpdateStokController::class, 'store'])->name('update.stok.store');

    Route::get('/update-stok/{tanggal}/lihat', [App\Http\Controllers\UpdateStokController::class, 'showByTanggal'])->name('update.stok.show');
    Route::get('/update-stok/{tanggal}/edit', [App\Http\Controllers\UpdateStokController::class, 'editByTanggal'])->name('update.stok.edit');
    Route::put('/update-stok/{tanggal}', [App\Http\Controllers\UpdateStokController::class, 'updateByTanggal'])->name('update.stok.update');
    Route::delete('/update-stok/{tanggal}', [App\Http\Controllers\UpdateStokController::class, 'destroyByTanggal'])->name('update.stok.delete');
    Route::get('/update-stok/export/{tanggal}', [App\Http\Controllers\UpdateStokController::class, 'downloadPdf'])->name('update.stok.export');
    Route::get('/stok-masuk', [BarangMasukController::class, 'index'])->name('stok.masuk.index');
    Route::get('/stok-masuk/create', [BarangMasukController::class, 'create'])->name('stok.masuk.create');
    Route::post('/stok-masuk', [BarangMasukController::class, 'store'])->name('stok.masuk.store');
    Route::put('/stok-masuk/{id}', [BarangMasukController::class, 'update'])->name('stok.masuk.update');
    Route::delete('/stok-masuk/{id}', [BarangMasukController::class, 'destroy'])->name('stok.masuk.destroy');

    Route::get('/stok-penyesuaian', [PenyesuaianStokController::class, 'index'])->name('stok.adjust.index');
    Route::get('/stok-penyesuaian/create', [PenyesuaianStokController::class, 'create'])->name('stok.adjust.create');
    Route::post('/stok-penyesuaian', [PenyesuaianStokController::class, 'store'])->name('stok.adjust.store');

    Route::get('/stok-opname/create', [OpnameController::class, 'create'])->name('stok.opname.create');
    Route::post('/stok-opname', [OpnameController::class, 'store'])->name('stok.opname.store');

    Route::get('/stok-riwayat', [RiwayatStokController::class, 'index'])->name('stok.riwayat');
    Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export/pdf', [App\Http\Controllers\LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');


    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
