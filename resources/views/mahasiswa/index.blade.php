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
            <a href="{{ route('mahasiswa.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i> Tambah Mahasiswa
            </a>
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
@endsection