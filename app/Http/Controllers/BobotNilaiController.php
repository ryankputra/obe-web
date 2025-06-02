<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MataKuliah; // Asumsikan Anda memiliki model MataKuliah
use Illuminate\Support\Facades\DB; // Untuk query yang lebih kompleks jika diperlukan

class BobotNilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Mengambil query pencarian jika ada
        $search = $request->input('search');

        // Query dasar untuk mata kuliah
        // Kita perlu mengambil nama mata kuliah dan jumlah mahasiswa yang terkait.
        // Asumsi: Model MataKuliah memiliki relasi 'mahasiswas'
        // atau Anda memiliki tabel pivot seperti 'mahasiswa_mata_kuliah'.
        // Jika relasi 'mahasiswas' ada di model MataKuliah:
        $query = MataKuliah::withCount('mahasiswas'); // 'mahasiswas' adalah nama relasi

        // Jika ada parameter pencarian, filter berdasarkan nama atau kode mata kuliah
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_mk', 'like', "%{$search}%")
                  ->orWhere('kode_mk', 'like', "%{$search}%");
            });
        }

        // Ambil data dengan paginasi
        $mataKuliahs = $query->orderBy('nama_mk')->paginate(10); // Sesuaikan jumlah item per halaman

        // Kirim data ke view
        return view('bobot_nilai.index', compact('mataKuliahs', 'search'));
    }

    // Anda bisa menambahkan method lain di sini jika diperlukan (create, store, edit, update, destroy)
    // untuk mengelola bobot nilai jika fungsionalitasnya lebih dari sekadar menampilkan.
    // Misalnya, jika "Bobot Nilai" merujuk pada pengaturan bobot untuk setiap mata kuliah.
}
