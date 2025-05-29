@extends('layouts.app')

@section('title', 'Pilih Mata Kuliah')

@section('content')
<div class="container-fluid">
    {{-- Judul Halaman --}}
    <div class="mb-4">
        <h1 class="penilaian-heading mt-4">Pilih Mata Kuliah</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif {{-- Penutup untuk if session('success') --}}

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif {{-- Penutup untuk if session('error') --}}

    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0 text-center">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Kode MK</th>
                            <th class="text-start" style="width: 45%;">Nama Mata Kuliah</th>
                            <th style="width: 20%;">Jumlah Mahasiswa</th>
                            <th style="width: 20%;">SKS</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- AWAL DATA DUMMY --}}
                        <tr>
                            <td class="align-middle">MK - 001</td>
                            <td class="align-middle text-start"><a href="#">Pemrograman Web</a></td>
                            <td class="align-middle">7</td>
                            <td class="align-middle">3</td>
                        </tr>
                        <tr>
                            <td class="align-middle">MK - 002</td>
                            <td class="align-middle text-start"><a href="#">Pemrograman Mobile</a></td>
                            <td class="align-middle">9</td>
                            <td class="align-middle">4</td>
                        </tr>
                        <tr>
                            <td class="align-middle">MK - 003</td>
                            <td class="align-middle text-start"><a href="#">Pembelajaran Mesin</a></td>
                            <td class="align-middle">12</td>
                            <td class="align-middle">3</td>
                        </tr>
                        <tr>
                            <td class="align-middle">MK - 004</td>
                            <td class="align-middle text-start"><a href="#">Pancasila</a></td>
                            <td class="align-middle">25</td>
                            <td class="align-middle">3</td>
                        </tr>
                        <tr>
                            <td class="align-middle">MK - 005</td>
                            <td class="align-middle text-start"><a href="#">Bahasa Indonesia</a></td>
                            <td class="align-middle">18</td>
                            <td class="align-middle">3</td>
                        </tr>
                        {{-- AKHIR DATA DUMMY --}}

                        {{-- AWAL BLOK DATA DINAMIS YANG DIKOMENTARI PENUH --}}
                        {{-- Pastikan seluruh blok ini tetap dalam komentar Blade jika Anda menggunakan data dummy di atas --}}
                        {{--
                        @if(isset($assignedCourses) && !$assignedCourses->isEmpty())
                            @foreach($assignedCourses as $course)
                                <tr>
                                    <td class="align-middle">{{ $course->kode_mk ?? 'N/A' }}</td>
                                    <td class="align-middle text-start">
                                        @if(isset($course->id))
                                        <a href="{{ route('penilaian.mata_kuliah.show', ['id_mata_kuliah' => $course->id]) }}">{{ $course->nama_mata_kuliah ?? 'N/A' }}</a>
                                        @else
                                        {{ $course->nama_mata_kuliah ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td class="align-middle">{{ $course->jumlah_mahasiswa ?? 0 }}</td>
                                    <td class="align-middle">{{ $course->sks ?? 0 }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <i class="fas fa-info-circle fa-2x text-muted mb-2"></i><br>
                                    Tidak ada mata kuliah yang tersedia.
                                </td>
                            </tr>
                        @endif
                        --}}
                        {{-- AKHIR BLOK DATA DINAMIS YANG DIKOMENTARI PENUH --}}
                    </tbody>
                </table>
            </div>
        </div>
        {{-- AWAL BLOK PAGINASI YANG DIKOMENTARI PENUH --}}
        {{--
        @if(isset($assignedCourses) && $assignedCourses instanceof \Illuminate\Pagination\AbstractPaginator && $assignedCourses->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $assignedCourses->links('vendor.pagination.bootstrap-5') }}
            </div>
        @endif
        --}}
        {{-- AKHIR BLOK PAGINASI YANG DIKOMENTARI PENUH --}}
    </div>
</div>
@endsection {{-- Penutup untuk section('content') --}}

@section('styles')
<style>
    body {
        background-color: #def4ff;
    }

    .penilaian-heading {
        font-size: 2rem;
        font-weight: bold;
        color: #545CD8;
    }

    .table thead th {
        background-color: rgb(0, 114, 202) !important;
        color: white !important;
        vertical-align: middle;
        padding: 0.9rem 0.75rem;
        font-size: 0.9rem;
        font-weight: bold;
    }

    .table th.text-start, /* Untuk header */
    .table td.text-start { /* Untuk sel data */
        text-align: left !important;
    }

    .table tbody td {
        vertical-align: middle;
        padding: 0.8rem 0.75rem;
        font-size: 0.875rem;
        color: #5a5c69;
    }
    .table.table-bordered th,
    .table.table-bordered td {
        border: 1px solid #dee2e6;
    }

    .table tbody td a {
        color: #0d6efd;
        text-decoration: none;
    }
    .table tbody td a:hover {
        text-decoration: underline;
        color: #0a58ca;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fc;
    }

    .card.shadow {
        border: none;
    }
    .card-body.p-0 .table-responsive {
        overflow-x: auto;
    }
    .card-footer.bg-white{
        border-top:1px solid #e3e6f0;
    }

    .pagination {
        margin-bottom: 0;
        justify-content: flex-end;
    }
    .page-item.active .page-link {
        background-color: rgb(0, 114, 202);
        border-color: rgb(0, 114, 202);
        color: white;
    }
    .page-link {
        color: rgb(0, 114, 202);
    }
    .page-link:hover {
        color: #004275;
    }
</style>
@endsection {{-- Penutup untuk section('styles') --}}

@push('scripts')
<script>
    // Script JavaScript jika diperlukan
</script>
@endpush {{-- Penutup untuk push('scripts') --}}