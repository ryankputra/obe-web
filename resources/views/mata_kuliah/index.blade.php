@extends('layouts.app')

@section('title', 'Daftar Mata Kuliah')

@section('content')
    <div class="container-fluid">
        <h1 class="dashboard-heading mt-4">Daftar Mata Kuliah</h1>

        <!-- Action Buttons -->
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-end align-items-center">
                <a href="{{ route('mata_kuliah.create') }}"
                    class="btn btn-success rounded-circle me-2 d-flex justify-content-center align-items-center"
                    style="width: 40px; height: 40px;">
                    <i class="fas fa-plus text-white"></i>
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-filter me-2"></i>Filter Mata Kuliah
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('mata_kuliah.index') }}" class="row g-3">
                            <!-- Search Field -->
                            <div class="col-md-3">
                                <label for="search" class="form-label">Cari (Kode/Nama MK)</label>
                                <input type="text" class="form-control" id="search" name="search"
                                    placeholder="Kode atau Nama MK" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="dosen_id" class="form-label">Filter Dosen Pengampu</label>
                                <select class="form-control" id="dosen_id" name="dosen_id">
                                    <option value="">Semua Dosen</option>
                                    @foreach ($dosens as $dosen)
                                        <option value="{{ $dosen->id }}" {{ request('dosen_id') == $dosen->id ? 'selected' : '' }}>
                                            {{ $dosen->nama }} ({{ $dosen->nidn }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Semester Dropdown -->
                            <div class="col-md-3">
                                <label for="semester" class="form-label">Filter Semester</label>
                                <select class="form-control" id="semester" name="semester">
                                    <option value="">Semua Semester</option>
                                    @for ($i = 1; $i <= 8; $i++)
                                        <option value="{{ $i }}"
                                            {{ request('semester') == $i ? 'selected' : '' }}>
                                            Semester {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <!-- Status Dropdown -->
                            <div class="col-md-3">
                                <label for="status" class="form-label">Filter Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="Wajib Prodi" {{ request('status') == 'Wajib Prodi' ? 'selected' : '' }}>
                                        Wajib Prodi</option>
                                    <option value="Pilihan" {{ request('status') == 'Pilihan' ? 'selected' : '' }}>Pilihan
                                    </option>
                                    <option value="Wajib Fakultas"
                                        {{ request('status') == 'Wajib Fakultas' ? 'selected' : '' }}>Wajib Fakultas
                                    </option>
                                    <option value="Wajib Universitas"
                                        {{ request('status') == 'Wajib Universitas' ? 'selected' : '' }}>Wajib Universitas
                                    </option>
                                </select>
                            </div>
                            <!-- Action Buttons -->
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search me-1"></i> Filter
                                </button>
                                <a href="{{ route('mata_kuliah.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-sync-alt me-1"></i> Reset
                                </a>
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
                                <th rowspan="2">Dosen Pengampu</th>
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
                                    <td>
                                        @if ($mk->dosens->count() > 0)
                                            @foreach ($mk->dosens as $dosen)
                                                <span class="badge bg-primary mb-1">{{ $dosen->nama }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">Belum ada dosen</span>
                                        @endif
                                    </td>
                                    <td>{{ $mk->semester }}</td>
                                    <td>{{ $mk->sks_teori }}</td>
                                    <td>{{ $mk->sks_praktik }}</td>
                                    <td>{{ $mk->status_mata_kuliah }}</td>
                                    <td>
                                        <a href="{{ route('mata_kuliah.edit', $mk->kode_mk) }}"
                                            class="btn btn-outline-success btn-sm">
                                            Edit
                                        </a>
                                        <form action="{{ route('mata_kuliah.destroy', $mk->kode_mk) }}" method="POST"
                                            style="display: inline-block;">
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

                    <!-- Pagination Controls -->
                    <div class="d-flex justify-content-end align-items-center mt-3">
                        <button class="btn btn-primary me-2"
                            onclick="navigateToPage('{{ $mataKuliahs->appends(request()->query())->previousPageUrl() }}')"
                            {{ $mataKuliahs->onFirstPage() ? 'disabled' : '' }}>
                            <i class="fas fa-chevron-left"></i> Halaman Sebelumnya
                        </button>
                        <button class="btn btn-primary"
                            onclick="navigateToPage('{{ $mataKuliahs->appends(request()->query())->nextPageUrl() }}')"
                            {{ $mataKuliahs->hasMorePages() ? '' : 'disabled' }}>
                            Halaman Selanjutnya <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>

                    <div class="d-flex justify-content-start mt-3">
                        <div
                            style="border: 2px solid #000; background-color: white; padding: 10px 20px; font-weight: bold;">
                            Total SKS: {{ $totalSKS }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="toast align-items-center text-white bg-success border-0 show" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif
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

        .btn-outline-success:hover {
            background-color: #28a745;
            color: white;
        }

        .btn-outline-danger {
            border-color: #dc3545;
            color: #dc3545;
        }

        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: white;
        }

        .btn-primary {
            background-color: rgb(0, 114, 202) !important;
            border-color: rgb(0, 114, 202) !important;
        }

        .btn-primary:hover {
            background-color: rgb(0, 94, 182) !important;
            border-color: rgb(0, 94, 182) !important;
        }

        .btn-success {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
        }

        .btn-success:hover {
            background-color: #218838 !important;
            border-color: #218838 !important;
        }

        .btn-secondary {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
        }

        .btn-secondary:hover {
            background-color: #5a6268 !important;
            border-color: #5a6268 !important;
        }

        .card {
            border-radius: 10px;
        }

        .card-header {
            border-radius: 10px 10px 0 0 !important;
            font-weight: bold;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
        }

        .form-control:focus {
            border-color: rgb(0, 114, 202);
            box-shadow: 0 0 5px rgba(0, 114, 202, 0.3);
        }

        .form-label {
            font-weight: 500;
            color: #333;
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
            const semesterSelect = document.getElementById('semester');
            const statusSelect = document.getElementById('status');

            if (semesterSelect) {
                semesterSelect.addEventListener('change', function() {
                    this.form.submit();
                });
            }

            if (statusSelect) {
                statusSelect.addEventListener('change', function() {
                    this.form.submit();
                });
            }
        });
    </script>
@endsection
