<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisKendaraanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\PendistibusianController;
use App\Http\Controllers\PermintaanController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\HistoryController;
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

    // Permintaan
    Route::get('/permintaan', [PermintaanController::class, 'index'])->name('permintaan.index');
    Route::get('/permintaan/tambah', [PermintaanController::class, 'tambah'])->name('permintaan.formtambah');
    Route::post('/permintaan/simpan', [PermintaanController::class, 'store'])->name('permintaan.simpan');
    Route::get('/permintaan/list', [PermintaanController::class, 'getListPermintaan'])->name('permintaan.list');
    Route::get('/permintaan/{id}', [PermintaanController::class, 'show'])->name('permintaan.show');
    Route::get('/permintaan/{id}/edit', [PermintaanController::class, 'edit'])->name('permintaan.edit');
    Route::post('/permintaan/{id}/approve', [PermintaanController::class, 'approve'])->name('permintaan.approve');
    Route::post('/permintaan/{id}/reject', [PermintaanController::class, 'reject'])->name('permintaan.reject');
    Route::get('/permintaan/{id}/export', [PermintaanController::class, 'exportPdf'])->name('permintaan.export');
    Route::delete('/permintaan/{id}', [PermintaanController::class, 'destroy'])->name('permintaan.destroy');
    Route::put('/permintaan/{id}', [PenerimaanController::class, 'update'])->name('permintaan.update');



    // Penerimaan
    Route::get('/penerimaan', [PenerimaanController::class, 'index'])->name('penerimaan.index');
    Route::get('/penerimaan/tambah', [PenerimaanController::class, 'tambah'])->name('penerimaan.tambahan');
    Route::post('/penerimaan/simpan', [PenerimaanController::class, 'store'])->name('penerimaan.simpan');
    Route::get('/penerimaan/list', [PenerimaanController::class, 'getListPenerimaan'])->name('penerimaan.list');
    Route::get('/penerimaan/getListItembyId', [PenerimaanController::class, 'getListItembyId'])->name('penerimaan.getListItembyId');
    Route::get('/penerimaan/{id}/export', [PenerimaanController::class, 'exportPdf'])->name('penerimaan.export');
    route::delete('/penerimaan/{id}', [PenerimaanController::class, 'destroy'])->name('penerimaan.destroy');
    Route::get('/penerimaan/{id}/detail', [PenerimaanController::class, 'showDetail'])->name('penerimaan.detail');
    route::get('/penerimaan/{id}/edit', [PenerimaanController::class, 'edit'])->name('penerimaan.edit');
    Route::put('/penerimaan/{id}', [PenerimaanController::class, 'update'])->name('penerimaan.update');


    // Pendistribusian
    Route::get('/pendistribusian', [PendistibusianController::class, 'index'])->name('pendistribusian');
    Route::get('/pendistribusian/list', [PendistibusianController::class, 'getlistPendistribusian'])->name('pendistribusian.list');
    Route::get('/pendistribusian/items/{id}', [PendistibusianController::class, 'getItems'])->name('pendistribusian.items');
    Route::get('/pendistribusian/tambah', [PendistibusianController::class, 'tambah'])->name('pendistribusian.tambah');
    Route::post('pendistribusian/store', [PendistibusianController::class, 'store'])->name('pendistribusian.store');
    route::get('/pendistribusian/{id}/edit', [PendistibusianController::class, 'edit'])->name('pendistribusian.edit');
    Route::put('/pendistribusian/{id}', [PendistibusianController::class, 'update'])->name('pendistribusian.update');

    // History
    Route::get('/history' ,[HistoryController::class, 'index'])->name('history');
    Route::post('/history/export', [HistoryController::class, 'exportPDF'])->name('history.export');


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
    Route::post('/user/store', [UserController::class, 'store']);
    Route::put('/user/update/{id}', [UserController::class, 'update']);
    Route::delete('/user/destroy/{id}', [UserController::class, 'destroy']);
    Route::post('/{id}/reset-password', [UserController::class, 'resetPassword'])->name('user.reset-password');
    Route::post('/{id}/restore', [UserController::class, 'restore'])->name('user.restore');
    Route::delete('/{id}/force-delete', [UserController::class, 'forceDelete'])->name('user.force-delete');
});
