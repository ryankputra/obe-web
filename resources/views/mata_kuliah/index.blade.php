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
                <button id="saveCourseBtn"
                    class="btn btn-success rounded-circle d-flex justify-content-center align-items-center"
                    style="width: 40px; height: 40px;">
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
                                <tr>
                                    <td>{{ $mk['kode_mk'] }}</td>
                                    <td class="text-start">{{ $mk['nama_mk'] }}</td>
                                    <td>{{ $mk['deskripsi'] }}</td>
                                    <td>{{ $mk['semester'] }}</td>
                                    <td>{{ $mk['sks_teori'] }}</td>
                                    <td>{{ $mk['sks_praktik'] }}</td>
                                    <td>{{ $mk['status_mata_kuliah'] }}</td>
                                    <td>
                                        <a href="#" class="btn btn-outline-success btn-sm edit-course"
                                            data-bs-toggle="modal" data-bs-target="#editCourseModal"
                                            data-kode="{{ $mk['kode_mk'] }}" data-nama="{{ $mk['nama_mk'] }}"
                                            data-deskripsi="{{ $mk['deskripsi'] }}" data-semester="{{ $mk['semester'] }}"
                                            data-teori="{{ $mk['sks_teori'] }}" data-praktik="{{ $mk['sks_praktik'] }}"
                                            data-status="{{ $mk['status_mata_kuliah'] }}">Edit</a>
                                        <a href="#" class="btn btn-outline-danger btn-sm delete-course"
                                            data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                            data-kode="{{ $mk['kode_mk'] }}">Hapus</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambah Mata Kuliah Modal -->
    @include('mata_kuliah.create')

    <!-- Edit Mata Kuliah Modal -->
    @include('mata_kuliah.edit')

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus mata kuliah ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Hapus</button>
                </div>
            </div>
        </div>
    </div>

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
            ;
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
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Logika untuk mengisi data form edit dengan data yang sesuai
            document.getElementById('editCourseModal').addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget; // Button that triggered the modal
                const kodeMk = button.getAttribute('data-kode'); // Get the kode_mk from the button

                // Update the form's action URL
                const form = document.getElementById('editCourseForm');
                form.action = "{{ route('mata_kuliah.update', '') }}/" + kodeMk;

                // Populate the form fields
                document.getElementById('kodeMk').value = kodeMk;
                document.getElementById('namaMk').value = button.getAttribute('data-nama');
                document.getElementById('deskripsi').value = button.getAttribute('data-deskripsi');
                document.getElementById('semester').value = button.getAttribute('data-semester');
                document.getElementById('sksTeori').value = button.getAttribute('data-teori');
                document.getElementById('sksPraktik').value = button.getAttribute('data-praktik');
                document.getElementById('statusMataKuliah').value = button.getAttribute('data-status');
            });

            // Handle form submission via AJAX
            document.getElementById('editCourseForm').addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission

                const form = event.target;
                const formData = new FormData(form);

                // Add the _method field for Laravel to recognize it as a PUT request
                formData.append('_method', 'PUT');

                fetch(form.action, {
                        method: 'POST', // Always use POST for form submissions
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show a success message
                            const successToast = new bootstrap.Toast(document.getElementById(
                                'successToast'));
                            successToast.show();

                            // Update the table dynamically
                            const rows = document.querySelectorAll('#courseTable tbody tr');
                            rows.forEach(row => {
                                if (row.children[0].textContent === data.kode_mk) {
                                    row.children[1].textContent = data.nama_mk;
                                    row.children[2].textContent = data.deskripsi;
                                    row.children[3].textContent = data.semester;
                                    row.children[4].textContent = data.sks_teori;
                                    row.children[5].textContent = data.sks_praktik;
                                    row.children[6].textContent = data.status_mata_kuliah;
                                }
                            });

                            // Close the modal
                            const editCourseModal = new bootstrap.Modal(document.getElementById(
                                'editCourseModal'));
                            editCourseModal.hide();
                        } else {
                            alert('Gagal memperbarui data.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });

            // Logika untuk menghapus Mata Kuliah
            document.getElementById('deleteConfirmModal').addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const kodeMk = button.getAttribute('data-kode');

                document.getElementById('confirmDeleteButton').addEventListener('click', function() {
                    fetch("{{ route('mata_kuliah.destroy', '') }}/" + kodeMk, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                _method: 'DELETE'
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Remove the row from the table
                                const rows = document.querySelectorAll('#courseTable tbody tr');
                                rows.forEach(row => {
                                    if (row.children[0].textContent === kodeMk) {
                                        row.remove();
                                    }
                                });

                                // Show a success message
                                const successToast = new bootstrap.Toast(document
                                    .getElementById('successToast'));
                                successToast.show();
                            } else {
                                alert('Gagal menghapus data.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });

                    // Close the modal
                    const deleteConfirmModal = new bootstrap.Modal(document.getElementById(
                        'deleteConfirmModal'));
                    deleteConfirmModal.hide();
                }, {
                    once: true
                });
            });

            // Logika untuk menyimpan semua perubahan (implementasi logika penyimpanan sesuai kebutuhan aplikasi)
            document.getElementById('saveCourseBtn').addEventListener('click', function() {
                // Implementasi logika penyimpanan (misalnya, melalui AJAX)
                const successToast = new bootstrap.Toast(document.getElementById('successToast'));
                successToast.show();
            });
        });
    </script>
@endsection
