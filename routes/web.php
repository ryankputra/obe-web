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
use App\Http\Controllers\UserController;

// Route untuk halaman utama (arahkan ke login)
Route::get('/', function () {
    return view('auth.login');
});

Route::get('password/reset', function () {
    return view('auth.passwords.email');
})->name('password.request');

// User Management (outside auth for account creation)
Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Authentication routes
Auth::routes();

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    // Resources
    Route::resource('mata_kuliah', MataKuliahController::class);
    Route::resource('dosen', DosenController::class);
    Route::resource('cpl', CplController::class);
    Route::resource('cpmk', CpmkController::class);

    // Mahasiswa with custom update route for NIM
    Route::resource('mahasiswa', MahasiswaController::class)->except(['update']);
    Route::put('mahasiswa/{nim}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');

    // Fakultas FST Routes
    Route::prefix('fakultasfst')->group(function () {
        Route::get('/', [ProdiController::class, 'index'])->name('fakultasfst.index');

        Route::prefix('prodi')->group(function () {
            Route::post('/', [ProdiController::class, 'store'])->name('fakultasfst.prodi.store');
            Route::get('/{prodi}', [ProdiController::class, 'show'])->name('fakultasfst.prodi.show');
            Route::put('/{prodi}', [ProdiController::class, 'update'])->name('fakultasfst.prodi.update');
            Route::delete('/{prodi}', [ProdiController::class, 'destroy'])->name('fakultasfst.prodi.destroy');
        });
    });
});

// Additional dosen route (consider moving inside auth group if it needs authentication)
Route::get('/dosen/{id}/kompetensi', [DosenController::class, 'showKompetensi'])->name('dosen.kompetensi');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
