<?php

namespace App\Http\Controllers;
use App\Models\Cpmk;

use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\EventAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk Auth::check() dan Auth::user()->role

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $prodiData = Prodi::withCount('mahasiswas')->get()->map(function ($prodi) {
            return [
                'nama_prodi' => $prodi->nama_prodi,
                'jumlah_mahasiswa' => $prodi->mahasiswas_count,
            ];
        });
    
        if ($prodiData->isEmpty()) {
            $prodiData = collect([['nama_prodi' => 'Tidak ada data', 'jumlah_mahasiswa' => 0]]);
        }
    
        $events = collect();
    
        if (Auth::check() && (Auth::user()->role == 'admin' || Auth::user()->role == 'dosen')) {
            $events = EventAkademik::select(
                'tanggal_event as date',
                'deskripsi as description',
                'tipe as type'
            )->get();
        }
    
        $grafikCpmk = collect();
    
        if (Auth::user()->role === 'dosen') {
    $userId = Auth::id(); // Mengambil ID user (dosen)

    $grafikCpmk = Cpmk::where('pic', $userId)
        ->with('jenisPenilaianBobot', 'mataKuliah') // pastikan relasi ini ada
        ->get()
        ->map(function ($cpmk) {
            $totalNilai = $cpmk->jenisPenilaianBobot->sum('nilai');
            return [
                'kode_cpmk' => $cpmk->kode_cpmk,
                'nama_mk' => optional($cpmk->mataKuliah)->nama_mata_kuliah ?? 'Tidak diketahui',
                'total_nilai' => round($totalNilai, 2),
            ];
        })
        ->sortByDesc('total_nilai') // urutkan dari nilai terbesar
        ->take(1); // hanya ambil 1 data tertinggi
}

    
        return view('dashboard', compact('prodiData', 'events', 'grafikCpmk'));
    }
    
}




