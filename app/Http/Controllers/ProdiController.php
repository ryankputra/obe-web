<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function index()
    {
        $prodis = Prodi::all();
        return view('fakultasfst.index', compact('prodis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_prodi' => 'required|string|max:255|unique:prodis',
            'jumlah_mahasiswa' => 'required|integer|min:0',
        ]);

        Prodi::create($validated);

        return redirect()->route('fakultasfst.index')
            ->with('success', 'Program studi berhasil ditambahkan');
    }

    public function show(Prodi $prodi)
    {
        return view('fakultasfst.prodi-detail', compact('prodi'));
    }

    public function update(Request $request, Prodi $prodi)
    {
        $validated = $request->validate([
            'nama_prodi' => 'required|string|max:255|unique:prodis,nama_prodi,' . $prodi->id,
            'jumlah_mahasiswa' => 'required|integer|min:0',
        ]);

        $prodi->update($validated);

        return redirect()->route('fakultasfst.index')
            ->with('success', 'Program studi berhasil diperbarui');
    }

    public function destroy(Prodi $prodi)
    {
        $prodi->delete();

        return redirect()->route('fakultasfst.index')
            ->with('success', 'Program studi berhasil dihapus');
    }

    // Remove the edit() method since you're using modal
}
