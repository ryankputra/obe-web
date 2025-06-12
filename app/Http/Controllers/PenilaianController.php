<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Penilaian;
use App\Models\BobotPenilaian; // Pastikan ini mengarah ke model BobotPenilaian yang baru saja kita perbaiki
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log; // Tambahkan ini untuk logging

class PenilaianController extends Controller
{
    /**
     * Menampilkan daftar mata kuliah yang diampu oleh dosen.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $assignedCoursesPaginated = new LengthAwarePaginator([], 0, 10);
        $assignedCoursesPaginated->withPath($request->url());

        if ($user && $user->role === 'dosen') {
            // Pastikan relasi mataKuliahYangDiampu ada di model User atau Dosen
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
                Log::warning('Pengguna dosen ' . $user->name . ' tidak memiliki method relasi mataKuliahYangDiampu.');
            }
        } else {
            Log::info('Pengguna saat ini bukan dosen atau tidak terautentikasi saat mengakses penilaian index.');
        }

        return view('penilaian.index', [
            'assignedCourses' => $assignedCoursesPaginated,
            'request' => $request,
        ]);
    }

    /**
     * Menampilkan detail mata kuliah (mungkin daftar CPMK).
     */
    public function showDetailMataKuliah($id_mata_kuliah): View
    {
        $mataKuliah = MataKuliah::with('cpmks')->find($id_mata_kuliah);

        if (!$mataKuliah) {
            return redirect()->route('penilaian.index')->with('error', 'Mata kuliah tidak ditemukan.');
        }

        return view('penilaian.detail_mk', compact('mataKuliah'));
    }

    /**
     * [DEPRECATED / TIDAK DIGUNAKAN UNTUK INPUT NILAI]
     * Method ini tidak mengirimkan $bobotPenilaian ke view.
     * Gunakan method inputNilai() untuk halaman input nilai.
     */
    public function showPenilaianMataKuliah($id_mata_kuliah): View
    {
        $mataKuliah = MataKuliah::with(['mahasiswas.penilaian' => function($q) use ($id_mata_kuliah) {
            $q->where('mata_kuliah_id', $id_mata_kuliah);
        }])->find($id_mata_kuliah);

        if (!$mataKuliah) {
            abort(404, 'Mata Kuliah tidak ditemukan.');
        }

        $mahasiswaDiKelas = $mataKuliah->mahasiswas ?? collect();

        // Method ini TIDAK mengirimkan $bobotPenilaian.
        // Jika Anda ingin ini menjadi halaman input nilai, tambahkan logika pengambilan bobot di sini juga.
        return view('penilaian.show_mata_kuliah', compact('mataKuliah', 'mahasiswaDiKelas'));
    }

    /**
     * Menyimpan nilai-nilai yang dimasukkan oleh dosen.
     */
    public function storeNilai(Request $request, $id_mata_kuliah)
    {
        $request->validate([
            'nilai.*.keaktifan' => 'nullable|numeric|min:0|max:100',
            'nilai.*.tugas'     => 'nullable|numeric|min:0|max:100',
            'nilai.*.proyek'    => 'nullable|numeric|min:0|max:100',
            'nilai.*.kuis'      => 'nullable|numeric|min:0|max:100',
            'nilai.*.uts'       => 'nullable|numeric|min:0|max:100',
            'nilai.*.uas'       => 'nullable|numeric|min:0|max:100',
        ]);

        $mataKuliah = MataKuliah::findOrFail($id_mata_kuliah);
        
        // Inisialisasi bobotPenilaian dengan nilai default 0
        $bobotPenilaian = [
            'keaktifan' => 0,
            'tugas'     => 0,
            'proyek'    => 0,
            'kuis'      => 0,
            'uts'       => 0,
            'uas'       => 0,
        ];

        // Mengambil CPMK pertama dari mata kuliah ini untuk mendapatkan bobot penilaian
        $cpmk = $mataKuliah->cpmks->first(); // Pastikan relasi cpmks() ada di model MataKuliah

        if ($cpmk) {
            // Mengambil SEMUA bobot jenis penilaian untuk CPMK ini
            $jenisBobots = BobotPenilaian::where('cpmk_id', $cpmk->id)->get(); 
            
            foreach ($jenisBobots as $jb) {
                // Pastikan nama jenis penilaian di DB (jenis_penilaian) sesuai dengan key di array $bobotPenilaian
                $key = strtolower(str_replace(' ', '_', $jb->jenis_penilaian));
                if (array_key_exists($key, $bobotPenilaian)) {
                    $bobotPenilaian[$key] = $jb->bobot;
                }
            }
        } else {
            Log::warning("Tidak ada CPMK ditemukan untuk mata kuliah ID: {$id_mata_kuliah}. Bobot penilaian akan dihitung dengan 0.");
        }

        foreach ($request->nilai as $mahasiswa_nim => $nilai) {
            $nilai_akhir = 0;
            
            // Pastikan nilai diubah ke float dan default ke 0 jika null/kosong
            $nilai_keaktifan = (float)($nilai['keaktifan'] ?? 0);
            $nilai_tugas     = (float)($nilai['tugas'] ?? 0);
            $nilai_proyek    = (float)($nilai['proyek'] ?? 0);
            $nilai_kuis      = (float)($nilai['kuis'] ?? 0);
            $nilai_uts       = (float)($nilai['uts'] ?? 0);
            $nilai_uas       = (float)($nilai['uas'] ?? 0);

            // Perhitungan nilai akhir menggunakan bobot yang telah diambil
            $nilai_akhir += $nilai_keaktifan * ($bobotPenilaian['keaktifan'] / 100);
            $nilai_akhir += $nilai_tugas     * ($bobotPenilaian['tugas'] / 100);
            $nilai_akhir += $nilai_proyek    * ($bobotPenilaian['proyek'] / 100);
            $nilai_akhir += $nilai_kuis      * ($bobotPenilaian['kuis'] / 100);
            $nilai_akhir += $nilai_uts       * ($bobotPenilaian['uts'] / 100);
            $nilai_akhir += $nilai_uas       * ($bobotPenilaian['uas'] / 100);

            Penilaian::updateOrCreate(
                [
                    'mahasiswa_nim'       => $mahasiswa_nim,
                    'mata_kuliah_kode_mk' => $id_mata_kuliah, // Pastikan ini konsisten dengan kolom di DB Anda
                ],
                [
                    'keaktifan'   => ($nilai['keaktifan'] === null) ? null : $nilai_keaktifan, // Simpan null jika input kosong
                    'tugas'       => ($nilai['tugas'] === null) ? null : $nilai_tugas,
                    'proyek'      => ($nilai['proyek'] === null) ? null : $nilai_proyek,
                    'kuis'        => ($nilai['kuis'] === null) ? null : $nilai_kuis,
                    'uts'         => ($nilai['uts'] === null) ? null : $nilai_uts,
                    'uas'         => ($nilai['uas'] === null) ? null : $nilai_uas,
                    'nilai_akhir' => round($nilai_akhir, 2), // Bulatkan nilai akhir ke 2 desimal
                ]
            );
        }

        return redirect()->back()->with('success', 'Nilai berhasil disimpan!');
    }

    /**
     * Menampilkan halaman input nilai untuk mata kuliah tertentu.
     * Method ini mengirimkan data bobot penilaian ke view.
     */
    public function inputNilai($id_mata_kuliah): View
    {
        // Pastikan relasi mahasiswas dan cpmks ada di model MataKuliah
        $mataKuliah = MataKuliah::with('mahasiswas.penilaian', 'cpmks')->find($id_mata_kuliah);

        if (!$mataKuliah) {
            return redirect()->route('penilaian.index')->with('error', 'Mata kuliah tidak ditemukan.');
        }

        // Inisialisasi bobotPenilaian dengan nilai default 0
        $bobotPenilaian = [
            'keaktifan' => 0,
            'tugas'     => 0,
            'proyek'    => 0,
            'kuis'      => 0,
            'uts'       => 0,
            'uas'       => 0,
        ];

        // Mengambil CPMK pertama dari mata kuliah ini untuk mendapatkan bobot penilaian
        $cpmk = $mataKuliah->cpmks->first(); // Pastikan relasi cpmks() ada di model MataKuliah

        if ($cpmk) {
            // Mengambil SEMUA bobot jenis penilaian untuk CPMK ini
            $jenisBobots = BobotPenilaian::where('cpmk_id', $cpmk->id)->get(); 
            
            foreach ($jenisBobots as $jb) {
                // Pastikan nama jenis penilaian di DB (jenis_penilaian) sesuai dengan key di array $bobotPenilaian
                $key = strtolower(str_replace(' ', '_', $jb->jenis_penilaian));
                if (array_key_exists($key, $bobotPenilaian)) {
                    $bobotPenilaian[$key] = $jb->bobot;
                }
            }
        } else {
            Log::warning("Tidak ada CPMK ditemukan untuk mata kuliah ID: {$id_mata_kuliah}. Bobot penilaian akan ditampilkan sebagai 0.");
        }

        $mahasiswaDiKelas = $mataKuliah->mahasiswas ?? collect();

        // Pastikan Anda meneruskan 'bobotPenilaian' ke view
        return view('penilaian.show_mata_kuliah', compact('mataKuliah', 'mahasiswaDiKelas', 'bobotPenilaian'));
    }
}