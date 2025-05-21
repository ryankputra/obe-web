<?php

namespace App\Http\Controllers;

use App\Models\Cpl;
use Illuminate\Http\Request;

class CplController extends Controller
{
    public function index()
    {
        $cpls = Cpl::orderBy('kode_cpl')->paginate(10);
        return view('cpl.index', compact('cpls'));
    }

    public function create()
    {
        return view('cpl.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_cpl' => 'required|unique:cpls,kode_cpl|max:10',
            'deskripsi' => 'required'
        ]);

        Cpl::create($validated);
        return redirect()->route('cpl.index')->with('success', 'CPL berhasil ditambahkan.');
    }

    public function edit(Cpl $cpl)
    {
        return view('cpl.edit', compact('cpl'));
    }

    public function update(Request $request, Cpl $cpl)
    {
        $validated = $request->validate([
            'kode_cpl' => 'required|unique:cpls,kode_cpl,'.$cpl->id.'|max:10',
            'deskripsi' => 'required'
        ]);

        $cpl->update($validated);
        return redirect()->route('cpl.index')->with('success', 'CPL berhasil diperbarui.');
    }

    public function destroy(Cpl $cpl)
    {
        $cpl->delete();
        return redirect()->route('cpl.index')->with('success', 'CPL berhasil dihapus.');
    }
    public function show($id)
    {
        $cpl = Cpl::with('cpmks')->findOrFail($id);
        
        return view('cpl.show', compact('cpl'));
    }
}