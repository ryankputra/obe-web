@extends('layouts.app')

@section('title', 'Fakultas FST Sains dan Teknologi')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4 text-purple">Fakultas Sains dan Teknologi</h1>

        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-success rounded-circle me-2 d-flex justify-content-center align-items-center"
                style="width: 40px; height: 40px;" data-bs-toggle="modal" data-bs-target="#addProdiModal">
                <i class="fas fa-plus text-white"></i>
            </button>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead style="background-color: #2f5f98; color: #fff;">
                            <tr>
                                <th>Prodi</th>
                                <th>Jumlah Mahasiswa</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($prodis as $prodi)
                                <tr data-id="{{ $prodi->id }}">
                                    <td class="text-start">{{ $prodi->nama_prodi }}</td>
                                    <td>{{ $prodi->jumlah_mahasiswa }}</td>
                                    <td>
                                        <a href="{{ route('fakultasfst.prodi.show', $prodi->id) }}"
                                            class="btn btn-outline-info btn-sm">
                                            Lihat
                                        </a>
                                        <button class="btn btn-outline-danger btn-sm delete-btn"
                                            data-id="{{ $prodi->id }}"
                                            data-nama="{{ $prodi->nama_prodi }}">Hapus</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- cek -->
    <!-- Modal untuk Tambah Prodi -->
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
                            <label for="prodiNama" class="form-label">Nama Prodi</label>
                            <input type="text" class="form-control" id="prodiNama" name="nama_prodi" required>
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

    <!-- Modal untuk Hapus Prodi -->
    <div class="modal fade" id="deleteProdiModal" tabindex="-1" aria-labelledby="deleteProdiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteProdiModalLabel">Hapus Prodi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteProdiForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus program studi <strong id="prodiToDelete"></strong>?
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

        .table thead {
            background-color: #2f5f98 !important;
            color: #fff !important;
        }

        .table th {
            background-color: #2f5f98 !important;
            color: #fff !important;
        }

        .header-blue {
            background-color: #2f5f98 !important;
        }

        .text-purple {
            color: rgb(0, 0, 0) !important;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            // Handle delete button click
            $('.delete-btn').click(function() {
                const id = $(this).data('id');
                const nama-cdn = $(this).data('nama');

                $('#deleteProdiForm').attr('action', `/fakultasfst/prodi/${id}`);
                $('#prodiToDelete').text(nama);

                $('#deleteProdiModal').modal('show');
            });

            // Form submission handling for add prodi
            $('#addProdiForm').submit(function(e) {
                e.preventDefault();
                const form = $(this);

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + (xhr.responseJSON?.message || 'Silakan coba lagi.'));
                    }
                });
            });

            // Form submission handling for delete prodi
            $('#deleteProdiForm').submit(function(e) {
                e.preventDefault();
                const form = $(this);

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + (xhr.responseJSON?.message || 'Silakan coba lagi.'));
                    }
                });
            });
        });
    </script>
@endsection