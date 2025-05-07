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

    // app/Http/Controllers/CpmkController.php (Store Method)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_cpl' => 'required',
            'kode_cpmk' => 'required|integer',
            'mata_kuliah' => 'required',
            'deskripsi' => 'required',
            'pic' => 'required',
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