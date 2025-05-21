<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function index()
    {
        $prodis = Prodi::withCount('mahasiswas')->get()->map(function ($prodi) {
            return (object)[
                'id' => $prodi->id,
                'nama_prodi' => $prodi->nama_prodi,
                'jumlah_mahasiswa' => $prodi->mahasiswas_count,
            ];
        });

        return view('fakultasfst.index', compact('prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_prodi' => 'required|string|max:255|unique:prodis',
        ]);

        Prodi::create([
            'nama_prodi' => $request->nama_prodi,
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
        ]);

        $prodi = Prodi::findOrFail($id);
        $prodi->update([
            'nama_prodi' => $request->nama_prodi,
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