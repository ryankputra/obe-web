<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // dd('--- STEP 1: Method login() di LoginController DIPANGGIL ---'); // AKTIFKAN INI DULU

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // dd('--- STEP 2: Auth::attempt() BERHASIL ---'); // JIKA STEP 1 MUNCUL, AKTIFKAN INI

            $request->session()->regenerate();
            $user = Auth::user();

            // dd('--- STEP 3: Data User ---', $user, '--- Role User ---', $user->role ?? 'ROLE TIDAK DITEMUKAN'); // INI KRUSIAL

            if ($user && isset($user->role) && $user->role === 'dosen') { // Pastikan 'dosen' sesuai dengan database
                // dd('--- STEP 4: KONDISI DOSEN TERPENUHI, AKAN REDIRECT KE PENILAIAN ---'); // APAKAH INI MUNCUL?
                return redirect()->route('penilaian.index');
            }

            // dd('--- STEP 5: KONDISI DOSEN TIDAK TERPENUHI, AKAN REDIRECT KE DASHBOARD INTENDED ---', $user->role ?? 'Role tidak diset'); // ATAU INI YANG MUNCUL?
            return redirect()->intended('dashboard');

        } else {
             dd('--- STEP 6: Auth::attempt() GAGAL ---'); // JIKA LOGIN GAGAL
            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau kata sandi salah. Silakan coba lagi.']);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}