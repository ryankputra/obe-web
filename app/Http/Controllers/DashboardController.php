<?php
//cek
namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Http\Request;

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

        // Tangani kasus kosong
        if ($prodiData->isEmpty()) {
            $prodiData = collect([['nama_prodi' => 'Tidak ada data', 'jumlah_mahasiswa' => 0]]);
        }

        return view('dashboard', compact('prodiData'));
    }
}