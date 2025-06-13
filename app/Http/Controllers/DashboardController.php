<?php

namespace App\Http\Controllers;

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
        // Ambil data jumlah mahasiswa per prodi
        $prodiData = Prodi::withCount('mahasiswas')->get()->map(function ($prodi) {
            return [
                'nama_prodi' => $prodi->nama_prodi,
                'jumlah_mahasiswa' => $prodi->mahasiswas_count,
            ];
        });

        // Tangani kasus kosong untuk grafik
        if ($prodiData->isEmpty()) {
            $prodiData = collect([['nama_prodi' => 'Tidak ada data', 'jumlah_mahasiswa' => 0]]);
        }

        // --- Logika untuk mengambil data event akademik ---
        $events = collect(); // Inisialisasi sebagai koleksi kosong Laravel

        // Periksa apakah pengguna sudah login dan memiliki peran 'admin' ATAU 'dosen'
        if (Auth::check() && (Auth::user()->role == 'admin' || Auth::user()->role == 'dosen')) {
            $events = EventAkademik::select(
                'tanggal_event as date',
                'deskripsi as description',
                'tipe as type'
            )->get();
        }
        // --- Akhir logika data event ---

        // Kirimkan data prodi dan event ke view dashboard
        return view('dashboard', compact('prodiData', 'events'));
    }
}