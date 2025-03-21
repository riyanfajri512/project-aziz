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
Route::get('/jeniskendaraan', [JenisKendaraanController::class, 'index'])->name('jenis-kendaraan');
Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori');
Route::get('/lokasi', [LokasiController::class, 'index'])->name('lokasi');
Route::get('/sp', [SpController::class, 'index'])->name('sp');
Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier');
Route::get('/user', [UserController::class, 'index'])->name('user');
