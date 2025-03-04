<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CPLController extends Controller
{
    public function index()
    {
        return view('cpl.index');
    }

    public function create()
    {
        return view('cpl.create');
    }

    // Tambahkan metode lainnya (store, show, edit, update, destroy) sesuai kebutuhan
}
