@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Daftar Mahasiswa</h1>

    {{-- Filter --}}
    <form method="GET" action="{{ route('mahasiswa.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="search" class="form-label">Cari</label>
            <input type="text" class="form-control" id="search" name="search" placeholder="Nama, NIM, atau Email" value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <label for="prodi" class="form-label">Program Studi</label>
            <select class="form-control" id="prodi" name="prodi">
                <option value="">Semua Prodi</option>
                @foreach ($prodis as $prodi)
                    <option value="{{ $prodi }}" {{ request('prodi') == $prodi ? 'selected' : '' }}>
                        {{ $prodi }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label for="angkatan" class="form-label">Angkatan</label>
            <select class="form-control" id="angkatan" name="angkatan">
                <option value="">Semua Angkatan</option>
                @foreach ($angkatans as $angkatan)
                    <option value="{{ $angkatan }}" {{ request('angkatan') == $angkatan ? 'selected' : '' }}>
                        {{ $angkatan }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Terapkan Filter</button>
        </div>
    </form>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel Mahasiswa --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Program Studi</th>
                    <th>Angkatan</th>
                    <th>Email</th>
                    <th>No. HP</th>
                    <th>Jenis Kelamin</th>
                    <th>Alamat</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($mahasiswas as $mhs)
                    <tr>
                        <td>{{ $mhs->nim }}</td>
                        <td>{{ $mhs->nama }}</td>
                        <td>{{ $mhs->prodi->nama_prodi ?? '-' }}</td>
                        <td>{{ $mhs->angkatan }}</td>
                        <td>{{ $mhs->email }}</td>
                        <td>{{ $mhs->no_hp }}</td>
                        <td>{{ $mhs->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td>{{ $mhs->alamat }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Data mahasiswa tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $mahasiswas->withQueryString()->links() }}
    </div>
</div>
@endsection
