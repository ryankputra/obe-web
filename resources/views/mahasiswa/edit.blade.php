@extends('layouts.app')

@section('title', 'Edit Mahasiswa')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">Edit Mahasiswa</h1>

    <form method="POST" action="{{ route('mahasiswa.update', $mahasiswa->nim) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nim">NIM</label>
            <input type="text" name="nim" class="form-control" value="{{ $mahasiswa->nim }}" readonly>
        </div>

        <div class="mb-3">
            <label for="nama">Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ $mahasiswa->nama }}" required>
        </div>

        <div class="mb-3">
            <label for="jenis_kelamin">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-control" required>
                <option value="L" {{ $mahasiswa->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ $mahasiswa->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="prodi_id">Prodi</label>
            <select name="prodi_id" class="form-control" required>
                @foreach($prodis as $prodi)
                    <option value="{{ $prodi->id }}" {{ $mahasiswa->prodi_id == $prodi->id ? 'selected' : '' }}>
                        {{ $prodi->nama_prodi }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="angkatan">Angkatan</label>
            <input type="number" name="angkatan" class="form-control" value="{{ $mahasiswa->angkatan }}" required>
        </div>

        <div class="mb-3">
            <label for="alamat">Alamat</label>
            <textarea name="alamat" class="form-control" required>{{ $mahasiswa->alamat }}</textarea>
        </div>

        <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $mahasiswa->email }}" required>
        </div>

        <div class="mb-3">
            <label for="no_hp">No HP</label>
            <input type="text" name="no_hp" class="form-control" value="{{ $mahasiswa->no_hp }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
