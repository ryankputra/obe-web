<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CplController extends Controller
{
    public function index()
    {
        // Logika untuk mengambil data CPL
        $cpl = [
            ['kode' => 'CPL - 001', 'deskripsi' => 'Lorem ipsum dolor sit amet'],
            ['kode' => 'CPL - 002', 'deskripsi' => 'Lorem ipsum dolor sit amet'],
            ['kode' => 'CPL - 003', 'deskripsi' => 'Lorem ipsum dolor sit amet'],
        ];

        return view('cpl.cpl', compact('cpl'));
    }
}
