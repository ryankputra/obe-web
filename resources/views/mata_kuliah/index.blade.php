@extends('layouts.app')

@section('title', 'Daftar Mata Kuliah')

@section('content')
    <div class="container-fluid">
        <h1 class="dashboard-heading mt-4">Daftar Mata Kuliah</h1>
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-end align-items-center">
                <button class="btn btn-primary rounded-circle me-2 d-flex justify-content-center align-items-center"
                    style="width: 40px; height: 40px;" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                    <i class="fas fa-plus text-white"></i>
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
                                <th rowspan="2">Status Mata Kuliah</th>
                                <th rowspan="2">Action</th>
                            </tr>
                            <tr>
                                <th>Teori</th>
                                <th>Praktik</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mataKuliahs as $mk)
                                <tr data-id="{{ $mk->id }}"> <!-- Added data-id -->
                                    <td>{{ $mk->kode_mk }}</td>
                                    <td class="text-start">{{ $mk->nama_mk }}</td>
                                    <td>{{ $mk->deskripsi }}</td>
                                    <td>{{ $mk->semester }}</td>
                                    <td>{{ $mk->sks_teori }}</td>
                                    <td>{{ $mk->sks_praktik }}</td>
                                    <td>{{ $mk->status_mata_kuliah }}</td>
                                    <td>
                                        <a href="#" class="btn btn-outline-success btn-sm edit-course"
                                            data-bs-toggle="modal" data-bs-target="#editCourseModal"
                                            data-id="{{ $mk->id }}" data-kode="{{ $mk->kode_mk }}"
                                            data-nama="{{ $mk->nama_mk }}" data-deskripsi="{{ $mk->deskripsi }}"
                                            data-semester="{{ $mk->semester }}" data-teori="{{ $mk->sks_teori }}"
                                            data-praktik="{{ $mk->sks_praktik }}"
                                            data-status="{{ $mk->status_mata_kuliah }}">Edit</a>
                                        <a href="#" class="btn btn-outline-danger btn-sm delete-course"
                                            data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                            data-id="{{ $mk->id }}">Hapus</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-start mt-3">
                    <div style="border: 2px solid #000; background-color: white; padding: 10px 20px; font-weight: bold;">
                        Total SKS:
                        {{ $mataKuliahs->sum(function($mk) {
                            return $mk->sks_teori + $mk->sks_praktik;
                        }) }}
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambah Mata Kuliah Modal -->
    @include('mata_kuliah.create')

    <!-- Edit Mata Kuliah Modal -->
    @include('mata_kuliah.edit')

    <!-- Delete Confirmation Modal -->
    @include('mata_kuliah.delete')

    <!-- Toast Notification -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Operasi berhasil dilakukan!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
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
            background-color:rgb(40, 187, 72) !important;
            border: none;
        }

        .btn-success {
            background-color: #28a745 !important;
            border: none;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Populate Form Fields
            document.getElementById('editCourseModal').addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                // document.getElementById('editCourseId').value = button.getAttribute('data-id');
                // Populate fields with fallback for null values
                document.getElementById('kodeMk').value = button.getAttribute('data-kode') || '';
                document.getElementById('namaMk').value = button.getAttribute('data-nama') || '';
                document.getElementById('deskripsi').value = button.getAttribute('data-deskripsi') ||
                ''; // Handles null
                document.getElementById('semester').value = button.getAttribute('data-semester') || '';
                document.getElementById('sksTeori').value = button.getAttribute('data-teori') || '';
                document.getElementById('sksPraktik').value = button.getAttribute('data-praktik') || '';
                document.getElementById('statusMataKuliah').value = button.getAttribute('data-status') ||
                    'Wajib Prodi';

                // Update form action URL
                const form = document.getElementById('editCourseForm');
                form.action = "{{ route('mata_kuliah.update', '') }}/" + button.getAttribute('data-id');
            });

            // Handle Form Submission
            document.getElementById('editCourseForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                formData.append('_method', 'PUT');

                fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Close Modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'editCourseModal'));
                            if (modal) modal.hide();

                            // Update Table Row
                            const row = document.querySelector(`tr[data-id="${data.id}"]`);
                            if (row) {
                                row.cells[0].textContent = data.kode_mk;
                                row.cells[1].textContent = data.nama_mk;
                                row.cells[2].textContent = data.deskripsi;
                                row.cells[3].textContent = data.semester;
                                row.cells[4].textContent = data.sks_teori;
                                row.cells[5].textContent = data.sks_praktik;
                                row.cells[6].textContent = data.status_mata_kuliah;
                            }

                            // Show Success Toast
                            const toast = new bootstrap.Toast(document.getElementById('successToast'));
                            toast.show();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });
    </script>
@endsection
