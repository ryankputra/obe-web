<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class fakultasVokasiController extends Controller
{
    public function Vokasi()
    {
        // Ambil data program studi dari database jika diperlukan
        $prodi = [
            ['nama' => 'Akuntansi', 'jumlah' => 300],
            ['nama' => 'Manegenemt Keuangan', 'jumlah' => 250],
            ['nama' => 'Bahasa Inggris', 'jumlah' => 200],
        ];

        return view('fakultasVokasi.Vokasi', compact('prodi'));
    }
}
