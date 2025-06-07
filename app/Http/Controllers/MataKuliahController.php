<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index(Request $request)
    {
        $query = MataKuliah::with('dosens')->withCount('mahasiswas'); // Tambahkan withCount('mahasiswas')

        // Apply filters
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

        // Clone the query for total SKS calculation
        $totalSKS = (clone $query)->get()->sum(function ($mk) {
            return (int)$mk->sks_teori + (int)$mk->sks_praktik;
        });

        $mataKuliahs = $query->orderBy('kode_mk')->paginate(10); // Tambahkan orderBy untuk konsistensi

        return view('mata_kuliah.index', [
            'mataKuliahs' => $mataKuliahs,
            'totalSKS' => $totalSKS,
            'dosens' => \App\Models\Dosen::all(),
        ]);
    }

    public function create()
    {
        $oldMahasiswaNims = old('mahasiswa_nims', []);
        $repopulatedSelectedMahasiswas = [];
        if (!empty($oldMahasiswaNims)) {
            $mahasiswaModels = Mahasiswa::whereIn('nim', $oldMahasiswaNims)->get(['nim', 'nama']);
            // Untuk menjaga urutan dari old() dan memastikan kita memiliki nama
            $mahasiswaDataMap = $mahasiswaModels->keyBy('nim');
            foreach ($oldMahasiswaNims as $nim) {
                if (isset($mahasiswaDataMap[$nim])) {
                    $repopulatedSelectedMahasiswas[] = (object) ['nim' => $nim, 'nama' => $mahasiswaDataMap[$nim]->nama];
                }
            }
        }

        return view('mata_kuliah.create', [
            'dosens' => Dosen::orderBy('nama')->get(),
            'repopulatedSelectedMahasiswas' => $repopulatedSelectedMahasiswas // Kirim mahasiswa yang dipilih sebelumnya
        ]);
    }

    public function edit(MataKuliah $mataKuliah)
    {
        $mataKuliah->load('dosens', 'mahasiswas'); // Pastikan mahasiswa juga di-load

        $oldMahasiswaNims = old('mahasiswa_nims', $mataKuliah->mahasiswas->pluck('nim')->toArray());
        $repopulatedSelectedMahasiswas = [];

        if (!empty($oldMahasiswaNims)) {
            // Ambil model Mahasiswa berdasarkan NIM yang ada di oldMahasiswaNims
            // Ini penting untuk mendapatkan nama mahasiswa jika data berasal dari old() setelah validasi gagal
            // dan belum tentu semua NIM dari old() ada di relasi $mataKuliah->mahasiswas
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mk' => 'required|unique:mata_kuliahs,kode_mk',
            'nama_mk' => 'required',
            'semester' => 'required|integer|between:1,8',
            'sks_teori' => 'required|integer|min:0',
            'sks_praktik' => 'required|integer|min:0',
            'status_mata_kuliah' => 'required|in:Wajib Prodi,Pilihan,Wajib Fakultas,Wajib Universitas',
            'dosen_ids' => 'nullable|array',
            'dosen_ids.*' => 'exists:dosens,id',
            'mahasiswa_nims' => 'nullable|array', // Validasi untuk mahasiswa
            'mahasiswa_nims.*' => 'exists:mahasiswas,nim' // Pastikan NIM mahasiswa ada
        ]);

        $mataKuliah = MataKuliah::create($validated);

        if ($request->has('dosen_ids')) {
            $mataKuliah->dosens()->sync($request->dosen_ids);
        }

        if ($request->has('mahasiswa_nims')) {
            $mataKuliah->mahasiswas()->sync($request->mahasiswa_nims);
        }

        return redirect()->route('mata_kuliah.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan');
    }

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
            'mahasiswa_nims' => 'nullable|array', // Tambahkan juga di update jika diperlukan
            'mahasiswa_nims.*' => 'exists:mahasiswas,nim' // Tambahkan juga di update jika diperlukan
        ]);

        $mataKuliah->update($validated);

        $mataKuliah->dosens()->sync($request->dosen_ids ?? []);
        $mataKuliah->mahasiswas()->sync($request->mahasiswa_nims ?? []); // Tambahkan juga di update jika diperlukan

        return redirect()->route('mata_kuliah.index')
            ->with('success', 'Mata kuliah berhasil diperbarui');
    }

    public function destroy(MataKuliah $mataKuliah)
    {
        $mataKuliah->dosens()->detach();
        $mataKuliah->mahasiswas()->detach(); // Pastikan relasi mahasiswa juga di-detach
        $mataKuliah->delete();

        return redirect()->route('mata_kuliah.index')
            ->with('success', 'Mata kuliah berhasil dihapus');
    }
}
