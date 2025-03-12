<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CpmkController extends Controller
{
    public function index()
    {
        // Logika untuk mengambil data CPMK
        $cpmk = [
            ['kode' => 'CPMK - 001', 'deskripsi' => 'Lorem ipsum dolor sit amet', 'cpl' => 'CPL - 001'],
            ['kode' => 'CPMK - 002', 'deskripsi' => 'Lorem ipsum dolor sit amet', 'cpl' => 'CPL - 002'],
            ['kode' => 'CPMK - 003', 'deskripsi' => 'Lorem ipsum dolor sit amet', 'cpl' => 'CPL - 003'],
        ];

        // Logika untuk mengambil data CPL
        $cpl = [
            ['kode' => 'CPL - 001', 'deskripsi' => 'CPL Description 1'],
            ['kode' => 'CPL - 002', 'deskripsi' => 'CPL Description 2'],
            ['kode' => 'CPL - 003', 'deskripsi' => 'CPL Description 3'],
        ];

        return view('cpmk.cpmk', compact('cpmk', 'cpl'));
    }
}
