<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    /**
     * Menampilkan daftar mata kuliah dengan filter dan paginasi.
     */
    public function index(Request $request)
    {
        $query = MataKuliah::with('dosens')->withCount('mahasiswas');

        // Terapkan filter pencarian
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('kode_mk', 'like', "%$search%")
                    ->orWhere('nama_mk', 'like', "%$search%");
            });
        }
        if ($request->filled('dosen_id')) {
            $query->whereHas('dosens', function ($q) use ($request) {
                $q->where('dosens.id', $request->input('dosen_id'));
            });
        }
        if ($request->filled('semester')) {
            $query->where('semester', $request->input('semester'));
        }
        if ($request->filled('status')) {
            $query->where('status_mata_kuliah', $request->input('status'));
        }

        // Hitung total SKS dari hasil query
        $totalSKS = (clone $query)->get()->sum(function ($mk) {
            return (int)$mk->sks_teori + (int)$mk->sks_praktik;
        });

        $mataKuliahs = $query->orderBy('kode_mk')->paginate(10);

        return view('mata_kuliah.index', [
            'mataKuliahs' => $mataKuliahs,
            'totalSKS' => $totalSKS,
            'dosens' => \App\Models\Dosen::orderBy('nama')->get(),
        ]);
    }

    /**
     * Menampilkan form untuk membuat mata kuliah baru.
     */
    public function create()
    {
        $oldMahasiswaNims = old('mahasiswa_nims', []);
        $repopulatedSelectedMahasiswas = [];
        if (!empty($oldMahasiswaNims)) {
            $mahasiswaModels = Mahasiswa::whereIn('nim', $oldMahasiswaNims)->get(['nim', 'nama']);
            $mahasiswaDataMap = $mahasiswaModels->keyBy('nim');
            foreach ($oldMahasiswaNims as $nim) {
                if (isset($mahasiswaDataMap[$nim])) {
                    $repopulatedSelectedMahasiswas[] = (object) ['nim' => $nim, 'nama' => $mahasiswaDataMap[$nim]->nama];
                }
            }
        }

        return view('mata_kuliah.create', [
            'dosens' => Dosen::orderBy('nama')->get(),
            'repopulatedSelectedMahasiswas' => $repopulatedSelectedMahasiswas
        ]);
    }

    /**
     * Menyimpan mata kuliah baru ke database.
     * Ini adalah method yang sudah diperbaiki.
     */
    public function store(Request $request)
    {
        // Validasi input, termasuk dosen dan mahasiswa
        $validatedData = $request->validate([
            'kode_mk' => 'required|string|max:10|unique:mata_kuliahs,kode_mk',
            'nama_mk' => 'required|string|max:255',
            'semester' => 'required|integer|min:1|max:8',
            'sks_teori' => 'required|integer|min:0',
            'sks_praktik' => 'required|integer|min:0',
            'status_mata_kuliah' => 'required|string',
            'dosen_ids' => 'nullable|array',
            'dosen_ids.*' => 'exists:dosens,id',
            'mahasiswa_nims' => 'nullable|array', // Validasi untuk mahasiswa
            'mahasiswa_nims.*' => 'exists:mahasiswas,nim', // Pastikan NIM mahasiswa ada
        ]);

        // Buat record mata kuliah
        $mataKuliah = MataKuliah::create($validatedData);

        // Simpan relasi dosen
        if ($request->filled('dosen_ids')) {
            $mataKuliah->dosens()->sync($request->dosen_ids);
        }

        // Simpan relasi mahasiswa
        if ($request->filled('mahasiswa_nims')) {
            $mataKuliah->mahasiswas()->sync($request->mahasiswa_nims);
        }

        return redirect()->route('mata_kuliah.index')->with('success', 'Mata Kuliah berhasil ditambahkan!');
    }


    /**
     * Menampilkan form untuk mengedit mata kuliah.
     */
    public function edit(MataKuliah $mataKuliah)
    {
        $mataKuliah->load('dosens', 'mahasiswas');

        $oldMahasiswaNims = old('mahasiswa_nims', $mataKuliah->mahasiswas->pluck('nim')->toArray());
        $repopulatedSelectedMahasiswas = [];

        if (!empty($oldMahasiswaNims)) {
            $mahasiswaModels = Mahasiswa::whereIn('nim', $oldMahasiswaNims)->get(['nim', 'nama']);
            $mahasiswaDataMap = $mahasiswaModels->keyBy('nim');
            foreach ($oldMahasiswaNims as $nim) {
                if (isset($mahasiswaDataMap[$nim])) {
                    $repopulatedSelectedMahasiswas[] = (object) ['nim' => $nim, 'nama' => $mahasiswaDataMap[$nim]->nama];
                }
            }
        }
        return view('mata_kuliah.edit', [
            'mataKuliah' => $mataKuliah,
            'dosens' => Dosen::orderBy('nama')->get(),
            'repopulatedSelectedMahasiswas' => $repopulatedSelectedMahasiswas
        ]);
    }

    /**
     * Memperbarui mata kuliah di database.
     */
    public function update(Request $request, MataKuliah $mataKuliah)
    {
        $validated = $request->validate([
            'kode_mk' => 'required|unique:mata_kuliahs,kode_mk,' . $mataKuliah->kode_mk . ',kode_mk',
            'nama_mk' => 'required',
            'semester' => 'required|integer|between:1,8',
            'sks_teori' => 'required|integer|min:0',
            'sks_praktik' => 'required|integer|min:0',
            'status_mata_kuliah' => 'required|in:Wajib Prodi,Pilihan,Wajib Fakultas,Wajib Universitas',
            'dosen_ids' => 'nullable|array',
            'dosen_ids.*' => 'exists:dosens,id',
            'mahasiswa_nims' => 'nullable|array',
            'mahasiswa_nims.*' => 'exists:mahasiswas,nim'
        ]);

        $mataKuliah->update($validated);

        // Sinkronisasi relasi dosen dan mahasiswa
        $mataKuliah->dosens()->sync($request->dosen_ids ?? []);
        $mataKuliah->mahasiswas()->sync($request->mahasiswa_nims ?? []);

        return redirect()->route('mata_kuliah.index')
            ->with('success', 'Mata kuliah berhasil diperbarui');
    }

    /**
     * Menghapus mata kuliah dari database.
     */
    public function destroy(MataKuliah $mataKuliah)
    {
        // Lepaskan semua relasi sebelum menghapus
        $mataKuliah->dosens()->detach();
        $mataKuliah->mahasiswas()->detach();
        $mataKuliah->delete();

        return redirect()->route('mata_kuliah.index')
            ->with('success', 'Mata kuliah berhasil dihapus');
    }
}