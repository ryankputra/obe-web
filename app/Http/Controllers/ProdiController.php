<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function index()
    {
        // Mengambil semua prodi beserta jumlah mahasiswa yang terkait
        // Hasil dari withCount('mahasiswas') akan menjadi properti 'mahasiswas_count'
        $prodis = Prodi::withCount('mahasiswas')->orderBy('nama_prodi')->get(); 
        // Ditambahkan orderBy untuk konsistensi tampilan, bisa disesuaikan jika tidak diperlukan

        return view('fakultasfst.index', compact('prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_prodi' => 'required|string|max:255|unique:prodis,nama_prodi',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Prodi::create([
            'nama_prodi' => $request->nama_prodi,
            'status' => $request->status,
        ]);

        // Mengembalikan response JSON jika Anda menggunakan AJAX untuk form submission
        // Jika tidak, lebih baik redirect dengan pesan sukses:
        // return redirect()->route('fakultasfst.index')->with('success', 'Prodi berhasil ditambahkan.');
        return response()->json(['message' => 'Prodi berhasil ditambahkan', 'redirect_url' => route('fakultasfst.index')]);
    }

    public function show(Prodi $prodi)
    {
        // Redirect ke daftar mahasiswa dengan filter prodi
        // Ini adalah pendekatan yang valid jika Anda tidak memiliki halaman detail khusus untuk prodi.
        // Pastikan route 'mahasiswa.index' dapat menerima parameter 'prodi' untuk filtering.
        return redirect()->route('mahasiswa.index', ['prodi_id' => $prodi->id]); // Menggunakan prodi_id lebih umum untuk filter
    }

    public function update(Request $request, $id)
    {
        $prodi = Prodi::findOrFail($id); // Pastikan $id adalah primary key dari Prodi

        $request->validate([
            'nama_prodi' => 'required|string|max:255|unique:prodis,nama_prodi,' . $prodi->id,
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $prodi->update([
            'nama_prodi' => $request->nama_prodi,
            'status' => $request->status,
        ]);
        
        // Mengembalikan response JSON jika Anda menggunakan AJAX untuk form submission
        // Jika tidak, lebih baik redirect dengan pesan sukses:
        // return redirect()->route('fakultasfst.index')->with('success', 'Prodi berhasil diperbarui.');
        return response()->json(['message' => 'Prodi berhasil diperbarui', 'redirect_url' => route('fakultasfst.index')]);
    }

    public function destroy($id)
    {
        $prodi = Prodi::findOrFail($id);

        // Pertimbangkan untuk menambahkan pengecekan apakah prodi masih memiliki mahasiswa
        if ($prodi->mahasiswas()->count() > 0) {
             return response()->json(['message' => 'Program Studi tidak dapat dihapus karena masih memiliki mahasiswa.'], 422); // 422 Unprocessable Entity
        }
        
        $prodi->delete();

        // Mengembalikan response JSON jika Anda menggunakan AJAX untuk form submission
        // Jika tidak, lebih baik redirect dengan pesan sukses:
        // return redirect()->route('fakultasfst.index')->with('success', 'Prodi berhasil dihapus.');
        return response()->json(['message' => 'Prodi berhasil dihapus', 'redirect_url' => route('fakultasfst.index')]);
    }
}
