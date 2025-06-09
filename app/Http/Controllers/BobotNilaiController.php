<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CpmkJenisPenilaianBobot; // Import model CpmkJenisPenilaianBobot
use App\Models\Cpmk; // Pastikan model Cpmk di-import
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
        $mataKuliahModel = new MataKuliah();
        $query = MataKuliah::withCount('mahasiswas')
                           ->whereNotNull($mataKuliahModel->getTable() . '.' . $mataKuliahModel->getKeyName()); // Memastikan primary key tidak null


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

    /**
     * Menampilkan halaman detail untuk mengatur bobot CPMK suatu mata kuliah.
     *
     * @param  \App\Models\MataKuliah  $mataKuliah
     * @return \Illuminate\View\View
     */
    public function show(MataKuliah $mataKuliah)
    {
        // Load relasi CPMK untuk mata kuliah ini.
        // Asumsikan relasi bernama 'cpmks' pada model MataKuliah.
        // Jika bobot CPMK disimpan dalam pivot table (misal cpmk_mata_kuliah dengan kolom 'bobot'),
        // pastikan relasi (belongsToMany) sudah dikonfigurasi untuk mengambil pivot data.
        // Contoh: $mataKuliah->load('cpmks.pivot') jika perlu
        $mataKuliah->load('cpmks'); 

        return view('bobot_nilai.show', compact('mataKuliah'));
    }

    // Anda mungkin perlu menambahkan metode storeBobotCpmkMk jika belum ada
    // public function storeBobotCpmkMk(Request $request, MataKuliah $mataKuliah)
    // {
    //     // Logika untuk menyimpan bobot CPMK terhadap Mata Kuliah
    //     // ...
    //     // Contoh validasi dan penyimpanan:
    //     $validatedData = $request->validate([
    //         'bobot_cpmk_mk' => 'required|array',
    //         'bobot_cpmk_mk.*' => 'nullable|numeric|min:0|max:100',
    //     ]);

    //     $totalBobot = 0;
    //     if (isset($validatedData['bobot_cpmk_mk'])) {
    //         foreach ($validatedData['bobot_cpmk_mk'] as $bobot) {
    //             if (!is_null($bobot)) {
    //                 $totalBobot += (float)$bobot;
    //             }
    //         }
    //     }
    //     // Lanjutkan dengan validasi total bobot dan penyimpanan
    //     // ...
    //     return redirect()->route('bobot_nilai.show', $mataKuliah->getKey())
    //                      ->with('success_cpmk', 'Bobot CPMK terhadap MK berhasil diperbarui.');
    // }

    /**
     * Menampilkan halaman untuk mengatur jenis penilaian dan bobotnya untuk sebuah CPMK.
     *
     * @param  \App\Models\MataKuliah  $mataKuliah
     * @param  \App\Models\Cpmk  $cpmk
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function aturJenisPenilaian(MataKuliah $mataKuliah, Cpmk $cpmk)
    {
        if ($cpmk->mata_kuliah !== $mataKuliah->getKey()) {
            return redirect()->route('bobot_nilai.show', $mataKuliah->getKey())
                             ->with('error_cpmk', 'CPMK tidak valid untuk mata kuliah ini.');
        }

        $jenisPenilaianList = ['Keaktifan', 'Tugas', 'Kuis', 'Proyek', 'UTS', 'UAS'];
        
        // Mengambil bobot yang sudah ada dari database
        $bobotData = $cpmk->jenisPenilaianBobot()->pluck('bobot', 'jenis_penilaian')->all();
        $existingBobot = [];
        foreach ($jenisPenilaianList as $jenis) {
           $key = strtolower(str_replace(' ', '_', $jenis)); // e.g., 'keaktifan'
           $existingBobot[$key] = $bobotData[$key] ?? null;
        }

        return view('bobot_nilai.cpmk_jenis_penilaian', compact('mataKuliah', 'cpmk', 'jenisPenilaianList', 'existingBobot'));
    }

    /**
     * Menyimpan jenis penilaian dan bobotnya untuk sebuah CPMK.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MataKuliah  $mataKuliah
     * @param  \App\Models\Cpmk  $cpmk
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeJenisPenilaian(Request $request, MataKuliah $mataKuliah, Cpmk $cpmk)
    {
        if ($cpmk->mata_kuliah !== $mataKuliah->getKey()) {
            return redirect()->route('bobot_nilai.show', $mataKuliah->getKey())
                             ->with('error_cpmk', 'Operasi tidak valid.');
        }

        $validatedData = $request->validate([
            'bobot_jenis' => 'required|array',
            'bobot_jenis.*' => 'nullable|numeric|min:0|max:100',
        ]);

        // Hitung total bobot yang diinput
        $totalBobotJenis = 0;
        foreach ($validatedData['bobot_jenis'] as $bobot) {
            if (is_numeric($bobot)) {
                $totalBobotJenis += (float)$bobot;
            }
        }

        // Validasi total bobot harus 100 jika ada inputan, toleransi kecil untuk floating point
        if ($totalBobotJenis > 0 && abs($totalBobotJenis - 100) > 0.01) {
            return redirect()->back()->withInput()->with('error_jenis', 'Total bobot untuk semua jenis penilaian harus 100%. Saat ini totalnya: ' . $totalBobotJenis . '%.');
        }

        // Menyimpan atau memperbarui bobot jenis penilaian
        DB::transaction(function () use ($cpmk, $validatedData) {
            foreach ($validatedData['bobot_jenis'] as $jenisKey => $bobotValue) {
                // $jenisKey akan berupa 'keaktifan', 'tugas', dll.
                $cpmk->jenisPenilaianBobot()->updateOrCreate(
                    ['jenis_penilaian' => $jenisKey], // Kriteria pencarian
                    ['bobot' => $bobotValue]          // Data untuk diupdate atau create
                );
            }
        });

        return redirect()->route('bobot_nilai.show', $mataKuliah->getKey())
                         ->with('success_cpmk', 'Bobot jenis penilaian untuk CPMK ' . $cpmk->kode_cpmk . ' berhasil diperbarui.');
    }
}
