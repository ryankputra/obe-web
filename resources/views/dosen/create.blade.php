@extends('layouts.app')

@section('title', 'Tambah Dosen Baru')

@section('content')
<div class="container-fluid">
    <h1 class="dashboard-heading mt-4">Tambah Dosen Baru</h1>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('dosen.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="nidn" class="form-label">NIDN</label>
                            <input type="text" class="form-control" id="nidn" name="nidn" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Dosen</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                        </div>
                        <div class="mb-3">
                            <label for="kompetensi" class="form-label">Kompetensi</label>
                            <input type="text" class="form-control" id="kompetensi" name="kompetensi" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('dosen.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection