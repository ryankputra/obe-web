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
            'nama_prodi' => 'required|string|max:255',
        ]);

        Prodi::create([
            'nama_prodi' => $request->nama_prodi,
        ]);

        return redirect()->route('fakultasfst.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan');
    }
    


    public function show(Prodi $prodi)
    {
        return view('fakultasfst.prodi-detail', compact('prodi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_prodi' => 'required|string|max:255',
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

   


    // Remove the edit() method since you're using modal
}



