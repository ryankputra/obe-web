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
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\BobotNilaiController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('password/reset', function () {
    return view('auth.passwords.email');
})->name('password.request');

Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
});

Route::prefix('bobot-nilai')->name('bobot_nilai.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [BobotNilaiController::class, 'index'])->name('index');

});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    Route::resource('mata_kuliah', MataKuliahController::class);
    Route::resource('dosen', DosenController::class);
    Route::resource('cpl', CplController::class);
    Route::resource('cpmk', CpmkController::class);

    // Route untuk pencarian mahasiswa (JSON)
    Route::get('/api/mahasiswa/search', [MahasiswaController::class, 'searchJson'])->name('mahasiswa.search.json');

    Route::resource('mahasiswa', MahasiswaController::class)->except(['update']);
    Route::put('mahasiswa/{mahasiswa}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');

    Route::prefix('fakultasfst')->name('fakultasfst.')->group(function () {
        Route::get('/', [ProdiController::class, 'index'])->name('index');

        Route::prefix('prodi')->name('prodi.')->group(function () {
            Route::post('/', [ProdiController::class, 'store'])->name('store');
            Route::get('/{prodi}', [ProdiController::class, 'show'])->name('show');
            Route::put('/{prodi}', [ProdiController::class, 'update'])->name('update');
            Route::delete('/{prodi}', [ProdiController::class, 'destroy'])->name('destroy');
        });
    });

    Route::prefix('penilaian')->name('penilaian.')->group(function () {
        Route::get('/', [PenilaianController::class, 'index'])->name('index');
        Route::get('/mata-kuliah/{id_mata_kuliah}', [PenilaianController::class, 'showPenilaianMataKuliah'])->name('mata_kuliah.show');

        Route::post('/mata-kuliah/{id_mata_kuliah}/store', [PenilaianController::class, 'storeNilai'])->name('store');
    });
});

Route::get('/dosen/{dosen}/kompetensi', [DosenController::class, 'showKompetensi'])->name('dosen.kompetensi')->middleware('auth');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
