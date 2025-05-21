@extends('layouts.app')

@section('title', 'Tambah Mahasiswa')

@section('content')
<div class="container-fluid">
    <h1 class="dashboard-heading mt-4">Tambah Mahasiswa</h1>

    <!-- Notifiikasi Sukses -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Notifiikasi Error -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Back Button -->
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-end align-items-center">
            <a href="{{ route('mahasiswa.index') }}"
               class="btn btn-secondary rounded-circle me-2 d-flex justify-content-center align-items-center"
               style="width: 40px; height: 40px;">
                <i class="fas fa-arrow-left text-white"></i>
            </a>
        </div>
    </div>

    <!-- Form Tambah Mahasiswa -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-user-plus me-2"></i> Form Input Mahasiswa
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('mahasiswa.store') }}" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label for="nim" class="form-label">NIM</label>
                    <input type="text" name="nim" id="nim" class="form-control" required value="{{ old('nim') }}">
                </div>
                <div class="col-md-6">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" name="nama" id="nama" class="form-control" required value="{{ old('nama') }}">
                </div>
                <div class="col-md-6">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                        <option value="">Pilih</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="prodi_id" class="form-label">Prodi</label>
                    <select name="prodi_id" id="prodi_id" class="form-control" required>
                        <option value="">Pilih Prodi</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="angkatan" class="form-label">Angkatan</label>
                    <input type="number" name="angkatan" id="angkatan" class="form-control" min="2000" max="{{ date('Y') + 1 }}" required value="{{ old('angkatan') }}">
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required value="{{ old('email') }}">
                </div>
                <div class="col-md-6">
                    <label for="no_hp" class="form-label">No HP</label>
                    <input type="text" name="no_hp" id="no_hp" class="form-control" required value="{{ old('no_hp') }}">
                </div>
                <div class="col-md-12">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" required>{{ old('alamat') }}</textarea>
                </div>
                <div class="col-12 d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                    <button type="submit" name="continue" value="1" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Simpan dan Tambah Lagi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    body {
        background-color: #def4ff;
    }

    .dashboard-heading {
        font-size: 2rem;
        font-weight: bold;
        color: #333;
    }

    .card-header {
        background-color: rgb(0, 114, 202);
        color: white;
    }
</style>
@endsection
