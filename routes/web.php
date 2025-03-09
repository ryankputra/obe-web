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
use App\Http\Controllers\fakultasfstController;
use App\Http\Controllers\InformatikaController;
use App\Http\Controllers\sisteminformasiController;
use App\Http\Controllers\RekayasaperangkatlunakController;
use App\Http\Controllers\ProfileController;


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

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    // Route untuk mata kuliah
    Route::resource('mata_kuliah', MataKuliahController::class);
    Route::post('/saveMk', [MataKuliahController::class, 'saveMk'])->name('saveMk');

    // Route untuk dosen
    Route::get('/dosen', [DosenController::class, 'index'])->name('dosen.index');
    

    // Route untuk mahasiswa
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::get('/fakultasfst/sains-teknologi', [fakultasfstController::class, 'sainsTeknologi'])->name('fakultasfst.sains-teknologi');
    Route::get('/fakultasfst/sains-teknologi/informatika', [InformatikaController::class, 'index'])->name('fakultasfst.sains-teknologi.informatika');
    Route::get('/fakultasfst/sains-teknologi/sisteminformasi', [sisteminformasiController::class, 'index'])->name('fakultasfst.sains-teknologi.sisteminformasi');
    Route::get('/fakultasfst/sains-teknologi/Rekayasaperangkatlunak', [RekayasaperangkatlunakController::class, 'index'])->name('fakultasfst.sains-teknologi.Rekayasaperangkatlunak');



    // Route untuk CPL
    Route::resource('cpl', CPLController::class);

    // Route untuk CPMK
    Route::resource('cpmk', CPMKController::class);
});

// Route untuk logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
