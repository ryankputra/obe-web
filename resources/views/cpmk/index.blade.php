@extends('layouts.app')

@section('title', 'CPMK')

@section('content')
    <div class="container-fluid">
        <h1 class="mt-4">CPMK</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="d-flex justify-content-end align-items-center mb-3">
            <div class="d-flex">
                <a href="{{ route('cpmk.create') }}"
                    class="btn btn-success rounded-circle me-2 d-flex justify-content-center align-items-center"
                    style="width: 40px; height: 40px;">
                    <i class="fas fa-plus text-white"></i>
                </a>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-filter me-2"></i>Filter CPMK
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('cpmk.index') }}">
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label for="kode_cpmk" class="form-label">Kode CPMK</label>
                                    <input type="text" name="kode_cpmk" class="form-control"
                                        value="{{ request('kode_cpmk') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="kode_cpl" class="form-label">Kode CPL</label>
                                    <select name="kode_cpl" class="form-control">
                                        <option value="">Semua CPL</option>
                                        @foreach ($availableCpls as $cpl)
                                            <option value="{{ $cpl->kode_cpl }}"
                                                {{ request('kode_cpl') == $cpl->kode_cpl ? 'selected' : '' }}>
                                                {{ $cpl->kode_cpl }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="mata_kuliah" class="form-label">Mata Kuliah</label>
                                    <select name="mata_kuliah" class="form-control">
                                        <option value="">Semua MK</option>
                                        @foreach ($availableMatakuliahs as $mk)
                                            <option value="{{ $mk->kode_mk }}"
                                                {{ request('mata_kuliah') == $mk->kode_mk ? 'selected' : '' }}>
                                                {{ $mk->kode_mk }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="pic" class="form-label">PIC</label>
                                    <select name="pic" class="form-control">
                                        <option value="">Semua PIC</option>
                                        @foreach ($availablePics as $pic)
                                            <option value="{{ $pic }}"
                                                {{ request('pic') == $pic ? 'selected' : '' }}>
                                                {{ $pic }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="bobot" class="form-label">Bobot</label>
                                    <input type="number" name="bobot" class="form-control"
                                        value="{{ request('bobot') }}">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2 w-100">
                                        <i class="fas fa-search me-1"></i>Filter
                                    </button>
                                    <a href="{{ route('cpmk.index') }}" class="btn btn-secondary w-100 mt-2">
                                        <i class="fas fa-sync-alt me-1"></i>Reset
                                    </a>
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
                    <table class="table table-bordered">
                        <thead style="background-color: #2f5f98; color: #fff;">
                            <tr>
                                <th class="text-start">Kode CPMK</th>
                                <th class="text-start">Deskripsi</th>
                                <th class="text-start">Kode CPL</th>
                                <th class="text-start">Mata Kuliah</th>
                                <th class="text-start">Bobot</th>
                                <th class="text-start">PIC</th>
                                <th class="text-start">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cpmks as $cpmk)
                                <tr>
                                    <td class="text-start">{{ $cpmk->kode_cpmk }}</td>
                                    <td class="text-start">{{ $cpmk->deskripsi }}</td>
                                    <td class="text-start">{{ $cpmk->kode_cpl }}</td>
                                    <td class="text-start">{{ $cpmk->nama_mk }}</td>
                                    <td class="text-start">{{ $cpmk->bobot }}</td>
                                    <td class="text-start">{{ $cpmk->pic }}</td>
                                    <td class="text-start">
                                        {{-- [DIUBAH] Tombol Edit sekarang menjadi link ke halaman edit --}}
                                        <a href="{{ route('cpmk.edit', $cpmk->id) }}" class="btn btn-outline-info btn-sm">
                                            Edit
                                        </a>
                                        <form action="{{ route('cpmk.destroy', $cpmk->id) }}" method="POST"
                                            style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm delete-btn"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus CPMK ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-start">Tidak ada data CPMK</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- [DIHAPUS] Seluruh bagian Modal Edit tidak diperlukan lagi --}}

@endsection

@section('styles')
    <style>
        body {
            background-color: #def4ff;
        }

        .table thead {
            background-color: #2f5f98 !important;
            color: #fff !important;
        }

        .table th {
            background-color: #2f5f98 !important;
            color: #fff !important;
        }

        .btn-outline-info {
            color: #17a2b8;
            border-color: #17a2b8;
        }

        .btn-outline-info:hover {
            color: #fff;
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .btn-outline-danger {
            color: #dc3545;
            border-color: #dc3545;
        }

        .btn-outline-danger:hover {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }
    </style>
@endsection

@section('scripts')
    {{-- [DIHAPUS] Script untuk menangani modal edit tidak diperlukan lagi --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Script lain yang tidak berhubungan dengan modal bisa diletakkan di sini
        });
    </script>
    @push('scripts')
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data CPMK akan dihapus permanen",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
