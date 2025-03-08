<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RekayasaperangkatlunakController extends Controller
{
    public function index()
    {
        // Logika untuk mengambil data mahasiswa sistem informasi
        $mahasiswa = [
            ['nim' => '2230166', 'nama' => 'Mhs 1', 'angkatan' => 2022, 'email' => 'mhs1@gmail.com', 'kontak' => '08888', 'gender' => 'Perempuan', 'ipk' => 3.0],
            ['nim' => '1111222', 'nama' => 'Mhs 2', 'angkatan' => 2022, 'email' => 'mhs2@gmail.com', 'kontak' => '08999', 'gender' => 'Laki-laki', 'ipk' => 3.1],
            ['nim' => '3300112', 'nama' => 'Mhs 3', 'angkatan' => 2024, 'email' => 'mhs3@gmail.com', 'kontak' => '08777', 'gender' => 'Perempuan', 'ipk' => 3.2],
            ['nim' => '2230166', 'nama' => 'Mhs 4', 'angkatan' => 2022, 'email' => 'mhs22@gmail.com', 'kontak' => '08888', 'gender' => 'Laki-laki', 'ipk' => 3.3],
        ];

        return view('fakultasfst.Rekayasaperangkatlunak', compact('mahasiswa'));
    }
}
