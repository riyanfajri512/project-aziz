<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisKendaraanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\PendistibusianController;
use App\Http\Controllers\PermintaanController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\SpController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', [LoginController::class, 'showLoginForm'])->name('showLoginForm');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboardnew', [DashboardController::class, 'index'])->name('dashboard');

    //Permintaan
    Route::get('/permintaan', [PermintaanController::class, 'index'])->name('permintaan.index');
    Route::get('/permintaan/tambah', [PermintaanController::class, 'tambah'])->name('permintaan.formtambah');

    // Penerimaan
    Route::get('/penerimaan', [PenerimaanController::class, 'index'])->name('penerimaan');
    Route::get('/penerimaan/tambah', [PenerimaanController::class, 'tambah'])->name('penerimaan.tambahan');

    // pendistribusian
    Route::get('/pendistribusian', [PendistibusianController::class, 'index'])->name('pendistribusian');
    Route::get('/pendistribusian/tambah', [PendistibusianController::class, 'tambah'])->name('pendistribusian.tambah');

    // Jenis JenisKendaraanController
    Route::get('/jeniskendaraan', [JenisKendaraanController::class, 'index'])->name('jenis-kendaraan');
    Route::post('/jeniskendaraan/store', [JenisKendaraanController::class, 'store']);
    Route::put('/jeniskendaraan/update/{id}', [JenisKendaraanController::class, 'update']);
    Route::delete('/jeniskendaraan/destroy/{id}', [JenisKendaraanController::class, 'destroy']);

    // Kategori
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori');
    route::post('/kategori/store', [KategoriController::class, 'store']);
    route::put('/kategori/update/{id}', [KategoriController::class, 'update']);
    route::delete('/kategori/destroy/{id}', [KategoriController::class, 'destroy']);


    // Lokasi
    Route::get('/lokasi', [LokasiController::class, 'index'])->name('lokasi');
    Route::post('/lokasi/store', [LokasiController::class, 'store']);
    Route::put('/lokasi/update/{id}', [LokasiController::class, 'update']);
    Route::delete('/lokasi/destroy/{id}', [LokasiController::class, 'destroy']);

    // Sp
    Route::get('/sp', [SpController::class, 'index'])->name('sp');
    Route::post('/sp/store', [SpController::class, 'store']);
    Route::put('/sp/update/{id}', [SpController::class, 'update']);
    Route::delete('/sp/destroy/{id}', [SpController::class, 'destroy']);

    // Supplier
    Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier');
    Route::post('/supplier/store', [SupplierController::class, 'store']);
    Route::put('/supplier/update/{id}', [SupplierController::class, 'update']);
    Route::delete('/supplier/destroy/{id}', [SupplierController::class, 'destroy']);

    // User
    Route::get('/user', [UserController::class, 'index'])->name('user');
});




