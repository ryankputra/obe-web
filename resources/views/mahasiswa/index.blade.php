@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
<div class="container-fluid">
    <h1 class="dashboard-heading mt-4">Data Mahasiswa</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-end align-items-center">
            <a href="{{ route('mahasiswa.create') }}"
               class="btn btn-success rounded-circle me-2 d-flex justify-content-center align-items-center"
               style="width: 40px; height: 40px;">
                <i class="fas fa-plus text-white"></i>
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-filter me-2"></i> Filter Mahasiswa
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('mahasiswa.index') }}" class="row g-3">
                        <!-- Search Field -->
                        <div class="col-md-3">
                            <label for="search" class="form-label">Cari (NIM/Nama/Email)</label>
                            <input type="text" class="form-control" id="search" name="search"
                                   value="{{ request('search') }}" placeholder="Masukkan pencarian...">
                        </div>

                        <!-- Prodi Dropdown -->
                        <div class="col-md-3">
                            <label for="prodi" class="form-label">Program Studi</label>
                            <select class="form-control" id="prodi" name="prodi">
                                <option value="">Semua Prodi</option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi }}"
                                            {{ request('prodi') == $prodi ? 'selected' : '' }}>
                                        {{ $prodi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Angkatan Dropdown -->
                        <div class="col-md-3">
                            <label for="angkatan" class="form-label">Angkatan</label>
                            <select class="form-control" id="angkatan" name="angkatan">
                                <option value="">Semua Angkatan</option>
                                @foreach ($angkatans as $angkatan)
                                    <option value="{{ $angkatan }}"
                                            {{ request('angkatan') == $angkatan ? 'selected' : '' }}>
                                        {{ $angkatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Actiion Buttons -->
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search me-1"></i> Filter
                            </button>
                            <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">
                                <i class="fas fa-sync-alt me-1"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Mahasiswa Table -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Prodi</th>
                            <th>Angkatan</th>
                            <th>Alamat</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mahasiswas as $mhs)
                            <tr>
                                <td>{{ $mhs->nim }}</td>
                                <td>{{ $mhs->nama }}</td>
                                <td>{{ $mhs->jenis_kelamin }}</td>
                                <td>{{ $mhs->prodi->nama_prodi ?? '-' }}</td>
                                <td>{{ $mhs->angkatan }}</td>
                                <td>{{ $mhs->alamat }}</td>
                                <td>{{ $mhs->email }}</td>
                                <td>{{ $mhs->no_hp }}</td>
                                <td>
                                    <a href="{{ route('mahasiswa.edit', $mhs->nim) }}"
                                       class="btn btn-outline-success btn-sm">Edit</a>
                                    <form action="{{ route('mahasiswa.destroy', $mhs->nim) }}" method="POST"
                                          style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data mahasiswa ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination Controls -->
                <div class="d-flex justify-content-end align-items-center mt-3">
                    <button class="btn btn-primary me-2"
                            onclick="navigateToPage('{{ $mahasiswas->appends(request()->query())->previousPageUrl() }}')"
                            {{ $mahasiswas->onFirstPage() ? 'disabled' : '' }}>
                        <i class="fas fa-chevron-left"></i> Halaman Sebelumnya
                    </button>
                    <button class="btn btn-primary"
                            onclick="navigateToPage('{{ $mahasiswas->appends(request()->query())->nextPageUrl() }}')"
                            {{ $mahasiswas->hasMorePages() ? '' : 'disabled' }}>
                        Halaman Selanjutnya <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
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

        .table thead th {
            background-color: rgb(0, 114, 202) !important;
            color: white !important;
        }
    </style>
@endsection

@section('scripts')
    <script>
        function navigateToPage(url) {
            if (url) {
                window.location.href = url;
            }
        }

        // Auto-submit form when dropdowns change
        document.addEventListener('DOMContentLoaded', function() {
            const prodiSelect = document.getElementById('prodi');
            const angkatanSelect = document.getElementById('angkatan');

            if (prodiSelect) {
                prodiSelect.addEventListener('change', function() {
                    this.form.submit();
                });
            }

            if (angkatanSelect) {
                angkatanSelect.addEventListener('change', function() {
                    this.form.submit();
                });
            }
        });
    </script>
@endsection