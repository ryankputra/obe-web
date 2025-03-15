<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class sistemmanegementController extends Controller
{
    public function index()
    {
        $mahasiswa = [
            ['nim' => '22301660', 'nama' => 'Ryan', 'angkatan' => 2022, 'email' => 'mhs1@gmail.com', 'kontak' => '08888', 'gender' => 'Perempuan', 'ipk' => 3.0],
            ['nim' => '1111222', 'nama' => 'Mhs 2', 'angkatan' => 2022, 'email' => 'mhs2@gmail.com', 'kontak' => '08999', 'gender' => 'Laki-laki', 'ipk' => 3.1],
            ['nim' => '3300112', 'nama' => 'Mhs 3', 'angkatan' => 2024, 'email' => 'mhs3@gmail.com', 'kontak' => '08777', 'gender' => 'Perempuan', 'ipk' => 3.2],
        ];

        return view('fakultasfeb.sistemmanegement', compact('mahasiswa'));
    }
}
