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
        $validated = $request->validate([
            'nama_prodi' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $prodi = Prodi::create($validated);

        // Jika request AJAX (fetch), balas JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Prodi berhasil ditambahkan!', 'prodi' => $prodi]);
        }

        // Jika bukan AJAX, balas redirect biasa
        return redirect()->route('fakultasfst.index')->with('success', 'Prodi berhasil ditambahkan!');
    }

    public function show(Prodi $prodi)
    {
        // Redirect ke daftar mahasiswa dengan filter prodi
        // Ini adalah pendekatan yang valid jika Anda tidak memiliki halaman detail khusus untuk prodi.
        // Pastikan route 'mahasiswa.index' dapat menerima parameter 'prodi' untuk filtering.
        return redirect()->route('mahasiswa.index', ['prodi_id' => $prodi->id]); // Menggunakan prodi_id lebih umum untuk filter
    }

    public function update(Request $request, Prodi $prodi)
    {
        $request->validate([
            'nama_prodi' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $prodi->update([
            'nama_prodi' => $request->nama_prodi,
            'status' => $request->status,
        ]);

        // Jika request AJAX, balas JSON. Jika bukan, redirect.
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Prodi berhasil diperbarui',
                'redirect_url' => route('fakultasfst.index')
            ]);
        }
        return redirect()->route('fakultasfst.index')->with('success', 'Prodi berhasil diperbarui');
    }

    public function destroy(Prodi $prodi)
    {
        if ($prodi->mahasiswas()->count() > 0) {
            $msg = 'Tidak bisa menghapus prodi yang memiliki mahasiswa!';
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['message' => $msg], 400);
            }
            return redirect()->route('fakultasfst.index')->with('error', $msg);
        }

        $prodi->delete();
        $msg = 'Prodi berhasil dihapus';

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['message' => $msg]);
        }
        return redirect()->route('fakultasfst.index')->with('success', $msg);
    }
}
