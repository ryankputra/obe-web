<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Penilaian; // Pastikan model Penilaian ada dan di-import
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class PenilaianController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $assignedCoursesPaginated = new LengthAwarePaginator([], 0, 10);
        $assignedCoursesPaginated->withPath($request->url());

        if ($user && $user->role === 'dosen') {
            if (method_exists($user, 'mataKuliahYangDiampu')) {
                $query = $user->mataKuliahYangDiampu();

                if ($request->filled('search_penilaian')) {
                    $search = $request->input('search_penilaian');
                    $query->where(function ($q) use ($search) {
                        $q->where('kode_mk', 'like', "%{$search}%")
                            ->orWhere('nama_mk', 'like', "%{$search}%");
                    });
                }

                $paginatorInstance = $query->withCount('mahasiswas')
                    ->orderBy('kode_mk', 'asc')
                    ->paginate(10)
                    ->withQueryString();

                $transformedPaginator = $paginatorInstance->through(function ($course) {
                    return (object) [
                        'id' => $course->id,
                        'kode_mk' => $course->kode_mk ?? 'N/A',
                        'nama_mk' => $course->nama_mk ?? ($course->nama_mata_kuliah ?? 'N/A'),
                        'mahasiswas_count' => $course->mahasiswas_count ?? 0,
                        'sks_teori' => $course->sks_teori ?? 0,
                        'sks_praktik' => $course->sks_praktik ?? 0,
                    ];
                });
                $assignedCoursesPaginated = $transformedPaginator;
            } else {
                \Illuminate\Support\Facades\Log::warning('Pengguna dosen ' . $user->name . ' tidak memiliki method relasi mataKuliahYangDiampu.');
            }
        } else {
            \Illuminate\Support\Facades\Log::info('Pengguna saat ini bukan dosen atau tidak terautentikasi saat mengakses penilaian index.');
        }

        return view('penilaian.index', [
            'assignedCourses' => $assignedCoursesPaginated,
            'request' => $request,
        ]);
    }

    public function showDetailMataKuliah($id_mata_kuliah): View
    {
        $mataKuliah = MataKuliah::with('cpmks')->find($id_mata_kuliah); // Memuat relasi cpmks

        if (!$mataKuliah) {
            return redirect()->route('penilaian.index')->with('error', 'Mata kuliah tidak ditemukan.');
        }
        // Variabel $bobotPenilaian tidak lagi relevan jika kita menampilkan daftar CPMK

        return view('penilaian.detail_mk', compact('mataKuliah'));
    }

    public function showPenilaianMataKuliah($id_mata_kuliah): View
    {
        $mataKuliah = MataKuliah::with(['mahasiswas.penilaian' => function($q) use ($id_mata_kuliah) {
            $q->where('mata_kuliah_id', $id_mata_kuliah);
        }])->find($id_mata_kuliah);

        if (!$mataKuliah) {
            // Atau redirect dengan pesan error, sesuai kebutuhan aplikasi Anda
            abort(404, 'Mata Kuliah tidak ditemukan.');
        }

        $mahasiswaDiKelas = $mataKuliah->mahasiswas ?? collect();

        return view('penilaian.show_mata_kuliah', compact('mataKuliah', 'mahasiswaDiKelas'));
    }

    public function storeNilai(Request $request, $id_mata_kuliah)
    {
        $validatedData = $request->validate([
            'nilai.*.tugas' => 'nullable|numeric|min:0|max:100',
            'nilai.*.keaktifan' => 'nullable|numeric|min:0|max:100',
            'nilai.*.proyek' => 'nullable|numeric|min:0|max:100', // Validasi untuk kolom proyek
            'nilai.*.uts' => 'nullable|numeric|min:0|max:100',
            'nilai.*.uas' => 'nullable|numeric|min:0|max:100',
        ]);

        foreach ($request->nilai as $mahasiswa_nim => $nilai) {
            Penilaian::updateOrCreate(
                [
                    'mahasiswa_nim' => $mahasiswa_nim, // Gunakan mahasiswa_nim sesuai struktur tabel
                    'mata_kuliah_kode_mk' => $id_mata_kuliah, // Gunakan mata_kuliah_kode_mk sesuai struktur tabel
                ],
                [
                    'tugas' => $nilai['tugas'] ?? null,
                    'keaktifan' => $nilai['keaktifan'] ?? null,
                    'proyek' => $nilai['proyek'] ?? null, // Jika ada kolom proyek
                    'uts' => $nilai['uts'] ?? null,
                    'uas' => $nilai['uas'] ?? null,
                ]
            );
        }

        return redirect()->back()->with('success', 'Nilai berhasil disimpan!');
    }


    public function inputNilai($id_mata_kuliah)
    {
        $mataKuliah = MataKuliah::with('mahasiswas')->find($id_mata_kuliah);

        if (!$mataKuliah) {
            return redirect()->route('penilaian.index')->with('error', 'Mata kuliah tidak ditemukan.');
        }

        $mahasiswaDiKelas = $mataKuliah->mahasiswas ?? collect();

        return view('penilaian.show_mata_kuliah', compact('mataKuliah', 'mahasiswaDiKelas'));
    }
}
