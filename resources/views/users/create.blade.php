@extends('layouts.app')

@section('title', 'Tambah Akun Pengguna')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-purple">Tambah Akun Pengguna</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('users.store') }}" method="POST" class="p-4 shadow-sm rounded bg-white">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label fw-bold">Nama</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label fw-bold">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                id="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-bold">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                id="password" name="password" placeholder="Minimal 6 karakter">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password</label>
            <input type="password" class="form-control"
                id="password_confirmation" name="password_confirmation" placeholder="Ulangi password">
        </div>

        <div class="mb-3">
            <label for="role" class="form-label fw-bold">Role</label>
            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
                <option value="">-- Pilih Role --</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
            </select>
            @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('users.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection

@section('styles')
    <style>
        body {
            background-color: #def4ff;
        }

        .text-purple {
            color: #2f5f98;
        }

        .form-label {
            color: #2f5f98;
        }

        .form-control, .form-select {
            border-radius: 0.375rem;
        }

        .btn-primary {
            background-color: #2f5f98;
            border-color: #2f5f98;
        }

        .btn-primary:hover {
            background-color: #254d7b;
        }
    </style>
@endsection
