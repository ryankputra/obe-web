@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Akun Pengguna</h2>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    value="{{ old('name', $user->name) }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                    name="email" value="{{ old('email', $user->email) }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                    name="password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-control @error('role') is-invalid @enderror" id="role" name="role">
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="dosen" {{ $user->role == 'dosen' ? 'selected' : '' }}>Dosen</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3" id="dosen-select-group" style="display: none;">
                <label for="dosen_id" class="form-label">Pilih Dosen</label>
                <select class="form-control @error('dosen_id') is-invalid @enderror" id="dosen_id" name="dosen_id">
                    <option value="">-- Pilih Dosen --</option>
                    @foreach ($dosens as $dosen)
                        <option value="{{ $dosen->id }}"
                            {{ old('dosen_id', $user->dosen_id) == $dosen->id ? 'selected' : '' }}>
                            {{ $dosen->nama }} ({{ $dosen->nidn }})
                        </option>
                    @endforeach
                </select>
                @error('dosen_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const dosenGroup = document.getElementById('dosen-select-group');

            function toggleDosenSelect() {
                if (roleSelect.value === 'dosen') {
                    dosenGroup.style.display = '';
                } else {
                    dosenGroup.style.display = 'none';
                }
            }

            roleSelect.addEventListener('change', toggleDosenSelect);

            toggleDosenSelect();
        });
    </script>
@endsection
