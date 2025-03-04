<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index()
    {
        return view('dosen.index');
    }

    public function create()
    {
        return view('dosen.create');
    }

    // Tambahkan metode lainnya (store, show, edit, update, destroy) sesuai kebutuhan
}
