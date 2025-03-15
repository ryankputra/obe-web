<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class fakultasfebController extends Controller
{
    public function feb()
    {
        // Ambil data program studi dari database jika diperlukan
        $prodi = [
            ['nama' => 'akuntansi', 'jumlah' => 300],
            ['nama' => 'Sistem Manegenemt', 'jumlah' => 250],
        ];

        return view('fakultasfeb.feb', compact('prodi'));
    }
}
