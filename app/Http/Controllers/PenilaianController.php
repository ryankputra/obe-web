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
        $mataKuliah = MataKuliah::with(['mahasiswas.penilaian' => function ($q) use ($id_mata_kuliah) {
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


    /**
     * Menampilkan halaman input nilai untuk mata kuliah tertentu.
     * Method ini mengirimkan data bobot penilaian ke view.
     */
    public function inputNilai($id_mata_kuliah, $cpmk_id): View
    {
        $mataKuliah = MataKuliah::with(['mahasiswas' => function ($q) use ($cpmk_id, $id_mata_kuliah) {
            $q->with(['penilaian' => function ($q2) use ($cpmk_id, $id_mata_kuliah) {
                $q2->where('cpmk_id', $cpmk_id)
                    ->where('mata_kuliah_kode_mk', $id_mata_kuliah);
            }]);
        }, 'cpmks'])->find($id_mata_kuliah);

        if (!$mataKuliah) {
            return redirect()->route('penilaian.index')->with('error', 'Mata kuliah tidak ditemukan.');
        }

        $cpmk = $mataKuliah->cpmks->where('id', $cpmk_id)->first();
        if (!$cpmk) {
            return redirect()->route('penilaian.detail', $id_mata_kuliah)->with('error', 'CPMK tidak ditemukan.');
        }

        $bobotPenilaian = [
            'keaktifan' => 0,
            'tugas'     => 0,
            'proyek'    => 0,
            'kuis'      => 0,
            'uts'       => 0,
            'uas'       => 0,
        ];

        $jenisBobots = \App\Models\BobotPenilaian::where('cpmk_id', $cpmk->id)->get();
        foreach ($jenisBobots as $jb) {
            $key = strtolower(str_replace(' ', '_', $jb->jenis_penilaian));
            if (array_key_exists($key, $bobotPenilaian)) {
                $bobotPenilaian[$key] = $jb->bobot;
            }
        }

        // Filter hanya yang bobotnya > 0
        $bobotPenilaian = array_filter($bobotPenilaian, fn($b) => $b > 0);

        $mahasiswaDiKelas = $mataKuliah->mahasiswas ?? collect();

        return view('penilaian.show_mata_kuliah', compact('mataKuliah', 'mahasiswaDiKelas', 'bobotPenilaian', 'cpmk'));
    }

    public function storeNilai(Request $request, $id_mata_kuliah, $cpmk_id)
    {
        $mataKuliah = MataKuliah::findOrFail($id_mata_kuliah);

        $cpmk = $mataKuliah->cpmks->where('id', $cpmk_id)->first();
        if (!$cpmk) {
            return redirect()->route('penilaian.detail', $id_mata_kuliah)->with('error', 'CPMK tidak ditemukan.');
        }

        $bobotPenilaian = [
            'keaktifan' => 0,
            'tugas'     => 0,
            'proyek'    => 0,
            'kuis'      => 0,
            'uts'       => 0,
            'uas'       => 0,
        ];

        $jenisBobots = \App\Models\BobotPenilaian::where('cpmk_id', $cpmk->id)->get();
        foreach ($jenisBobots as $jb) {
            $key = strtolower(str_replace(' ', '_', $jb->jenis_penilaian));
            if (array_key_exists($key, $bobotPenilaian)) {
                $bobotPenilaian[$key] = $jb->bobot;
            }
        }

        // Hanya validasi field yang ada bobotnya
        $rules = [];
        foreach ($bobotPenilaian as $key => $bobot) {
            if ($bobot > 0) {
                $rules["nilai.*.$key"] = 'nullable|numeric|min:0|max:100';
            }
        }
        $request->validate($rules);

        // Hitung total bobot yang digunakan
        $totalBobot = array_sum(array_filter($bobotPenilaian, fn($b) => $b > 0));

        foreach ($request->nilai as $mahasiswa_nim => $nilai) {
            $nilai_akhir = 0;
            foreach ($bobotPenilaian as $key => $bobot) {
                if ($bobot > 0) {
                    $nilaiItem = (float)($nilai[$key] ?? 0);
                    $nilai_akhir += $nilaiItem * $bobot;
                }
            }
            $nilai_akhir = $totalBobot > 0 ? round($nilai_akhir / $totalBobot, 2) : 0;

            Penilaian::updateOrCreate(
                [
                    'mahasiswa_nim'       => $mahasiswa_nim,
                    'mata_kuliah_kode_mk' => $id_mata_kuliah,
                    'cpmk_id'             => $cpmk->id, // <-- WAJIB ADA
                ],
                array_merge(
                    collect($bobotPenilaian)->filter(fn($b) => $b > 0)->mapWithKeys(function ($b, $k) use ($nilai) {
                        return [$k => ($nilai[$k] === null ? null : (float)($nilai[$k] ?? 0))];
                    })->toArray(),
                    ['nilai_akhir' => $nilai_akhir]
                )
            );
        }

        return redirect()->back()->with('success', 'Nilai berhasil disimpan!');
    }

    /**
     * Menyimpan nilai secara massal untuk mata kuliah tertentu.
     */
    public function storeMass(Request $request, $id_mata_kuliah)
    {
        $mataKuliah = MataKuliah::findOrFail($id_mata_kuliah);

        $bobotPenilaian = [
            'keaktifan' => 0,
            'tugas'     => 0,
            'proyek'    => 0,
            'kuis'      => 0,
            'uts'       => 0,
            'uas'       => 0,
        ];

        // Ambil semua CPMK yang terkait dengan mata kuliah ini
        $cpmks = $mataKuliah->cpmks;

        // Untuk setiap CPMK, simpan nilai mahasiswa
        foreach ($cpmks as $cpmk) {
            $jenisBobots = \App\Models\BobotPenilaian::where('cpmk_id', $cpmk->id)->get();
            foreach ($jenisBobots as $jb) {
                $key = strtolower(str_replace(' ', '_', $jb->jenis_penilaian));
                if (array_key_exists($key, $bobotPenilaian)) {
                    $bobotPenilaian[$key] = $jb->bobot;
                }
            }

            // Hanya validasi field yang ada bobotnya
            $rules = [];
            foreach ($bobotPenilaian as $key => $bobot) {
                if ($bobot > 0) {
                    $rules["nilai.*.$key"] = 'nullable|numeric|min:0|max:100';
                }
            }
            $request->validate($rules);

            // Hitung total bobot yang digunakan
            $totalBobot = array_sum(array_filter($bobotPenilaian, fn($b) => $b > 0));

            foreach ($request->nilai as $mahasiswa_nim => $nilai) {
                $nilai_akhir = 0;
                foreach ($bobotPenilaian as $key => $bobot) {
                    if ($bobot > 0) {
                        $nilaiItem = (float)($nilai[$key] ?? 0);
                        $nilai_akhir += $nilaiItem * $bobot;
                    }
                }
                $nilai_akhir = $totalBobot > 0 ? round($nilai_akhir / $totalBobot, 2) : 0;

                Penilaian::updateOrCreate(
                    [
                        'mahasiswa_nim'       => $mahasiswa_nim,
                        'mata_kuliah_kode_mk' => $id_mata_kuliah,
                        'cpmk_id'             => $cpmk->id, // <-- WAJIB ADA
                    ],
                    array_merge(
                        collect($bobotPenilaian)->filter(fn($b) => $b > 0)->mapWithKeys(function ($b, $k) use ($nilai) {
                            return [$k => ($nilai[$k] === null ? null : (float)($nilai[$k] ?? 0))];
                        })->toArray(),
                        ['nilai_akhir' => $nilai_akhir]
                    )
                );
            }
        }

        return redirect()->back()->with('success', 'Nilai berhasil disimpan secara massal!');
    }
}
