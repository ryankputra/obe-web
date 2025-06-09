<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        // Ambil data profil pengguna dari database atau session
        $user = auth()->user();
        // Jika pengguna adalah dosen, load relasi dosen untuk memastikan data profil dosen tersedia
        if ($user->role === 'dosen') {
            $user->load('dosen');
        }

        // Kirim data profil ke view
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string|max:50',
            'kewarganegaraan' => 'nullable|string|max:100',
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi untuk avatar
        ]);

        $user->email = $validatedData['email'];

        // Handle file upload untuk avatar
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            // Simpan avatar baru
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        // Jika profil ini untuk Dosen dan data ada di model Dosen
        // Sesuaikan jika no_hp dll ada di model User
        if ($user->dosen) {
            $dosen = $user->dosen;
            $dosen->tempat_lahir = $validatedData['tempat_lahir'] ?? $dosen->tempat_lahir;
            $dosen->tanggal_lahir = $validatedData['tanggal_lahir'] ?? $dosen->tanggal_lahir;
            $dosen->agama = $validatedData['agama'] ?? $dosen->agama;
            $dosen->kewarganegaraan = $validatedData['kewarganegaraan'] ?? $dosen->kewarganegaraan;

            \Illuminate\Support\Facades\Log::info('Updating Dosen Profile (Before Save):', [
                'dosen_id' => $dosen->id,
                'is_dirty' => $dosen->isDirty(),
                'dirty_attributes' => $dosen->getDirty(),
                'attributes_to_save' => [
                    'tempat_lahir' => $dosen->tempat_lahir,
                    'tanggal_lahir' => $dosen->tanggal_lahir,
                    'agama' => $dosen->agama,
                    'kewarganegaraan' => $dosen->kewarganegaraan,
                ]
            ]);

            $dosen->save();
            $dosen->refresh(); // Muat ulang dari database untuk memastikan

            \Illuminate\Support\Facades\Log::info('Dosen Profile (After Save & Refresh):', [
                'dosen_id' => $dosen->id,
                'tempat_lahir' => $dosen->tempat_lahir,
                'tanggal_lahir' => $dosen->tanggal_lahir,
                'agama' => $dosen->agama,
                'kewarganegaraan' => $dosen->kewarganegaraan,
            ]);
        }

        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
