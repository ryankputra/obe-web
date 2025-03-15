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

    public function saveMk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_mk' => 'required|unique:mata_kuliahs,kode_mk',
            'nama_mk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'semester' => 'required|integer|between:1,8',
            'sks_teori' => 'required|integer|min:0',
            'sks_praktik' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        MataKuliah::create($request->only(['kode_mk', 'nama_mk', 'deskripsi', 'semester', 'sks_teori', 'sks_praktik']));

        return redirect()->route('mata_kuliah.index')->with('success', 'Mata Kuliah berhasil ditambahkan!');
    }

    public function editMk(Request $request, $id)
{
    // Cek apakah data ada di database
    $mataKuliah = MataKuliah::find($id);

    if (!$mataKuliah) {
        return response()->json(['success' => false, 'message' => 'Mata Kuliah tidak ditemukan'], 404);
    }

    // Debug sebelum update
    Log::info('Data sebelum update:', $mataKuliah->toArray());


    // Update record
    $mataKuliah->update([
        'kode_mk' => $request->kode_mk,
        'nama_mk' => $request->nama_mk,
        'deskripsi' => $request->deskripsi,
        'semester' => $request->semester,
        'sks_teori' => $request->sks_teori,
        'sks_praktik' => $request->sks_praktik
    ]);

    // Debug setelah update
    $mataKuliah->refresh();
    Log::info('Data setelah update:', $mataKuliah->toArray());

    return response()->json(['success' => true, 'message' => 'Data berhasil diupdate', 'data' => $mataKuliah]);
}

}
