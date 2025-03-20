<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisKendaraanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboardnew', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/jeniskendaraan', [JenisKendaraanController::class, 'index'])->name('jenis-kendaraan');
