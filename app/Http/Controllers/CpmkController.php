<?php

namespace App\Http\Controllers;

use App\Models\Cpl;
use App\Models\Cpmk;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class CpmkController extends Controller
{
    public function index()
    {
        $cpmks = Cpmk::with('cpl')->get();
        $cpls = Cpl::all();
        $matakuliahs = MataKuliah::all();
        return view('cpmk.index', compact('cpmks', 'cpls', 'matakuliahs'));

        
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_cpl' => 'required|exists:cpls,kode_cpl',
            'kode_cpmk' => 'required|numeric|min:1|max:999',
            'mata_kuliah' => 'required|exists:mata_kuliahs,kode_mk',
            'deskripsi' => 'required',
            'pic' => 'required'
        ]);

        // Generate combined code
        $combinedCode = $validated['kode_cpl'] . str_pad($validated['kode_cpmk'], 3, '0', STR_PAD_LEFT);

        Cpmk::create([
            'kode_cpl' => $validated['kode_cpl'],
            'kode_cpmk' => $combinedCode,
            'mata_kuliah' => $validated['mata_kuliah'],
            'deskripsi' => $validated['deskripsi'],
            'pic' => $validated['pic']
        ]);

        return redirect()->route('cpmk.index')->with('success', 'CPMK berhasil ditambahkan.');
    }

    public function update(Request $request, Cpmk $cpmk)
    {
        $validated = $request->validate([
            'kode_cpl' => 'required|exists:cpls,kode_cpl',
            'mata_kuliah' => 'required|exists:mata_kuliahs,kode_mk',
            'deskripsi' => 'required',
            'pic' => 'required'
        ]);

        $cpmk->update($validated);
        return redirect()->route('cpmk.index')->with('success', 'CPMK berhasil diperbarui.');
    }

    public function destroy(Cpmk $cpmk)
    {
        $cpmk->delete();
        return redirect()->route('cpmk.index')->with('success', 'CPMK berhasil dihapus.');
    }
}