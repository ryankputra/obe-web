<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\fakultasfstController;
use App\Http\Controllers\InformatikaController;
use App\Http\Controllers\sisteminformasiController;
use App\Http\Controllers\RekayasaperangkatlunakController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\fakultasfebController;
use App\Http\Controllers\AkuntansiController;
use App\Http\Controllers\sistemmanegementController;

use App\Http\Controllers\fakultasVokasiController;
use App\Http\Controllers\AkuntansiVokasiController;
use App\Http\Controllers\ManagemenkeuController;
use App\Http\Controllers\BahasaingController;

use App\Http\Controllers\CplController;
use App\Http\Controllers\CpmkController;
use App\Http\Controllers\ProdiController;

// Route::get('/mata-kuliah', [MataKuliahController::class, 'index'])->name('listMk');
// Route::post('/mata-kuliah/save', [MataKuliahController::class, 'store'])->name('saveMk');
// Route::post('/mata-kuliah/update', [MataKuliahController::class, 'update'])->name('updateMk');
// Route::post('/mata-kuliah/delete', [MataKuliahController::class, 'destroy'])->name('deleteMk');









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
    Route::put('/mata-kuliah/{id}', [MataKuliahController::class, 'update'])->name('mata_kuliah.update');
    Route::delete('/mata-kuliah/{id}', [MataKuliahController::class, 'destroy'])->name('mata_kuliah.destroy');
    Route::resource('mata_kuliah', MataKuliahController::class);
    // Route::post('/saveMk', [MataKuliahController::class, 'saveMk'])->name('saveMk');
    // Route::put('/editMk/{id}', [MataKuliahController::class, 'editMk'])->name('editMk');

    // Route untuk dosen
    Route::resource('dosen', DosenController::class);

    //route cpl
    Route::resource('cpl', CplController::class);
    Route::resource('cpmk',CpmkController::class);


    // Route untuk mahasiswa
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::prefix('fakultasfst')->group(function () {
        Route::get('saintek', [ProdiController::class, 'index'])->name('fakultasfst.saintek');
        Route::get('saintek/{id}', [ProdiController::class, 'showDetail'])->name('fakultasfst.saintek.detail');

        // Prodi CRUD routes
        Route::post('prodi', [ProdiController::class, 'store'])->name('fakultasfst.prodi.store');
        Route::put('prodi/{id}', [ProdiController::class, 'update'])->name('fakultasfst.prodi.update');
        Route::delete('prodi/{id}', [ProdiController::class, 'destroy'])->name('fakultasfst.prodi.destroy');
    });


    Route::get('/informatika', function () {
        return view('fakultasfst.informatika');
    });
    Route::get('/sistem-informasi', function () {
        return view('fakultasfst.sistem-informasi');
    });
    
    Route::get('/rpl', function () {
        return view('fakultasfst.rpl');
    });
    
    

    // Route untuk CPL
    Route::get('/cpl', [CplController::class, 'index'])->name('cpl.index');

    // Route untuk CPMK
    Route::get('/cpmk', [CpmkController::class, 'index'])->name('cpmk.index');
});

// Route untuk logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
