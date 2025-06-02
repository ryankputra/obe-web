<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator; // Import Paginator

class PenilaianController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        // Inisialisasi sebagai Paginator kosong
        // Parameter: items, total, perPage
        $assignedCoursesPaginated = new LengthAwarePaginator([], 0, 10);
        $assignedCoursesPaginated->withPath($request->url()); // Agar link paginasi benar

        if ($user && $user->role === 'dosen') { // Pastikan kondisi role ini sudah benar
            if (method_exists($user, 'mataKuliahYangDiampu')) {
                $query = $user->mataKuliahYangDiampu(); // Ini seharusnya mengembalikan query builder, bukan koleksi

                if ($request->filled('search_penilaian')) {
                    $search = $request->input('search_penilaian');
                    $query->where(function ($q) use ($search) {
                        $q->where('kode_mk', 'like', "%{$search}%")
                            ->orWhere('nama_mk', 'like', "%{$search}%");
                    });
                }

                // Lakukan paginasi pada query builder
                $paginatorInstance = $query->withCount('mahasiswas') // Menghitung jumlah mahasiswa terkait
                    ->orderBy('kode_mk', 'asc')
                    ->paginate(10) // Menghasilkan LengthAwarePaginator
                    ->withQueryString(); // Agar filter tetap ada saat paginasi

                // Transformasi item menggunakan through() pada Paginator
                // Pastikan objek $course memiliki properti sks_teori dan sks_praktik
                $transformedPaginator = $paginatorInstance->through(function ($course) {
                    return (object) [
                        'id' => $course->id, // Pastikan $course->id ada
                        'kode_mk' => $course->kode_mk ?? 'N/A',
                        'nama_mk' => $course->nama_mk ?? ($course->nama_mata_kuliah ?? 'N/A'), // Fallback jika nama_mk tidak ada
                        'mahasiswas_count' => $course->mahasiswas_count ?? 0, // Gunakan hasil withCount
                        'sks_teori' => $course->sks_teori ?? 0, // Ambil sks_teori
                        'sks_praktik' => $course->sks_praktik ?? 0, // Ambil sks_praktik
                    ];
                });
                $assignedCoursesPaginated = $transformedPaginator; // Override dengan Paginator yang sudah ditransformasi
            } else {
                // Dosen tidak punya method 'mataKuliahYangDiampu', $assignedCoursesPaginated tetap Paginator kosong
                \Illuminate\Support\Facades\Log::warning('Pengguna dosen ' . $user->name . ' tidak memiliki method relasi mataKuliahYangDiampu.');
            }
        } else {
            // Bukan dosen atau tidak login, $assignedCoursesPaginated tetap Paginator kosong
            \Illuminate\Support\Facades\Log::info('Pengguna saat ini bukan dosen atau tidak terautentikasi saat mengakses penilaian index.');
        }

        return view('penilaian.index', [
            'assignedCourses' => $assignedCoursesPaginated,
            'request' => $request, // Menyertakan request untuk pencarian atau filter lain di view
        ]);
    }

    public function showPenilaianMataKuliah($id_mata_kuliah): View
    {
        // Eager load relasi yang dibutuhkan, misalnya 'mahasiswas'
        $mataKuliah = MataKuliah::with('mahasiswas')->find($id_mata_kuliah);

        if (!$mataKuliah) {
            // Jika mata kuliah tidak ditemukan, redirect dengan pesan error
            return redirect()->route('penilaian.index')->with('error', 'Mata kuliah tidak ditemukan.');
        }

        // PENTING: Implementasikan pengecekan otorisasi di sini.
        // Misalnya, cek apakah dosen yang login berhak menilai mata kuliah ini.
        // Contoh sederhana (perlu disesuaikan dengan logika aplikasi Anda):
        // $dosen = Auth::user();
        // if (!$dosen || !$dosen->mataKuliahYangDiampu()->where('mata_kuliah.id', $mataKuliah->id)->exists()) {
        //     return redirect()->route('penilaian.index')->with('error', 'Anda tidak berhak mengakses penilaian mata kuliah ini.');
        // }

        // Mengambil mahasiswa yang terdaftar di mata kuliah tersebut
        $mahasiswaDiKelas = $mataKuliah->mahasiswas ?? collect(); // Gunakan collect() kosong jika tidak ada mahasiswa

        // Anda mungkin tidak perlu menghitung total SKS di sini jika sudah ada di properti model
        // Namun, jika perlu, Anda bisa melakukannya seperti ini:
        // $totalSksMataKuliah = ($mataKuliah->sks_teori ?? 0) + ($mataKuliah->sks_praktik ?? 0);
        // $mataKuliah->sks_total = $totalSksMataKuliah; // Tambahkan sebagai properti dinamis jika perlu

        return view('penilaian.show_mata_kuliah', compact('mataKuliah', 'mahasiswaDiKelas'));
    }
}
