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

        return redirect()->route('mata_kuliah.index')->with('success', 'Mata Kuliah berhasil ditambahkan.');
    }

    public function update(Request $request, $kode_mk)
    {
        $validated = $request->validate([
            'kode_mk' => 'required|exists:mata_kuliahs,kode_mk',
            'nama_mk' => 'required|max:100',
            'deskripsi' => 'nullable|string',
            'semester' => 'required|integer|min:1|max:14',
            'sks_teori' => 'required|integer|min:0|max:10',
            'sks_praktik' => 'required|integer|min:0|max:10',
            'status_mata_kuliah' => 'required|in:Wajib Prodi,Wajib Universitas,Pilihan',
        ]);

        $mataKuliah = MataKuliah::where('kode_mk', $kode_mk)->first();
        $mataKuliah->update($validated);

        return response()->json([
            'success' => true,
            'kode_mk' => $mataKuliah->kode_mk,
            'nama_mk' => $mataKuliah->nama_mk,
            'deskripsi' => $mataKuliah->deskripsi,
            'semester' => $mataKuliah->semester,
            'sks_teori' => $mataKuliah->sks_teori,
            'sks_praktik' => $mataKuliah->sks_praktik,
            'status_mata_kuliah' => $mataKuliah->status_mata_kuliah,
        ]);
    }

    public function destroy($kode_mk)
    {
        $mataKuliah = MataKuliah::where('kode_mk', $kode_mk)->first();
        if ($mataKuliah) {
            $mataKuliah->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Mata Kuliah tidak ditemukan.'], 404);
        }
    }
}
