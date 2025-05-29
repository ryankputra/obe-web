@extends('layouts.app')

@section('title', 'Edit Akun Pengguna')

@section('content')
<div class="container-fluid">
    <h1 class="dashboard-heading mt-4">Edit Akun Pengguna</h1>

    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Notifikasi Error Validasi Laravel --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Tombol Aksi (Kembali) --}}
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-end align-items-center">
            <a href="{{ route('users.index') }}"
               class="btn btn-secondary rounded-circle me-2 d-flex justify-content-center align-items-center"
               title="Kembali ke Daftar Pengguna"
               style="width: 40px; height: 40px;">
                <i class="fas fa-users text-white"></i> {{-- Ikon yang relevan untuk pengguna --}}
            </a>
        </div>
    </div>

    {{-- Form Edit Akun Pengguna --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-user-edit me-2"></i> Form Edit Akun Pengguna
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('users.update', $user->id) }}"> {{-- Pastikan $user->id jika $user adalah objek --}}
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation">
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div> {{-- Menambahkan pesan error untuk konfirmasi password --}}
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required> {{-- Menambahkan required jika role wajib --}}
                        <option value="">-- Pilih Role --</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="dosen" {{ old('role', $user->role) == 'dosen' ? 'selected' : '' }}>Dosen</option>
                        {{-- Tambahkan role lain jika ada --}}
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3" id="dosen-select-group" style="display: {{ old('role', $user->role) == 'dosen' ? 'block' : 'none' }};"> {{-- Menyesuaikan display awal berdasarkan old value atau data user --}}
                    <label for="dosen_id" class="form-label">Pilih Dosen (Jika Role Dosen)</label>
                    <select class="form-select @error('dosen_id') is-invalid @enderror" id="dosen_id" name="dosen_id">
                        <option value="">-- Pilih Dosen --</option>
                        @if(isset($dosens)) {{-- Memastikan variabel $dosens ada --}}
                            @foreach ($dosens as $dosen)
                                <option value="{{ $dosen->id }}"
                                    {{ old('dosen_id', $user->dosen_id) == $dosen->id ? 'selected' : '' }}>
                                    {{ $dosen->nama }} ({{ $dosen->nidn }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('dosen_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const dosenGroup = document.getElementById('dosen-select-group');
            const dosenSelect = document.getElementById('dosen_id');

            function toggleDosenSelect() {
                if (roleSelect.value === 'dosen') {
                    dosenGroup.style.display = 'block'; // Menggunakan block agar label juga terlihat
                    // dosenSelect.required = true; // Opsional: membuat field dosen_id wajib jika role dosen
                } else {
                    dosenGroup.style.display = 'none';
                    // dosenSelect.required = false; // Opsional
                    // dosenSelect.value = ''; // Opsional: reset pilihan dosen jika role bukan dosen
                }
            }

            // Panggil saat ada perubahan pada pilihan role
            if(roleSelect) {
                roleSelect.addEventListener('change', toggleDosenSelect);
            }

            // Panggil saat halaman dimuat untuk mengatur state awal
            toggleDosenSelect();
        });
    </script>
@endsection

@section('styles')
<style>
    body {
        background-color: #def4ff; /* Latar belakang biru muda */
        font-family: 'Inter', sans-serif; /* Font yang konsisten */
    }

    .dashboard-heading {
        font-size: 2rem; /* Judul lebih besar */
        font-weight: bold;
        color: #333; /* Teks lebih gelap untuk judul */
        margin-bottom: 1.5rem; /* Jarak di bawah judul */
    }

    .card-header {
        /* background-color: rgb(0, 114, 202); Sudah diatur oleh bg-primary */
        /* color: white; Sudah diatur oleh text-white */
        font-weight: 500; /* Berat font medium untuk header kartu */
    }

    .form-label {
        font-weight: 500; /* Label sedikit lebih tebal */
    }

    .btn-primary {
        background-color: rgb(0, 114, 202);
        border-color: rgb(0, 114, 202);
    }
    .btn-primary:hover {
        background-color: #005ea0;
        border-color: #005ea0;
    }
    .btn-secondary {
        /* Gaya sekunder Bootstrap standar atau tema kustom Anda */
    }
    .btn-secondary:hover {
        /* Gaya hover sekunder Bootstrap standar atau tema kustom Anda */
    }

    .form-control, .form-select {
        border-radius: 0.375rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0072ca;
        box-shadow: 0 0 0 0.25rem rgba(0, 114, 202, 0.25);
    }

    .is-invalid {
        border-color: #dc3545; /* Warna bahaya Bootstrap */
    }
    .invalid-feedback {
        display: block; /* Selalu tampilkan jika ada pesan error */
        width: 100%;
        margin-top: 0.25rem;
        font-size: .875em;
        color: #dc3545;
    }

    .btn.rounded-circle {
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    }
    .btn.rounded-circle:hover {
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
</style>
@endsection
