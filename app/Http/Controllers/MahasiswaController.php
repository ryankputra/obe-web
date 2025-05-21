<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
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

        if ($request->has('continue')) {
            return redirect()
                ->route('mahasiswa.create')
                ->with('success', 'Mahasiswa berhasil ditambahkan')
                ->withInput();
        } else {
            return redirect()->route('mahasiswa.create')
                ->with('success', 'Mahasiswa berhasil ditambahkan, silakan input data berikutnya.');
        }
    }
}
