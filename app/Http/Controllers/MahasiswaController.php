<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index()
    {
        return view('mahasiswa.index');
    }

    public function create()
    {
        return view('mahasiswa.create');
    }

    // Tambahkan metode lainnya (store, show, edit, update, destroy) sesuai kebutuhan
}
