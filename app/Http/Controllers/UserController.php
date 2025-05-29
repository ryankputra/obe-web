<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function create()
    {
        return view('users.create', [
            'dosens' => \App\Models\Dosen::all()
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,dosen',
        ];

        if ($request->role === 'dosen') {
            $rules['dosen_id'] = 'required|exists:dosens,id';
        }

        $validated = $request->validate($rules);

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
        ]);

        // Assign dosen_id if role is dosen
        if ($user->role === 'dosen') {
            $user->dosen_id = $validated['dosen_id'];
            $user->save();
        }

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('users.edit', [
            'user' => $user,
            'dosens' => \App\Models\Dosen::all(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,dosen',
            'password' => 'nullable|string|min:6|confirmed',
        ];

        if ($request->role === 'dosen') {
            $rules['dosen_id'] = 'required|exists:dosens,id';
        }

        $validated = $request->validate($rules);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        if ($request->role === 'dosen') {
            $user->dosen_id = $validated['dosen_id'];
        } else {
            $user->dosen_id = null;
        }
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }
        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Akun berhasil dihapus!');
    }
}
