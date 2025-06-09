@extends('layouts.app')

@section('title', 'Fakultas FST Sains dan Teknologi')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4 text-purple">Fakultas Sains dan Teknologi</h1>

        {{-- Notifikasi Sukses/Error --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-success rounded-circle me-2 d-flex justify-content-center align-items-center"
                style="width: 40px; height: 40px;" data-bs-toggle="modal" data-bs-target="#addProdiModal"
                title="Tambah Prodi">
                <i class="fas fa-plus text-white"></i>
            </button>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead style="background-color: #2f5f98; color: #fff;">
                            <tr>
                                <th>No.</th>
                                <th>Prodi</th>
                                <th>Jumlah Mahasiswa</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($prodis as $index => $prodi)
                                <tr data-id="{{ $prodi->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-start">{{ $prodi->nama_prodi }}</td>
                                    <td>{{ $prodi->mahasiswas_count }}</td>
                                    <td>
                                        @if ($prodi->status == 'aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('mahasiswa.index', ['prodi_id' => $prodi->id]) }}"
                                            class="btn btn-outline-info btn-sm" title="Lihat Mahasiswa Prodi Ini"><i
                                                class="fas fa-users"></i></a>
                                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProdiModal{{ $prodi->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <!-- Tombol Hapus -->
                                        <button type="button"
                                            class="btn btn-outline-danger btn-sm delete-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteProdiModal"
                                            data-id="{{ $prodi->id }}"
                                            data-nama="{{ $prodi->nama_prodi }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                {{-- Modal Edit Prodi --}}
                                <div class="modal fade" id="editProdiModal{{ $prodi->id }}" tabindex="-1" aria-labelledby="editProdiModalLabel{{ $prodi->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('fakultasfst.prodi.update', $prodi->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editProdiModalLabel{{ $prodi->id }}">Edit Program Studi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Prodi <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="nama_prodi" value="{{ $prodi->nama_prodi }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                                        <select class="form-select" name="status" required>
                                                            <option value="aktif" {{ $prodi->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                            <option value="nonaktif" {{ $prodi->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data program studi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if (method_exists($prodis, 'links'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $prodis->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal Tambah Prodi --}}
    <div class="modal fade" id="addProdiModal" tabindex="-1" aria-labelledby="addProdiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProdiModalLabel">Tambah Prodi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addProdiForm" action="{{ route('fakultasfst.prodi.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="prodiNama" class="form-label">Nama Prodi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="prodiNama" name="nama_prodi" required>
                        </div>
                        <div class="mb-3">
                            <label for="prodiStatus" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="prodiStatus" name="status" required>
                                <option value="aktif" selected>Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus Prodi --}}
    <div class="modal fade" id="deleteProdiModal" tabindex="-1" aria-labelledby="deleteProdiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteProdiForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteProdiModalLabel">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <p>Yakin ingin menghapus prodi <strong id="prodiToDelete"></strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        body {
            background-color: #def4ff;
        }

        .table th {
            background-color: #2f5f98 !important;
            color: #fff !important;
            vertical-align: middle;
        }

        .table td {
            vertical-align: middle;
        }

        .text-purple {
            color: rgb(0, 0, 0) !important;
        }

        .btn-outline-info {
            color: #0dcaf0;
            border-color: #0dcaf0;
        }

        .btn-outline-info:hover {
            color: #000;
            background-color: #0dcaf0;
            border-color: #0dcaf0;
        }

        .form-select {
            border-radius: 0.25rem;
        }

        .badge {
            font-size: 0.85em;
        }

        .modal-backdrop {
            z-index: 1040 !important;
        }

        .modal {
            z-index: 1045 !important;
        }

        .dropdown-menu {
            z-index: 1055 !important;
        }
    </style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var deleteProdiModal = document.getElementById('deleteProdiModal');
    deleteProdiModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var nama = button.getAttribute('data-nama');
        // Set nama prodi di modal
        document.getElementById('prodiToDelete').textContent = nama;
        // Set action form
        var form = document.getElementById('deleteProdiForm');
        form.action = '/fakultasfst/prodi/' + id;
    });
});
</script>
@endsection

<link rel="stylesheet" href="fakultasfst:8">
