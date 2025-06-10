@extends('layouts.app')

@section('title', 'Profil')

@section('styles')
    <style>
        body {
            background-color: #e6f0ff;
        }

        .profile-card {
            max-width: 430px;
            margin: 40px auto;
            background: #e6f0ff;
            border-radius: 18px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            padding: 32px 32px 24px 32px;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px auto;
            overflow: hidden;
            border: 3px solid #3a2bb7;
            position: relative;
        }

        .profile-avatar img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-avatar i {
            font-size: 3.5rem;
            color: #222;
        }

        .profile-title {
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 0;
            color: #3a2bb7;
        }

        .profile-title span {
            font-weight: normal;
            color: #aaa;
            font-style: italic;
        }

        .profile-nidn {
            text-align: center;
            color: #888;
            font-size: 1.1rem;
            margin-bottom: 18px;
            font-style: italic;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 4px;
        }

        .form-control {
            border-radius: 12px;
            margin-bottom: 10px;
            font-size: 1rem;
        }

        .btn-simpan {
            width: 100%;
            background: #4be11a;
            color: #fff;
            font-weight: bold;
            border-radius: 12px;
            border: none;
            padding: 10px 0;
            font-size: 1.1rem;
            margin-top: 10px;
            transition: background 0.2s;
        }

        .btn-simpan:hover {
            background: #38b80f;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background-color: #ffe6e6;
            border: 1px solid #ffcccc;
            color: #cc0000;
        }

        .alert-success {
            background-color: #e6ffe6;
            border: 1px solid #ccffcc;
            color: #006600;
        }

        .alert-dismissible {
            position: relative;
            padding-right: 3rem;
        }

        .alert .btn-close {
            position: absolute;
            top: 0.75rem;
            right: 1rem;
            color: inherit;
        }

        /* Animation for alerts */
        .alert.fade {
            transition: opacity 0.15s linear;
        }

        .alert.fade.show {
            opacity: 1;
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.875em;
            margin-top: -8px;
            margin-bottom: 8px;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .fallback-avatar {
            content: url('{{ asset('images/default-avatar.png') }}');
            object-fit: cover;
        }
    </style>
@endsection

@section('content')
    <div class="profile-card">
        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Error Message --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- CSRF Error Message --}}
        @if (session('csrf_error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                Sesi Anda telah berakhir. Silakan muat ulang halaman dan coba lagi.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Existing validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="profile-avatar">
            @if ($user->avatar && Storage::disk('public')->exists($user->avatar))
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}'s Avatar"
                    onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.png') }}'; this.classList.add('fallback-avatar');">
            @else
                <i class="fas fa-user"></i>
            @endif
        </div>
        <div class="profile-title">
            {{ $user->name ?? 'Nama Pengguna' }}
        </div>
        <div class="profile-nidn">
            @if ($user->role === 'dosen' && $user->dosen)
                {{ $user->dosen->nidn ?? 'NIDN Belum diatur' }} <span>(NIDN)</span>
            @elseif ($user->role === 'admin')
                <span>(Administrator)</span>
            @endif
        </div>
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Ganti Foto Profil:</label>
                <input type="file" class="form-control @error('avatar') is-invalid @enderror" name="avatar"
                    accept="image/*">
                @error('avatar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email :</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                    value="{{ old('email', $user->email ?? '') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password Baru :</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                <small class="form-text text-muted">Password minimal 6 karakter. Kosongkan jika tidak ingin mengubah
                    password.</small>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Konfirmasi Password :</label>
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                    name="password_confirmation">
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <label class="form-label">Tempat Lahir :</label>
            <input type="text" class="form-control" name="tempat_lahir"
                value="{{ old('tempat_lahir', optional($user->dosen)->tempat_lahir ?? '') }}">

            <label class="form-label">Tanggal Lahir :</label>
            <input type="date" class="form-control" name="tanggal_lahir"
                value="{{ old('tanggal_lahir', optional($user->dosen)->tanggal_lahir ?? '') }}">

            <label class="form-label">Agama :</label>
            <select class="form-control" name="agama">
                <option value="">Pilih Agama</option>
                <option value="islam"
                    {{ old('agama', optional($user->dosen)->agama ?? '') == 'islam' ? 'selected' : '' }}>
                    Islam</option>
                <option value="kristen"
                    {{ old('agama', optional($user->dosen)->agama ?? '') == 'kristen' ? 'selected' : '' }}>Kristen</option>
                <option value="katolik"
                    {{ old('agama', optional($user->dosen)->agama ?? '') == 'katolik' ? 'selected' : '' }}>Katolik</option>
                <option value="hindu"
                    {{ old('agama', optional($user->dosen)->agama ?? '') == 'hindu' ? 'selected' : '' }}>Hindu</option>
                <option value="budha"
                    {{ old('agama', optional($user->dosen)->agama ?? '') == 'budha' ? 'selected' : '' }}>Budha</option>
                <option value="lainnya"
                    {{ old('agama', optional($user->dosen)->agama ?? '') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>

            <label class="form-label">Kewarganegaraan :</label>
            <input type="text" class="form-control" name="kewarganegaraan"
                value="{{ old('kewarganegaraan', optional($user->dosen)->kewarganegaraan ?? '') }}">

            <button type="submit" class="btn-simpan" id="submitBtn">
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                SIMPAN
            </button>
        </form>
    </div>
@endsection

@push('scripts')
    {{-- Existing scripts --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector('form').addEventListener('submit', function() {
            const btn = document.querySelector('#submitBtn');
            btn.disabled = true;
            btn.querySelector('.spinner-border').classList.remove('d-none');
        });
    </script>
    <script>
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const password = form.querySelector('input[name="password"]');
            const confirmation = form.querySelector('input[name="password_confirmation"]');

            if (password.value && password.value.length < 6) {
                e.preventDefault();
                alert('Password minimal harus 6 karakter');
                return false;
            }

            if (password.value !== confirmation.value) {
                e.preventDefault();
                alert('Konfirmasi password tidak cocok');
                return false;
            }
        });
    </script>
@endpush
