<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Prodi; // Pastikan model Prodi di-import
use Illuminate\Http\Request;

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
        // Memulai query untuk Mahasiswa, dengan eager loading relasi 'prodi'
        $query = Mahasiswa::with('prodi');

        // 1. Filter berdasarkan Pencarian (NIM, Nama, Email)
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nim', 'like', '%' . $search . '%')
                  ->orWhere('nama', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // 2. Filter berdasarkan Program Studi
        // Prioritaskan filter berdasarkan 'prodi_id' jika ada (misalnya dari link halaman fakultas)
        if ($prodiId = $request->query('prodi_id')) {
            $query->where('prodi_id', $prodiId);
        }
        // Jika tidak ada 'prodi_id', coba filter berdasarkan 'prodi' (nama prodi dari dropdown di halaman mahasiswa)
        elseif ($prodiNama = $request->query('prodi')) {
            $query->whereHas('prodi', function ($q) use ($prodiNama) {
                $q->where('nama_prodi', $prodiNama);
            });
        }

        // 3. Filter berdasarkan Angkatan
        if ($angkatan = $request->query('angkatan')) {
            $query->where('angkatan', $angkatan);
        }

        // Mengurutkan hasil dan melakukan paginasi
        // appends($request->query()) agar parameter filter tetap terbawa saat navigasi paginasi
        $mahasiswas = $query->orderBy('nama', 'asc')->paginate(10)->appends($request->query());

        // Data untuk filter dropdown di view mahasiswa.index
        // Mengambil semua prodi (id dan nama) untuk filter dropdown
        $prodis = Prodi::orderBy('nama_prodi', 'asc')->get();

        // Mengambil semua angkatan unik untuk filter dropdown
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
    public function create()
    {
        // Hanya prodi yang aktif yang bisa dipilih saat membuat mahasiswa baru
        $prodis = Prodi::where('status', 'aktif')->orderBy('nama_prodi')->get();
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
        $request->validate([
            'prodi_id' => 'required|exists:prodis,id', // Validasi bahwa prodi_id ada di tabel prodis
            'nim' => 'required|string|max:20|unique:mahasiswas,nim',
            'nama' => 'required|string|max:255',
            'angkatan' => 'required|integer|digits_between:4,4|min:1900|max:' . (date('Y') + 5), // Angkatan 4 digit
            'email' => 'required|string|email|max:255|unique:mahasiswas,email',
            'no_hp' => 'required|string|max:20', // no_hp biasanya string
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan', // Sesuaikan dengan nilai di form Anda
            'alamat' => 'required|string',
        ]);

        Mahasiswa::create($request->all());

        return redirect()->route('mahasiswa.index') // Redirect ke halaman index setelah berhasil
                         ->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail mahasiswa (bisa redirect ke edit).
     *
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function show(Mahasiswa $mahasiswa) // Menggunakan Route Model Binding
    {
        // Umumnya, show bisa menampilkan detail atau langsung ke edit
        // return view('mahasiswa.show', compact('mahasiswa'));
        return redirect()->route('mahasiswa.edit', $mahasiswa->nim);
    }

    /**
     * Menampilkan form untuk mengedit data mahasiswa.
     *
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function edit(Mahasiswa $mahasiswa) // Menggunakan Route Model Binding
    {
        // Hanya prodi yang aktif yang bisa dipilih
        $prodis = Prodi::where('status', 'aktif')->orderBy('nama_prodi')->get();
        return view('mahasiswa.edit', compact('mahasiswa', 'prodis'));
    }

    /**
     * Memperbarui data mahasiswa di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mahasiswa $mahasiswa) // Menggunakan Route Model Binding
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'prodi_id' => 'required|exists:prodis,id',
            'angkatan' => 'required|integer|digits_between:4,4|min:1900|max:' . (date('Y') + 5),
            'alamat' => 'required|string',
            // Validasi unique email, kecuali untuk email mahasiswa saat ini
            'email' => 'required|string|email|max:255|unique:mahasiswas,email,' . $mahasiswa->id,
            'no_hp' => 'required|string|max:20',
        ]);

        $mahasiswa->update($request->all());

        return redirect()->route('mahasiswa.index')
                         ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    /**
     * Menghapus data mahasiswa dari database.
     *
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mahasiswa $mahasiswa) // Menggunakan Route Model Binding
    {
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')
                         ->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}
