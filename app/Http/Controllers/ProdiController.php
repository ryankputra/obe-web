<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function index()
    {
        $prodis = Prodi::all();
        return view('fakultasfst.index', compact('prodis'));
    }

    public function store(Request $request)
    {
        // ... validation and creation logic

        return redirect()->route('fakultasfst.index')
            ->with('success', 'Program studi berhasil ditambahkan');
    }

    public function update(Request $request, Prodi $prodi)
    {
        // ... validation and update logic

        return redirect()->route('fakultasfst.index')
            ->with('success', 'Program studi berhasil diperbarui');
    }

    public function destroy(Prodi $prodi)
    {
        $prodi->delete();

        return redirect()->route('fakultasfst.index')
            ->with('success', 'Program studi berhasil dihapus');
    }
}
