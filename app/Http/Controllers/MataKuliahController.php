<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index()
    {
        // Data dummy untuk contoh
        $mataKuliahs = [
            ['kode_mk' => 'MK-001', 'nama_mk' => 'Pemrograman Web', 'deskripsi' => '', 'semester' => 4, 'sks_teori' => 1, 'sks_praktik' => 3],
            ['kode_mk' => 'MK-002', 'nama_mk' => 'Pemrograman Mobile', 'deskripsi' => '', 'semester' => 3, 'sks_teori' => 1, 'sks_praktik' => 3],
            ['kode_mk' => 'MK-003', 'nama_mk' => 'Pembelajaran Mesin', 'deskripsi' => '', 'semester' => 4, 'sks_teori' => 2, 'sks_praktik' => 2],
        ];

        return view('mata_kuliah.index', compact('mataKuliahs'));
    }
}
