<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index()
    {
        return view('mata_kuliah.index');
    }

    public function create()
    {
        return view('mata_kuliah.create');
    }

    // Tambahkan metode lainnya (store, show, edit, update, destroy) sesuai kebutuhan
}
