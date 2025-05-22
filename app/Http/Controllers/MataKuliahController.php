<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index(Request $request)
    {
        $query = MataKuliah::query();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('kode_mk', 'like', "%$search%")
                    ->orWhere('nama_mk', 'like', "%$search%");
            });
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->input('semester'));
        }

        if ($request->filled('status')) {
            $query->where('status_mata_kuliah', $request->input('status'));
        }

        $mataKuliahs = $query->paginate(10);

        $totalSKS = $query->get()->sum(function ($mk) {
            return $mk->sks_teori + $mk->sks_praktik;
        });


        return view('mata_kuliah.index', [
            'mataKuliahs' => $mataKuliahs,
            'totalSKS' => $totalSKS
        ]);
    }

    public function create()
    {
        return view('mata_kuliah.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mk' => 'required|unique:mata_kuliahs,kode_mk',
            'nama_mk' => 'required',
            'deskripsi' => 'nullable',
            'semester' => 'required|integer|between:1,8',
            'sks_teori' => 'required|integer|min:0',
            'sks_praktik' => 'required|integer|min:0',
            'status_mata_kuliah' => 'required|in:Wajib Prodi,Pilihan,Wajib Fakultas,Wajib Universitas',
        ]);

        MataKuliah::create($validated);

        return redirect()->route('mata_kuliah.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan');
    }

    public function edit(MataKuliah $mataKuliah)
    {
        return view('mata_kuliah.edit', compact('mataKuliah'));
    }

    public function update(Request $request, MataKuliah $mataKuliah)
    {
        $validated = $request->validate([
            'kode_mk' => 'required|unique:mata_kuliahs,kode_mk,' . $mataKuliah->kode_mk . ',kode_mk',

            'nama_mk' => 'required',
            'deskripsi' => 'nullable',
            'semester' => 'required|integer|between:1,8',
            'sks_teori' => 'required|integer|min:0',
            'sks_praktik' => 'required|integer|min:0',
            'status_mata_kuliah' => 'required|in:Wajib Prodi,Pilihan,Wajib Fakultas,Wajib Universitas',
        ]);

        $mataKuliah->update($validated);

        return redirect()->route('mata_kuliah.index')
            ->with('success', 'Mata kuliah berhasil diperbarui');
    }

    public function destroy(MataKuliah $mataKuliah)
    {
        $mataKuliah->delete();

        return redirect()->route('mata_kuliah.index')
            ->with('success', 'Mata kuliah berhasil dihapus');
    }
}
