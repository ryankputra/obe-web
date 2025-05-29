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
        $dosen = Auth::user();
        // Inisialisasi sebagai Paginator kosong
        // Parameter: items, total, perPage
        $assignedCoursesPaginated = new LengthAwarePaginator([], 0, 10);
        $assignedCoursesPaginated->withPath($request->url()); // Agar link paginasi benar

        if ($dosen && $dosen->role === 'dosen') { // Pastikan kondisi role ini sudah benar
            if (method_exists($dosen, 'mataKuliahYangDiampu')) {
                $query = $dosen->mataKuliahYangDiampu();

                if ($request->filled('search_penilaian')) {
                    $search = $request->input('search_penilaian');
                    $query->where(function ($q) use ($search) {
                        $q->where('kode_mk', 'like', "%{$search}%")
                          ->orWhere('nama_mk', 'like', "%{$search}%");
                    });
                }

                // Jika query menghasilkan data, maka $assignedCoursesPaginated akan di-override
                $paginatorInstance = $query->withCount('mahasiswas')
                                          ->orderBy('kode_mk', 'asc')
                                          ->paginate(10) // Menghasilkan LengthAwarePaginator
                                          ->withQueryString(); // Agar filter tetap ada saat paginasi

                // Transformasi item menggunakan through() pada Paginator
                $transformedPaginator = $paginatorInstance->through(function ($course) {
                    $totalSks = ($course->sks_teori ?? 0) + ($course->sks_praktik ?? 0);
                    return (object) [
                        'id' => $course->id,
                        'kode_mk' => $course->kode_mk ?? 'N/A',
                        'nama_mata_kuliah' => $course->nama_mk ?? ($course->nama_mata_kuliah ?? 'N/A'),
                        'jumlah_mahasiswa' => $course->mahasiswas_count ?? 0,
                        'sks' => $totalSks,
                    ];
                });
                $assignedCoursesPaginated = $transformedPaginator; // Override dengan Paginator yang sudah ditransformasi
            } else {
                // Dosen tidak punya method 'mataKuliahYangDiampu', $assignedCoursesPaginated tetap Paginator kosong
                 \Illuminate\Support\Facades\Log::warning('Pengguna dosen ' . $dosen->name . ' tidak memiliki method relasi mataKuliahYangDiampu.');
            }
        } else {
            // Bukan dosen atau tidak login, $assignedCoursesPaginated tetap Paginator kosong
             \Illuminate\Support\Facades\Log::info('Pengguna saat ini bukan dosen atau tidak terautentikasi saat mengakses penilaian index.');
        }

        return view('penilaian.index', [
            'assignedCourses' => $assignedCoursesPaginated,
            'request' => $request,
        ]);
    }

    // ... (method showPenilaianMataKuliah() Anda yang sudah ada) ...
    public function showPenilaianMataKuliah($id_mata_kuliah): View
    {
        $mataKuliah = MataKuliah::with('mahasiswas')->find($id_mata_kuliah);

        if (!$mataKuliah) {
            return redirect()->route('penilaian.index')->with('error', 'Mata kuliah tidak ditemukan.');
        }

        // PENTING: Implementasikan pengecekan otorisasi di sini.
        // $dosen = Auth::user();
        // if (!$this->userCanGradeCourse($dosen, $id_mata_kuliah)) { // Buat method helper jika perlu
        //     return redirect()->route('penilaian.index')->with('error', 'Anda tidak berhak mengakses penilaian mata kuliah ini.');
        // }

        $mahasiswaDiKelas = $mataKuliah->mahasiswas ?? collect();
        $totalSksMataKuliah = ($mataKuliah->sks_teori ?? 0) + ($mataKuliah->sks_praktik ?? 0);
        $mataKuliah->sks = $totalSksMataKuliah;

        return view('penilaian.show_mata_kuliah', compact('mataKuliah', 'mahasiswaDiKelas'));
    }
}