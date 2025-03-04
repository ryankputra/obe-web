<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\CPLController;
use App\Http\Controllers\CPMKController;
use App\Http\Controllers\Auth\LoginController;

// Route untuk halaman utama (arahkan ke login)
Route::get('/', function () {
    return view('auth.login');
});

// Tambahkan route autentikasi
Auth::routes();

// Route yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    // Route untuk dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Route untuk mata kuliah
    Route::resource('mata_kuliah', MataKuliahController::class);

    // Route untuk dosen
    Route::resource('dosen', DosenController::class);

    // Route untuk mahasiswa
    Route::resource('mahasiswa', MahasiswaController::class);

    // Route untuk CPL
    Route::resource('cpl', CPLController::class);

    // Route untuk CPMK
    Route::resource('cpmk', CPMKController::class);
});

// Route untuk logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
