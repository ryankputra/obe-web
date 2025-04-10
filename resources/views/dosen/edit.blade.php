@extends('layouts.app')

@section('title', 'Edit Dosen')

@section('content')
    <div class="container-fluid">
        <h1 class="dashboard-heading mt-4">Edit Data Dosen</h1>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('dosen.update', $dosen->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="nidn" class="form-label">NIDN</label>
                                <input type="text" class="form-control" id="nidn" name="nidn"
                                    value="{{ $dosen->nidn }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Dosen</label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    value="{{ $dosen->nama }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="gelar" class="form-label">Gelar</label>
                                <input type="text" class="form-control" id="gelar" name="gelar" value="{{ $dosen->gelar }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select class="form-control text-start" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="Laki-laki" {{ $dosen->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ $dosen->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $dosen->email }}">
                            </div>
                            <div class="mb-3">
                                <label for="kontak" class="form-label">Kontak</label>
                                <input type="number" class="form-control" id="kontak" name="kontak" value="{{ $dosen->kontak }}">
                            </div>
                            <div class="mb-3">
                                <label for="jabatan" class="form-label">Jabatan</label>
                                <input type="text" class="form-control" id="jabatan" name="jabatan" value="{{ $dosen->jabatan }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="kompetensi" class="form-label">Kompetensi</label>
                                <input type="text" class="form-control" id="kompetensi" name="kompetensi" value="{{ $dosen->kompetensi }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="prodi" class="form-label">Prodi</label>
                                <select class="form-control text-start" id="prodi" name="prodi" required>
                                    <option value="Informatika" {{ $dosen->prodi == 'Informatika' ? 'selected' : '' }}>Informatika</option>
                                    <option value="Sistem Informasi" {{ $dosen->prodi == 'Sistem Informasi' ? 'selected' : '' }}>Sistem Informasi</option>
                                    <option value="Rekayasa Perangkat Lunak" {{ $dosen->prodi == 'Rekayasa Perangkat Lunak' ? 'selected' : '' }}>Rekayasa Perangkat Lunak</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="{{ route('dosen.index') }}" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
