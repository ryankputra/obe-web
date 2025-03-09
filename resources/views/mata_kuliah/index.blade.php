@extends('layouts.app')

@section('title', 'Daftar Mata Kuliah')

@section('content')
<div class="container-fluid">
    <h1 class="dashboard-heading mt-4">Daftar Mata Kuliah</h1>
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-end align-items-center">
            <button class="btn btn-primary rounded-circle me-2 d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                <i class="fas fa-plus text-white"></i>
            </button>
            <button id="saveCourseBtn" class="btn btn-success rounded-circle d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                <i class="fas fa-save text-white"></i>
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="courseTable">
                    <thead>
                        <tr>
                            <th rowspan="2">Kode MK</th>
                            <th rowspan="2">Nama MK</th>
                            <th rowspan="2">Deskripsi</th>
                            <th rowspan="2">Semester</th>
                            <th colspan="2">SKS</th>
                            <th rowspan="2">Action</th>
                        </tr>
                        <tr>
                            <th>Teori</th>
                            <th>Praktik</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mataKuliahs as $mk)
                        <tr>
                            <td>{{ $mk['kode_mk'] }}</td>
                            <td class="text-start">{{ $mk['nama_mk'] }}</td>
                            <td>{{ $mk['deskripsi'] }}</td>
                            <td>{{ $mk['semester'] }}</td>
                            <td>{{ $mk['sks_teori'] }}</td>
                            <td>{{ $mk['sks_praktik'] }}</td>
                            <td>
                                <a href="#" class="btn btn-outline-success btn-sm edit-course" data-bs-toggle="modal" data-bs-target="#editCourseModal" data-kode="{{ $mk['kode_mk'] }}" data-nama="{{ $mk['nama_mk'] }}" data-deskripsi="{{ $mk['deskripsi'] }}" data-semester="{{ $mk['semester'] }}" data-teori="{{ $mk['sks_teori'] }}" data-praktik="{{ $mk['sks_praktik'] }}">Edit</a>
                                <a href="#" class="btn btn-outline-danger btn-sm delete-course" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" data-kode="{{ $mk['kode_mk'] }}">Hapus</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Include the Create Modal -->
@include('mata_kuliah.create')

<!-- Edit Mata Kuliah Modal -->
<div class="modal fade" id="editCourseModal" tabindex="-1" aria-labelledby="editCourseModalLabel" aria-hidden="true">
    <!-- Modal content here (same as before) -->
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <!-- Modal content here (same as before) -->
</div>

<!-- Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Operasi berhasil dilakukan!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    <style>
    body {
        background-color: #def4ff;
    }
    .dashboard-heading {
        font-size: 2rem;
        font-weight: bold;
        color: #333;
    }
    
    /* Warna header tabel */
    .table thead th {
        background-color: rgb(0, 114, 202) !important;
        color: white !important;
    }

    .table tbody tr:hover {
        background-color: #def4ff;
    }

    .table-bordered th,
    .table-bordered td {
        border: 2px solid #def4ff;
    }

    .table th {
        font-size: 16px;
        vertical-align: middle;
    }

    .table td {
        font-size: 14px;
        vertical-align: middle;
    }

    /* Rata kiri untuk kolom Nama MK */
    .text-start {
        text-align: left !important;
    }

    .btn-outline-success {
        border-color: #28a745;
        color: #28a745;
    }

    .btn-outline-danger {
        border-color: #dc3545;
        color: #dc3545;
    }

    .btn-primary {
        background-color: #007bff !important;
        border: none;
    }

    .btn-success {
        background-color: #28a745 !important;
        border: none;
    }
</style>
</style>
@endsection

@section('scripts')
<script>
    // Your JavaScript logic here
</script>
@endsection