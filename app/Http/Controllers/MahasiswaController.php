<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date; // Pastikan ini di-import jika menggunakan Date::now()

class MahasiswaController extends Controller
{
    /**
     * Menampilkan daftar mahasiswa dengan filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Mahasiswa::with('prodi');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nim', 'like', '%' . $search . '%')
                  ->orWhere('nama', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($prodiId = $request->query('prodi_id')) {
            $query->where('prodi_id', $prodiId);
        } elseif ($prodiNama = $request->query('prodi')) {
            $query->whereHas('prodi', function ($q) use ($prodiNama) {
                $q->where('nama_prodi', $prodiNama);
            });
        }

        if ($angkatan = $request->query('angkatan')) {
            $query->where('angkatan', $angkatan);
        }

        $mahasiswas = $query->orderBy('nama', 'asc')->paginate(10)->appends($request->query());
        $prodis = Prodi::orderBy('nama_prodi', 'asc')->get(['id', 'nama_prodi']);
        $angkatans = Mahasiswa::select('angkatan')
                              ->distinct()
                              ->orderBy('angkatan', 'desc')
                              ->pluck('angkatan');

        return view('mahasiswa.index', compact('mahasiswas', 'prodis', 'angkatans'));
    }

    /**
     * Menampilkan form untuk membuat mahasiswa baru.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() // <--- METHOD CREATE ADA DI SINI
    {
        $prodis = Prodi::where('status', 'aktif')->orderBy('nama_prodi')->get(['id', 'nama_prodi']);
        return view('mahasiswa.create', compact('prodis'));
    }

    /**
     * Menyimpan mahasiswa baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'prodi_id' => 'required|exists:prodis,id',
            'nim' => 'required|string|max:20|unique:mahasiswas,nim',
            'nama' => 'required|string|max:255',
            'angkatan' => 'required|integer|digits_between:4,4|min:1900|max:' . (Date::now()->year + 5),
            'email' => 'required|string|email|max:255|unique:mahasiswas,email',
            'no_hp' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
        ]);

        Mahasiswa::create($validatedData);

        return redirect()->route('mahasiswa.index')
                         ->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail mahasiswa.
     *
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function show(Mahasiswa $mahasiswa)
    {
        return redirect()->route('mahasiswa.edit', $mahasiswa);
    }

    /**
     * Menampilkan form untuk mengedit data mahasiswa.
     *
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        $prodis = Prodi::where('status', 'aktif')->orderBy('nama_prodi')->get(['id', 'nama_prodi']);
        return view('mahasiswa.edit', compact('mahasiswa', 'prodis'));
    }

    /**
     * Memperbarui data mahasiswa di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'prodi_id' => 'required|exists:prodis,id',
            'angkatan' => 'required|integer|digits_between:4,4|min:1900|max:' . (Date::now()->year + 5),
            'alamat' => 'required|string',
            'email' => 'required|string|email|max:255|unique:mahasiswas,email,' . $mahasiswa->nim . ',nim',
            'no_hp' => 'required|string|max:20',
        ]);

        $mahasiswa->update($validatedData);

        return redirect()->route('mahasiswa.index')
                         ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    /**
     * Menghapus data mahasiswa dari database.
     *
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')
                         ->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}