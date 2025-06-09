<?php

namespace App\Http\Controllers;

use App\Models\Cpl;
use App\Models\Cpmk;
use App\Models\Dosen;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Arr; // Import Arr helper

class CpmkController extends Controller
{
    /**
     * Menampilkan daftar CPMK dengan filter.
     * (Tidak ada perubahan di method ini)
     */
    public function index(Request $request)
    {
        $availableCpls = Cpl::select('kode_cpl')->distinct()->orderBy('kode_cpl')->get();
        $availableMatakuliahs = MataKuliah::select('kode_mk')->distinct()->orderBy('kode_mk')->get();
        $availablePics = Cpmk::select('pic')->whereNotNull('pic')->where('pic', '!=', '')->distinct()->orderBy('pic')->pluck('pic');

        $query = Cpmk::query();

        if ($request->filled('kode_cpmk')) {
            $query->where('kode_cpmk', 'like', '%' . $request->kode_cpmk . '%');
        }
        if ($request->filled('kode_cpl')) {
            $query->where('kode_cpl', $request->kode_cpl);
        }
        if ($request->filled('mata_kuliah')) {
            $query->where('mata_kuliah', $request->mata_kuliah);
        }
        if ($request->filled('pic')) {
            $query->where('pic', 'like', '%' . $request->pic . '%');
        }
        if ($request->filled('bobot')) {
            $query->where('bobot', $request->bobot);
        }

        $query->join('cpls', 'cpmks.kode_cpl', '=', 'cpls.kode_cpl')
              ->orderByRaw("CAST(SUBSTRING(cpls.kode_cpl, 4) AS UNSIGNED) ASC")
              ->orderByRaw("CAST(SUBSTRING(cpmks.kode_cpmk, 6) AS UNSIGNED) ASC")
              ->select('cpmks.*');

        $cpmks = $query->paginate(10)->withQueryString();

        // Data ini tidak lagi diperlukan untuk modal edit, tapi bisa dibiarkan jika ada fungsi lain
        $cpls = Cpl::all()->sortBy(fn($item) => (int) filter_var($item->kode_cpl, FILTER_SANITIZE_NUMBER_INT));
        $matakuliahs = MataKuliah::all();

        return view('cpmk.index', compact('cpmks', 'availableCpls', 'availableMatakuliahs', 'availablePics', 'cpls', 'matakuliahs'));
    }

    /**
     * Menampilkan form untuk membuat CPMK baru.
     * (Tidak ada perubahan di method ini)
     */
    public function create()
    {
        $cpls = Cpl::all();
        $matakuliahs = MataKuliah::all();
        return view('cpmk.create', compact('cpls', 'matakuliahs'));
    }

    /**
     * Menyimpan CPMK baru ke database.
     * (Tidak ada perubahan di method ini)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_cpl' => 'required|exists:cpls,kode_cpl',
            'kode_cpmk' => 'required|integer',
            'mata_kuliah' => 'required|exists:mata_kuliah,kode_mk', // Koreksi nama tabel
            'deskripsi' => 'required',
            'bobot' => 'required|numeric|min:0',
            'pic_ids' => 'required|array|min:1',
            'pic_ids.*' => 'required|exists:dosens,id',
        ]);

        $dosenNames = Dosen::whereIn('id', $validated['pic_ids'])->pluck('nama')->toArray();
        $picString = implode(', ', $dosenNames);

        $cplNumber = substr($validated['kode_cpl'], 3);
        $kode_cpmk = 'CPMK' . $cplNumber . str_pad($validated['kode_cpmk'], 3, '0', STR_PAD_LEFT);

        if (Cpmk::where('kode_cpmk', $kode_cpmk)->exists()) {
            return back()->withErrors(['kode_cpmk' => 'Kode CPMK sudah ada.'])->withInput();
        }

        Cpmk::create([
            'kode_cpl' => $validated['kode_cpl'],
            'kode_cpmk' => $kode_cpmk,
            'mata_kuliah' => $validated['mata_kuliah'],
            'deskripsi' => $validated['deskripsi'],
            'bobot' => $validated['bobot'],
            'pic' => $picString,
        ]);

        return redirect()->route('cpmk.index')->with('success', 'CPMK berhasil ditambahkan.');
    }

    /**
     * [DIUBAH] Menampilkan form untuk mengedit CPMK.
     */
    public function edit(Cpmk $cpmk) // Menggunakan Route Model Binding
    {
        $cpls = Cpl::all();
        $matakuliahs = MataKuliah::all();

        // Logika untuk mengambil data PIC yang sudah terpilih dari string nama
        // 1. Ambil string nama dari kolom 'pic'
        $picNamesString = $cpmk->pic;
        // 2. Ubah menjadi array nama
        $picNamesArray = array_map('trim', explode(',', $picNamesString));
        // 3. Cari Dosen berdasarkan array nama tersebut untuk mendapatkan data lengkap (id, nama, nidn)
        $selectedPics = Dosen::whereIn('nama', $picNamesArray)->get();

        return view('cpmk.edit', compact('cpmk', 'cpls', 'matakuliahs', 'selectedPics'));
    }

    /**
     * [DIUBAH] Memperbarui CPMK di database.
     */
    public function update(Request $request, Cpmk $cpmk) // Menggunakan Route Model Binding
    {
        // Validasi disesuaikan dengan form edit yang baru
        $validated = $request->validate([
            'kode_cpl' => 'required|exists:cpls,kode_cpl',
            'mata_kuliah' => 'required|exists:mata_kuliah,kode_mk', // Koreksi nama tabel
            'deskripsi' => 'required',
            'bobot' => 'required|numeric|min:0',
            'pic_ids' => 'required|array|min:1',
            'pic_ids.*' => 'required|exists:dosens,id',
        ]);

        // Menggunakan logika yang sama dengan 'store' untuk mengubah array ID menjadi string nama
        $dosenNames = Dosen::whereIn('id', $validated['pic_ids'])->pluck('nama')->toArray();
        $picString = implode(', ', $dosenNames);

        // Update data CPMK
        $cpmk->update([
            'kode_cpl' => $validated['kode_cpl'],
            'mata_kuliah' => $validated['mata_kuliah'],
            'deskripsi' => $validated['deskripsi'],
            'bobot' => $validated['bobot'],
            'pic' => $picString, // Update dengan string nama yang baru
        ]);

        return redirect()->route('cpmk.index')->with('success', 'CPMK berhasil diperbarui.');
    }

    /**
     * Menghapus CPMK dari database.
     * (Tidak ada perubahan di method ini)
     */
    public function destroy(Cpmk $cpmk)
    {
        $cpmk->delete();
        return redirect()->route('cpmk.index')->with('success', 'CPMK berhasil dihapus.');
    }
}