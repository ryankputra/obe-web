<?php

namespace App\Http\Controllers;

use App\Filters\V1\DosenFilter;
use App\Http\Resources\V1\DosenCollection;
use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $filter = new DosenFilter();
        $filterItems = $filter->transform($request);

        $dosens = Dosen::query();

        if (count($filterItems)) {
            $dosens->where($filterItems);
        }

        if ($request->wantsJson()) {
            return new DosenCollection($dosens->paginate()->appends($request->query()));
        }

        return view('dosen.index', [
            'dosens' => $dosens->paginate(10)->appends($request->query()),
            'filters' => $request->all()
        ]);
    }

    public function create()
    {
        return view('dosen.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nidn' => 'required|unique:dosens,nidn',
            'nama' => 'required',
            'email' => 'nullable|email',
            'jabatan' => 'required',
            'kompetensi' => 'required',
        ]);

        Dosen::create($validated);
        
        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil ditambahkan.');
    }

    public function edit(Dosen $dosen)
    {
        return view('dosen.edit', compact('dosen'));
    }

    public function update(Request $request, Dosen $dosen)
    {
        $validated = $request->validate([
            'nidn' => 'required|unique:dosens,nidn,'.$dosen->id,
            'nama' => 'required',
            'email' => 'nullable|email',
            'jabatan' => 'required',
            'kompetensi' => 'required',
        ]);

        $dosen->update($validated);
        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil diperbarui.');
    }

    public function destroy(Dosen $dosen)
    {
        $dosen->delete();
        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil dihapus.');
    }
}