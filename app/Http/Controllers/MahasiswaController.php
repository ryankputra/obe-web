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

    /**
     * Search mahasiswa for AJAX requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchJson(Request $request)
    {
        $queryTerm = $request->input('q', '');
        $selectedNims = $request->input('selected_nims', []); // Untuk mengecualikan yang sudah dipilih

        // \Illuminate\Support\Facades\Log::info('Search Mahasiswa JSON:', ['query' => $queryTerm, 'selected_nims' => $selectedNims]);

        $mahasiswaQuery = Mahasiswa::where(function ($sq) use ($queryTerm) {
            $sq->where('nama', 'LIKE', "%{$queryTerm}%")
               ->orWhere('nim', 'LIKE', "%{$queryTerm}%");
        });

        // Filter out empty strings from selectedNims if any, and ensure it's an array
        $validSelectedNims = [];
        if (is_array($selectedNims)) {
            foreach ($selectedNims as $nim) {
                if (!empty(trim((string)$nim))) { // Ensure NIM is not an empty or whitespace-only string
                    $validSelectedNims[] = (string)$nim;
                }
            }
        }

        if (!empty($validSelectedNims)) {
            $mahasiswaQuery->whereNotIn('nim', $validSelectedNims);
        }

        $results = $mahasiswaQuery->take(10)->get(['nim', 'nama']);
        return response()->json($results);
    }
}
