@extends('layouts.app')

@section('title', 'Bobot Nilai Mata Kuliah')

@section('content')
    <div class="container-fluid">
        <h1 class="dashboard-heading mt-4">Bobot Nilai Mata Kuliah</h1>

        <div class="row mb-3">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-filter me-2"></i>Filter Mata Kuliah
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('bobot_nilai.index') }}" class="row g-3">
                            <div class="col-md-9">
                                <label for="search" class="form-label">Cari Mata Kuliah (Kode/Nama)</label>
                                <input type="text" class="form-control" id="search" name="search"
                                    placeholder="Masukkan Kode atau Nama Mata Kuliah" value="{{ $search ?? '' }}">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search me-1"></i> Filter
                                </button>
                                <a href="{{ route('bobot_nilai.index') }}" class="btn btn-secondary">
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
                    <table class="table table-bordered text-center" id="bobotNilaiTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode MK</th>
                                <th>Nama Mata Kuliah</th>
                                <th>Jumlah Mahasiswa</th>
                                <!-- Kolom Aksi dihilangkan, fungsionalitas pindah ke klik baris -->
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mataKuliahs as $index => $mk)
                                <tr class="clickable-row" data-detail-url="{{ route('bobot_nilai.show', $mk->getKey()) }}">
                                    <td>{{ $mataKuliahs->firstItem() + $index }}</td>
                                    <td>{{ $mk->kode_mk }}</td>
                                    <td class="text-start">
                                        <a href="{{ route('bobot_nilai.show', $mk->getKey()) }}">{{ $mk->nama_mk }}</a>
                                    </td>
                                    <td>{{ $mk->mahasiswas_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data mata kuliah yang ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if ($mataKuliahs->hasPages())
                    <div class="d-flex justify-content-end align-items-center mt-3">
                        <button class="btn btn-primary me-2"
                            onclick="navigateToPage('{{ $mataKuliahs->appends(request()->query())->previousPageUrl() }}')"
                            {{ $mataKuliahs->onFirstPage() ? 'disabled' : '' }}>
                            <i class="fas fa-chevron-left"></i> Sebelumnya
                        </button>
                        <button class="btn btn-primary"
                            onclick="navigateToPage('{{ $mataKuliahs->appends(request()->query())->nextPageUrl() }}')"
                            {{ $mataKuliahs->hasMorePages() ? '' : 'disabled' }}>
                            Selanjutnya <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    @endif
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

    @if (session('error'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="toast align-items-center text-white bg-danger border-0 show" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('error') }}
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
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: #cceeff;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #b8daff;
            vertical-align: middle;
        }

        .clickable-row {
            cursor: pointer;
        }

        .btn-outline-primary {
            border-color: #007bff;
            color: #007bff;
        }
        .btn-outline-primary:hover {
            background-color: #007bff;
            color: white;
        }

        /* Modal z-index fixes if needed, Bootstrap 5 should handle this well generally */
        .modal-backdrop {
            z-index: 1040 !important; /* Default is 1050 for modal, 1040 for backdrop */
        }
        .modal {
            z-index: 1050 !important; /* Ensure modals are on top */
        }
    </style>
@endsection

@section('scripts')
    <!-- Load Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const clickableRows = document.querySelectorAll('#bobotNilaiTable tbody .clickable-row');
            clickableRows.forEach(row => {
                row.addEventListener('click', function(event) {
                    // If the click target or its parent is an anchor tag,
                    // let the anchor tag handle the navigation.
                    if (event.target.closest('a')) {
                        return;
                    }
                    const detailUrl = this.dataset.detailUrl;
                    if (detailUrl) {
                        window.location.href = detailUrl; // Navigate if other part of the row is clicked
                    }
                });
            });
            // Auto-dismiss toasts
            var toastElList = [].slice.call(document.querySelectorAll('.toast'));
            var toastList = toastElList.map(function(toastEl) {
                return new bootstrap.Toast(toastEl, { delay: 5000 }).show();
            });
        });

        function navigateToPage(url) {
            if (url) {
                window.location.href = url;
            }
        }
    </script>
@endsection