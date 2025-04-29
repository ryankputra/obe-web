<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CplController;
use App\Http\Controllers\CpmkController;
use App\Http\Controllers\ProdiController;


// Route untuk halaman utama (arahkan ke login)
Route::get('/', function () {
    return view('auth.login');
});

Route::get('password/reset', function () {
    return view('auth.passwords.email');
})->name('password.request');

// Tambahkan route autentikasi
Auth::routes();

// Route yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    // Mata Kuliah
    Route::resource('mata_kuliah', MataKuliahController::class);

    // Dosen
    Route::resource('dosen', DosenController::class);

    // CPL & CPMK
    Route::resource('cpl', CplController::class);
    Route::resource('cpmk', CpmkController::class);

    // Mahasiswa
    Route::resource('mahasiswa', MahasiswaController::class);

    // Fakultas FST Routes
    Route::prefix('fakultasfst')->group(function () {
        // Main index page
        Route::get('/', [ProdiController::class, 'index'])->name('fakultasfst.index');

        // Prodi CRUD routes
        Route::prefix('prodi')->group(function () {
            Route::post('/', [ProdiController::class, 'store'])->name('fakultasfst.prodi.store');
            Route::get('/{prodi}', [ProdiController::class, 'show'])->name('fakultasfst.prodi.show');
            Route::put('/{prodi}', [ProdiController::class, 'update'])->name('fakultasfst.prodi.update');
            Route::delete('/{prodi}', [ProdiController::class, 'destroy'])->name('fakultasfst.prodi.destroy');
        });
    });
});

Route::get('/dosen/{id}/kompetensi', [DosenController::class, 'showKompetensi'])->name('dosen.kompetensi');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
