<?php
//cek
namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index()
    {
        $mataKuliahs = MataKuliah::all();
        return view('mata_kuliah.index', compact('mataKuliahs'));
    }

    public function store(Request $request)
    {
        // Validasi dan simpan data
        $validated = $request->validate([
            'kode_mk' => 'required',
            'nama_mk' => 'required',
            'deskripsi' => 'nullable',
            'semester' => 'required|integer',
            'sks_teori' => 'required|integer',
            'sks_praktik' => 'required|integer',
            'status_mata_kuliah' => 'required'
        ]);

        $mataKuliah = MataKuliah::create($validated);

        return response()->json([
            'success' => true,
            'data' => $mataKuliah
        ]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:255',
            'nama_mk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'semester' => 'required|integer',
            'sks_teori' => 'required|integer',
            'sks_praktik' => 'required|integer',
            'status_mata_kuliah' => 'required|string',
        ]);

        $mataKuliah = MataKuliah::findOrFail($id);
        $mataKuliah->update($request->all());

        return response()->json([
            'success' => true,
            'id' => $mataKuliah->id,
            'kode_mk' => $mataKuliah->kode_mk,
            'nama_mk' => $mataKuliah->nama_mk,
            'deskripsi' => $mataKuliah->deskripsi,
            'semester' => $mataKuliah->semester,
            'sks_teori' => $mataKuliah->sks_teori,
            'sks_praktik' => $mataKuliah->sks_praktik,
            'status_mata_kuliah' => $mataKuliah->status_mata_kuliah,
        ]);
    }

    public function destroy($id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);
        $mataKuliah->delete();
        return response()->json(['success' => true]);
    }
}