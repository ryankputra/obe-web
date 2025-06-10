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
    protected $messages = [
        'kode_cpl.required' => 'CPL harus dipilih',
        'kode_cpl.exists' => 'CPL tidak valid',
        'mata_kuliah.required' => 'Mata kuliah harus dipilih',
        'mata_kuliah.exists' => 'Mata kuliah tidak valid',
        'deskripsi.required' => 'Deskripsi harus diisi',
        'bobot.required' => 'Bobot harus diisi',
        'bobot.numeric' => 'Bobot harus berupa angka',
        'bobot.min' => 'Bobot minimal 0',
        'pic_ids.required' => 'PIC harus dipilih',
        'pic_ids.array' => 'Format PIC tidak valid',
        'pic_ids.min' => 'Minimal satu PIC harus dipilih',
        'kode_cpmk.required' => 'Nomor urut CPMK harus diisi',
        'kode_cpmk.integer' => 'Nomor urut CPMK harus berupa angka',
        'kode_cpmk.min' => 'Nomor urut CPMK minimal 1',
        'kode_cpmk.max' => 'Nomor urut CPMK maksimal 999',
    ];

    /**
     * Menampilkan daftar CPMK dengan filter.
     * (Tidak ada perubahan di method ini)
     */
    public function index(Request $request)
    {
        $query = Cpmk::query()
            ->join('cpls', 'cpmks.kode_cpl', '=', 'cpls.kode_cpl')
            ->join('mata_kuliahs', 'cpmks.mata_kuliah', '=', 'mata_kuliahs.kode_mk')
            ->select('cpmks.*', 'mata_kuliahs.nama_mk');

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

        $query->orderByRaw("CAST(SUBSTRING(cpls.kode_cpl, 4) AS UNSIGNED) ASC")
            ->orderByRaw("CAST(SUBSTRING(cpmks.kode_cpmk, 6) AS UNSIGNED) ASC");

        $cpmks = $query->paginate(10)->withQueryString();

        // Add these lines to define the missing variables
        $availableCpls = Cpl::orderBy('kode_cpl')->get();
        $availableMatakuliahs = MataKuliah::orderBy('kode_mk')->get();
        $availablePics = Dosen::pluck('nama')->unique()->sort()->values();

        // Use all variables in compact
        return view('cpmk.index', compact(
            'cpmks',
            'availableCpls',
            'availableMatakuliahs',
            'availablePics'
        ));
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
     * [DIUBAH] Menghilangkan padding pada kode CPMK yang baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_cpl' => 'required|exists:cpls,kode_cpl',
            'kode_cpmk' => 'required|integer|min:1|max:999',
            'mata_kuliah' => 'required|exists:mata_kuliahs,kode_mk', // Koreksi nama tabel
            'deskripsi' => 'required',
            'bobot' => 'required|numeric|min:0',
            'pic_ids' => 'required|array|min:1',
            'pic_ids.*' => 'required|exists:dosens,id',
        ], $this->messages);

        $dosenNames = Dosen::whereIn('id', $validated['pic_ids'])->pluck('nama')->toArray();
        $picString = implode(', ', $dosenNames);

        $cplNumber = substr($validated['kode_cpl'], 3); // Gets "01" from "CPL01"
        $kode_cpmk = 'CPMK' . $cplNumber . $validated['kode_cpmk']; // Removes the padding

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
    public function update(Request $request, Cpmk $cpmk)
    {
        try {
            $validated = $request->validate([
                'kode_cpl' => 'required|exists:cpls,kode_cpl',
                'mata_kuliah' => 'required|exists:mata_kuliahs,kode_mk',
                'deskripsi' => 'required',
                'bobot' => 'required|numeric|min:0',
                'pic_ids' => 'required|array|min:1',
                'pic_ids.*' => 'required|exists:dosens,id',
            ], $this->messages);

            $dosenNames = Dosen::whereIn('id', $validated['pic_ids'])
                ->pluck('nama')
                ->toArray();
            $picString = implode(', ', $dosenNames);

            $cpmk->update([
                'kode_cpl' => $validated['kode_cpl'],
                'mata_kuliah' => $validated['mata_kuliah'],
                'deskripsi' => $validated['deskripsi'],
                'bobot' => $validated['bobot'],
                'pic' => $picString,
            ]);

            return redirect()->route('cpmk.index')
                ->with('success', 'CPMK berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui CPMK.'])
                ->withInput();
        }
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
