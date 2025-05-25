<?php

namespace App\Http\Controllers;

use App\Models\Cpl;
use App\Models\Cpmk;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class CpmkController extends Controller
{
    public function create()
    {
        $cpls = Cpl::all(); // Ambil data CPL untuk select
        $matakuliahs = MataKuliah::all(); // Ambil data Mata Kuliah
        return view('cpmk.create', compact('cpls', 'matakuliahs'));
    }

    public function index(Request $request)
    {
        // Get distinct values for filter dropdowns
        $availableCpls = Cpl::select('kode_cpl')->distinct()->orderBy('kode_cpl')->get();
        $availableMatakuliahs = MataKuliah::select('kode_mk')->distinct()->orderBy('kode_mk')->get();
        $availablePics = Cpmk::select('pic')->distinct()->orderBy('pic')->pluck('pic');

        // Build the query with CPL relationship and sorting
        $query = Cpmk::with('cpl')
            ->join('cpls', 'cpmks.kode_cpl', '=', 'cpls.kode_cpl');

        // Apply filters
        if ($request->filled('kode_cpmk')) {
            $query->where('cpmks.kode_cpmk', 'like', '%' . $request->kode_cpmk . '%');
        }

        if ($request->filled('kode_cpl')) {
            $query->where('cpmks.kode_cpl', $request->kode_cpl);
        }

        if ($request->filled('mata_kuliah')) {
            $query->where('cpmks.mata_kuliah', $request->mata_kuliah);
        }

        if ($request->filled('pic')) {
            $query->where('cpmks.pic', $request->pic);
        }

        if ($request->filled('bobot')) {
            $query->where('cpmks.bobot', $request->bobot);
        }

        // Apply sorting
        $query->orderByRaw("CAST(SUBSTRING(cpls.kode_cpl, 4) AS UNSIGNED) ASC")
            ->orderByRaw("CAST(SUBSTRING(cpmks.kode_cpmk, 6) AS UNSIGNED) ASC")
            ->select('cpmks.*');

        // Get CPLs for modals with proper sorting
        $cpls = Cpl::all()->sortBy(function ($item) {
            return (int) filter_var($item->kode_cpl, FILTER_SANITIZE_NUMBER_INT);
        });

        $matakuliahs = MataKuliah::all();

        // Paginate the results
        $cpmks = $query->paginate(10);

        return view('cpmk.index', [
            'cpmks' => $cpmks,
            'availableCpls' => $availableCpls,
            'availableMatakuliahs' => $availableMatakuliahs,
            'availablePics' => $availablePics,
            'cpls' => $cpls,
            'matakuliahs' => $matakuliahs,
        ]);
    }


    // app/Http/Controllers/CpmkController.php (Store Method)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_cpl' => 'required',
            'kode_cpmk' => 'required|integer',
            'mata_kuliah' => 'required',
            'deskripsi' => 'required',
            'pic' => 'required',
            'bobot' => 'required|numeric|min:0|max:100',

        ]);

        $cpl = Cpl::where('kode_cpl', $validated['kode_cpl'])->firstOrFail();
        $cplNumber = substr($cpl->kode_cpl, 3); // Remove 'CPL' prefix
        $kode_cpmk = 'CPMK' . $cplNumber . str_pad($validated['kode_cpmk'], 3, '0', STR_PAD_LEFT);

        if (Cpmk::where('kode_cpmk', $kode_cpmk)->exists()) {
            return back()->withErrors(['kode_cpmk' => 'Kode CPMK sudah ada.'])->withInput();
        }

        Cpmk::create([
            'kode_cpl' => $validated['kode_cpl'],
            'kode_cpmk' => $kode_cpmk,
            'mata_kuliah' => $validated['mata_kuliah'],
            'deskripsi' => $validated['deskripsi'],
            'pic' => $validated['pic'],
            'bobot' => $validated['bobot'],
        ]);

        return redirect()->route('cpmk.index')->with('success', 'CPMK berhasil ditambahkan');
    }

    // app/Http/Controllers/CpmkController.php (Update Method)
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kode_cpl' => 'required',
            'kode_cpmk' => 'required',
            'mata_kuliah' => 'required',
            'deskripsi' => 'required',
            'pic' => 'required',
            'bobot' => 'required|numeric|min:0|max:100',

        ]);

        if (Cpmk::where('kode_cpmk', $validated['kode_cpmk'])->where('id', '!=', $id)->exists()) {
            return back()->withErrors(['kode_cpmk' => 'Kode CPMK sudah ada.'])->withInput();
        }

        $cpmk = Cpmk::findOrFail($id);
        $cpmk->update($validated);

        return redirect()->route('cpmk.index')->with('success', 'CPMK berhasil diperbarui');
    }
    public function destroy(Cpmk $cpmk)
    {
        $cpmk->delete();
        return redirect()->route('cpmk.index')->with('success', 'CPMK berhasil dihapus.');
    }
}