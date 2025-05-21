@extends('layouts.app')

@section('title', 'Edit Mahasiswa')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">Edit Mahasiswa</h1>

    <!-- Notifikassi Eerror -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Form Edit Mahasiswa -->
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('mahasiswa.update', $mahasiswa->nim) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label>NIM</label>
                    <input type="text" name="nim" class="form-control" value="{{ old('nim', $mahasiswa->nim) }}" readonly>
                </div>
                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" required value="{{ old('nama', $mahasiswa->nama) }}">
                </div>
                <div class="mb-3">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="">Pilih</option>
                        <option value="L" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Prodi</label>
                    <select name="prodi_id" class="form-control" required>
                        <option value="">Pilih Prodi</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ old('prodi_id', $mahasiswa->prodi_id) == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label>Angkatan</label>
                    <input type="number" name="angkatan" class="form-control" min="2000" max="{{ date('Y') + 1 }}" required value="{{ old('angkatan', $mahasiswa->angkatan) }}">
                </div>
                <div class="mb-3">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" required>{{ old('alamat', $mahasiswa->alamat) }}</textarea>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required value="{{ old('email', $mahasiswa->email) }}">
                </div>
                <div class="mb-3">
                    <label>No HP</label>
                    <input type="text" name="no_hp" class="form-control" required value="{{ old('no_hp', $mahasiswa->no_hp) }}">
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection