@extends('layouts.app')

@section('title', 'Mahasiswa - RPL')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">Mahasiswa - RPL</h1>

    <!-- Tombol Kembali dan Tambah -->
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <a href="/mahasiswa" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <a href="#" class="btn btn-success">
                <i class="fas fa-plus me-1"></i> Tambah Mahasiswa
            </a>
        </div>
    </div>

    <!-- Tabel Mahasiswa -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Tahun Masuk</th>
                            <th>Email</th>
                            <th>Kontak</th>
                            <th>Jenis Kelamin</th>
                            <th>IPK</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>301233</td>
                            <td>Pend 1</td>
                            <td>2020</td>
                            <td>pend1@rpl.com</td>
                            <td>08555</td>
                            <td>Laki-laki</td>
                            <td>3.2</td>
                            <td>
                                <a href="#" class="btn btn-outline-success btn-sm">Edit</a>
                                <button class="btn btn-outline-danger btn-sm">Hapus</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')


<style>
    body {
        background-color: #def4ff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    h1 {
        font-size: 1.5rem;
        font-weight: bold;
    }

    .btn-primary {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 5px;
        font-size: 0.9rem;
    }

    .btn-primary i {
        margin-right: 8px;
    }

    .btn-success {
        background-color: #28a745;
        color: #fff;
        border: none;
        font-size: 1.2rem;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    .table {
        background-color: #fff;
        border: 1px solid #dee2e6;
        margin-top: 20px;
    }

    .table thead th {
        background-color: #007bff;
        color: #fff;
        font-size: 0.9rem;
        padding: 10px;
    }

    .table tbody td {
        font-size: 0.9rem;
        padding: 8px;
    }

    .btn-outline-primary {
        border-color: #007bff;
        color: #007bff;
        padding: 4px 8px;
        border-radius: 5px;
        font-size: 0.8rem;
    }

    .btn-outline-primary:hover {
        background-color: #007bff;
        color: #fff;
    }

    .btn-outline-success {
        border-color: #28a745;
        color: #28a745;
        padding: 4px 8px;
        border-radius: 5px;
        font-size: 0.8rem;
    }

    .btn-outline-success:hover {
        background-color: #28a745;
        color: #fff;
    }

    .btn-outline-danger {
        border-color: #dc3545;
        color: #dc3545;
        padding: 4px 8px;
        border-radius: 5px;
        font-size: 0.8rem;
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: #fff;
    }
</style>
@endsection
