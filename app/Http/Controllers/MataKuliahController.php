<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostRes;
use Illuminate\Http\Request;
use App\Models\MataKuliah;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class MataKuliahController extends Controller
{
    public function index()
    {
        $mataKuliahs = MataKuliah::all();
        return view('mata_kuliah.index', compact('mataKuliahs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mk' => 'required|unique:mata_kuliahs,kode_mk',
            'nama_mk' => 'required',
            'deskripsi' => 'nullable',
            'semester' => 'required|integer',
            'sks_teori' => 'required|integer|min:0',
            'sks_praktik' => 'required|integer|min:0',
            'status_mata_kuliah' => 'required',
        ]);

        MataKuliah::create($validated);

        return redirect()->route('listMk')->with('success', 'Mata Kuliah berhasil ditambahkan.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'kode_mk' => 'required|exists:mata_kuliahs,kode_mk',
            'nama_mk' => 'required',
            'deskripsi' => 'nullable',
            'semester' => 'required|integer',
            'sks_teori' => 'required|integer|min:0',
            'sks_praktik' => 'required|integer|min:0',
            'status_mata_kuliah' => 'required',
        ]);

        $mataKuliah = MataKuliah::where('kode_mk', $request->kode_mk)->first();
        $mataKuliah->update($validated);

        return redirect()->route('listMk')->with('success', 'Mata Kuliah berhasil diperbarui.');
    }

    public function destroy(Request $request)
    {
        MataKuliah::where('kode_mk', $request->kode_mk)->delete();

        return redirect()->route('listMk')->with('success', 'Mata Kuliah berhasil dihapus.');
    }
}
