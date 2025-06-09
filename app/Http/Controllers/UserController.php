<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen; // Pastikan Dosen model di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule; // Untuk Rule::unique yang lebih fleksibel jika diperlukan

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin'); // Pastikan middleware admin Anda sudah benar
    }

    public function index()
    {
        $users = User::with('dosen')->latest()->paginate(10); // Contoh dengan relasi dosen & paginasi
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $dosens = Dosen::select('id', 'nama', 'nidn', 'email')->orderBy('nama')->get(); // Ambil data dosen dengan email
        return view('users.create', compact('dosens')); // Kirim data ke view
    }

    public function store(Request $request)
    {
        $rules = [
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,dosen',
        ];

        $userData = [];

        if ($request->role === 'dosen') {
            $rules['dosen_id'] = 'required|exists:dosens,id';
            // Validasi email untuk dosen berdasarkan email yang di-supply (yang seharusnya email dosen)
            $rules['email'] = [
                'required',
                'email',
                Rule::unique('users', 'email') // Cek unik di tabel users
            ];
        } elseif ($request->role === 'admin') {
            $rules['name_admin' ] = 'required|string|max:255';
            // Validasi email untuk admin berdasarkan input
            $rules['email'] = [
                'required',
                'email',
                Rule::unique('users', 'email')
            ];
        } else {
            return back()->withErrors(['role' => 'Role pengguna tidak valid.'])->withInput();
        }

        $validated = $request->validate($rules);

        $userData['password'] = Hash::make($validated['password']);
        $userData['role'] = $validated['role'];

        if ($validated['role'] === 'dosen') {
            $dosen = Dosen::find($validated['dosen_id']);
            if ($dosen) {
                $userData['name'] = $dosen->nama;  // Ambil nama dari model Dosen
                $userData['email'] = $dosen->email; // Ambil email dari model Dosen (mengganti input form)
            } else {
                return back()->withErrors(['dosen_id' => 'Data dosen tidak ditemukan.'])->withInput();
            }
        } elseif ($validated['role'] === 'admin') {
            $userData['name'] = $validated['name_admin'];
            $userData['email'] = $validated['email']; // Ambil email dari input form untuk admin
        }

        if (empty($userData['name']) || empty($userData['email'])) {
            return back()->withErrors(['msg' => 'Nama atau Email pengguna tidak dapat ditentukan.'])->withInput();
        }

        // Pastikan tidak ada duplikasi email lagi setelah mengambil email dari Dosen
        // Ini sebagai lapisan keamanan tambahan jika email dosen somehow sudah terdaftar oleh user lain
        // dan validasi Rule::unique di atas tidak menangkapnya (misal jika input email form dimanipulasi)
        $existingUserByDefinitiveEmail = User::where('email', $userData['email'])->first();
        if($existingUserByDefinitiveEmail){
            return back()->withErrors(['email' => 'Email (' . $userData['email'] . ') ini sudah digunakan oleh akun lain.'])->withInput();
        }


        $user = User::create($userData); // Pastikan 'name', 'email', 'password', 'role' ada di $fillable User model

        if ($user->role === 'dosen' && isset($validated['dosen_id'])) {
            $user->dosen_id = $validated['dosen_id']; // Pastikan kolom 'dosen_id' ada di tabel users
            $user->save();
        }

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        // Untuk form edit, Anda juga perlu logika yang sama jika ingin mengubah nama/dosen
        return view('users.edit', [
            'user' => $user,
            'dosens' => Dosen::orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'password' => 'nullable|string|min:6|confirmed', // Password opsional saat update
            'role' => 'required|in:admin,dosen',
        ];

        $updateData = []; // Data untuk diupdate

        if ($request->role === 'dosen') {
            $rules['dosen_id'] = 'required|exists:dosens,id';
            $rules['email'] = [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id) // Abaikan user saat ini
            ];
        } elseif ($request->role === 'admin') {
            $rules['name_admin'] = 'required|string|max:255';
            $rules['email'] = [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id)
            ];
        } else {
            return back()->withErrors(['role' => 'Role pengguna tidak valid.'])->withInput();
        }

        $validated = $request->validate($rules);

        $updateData['role'] = $validated['role'];

        if ($validated['role'] === 'dosen') {
            $dosen = Dosen::find($validated['dosen_id']);
            if ($dosen) {
                $updateData['name'] = $dosen->nama;
                $updateData['email'] = $dosen->email;
                $updateData['dosen_id'] = $validated['dosen_id'];
            } else {
                return back()->withErrors(['dosen_id' => 'Data dosen tidak ditemukan.'])->withInput();
            }
        } elseif ($validated['role'] === 'admin') {
            $updateData['name'] = $validated['name_admin'];
            $updateData['email'] = $validated['email'];
            $updateData['dosen_id'] = null; // Pastikan dosen_id null jika diubah ke admin
        }

        if (empty($updateData['name']) || empty($updateData['email'])) {
             return back()->withErrors(['msg' => 'Nama atau Email pengguna tidak dapat ditentukan.'])->withInput();
        }

        // Cek lagi unique email setelah mengambil email dari Dosen
        $existingUserByDefinitiveEmail = User::where('email', $updateData['email'])->where('id', '!=', $user->id)->first();
        if($existingUserByDefinitiveEmail){
            return back()->withErrors(['email' => 'Email (' . $updateData['email'] . ') ini sudah digunakan oleh akun lain.'])->withInput();
        }


        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData); // Pastikan atribut di $updateData fillable

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');
    }


    public function destroy(User $user)
    {
        // Tambahkan logika jika ada relasi yang perlu dihapus atau dicek sebelum delete user
        // Misalnya, jika user adalah dosen, apakah perlu unlink dari tabel lain?
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Akun berhasil dihapus!');
    }
}