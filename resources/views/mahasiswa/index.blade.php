@extends('layouts.app')

@section('title', 'Fakultas Sains dan Teknologi')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">Fakultas Sains dan Teknologi</h1>

    <!-- Baris Tombol Aksi -->
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <!-- Tombol Lihat Semua Data Mahasiswa di sebelah kiri -->
            <button class="btn btn-primary">
                <i class="fas fa-database"></i> Lihat Semua Data Mahasiswa
            </button>

            <!-- Tombol Tambah di sebelah kanan -->
            <a href="#" class="btn btn-success rounded-circle d-flex justify-content-center align-items-center"
                style="width: 40px; height: 40px;">
                <i class="fas fa-plus text-white"></i>
            </a>
        </div>
    </div>

    <!-- Tabel Prodi dan Jumlah Mahasiswa -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>Prodi</th>
                            <th>Jumlah Mahasiswa</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Informatika</td>
                            <td>50</td>
                            <td>
                                <a href="/informatika" class="btn btn-outline-primary btn-sm">Lihat</a>
                                <a href="#" class="btn btn-outline-success btn-sm">Edit</a>
                                <button class="btn btn-outline-danger btn-sm">Hapus</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Sistem Informasi</td>
                            <td>60</td>
                            <td>
                                <a href="/sistem-informasi" class="btn btn-outline-primary btn-sm">Lihat</a>
                                <a href="#" class="btn btn-outline-success btn-sm">Edit</a>
                                <button class="btn btn-outline-danger btn-sm">Hapus</button>
                            </td>
                        </tr>
                        <tr>
                            <td>RPL</td>
                            <td>55</td>
                            <td>
                                <a href="rpl" class="btn btn-outline-primary btn-sm">Lihat</a>
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

@section('scripts')
<script>
    console.log('Halaman berhasil dimuat!');
</script>
@endsection
