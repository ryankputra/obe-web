@extends('layouts.app')

@section('title', 'Edit Dosen')

@section('content')
    <div class="container-fluid">
        <h1 class="dashboard-heading mt-4">Edit Data Dosen</h1>

        {{-- Notifikasi Sukses --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Notifikasi Error Validasi Laravel --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Tombol Aksi (Kembali) --}}
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-end align-items-center">
                <a href="{{ route('dosen.index') }}"
                    class="btn btn-secondary rounded-circle me-2 d-flex justify-content-center align-items-center"
                    title="Kembali ke Daftar Dosen" style="width: 40px; height: 40px;">
                    <i class="fas fa-user-tie text-white"></i>
                </a>
            </div>
        </div>

        {{-- Form Edit Dosen --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-user-edit me-2"></i> Form Edit Data Dosen
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('dosen.update', $dosen->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nidn" class="form-label">NIDN</label>
                        <input type="text" class="form-control @error('nidn') is-invalid @enderror" id="nidn"
                            name="nidn" value="{{ old('nidn', $dosen->nidn) }}" required>
                        @error('nidn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Dosen</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                            name="nama" value="{{ old('nama', $dosen->nama) }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="gelar" class="form-label">Gelar</label>
                        <input type="text" class="form-control @error('gelar') is-invalid @enderror" id="gelar"
                            name="gelar" value="{{ old('gelar', $dosen->gelar) }}" required>
                        @error('gelar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin"
                            name="jenis_kelamin" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki"
                                {{ old('jenis_kelamin', $dosen->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>
                                Laki-laki</option>
                            <option value="Perempuan"
                                {{ old('jenis_kelamin', $dosen->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                                Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email', $dosen->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kontak" class="form-label">Kontak</label>
                        <input type="text" class="form-control @error('kontak') is-invalid @enderror" id="kontak"
                            name="kontak" value="{{ old('kontak', $dosen->kontak) }}">
                        @error('kontak')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan"
                            name="jabatan" value="{{ old('jabatan', $dosen->jabatan) }}" required>
                        @error('jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kompetensi" class="form-label">Kompetensi</label>
                        <input type="text" class="form-control @error('kompetensi') is-invalid @enderror" id="kompetensi"
                            name="kompetensi" value="{{ old('kompetensi', $dosen->kompetensi) }}" required>
                        @error('kompetensi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="prodi" class="form-label">Prodi</label>
                        <select class="form-select @error('prodi') is-invalid @enderror" id="prodi" name="prodi"
                            required>
                            <option value="">-- Pilih Prodi --</option>
                            @foreach ($prodis as $prodi)
                                <option value="{{ $prodi->nama_prodi }}"
                                    {{ old('prodi', $dosen->prodi) == $prodi->nama_prodi ? 'selected' : '' }}>
                                    {{ $prodi->nama_prodi }}
                                </option>
                            @endforeach
                        </select>
                        @error('prodi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('dosen.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
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
            /* Latar belakang biru muda */
            font-family: 'Inter', sans-serif;
            /* Font yang konsisten */
        }

        .dashboard-heading {
            font-size: 2rem;
            /* Judul lebih besar */
            font-weight: bold;
            color: #333;
            /* Teks lebih gelap untuk judul */
            margin-bottom: 1.5rem;
            /* Jarak di bawah judul */
        }

        .card-header {
            /* background-color: rgb(0, 114, 202); Sudah diatur oleh bg-primary */
            /* color: white; Sudah diatur oleh text-white */
            font-weight: 500;
            /* Berat font medium untuk header kartu */
        }

        .form-label {
            font-weight: 500;
            /* Label sedikit lebih tebal */
        }

        .btn-primary {
            background-color: rgb(0, 114, 202);
            border-color: rgb(0, 114, 202);
        }

        .btn-primary:hover {
            background-color: #005ea0;
            border-color: #005ea0;
        }

        .btn-secondary {
            /* Gaya sekunder Bootstrap standar atau tema kustom Anda */
        }

        .btn-secondary:hover {
            /* Gaya hover sekunder Bootstrap standar atau tema kustom Anda */
        }

        .form-control,
        .form-select {
            border-radius: 0.375rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0072ca;
            box-shadow: 0 0 0 0.25rem rgba(0, 114, 202, 0.25);
        }

        .is-invalid {
            border-color: #dc3545;
            /* Warna bahaya Bootstrap */
        }

        .invalid-feedback {
            display: block;
            /* Selalu tampilkan jika ada pesan error */
            width: 100%;
            margin-top: 0.25rem;
            font-size: .875em;
            color: #dc3545;
        }

        .btn.rounded-circle {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        .btn.rounded-circle:hover {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
@endsection
