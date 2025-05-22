@extends('layouts.app')

@section('title', 'Daftar Mata Kuliah')

@section('content')
    <div class="container-fluid">
        <h1 class="dashboard-heading mt-4">Daftar Mata Kuliah</h1>
        
        <!-- Action Buttons -->
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-end align-items-center">
                <a href="{{ route('mata_kuliah.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i> Tambah Mata Kuliah
                </a>
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="{{ route('mata_kuliah.index') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="search">Cari Mata Kuliah</label>
                                        <input type="text" name="search" class="form-control" 
                                            placeholder="Kode atau Nama MK" value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="semester">Filter Semester</label>
                                        <select class="form-control" name="semester">
                                            <option value="">Semua Semester</option>
                                            @for($i = 1; $i <= 8; $i++)
                                                <option value="{{ $i }}" {{ request('semester') == $i ? 'selected' : '' }}>
                                                    Semester {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="status">Filter Status</label>
                                        <select class="form-control" name="status">
                                            <option value="">Semua Status</option>
                                            <option value="Wajib Prodi" {{ request('status') == 'Wajib Prodi' ? 'selected' : '' }}>Wajib Prodi</option>
                                            <option value="Pilihan" {{ request('status') == 'Pilihan' ? 'selected' : '' }}>Pilihan</option>
                                            <option value="Wajib Fakultas" {{ request('status') == 'Wajib Fakultas' ? 'selected' : '' }}>Wajib Fakultas</option>
                                            <option value="Wajib Universitas" {{ request('status') == 'Wajib Universitas' ? 'selected' : '' }}>Wajib Universitas</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                                    <a href="{{ route('mata_kuliah.index') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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
                                <th rowspan="2">Status Mata Kuliah</th>
                                <th rowspan="2">Action</th>
                            </tr>
                            <tr>
                                <th>Teori</th>
                                <th>Praktik</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mataKuliahs as $mk)
                                <tr>
                                    <td>{{ $mk->kode_mk }}</td>
                                    <td class="text-start">{{ $mk->nama_mk }}</td>
                                    <td>{{ $mk->deskripsi }}</td>
                                    <td>{{ $mk->semester }}</td>
                                    <td>{{ $mk->sks_teori }}</td>
                                    <td>{{ $mk->sks_praktik }}</td>
                                    <td>{{ $mk->status_mata_kuliah }}</td>
                                    <td>
                                        <a href="{{ route('mata_kuliah.edit', $mk->kode_mk) }}" class="btn btn-outline-success btn-sm">
                                            Edit
                                        </a>
                                        <form action="{{ route('mata_kuliah.destroy', $mk->kode_mk) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data mata kuliah</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $mataKuliahs->links() }}
                    </div>
                    
                    <div class="d-flex justify-content-start mt-3">
                        <div style="border: 2px solid #000; background-color: white; padding: 10px 20px; font-weight: bold;">
                            Total SKS: {{ $totalSKS }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="toast align-items-center text-white bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('styles')
    <style>
        body {
            background-color: #def4ff !important;
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
            background-color: rgb(40, 187, 72) !important;
            border: none;
        }

        .btn-success {
            background-color: #28a745 !important;
            border: none;
        }
        
        .card {
            border-radius: 10px;
        }
        
        .form-control {
            border-radius: 5px;
        }
        
        /* Pagination styling */
        .pagination .page-item.active .page-link {
            background-color: rgb(0, 114, 202);
            border-color: rgb(0, 114, 202);
        }
        
        .pagination .page-link {
            color: rgb(0, 114, 202);
        }
    </style>
@endsection