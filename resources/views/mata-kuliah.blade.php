@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Mata Kuliah</h1>
        <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
    </div>

    <div class="mb-4">
        <h2>Dosen</h2>
        <h3>Mahasiswa</h3>
        <h4>CPL</h4>
        <h4>CPMK</h4>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode MK</th>
                <th>Nama MK</th>
                <th>SKS</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mataKuliah as $mk)
                <tr>
                    <td>{{ $mk['kode_mk'] }}</td>
                    <td>{{ $mk['nama_mk'] }}</td>
                    <td>{{ $mk['sks'] }}</td>
                    <td>
                        <a href="{{ route('mata-kuliah.edit', $mk['id']) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('mata-kuliah.destroy', $mk['id']) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('mata-kuliah.create') }}" class="btn btn-primary">Tambah Mata Kuliah</a>
</div>
@endsection