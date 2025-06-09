
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
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        padding: 32px 32px 24px 32px;
    }
    .profile-avatar {
    width: 100px; /* Perbesar sedikit untuk gambar */
    height: 100px; /* Perbesar sedikit untuk gambar */
        border-radius: 50%;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
    font-size: 3.5rem; /* Sesuaikan jika masih menggunakan ikon */
        margin: 0 auto 10px auto;
        color: #222;
    overflow: hidden; /* Untuk memastikan gambar tidak keluar dari lingkaran */
    border: 3px solid #3a2bb7; /* Tambahkan border jika diinginkan */
}
.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Memastikan gambar mengisi area tanpa distorsi */
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
</style>
@endsection

@section('content')
<div class="profile-card">
    <div class="profile-avatar">
        @if ($user->avatar)
            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar Pengguna">
        @else
            <i class="fas fa-user"></i> {{-- Ikon default jika tidak ada avatar --}}
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

        <label class="form-label">Ganti Foto Profil:</label>
        <input type="file" class="form-control" name="avatar" accept="image/*">

        <label class="form-label">Email :</label>
        <input type="email" class="form-control" name="email" value="{{ old('email', $user->email ?? '') }}" required>

        <label class="form-label">Tempat Lahir :</label>
        <input type="text" class="form-control" name="tempat_lahir" value="{{ old('tempat_lahir', optional($user->dosen)->tempat_lahir ?? '') }}">

        <label class="form-label">Tanggal Lahir :</label>
        <input type="date" class="form-control" name="tanggal_lahir" value="{{ old('tanggal_lahir', optional($user->dosen)->tanggal_lahir ?? '') }}">

        <label class="form-label">Agama :</label>
        <select class="form-control" name="agama">
            <option value="">Pilih Agama</option>
            <option value="islam" {{ old('agama', optional($user->dosen)->agama ?? '') == 'islam' ? 'selected' : '' }}>Islam</option>
            <option value="kristen" {{ old('agama', optional($user->dosen)->agama ?? '') == 'kristen' ? 'selected' : '' }}>Kristen</option>
            <option value="katolik" {{ old('agama', optional($user->dosen)->agama ?? '') == 'katolik' ? 'selected' : '' }}>Katolik</option>
            <option value="hindu" {{ old('agama', optional($user->dosen)->agama ?? '') == 'hindu' ? 'selected' : '' }}>Hindu</option>
            <option value="budha" {{ old('agama', optional($user->dosen)->agama ?? '') == 'budha' ? 'selected' : '' }}>Budha</option>
            <option value="lainnya" {{ old('agama', optional($user->dosen)->agama ?? '') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
        </select>

        <label class="form-label">Kewarganegaraan :</label>
        <input type="text" class="form-control" name="kewarganegaraan" value="{{ old('kewarganegaraan', optional($user->dosen)->kewarganegaraan ?? '') }}">

        <label class="form-label">Password :</label>
        <input type="password" class="form-control" name="password">

        <label class="form-label">Konfirmasi Password :</label>
        <input type="password" class="form-control" name="password_confirmation">
        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>

        <button type="submit" class="btn-simpan">SIMPAN</button>
    </form>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush