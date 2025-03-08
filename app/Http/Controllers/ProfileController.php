<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        // Ambil data profil pengguna dari database atau session
        $user = auth()->user();

        // Kirim data profil ke view
        return view('profile.index', compact('user'));
    }
}
