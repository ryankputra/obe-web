<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CPMKController extends Controller
{
    public function index()
    {
        return view('cpmk.index');
    }

    public function create()
    {
        return view('cpmk.create');
    }

    // Tambahkan metode lainnya (store, show, edit, update, destroy) sesuai kebutuhan
}
