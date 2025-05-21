<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswas = Mahasiswa::with('prodi')->get();
        $prodis = Prodi::all();
        return view('mahasiswa.index', compact('mahasiswas', 'prodis'));
    }

    public function create()
    {
        $prodis = Prodi::all();
        return view('mahasiswa.create', compact('prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'prodi_id' => 'required|exists:prodis,id',
            'nim' => 'required|string|max:20|unique:mahasiswas',
            'nama' => 'required|string|max:255',
            'angkatan' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'email' => 'required|email|unique:mahasiswas',
            'no_hp' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string'
        ]);

        Mahasiswa::create($request->all());

        // Check if "Save and Continue" button was clicked
        if ($request->has('continue')) {
            return redirect()
                ->route('mahasiswa.create')
                ->with('success', 'Mahasiswa berhasil ditambahkan')
                ->withInput();
        }

        return redirect()
            ->route('mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        return view('mahasiswa.show', compact('mahasiswa'));
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $prodis = Prodi::all();
        return view('mahasiswa.edit', compact('mahasiswa', 'prodis'));
    }

    public function update(Request $request, $nim)
    {
        $request->validate([
            'nama' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'prodi_id' => 'required|exists:prodis,id',
            'angkatan' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'alamat' => 'required|string',
            'email' => 'required|email|unique:mahasiswas,email,' . $nim . ',nim',
            'no_hp' => 'required'
        ]);

        Mahasiswa::where('nim', $nim)->update($request->except('_token', '_method'));

        return redirect()
            ->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diubah.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();

        return redirect()
            ->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}
