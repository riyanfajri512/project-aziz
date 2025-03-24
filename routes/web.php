<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisKendaraanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\SpController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboardnew', [DashboardController::class, 'index'])->name('dashboard');

// Jenis Kendaraan
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



