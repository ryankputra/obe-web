<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MataKuliah;

class MataKuliahController extends Controller
{
    public function index()
    {
        // // Data dummy untuk contoh
        // $mataKuliahs = [
        //     ['kode_mk' => 'MK-001', 'nama_mk' => 'Pemrograman Web', 'deskripsi' => '', 'semester' => 4, 'sks_teori' => 1, 'sks_praktik' => 3],
        //     ['kode_mk' => 'MK-002', 'nama_mk' => 'Pemrograman Mobile', 'deskripsi' => '', 'semester' => 3, 'sks_teori' => 1, 'sks_praktik' => 3],
        //     ['kode_mk' => 'MK-003', 'nama_mk' => 'Pembelajaran Mesin', 'deskripsi' => '', 'semester' => 4, 'sks_teori' => 2, 'sks_praktik' => 2],
        // ];

        return view('mata_kuliah.index', ['mataKuliahs' => MataKuliah::all()]);
    }

    public function saveMk(Request $request)
    {
        $newMataKuliah = new MataKuliah;
        $newMataKuliah->kode_mk = $request->kode_mk;
        $newMataKuliah->nama_mk = $request->nama_mk;
        $newMataKuliah->deskripsi = $request->deskripsi;
        $newMataKuliah->semester = $request->semester;
        $newMataKuliah->sks_teori = $request->sks_teori;
        $newMataKuliah->sks_praktik = $request->sks_praktik;
        $newMataKuliah->save();

        return redirect()->route('mata_kuliah.index')->with('success', 'Mata Kuliah berhasil ditambahkan!');
    }
}