@extends('layouts.app')

@section('title', 'Tambah Akun Pengguna')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4 text-purple">Tambah Akun Pengguna</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form action="{{ route('users.store') }}" method="POST" class="p-4 shadow-sm rounded bg-white">
            @csrf

            <div class="mb-3">
                <label for="role" class="form-label fw-bold">Role</label>
                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3" id="admin-name-group" style="display: {{ old('role') == 'admin' ? 'block' : 'none' }};">
                <label for="name_admin" class="form-label fw-bold">Nama Admin</label>
                <input type="text" class="form-control @error('name_admin') is-invalid @enderror" id="name_admin" name="name_admin"
                    value="{{ old('name_admin') }}" placeholder="Masukkan nama lengkap admin">
                @error('name_admin')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3" id="dosen-select-group" style="display: {{ old('role') == 'dosen' ? 'block' : 'none' }};">
                <label for="dosen_id" class="form-label fw-bold">Pilih Dosen</label>
                <select class="form-select @error('dosen_id') is-invalid @enderror" id="dosen_id" name="dosen_id">
                    <option value="">-- Pilih Dosen --</option>
                    {{-- Asumsi $dosens memiliki properti 'id', 'nama', 'nidn', dan 'email' --}}
                    @foreach ($dosens as $dosen)
                        <option value="{{ $dosen->id }}" data-email="{{ $dosen->email }}" {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}>
                            {{ $dosen->nama }} ({{ $dosen->nidn }})
                        </option>
                    @endforeach
                </select>
                @error('dosen_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                    name="email" value="{{ old('email') }}" placeholder="contoh@email.com" {{ old('role') == 'dosen' ? 'readonly' : '' }}>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-bold">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password" placeholder="Minimal 6 karakter">
                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordButton">
                        <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                        placeholder="Ulangi password">
                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirmationButton">
                        <i class="bi bi-eye-slash" id="togglePasswordConfirmationIcon"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('users.index') }}" class="btn btn-secondary me-2">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
@endsection

@section('styles')
    {{-- PASTIKAN BARIS INI TIDAK DIKOMENTARI AGAR IKON MATA MUNCUL --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
        .form-control,
        .form-select {
            border-radius: 0.375rem;
        }
        .btn-primary {
            background-color: #2f5f98;
            border-color: #2f5f98;
        }
        .btn-primary:hover {
            background-color: #254d7b;
        }
        .input-group .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
        .input-group .form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .input-group .form-control:not(:focus).is-invalid {
            border-right: 1px solid #dee2e6;
        }
        .input-group .btn:has(+ .is-invalid) {
             border-color: #dc3545;
        }
        .input-group .form-control.is-invalid + .btn {
            border-left-color: #dc3545;
        }
        #togglePasswordButton, #togglePasswordConfirmationButton {
            cursor: pointer;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const dosenGroup = document.getElementById('dosen-select-group');
            const dosenSelect = document.getElementById('dosen_id');
            const emailInput = document.getElementById('email');
            const adminNameGroup = document.getElementById('admin-name-group');

            function handleRoleChange() {
                const selectedRole = roleSelect.value;
                const oldEmail = "{{ old('email', '') }}"; // Simpan old email awal
                const oldDosenId = "{{ old('dosen_id', '') }}";

                if (selectedRole === 'dosen') {
                    dosenGroup.style.display = 'block';
                    adminNameGroup.style.display = 'none';
                    emailInput.setAttribute('readonly', true);

                    // Jika ada old_dosen_id, coba isi email berdasarkan itu
                    if (dosenSelect.value) { // Jika sudah ada value (misal dari old())
                         const selectedDosenOption = dosenSelect.options[dosenSelect.selectedIndex];
                         if (selectedDosenOption && selectedDosenOption.dataset.email) {
                            emailInput.value = selectedDosenOption.dataset.email;
                         } else {
                            emailInput.value = ''; // Jika tidak ada data email, kosongkan
                         }
                    } else {
                        emailInput.value = ''; // Jika tidak ada dosen dipilih, email kosong
                    }

                } else if (selectedRole === 'admin') {
                    dosenGroup.style.display = 'none';
                    dosenSelect.value = ''; // Reset pilihan dosen
                    adminNameGroup.style.display = 'block';
                    emailInput.removeAttribute('readonly');
                    emailInput.value = oldEmail; // Kembalikan ke old email jika ada (untuk admin)
                } else { // Jika role belum dipilih atau role lain
                    dosenGroup.style.display = 'none';
                    dosenSelect.value = '';
                    adminNameGroup.style.display = 'none';
                    emailInput.removeAttribute('readonly');
                    emailInput.value = oldEmail;
                }
            }

            roleSelect.addEventListener('change', handleRoleChange);

            dosenSelect.addEventListener('change', function() {
                if (roleSelect.value === 'dosen') {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption && selectedOption.dataset.email) {
                        emailInput.value = selectedOption.dataset.email;
                    } else {
                        emailInput.value = '';
                    }
                }
            });

            // Panggil saat load untuk initial state berdasarkan old('role')
            handleRoleChange();


            // Fungsi untuk mengatur visibilitas password (Hold to See)
            function setupPasswordVisibility(inputId, buttonId, iconId) {
                const passwordInput = document.getElementById(inputId);
                const toggleButton = document.getElementById(buttonId);
                const icon = document.getElementById(iconId);

                if (!passwordInput || !toggleButton || !icon) return;

                const showPassword = () => {
                    passwordInput.setAttribute('type', 'text');
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                };

                const hidePassword = () => {
                    passwordInput.setAttribute('type', 'password');
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                };

                toggleButton.addEventListener('mousedown', showPassword);
                toggleButton.addEventListener('mouseup', hidePassword);
                toggleButton.addEventListener('mouseleave', hidePassword);

                toggleButton.addEventListener('touchstart', function(e) {
                    e.preventDefault();
                    showPassword();
                });
                toggleButton.addEventListener('touchend', hidePassword);
                toggleButton.addEventListener('touchcancel', hidePassword);
            }

            setupPasswordVisibility('password', 'togglePasswordButton', 'togglePasswordIcon');
            setupPasswordVisibility('password_confirmation', 'togglePasswordConfirmationButton', 'togglePasswordConfirmationIcon');
        });
    </script>
@endsection