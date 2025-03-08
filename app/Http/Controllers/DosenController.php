<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index()
    {
        $dosen = [
            ['nidn' => '12345678', 'nama' => 'Pak Tutus', 'email' => 'tutus@example.com', 'jabatan' => 'Dosen Tetap', 'kompetensi' => 'RPL'],
            ['nidn' => '87654321', 'nama' => 'Pak Aldy', 'email' => 'Aldy@example.com', 'jabatan' => 'Dosen Luar Biasa', 'kompetensi' => 'Semua bisa'],
            // Tambahkan data dosen lainnya sesuai kebutuhan
        ];

        return view('dosen.index', compact('dosen'));
    }
}
