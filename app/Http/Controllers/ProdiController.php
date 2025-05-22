<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function index()
    {
        $prodis = Prodi::withCount('mahasiswas')->get();
        return view('fakultasfst.index', compact('prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_prodi' => 'required|string|max:255|unique:prodis',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Prodi::create([
            'nama_prodi' => $request->nama_prodi,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Prodi berhasil ditambahkan']);
    }

    public function show(Prodi $prodi)
    {
        // Redirect ke daftar mahasiswa dengan filter prodi
        return redirect()->route('mahasiswa.index', ['prodi' => $prodi->nama_prodi]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_prodi' => 'required|string|max:255|unique:prodis,nama_prodi,' . $id,
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $prodi = Prodi::findOrFail($id);
        $prodi->update([
            'nama_prodi' => $request->nama_prodi,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Prodi berhasil diperbarui']);
    }

    public function destroy($id)
    {
        $prodi = Prodi::findOrFail($id);
        $prodi->delete();

        return response()->json(['message' => 'Prodi berhasil dihapus']);
    }
}