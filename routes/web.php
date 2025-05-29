<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\Auth\LoginController; // Pastikan path ini benar
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CplController;
use App\Http\Controllers\CpmkController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PenilaianController; // Pastikan sudah di-import

// Route untuk halaman utama (arahkan ke login)
Route::get('/', function () {
    return view('auth.login');
});

Route::get('password/reset', function () {
    return view('auth.passwords.email');
})->name('password.request');

// User Management (pertimbangkan untuk memindahkan sebagian besar ke dalam middleware 'auth')
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

    // --- Route untuk Penilaian ---
    Route::prefix('penilaian')->name('penilaian.')->group(function () {
        Route::get('/', [PenilaianController::class, 'index'])->name('index'); // Akan menjadi route('penilaian.index')
        Route::get('/mata-kuliah/{id_mata_kuliah}', [PenilaianController::class, 'showPenilaianMataKuliah'])->name('mata_kuliah.show'); // Akan menjadi route('penilaian.mata_kuliah.show')
        // Anda bisa menambahkan route lain di sini, misalnya untuk menyimpan nilai:
        // Route::post('/mata-kuliah/{id_mata_kuliah}/store', [PenilaianController::class, 'storeNilai'])->name('nilai.store');
    });
    // --- Akhir Route untuk Penilaian ---
});

// Additional dosen route (pertimbangkan untuk memindahkannya ke dalam grup 'auth' jika perlu login)
Route::get('/dosen/{id}/kompetensi', [DosenController::class, 'showKompetensi'])->name('dosen.kompetensi')->middleware('auth'); // Contoh jika diproteksi

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');