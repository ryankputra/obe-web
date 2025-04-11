@extends('layouts.app')

@section('title', 'CPL')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">CPL</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="d-flex justify-content-end align-items-center mb-3">
        <div class="d-flex">
            <a href="{{ route('cpl.create') }}" class="btn btn-success rounded-circle me-2 d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                <i class="fas fa-plus text-white"></i>
            </a>
        </div>
    </div>

    <!-- CPL Table -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 15%">Kode CPL</th>
                            <th style="width: 65%">Deskripsi</th>
                            <th style="width: 20%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cpls as $cpl)
                        <tr>
                            <td>{{ $cpl->kode_cpl }}</td>
                            <td class="text-start">{{ $cpl->deskripsi }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('cpl.edit', $cpl->id) }}" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('cpl.destroy', $cpl->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus CPL ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">Tidak ada data CPL</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination Buttons -->
                <div class="d-flex justify-content-end mt-3">
                    <button class="btn btn-primary me-2" onclick="navigateToPage('{{ $cpls->previousPageUrl() }}')" {{ $cpls->onFirstPage() ? 'disabled' : '' }}>
                        <i class="fas fa-chevron-left"></i> Halaman Sebelumnya
                    </button>
                    <button class="btn btn-primary" onclick="navigateToPage('{{ $cpls->nextPageUrl() }}')" {{ $cpls->hasMorePages() ? '' : 'disabled' }}>
                        Halaman Selanjutnya <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
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

    .dashboard-heading {
        font-size: 2rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 1.5rem;
    }

    /* Table styling */
    .table {
        border-collapse: collapse;
        width: 100%;
        margin-bottom: 1rem;
        background-color: #fff;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .table thead th {
        background-color: #2f5f98 !important;
        color: #fff !important;
        padding: 12px;
        text-align: center;
        vertical-align: middle;
        border: 1px solid #dee2e6;
    }

    .table tbody td {
        padding: 12px;
        vertical-align: middle;
        border: 1px solid #dee2e6;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .table tbody tr:hover {
        background-color: #e9ecef;
    }

    /* Button styling */
    .btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        transition: all 0.2s ease-in-out;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-outline-success {
        color: #28a745;
        border-color: #28a745;
    }

    .btn-outline-danger {
        color: #dc3545;
        border-color: #dc3545;
    }

    .btn-outline-success:hover {
        color: #fff;
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-outline-danger:hover {
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
    }

    /* Pagination styling */
    .d-flex.justify-content-end {
        gap: 0.5rem;
    }

    .btn-primary:hover {
        background-color: #0056b3;
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
</script>
@endsection
