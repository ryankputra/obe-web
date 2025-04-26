<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function index()
    {
        $prodis = Prodi::where('fakultas', 'Sains dan Teknologi')->get();
        return view('fakultasfst.saintek', compact('prodis'));
    }

    // public function showDetail($id)
    // {
    //     $prodi = Prodi::findOrFail($id);
    //     return view('fakultasfst.detail-prodi', compact('prodi'));
    // }

    public function store(Request $request)
    {
        $request->validate([
            'nama_prodi' => 'required|string|max:255',
            'jumlah_mahasiswa' => 'required|integer|min:0'
        ]);

        Prodi::create([
            'nama_prodi' => $request->nama_prodi,
            'jumlah_mahasiswa' => $request->jumlah_mahasiswa,
            'fakultas' => 'Sains dan Teknologi'
        ]);

        return redirect()->route('fakultasfst.saintek')->with('success', 'Program studi berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah_mahasiswa' => 'required|integer|min:0'
        ]);

        $prodi = Prodi::findOrFail($id);
        $prodi->update([
            'jumlah_mahasiswa' => $request->jumlah_mahasiswa
        ]);

        return redirect()->route('fakultasfst.saintek')->with('success', 'Program studi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $prodi = Prodi::findOrFail($id);
        $prodi->delete();

        return redirect()->route('fakultasfst.saintek')->with('success', 'Program studi berhasil dihapus');
    }
}
