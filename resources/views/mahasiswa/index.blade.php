@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">Data Mahasiswa</h1>

    <!-- Notifikasi Sukses -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <a href="/" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMahasiswaModal">
                <i class="fas fa-plus me-1"></i> Tambah Mahasiswa
            </button>
        </div>
    </div>

    <!-- Tabel Mahasiswa -->
    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Jenis Kelamin</th>
                    <th>Prodi</th>
                    <th>Alamat</th>
                    <th>Email</th>
                    <th>No HP</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswas as $mhs)
                <tr>
                    <td>{{ $mhs->nim }}</td>
                    <td>{{ $mhs->nama }}</td>
                    <td>{{ $mhs->jenis_kelamin }}</td>
                    <td>{{ $mhs->prodi->nama_prodi ?? '-' }}</td>
                    <td>{{ $mhs->alamat }}</td>
                    <td>{{ $mhs->email }}</td>
                    <td>{{ $mhs->no_hp }}</td>
                    <td>
                        <a href="{{ route('mahasiswa.edit', $mhs->nim) }}" class="btn btn-outline-success btn-sm">Edit</a>
                        <form action="{{ route('mahasiswa.destroy', $mhs->nim) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Mahasiswa -->
<div class="modal fade" id="addMahasiswaModal" tabindex="-1" aria-labelledby="addMahasiswaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('mahasiswa.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMahasiswaModalLabel">Tambah Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Tampilkan pesan error validasi jika ada --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label>NIM</label>
                        <input type="text" name="nim" class="form-control" required value="{{ old('nim') }}">
                    </div>
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required value="{{ old('nama') }}">
                    </div>
                    <div class="mb-3">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="">Pilih</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Prodi</label>
                        <select name="prodi_id" class="form-control" required>
                            <option value="">Pilih Prodi</option>
                            @foreach($prodis as $prodi)
                                <option value="{{ $prodi->id }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                    {{ $prodi->nama_prodi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Angkatan</label>
                        <input type="number" name="angkatan" class="form-control" min="2000" max="{{ date('Y') + 1 }}" required value="{{ old('angkatan') }}">
                    </div>
                    <div class="mb-3">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" required>{{ old('alamat') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label>No HP</label>
                        <input type="text" name="no_hp" class="form-control" required value="{{ old('no_hp') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
