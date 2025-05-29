<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index(Request $request)
    {
        $query = MataKuliah::with('dosens');

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

        $mataKuliahs = $query->paginate(10);

        return view('mata_kuliah.index', [
            'mataKuliahs' => $mataKuliahs,
            'totalSKS' => $totalSKS,
            'dosens' => \App\Models\Dosen::all(),
        ]);
    }

    public function create()
    {
        return view('mata_kuliah.create', [
            'dosens' => Dosen::all()
        ]);
    }

    public function edit(MataKuliah $mataKuliah)
    {
        return view('mata_kuliah.edit', [
            'mataKuliah' => $mataKuliah->load('dosens'),
            'dosens' => Dosen::all()
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
            'dosen_ids.*' => 'exists:dosens,id'
        ]);

        $mataKuliah = MataKuliah::create($validated);

        if ($request->has('dosen_ids')) {
            $mataKuliah->dosens()->sync($request->dosen_ids);
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
            'dosen_ids.*' => 'exists:dosens,id'
        ]);

        $mataKuliah->update($validated);

        $mataKuliah->dosens()->sync($request->dosen_ids ?? []);

        return redirect()->route('mata_kuliah.index')
            ->with('success', 'Mata kuliah berhasil diperbarui');
    }

    public function destroy(MataKuliah $mataKuliah)
    {
        $mataKuliah->dosens()->detach();
        $mataKuliah->delete();

        return redirect()->route('mata_kuliah.index')
            ->with('success', 'Mata kuliah berhasil dihapus');
    }
}
