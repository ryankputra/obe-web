@extends('layouts.app')

@section('title', 'Daftar Mata Kuliah')

@section('content')
<div class="container-fluid">
    <h1 class="dashboard-heading mt-4">Daftar Mata Kuliah</h1>
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-end align-items-center">
            <button class="btn btn-primary rounded-circle me-2 d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                <i class="fas fa-plus text-white"></i>
            </button>
            <button id="saveCourseBtn" class="btn btn-success rounded-circle d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
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
                        @foreach($mataKuliahs as $mk)
                        <tr>
                            <td>{{ $mk['kode_mk'] }}</td>
                            <td class="text-start">{{ $mk['nama_mk'] }}</td>
                            <td>{{ $mk['deskripsi'] }}</td>
                            <td>{{ $mk['semester'] }}</td>
                            <td>{{ $mk['sks_teori'] }}</td>
                            <td>{{ $mk['sks_praktik'] }}</td>
                            <td>{{ $mk['status_mata_kuliah'] }}</td>
                            <td>
                                <a href="#" class="btn btn-outline-success btn-sm edit-course" data-bs-toggle="modal" data-bs-target="#editCourseModal" data-kode="{{ $mk['kode_mk'] }}" data-nama="{{ $mk['nama_mk'] }}" data-deskripsi="{{ $mk['deskripsi'] }}" data-semester="{{ $mk['semester'] }}" data-teori="{{ $mk['sks_teori'] }}" data-praktik="{{ $mk['sks_praktik'] }}" data-status="{{ $mk['status_mata_kuliah'] }}">Edit</a>
                                <a href="#" class="btn btn-outline-danger btn-sm delete-course" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" data-kode="{{ $mk['kode_mk'] }}">Hapus</a>
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
<div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCourseModalLabel">Tambah Mata Kuliah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCourseForm">
                    <div class="mb-3">
                        <label for="kodeMk" class="form-label">Kode MK</label>
                        <input type="text" class="form-control text-start" id="kodeMk" name="kode_mk">
                    </div>
                    <div class="mb-3">
                        <label for="namaMk" class="form-label">Nama MK</label>
                        <input type="text" class="form-control text-start" id="namaMk" name="nama_mk">
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control text-start" id="deskripsi" name="deskripsi" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <input type="number" class="form-control text-start" id="semester" name="semester">
                    </div>
                    <div class="mb-3">
                        <label for="sksTeori" class="form-label">SKS Teori</label>
                        <input type="number" class="form-control text-start" id="sksTeori" name="sks_teori">
                    </div>
                    <div class="mb-3">
                        <label for="sksPraktik" class="form-label">SKS Praktik</label>
                        <input type="number" class="form-control text-start" id="sksPraktik" name="sks_praktik">
                    </div>
                    <div class="mb-3">
                        <label for="statusMataKuliah" class="form-label">Status Mata Kuliah</label>
                        <select class="form-control text-start" id="statusMataKuliah" name="status_mata_kuliah">
                            <option value="Wajib Prodi">Wajib Prodi</option>
                            <option value="Wajib Universitas">Wajib Universitas</option>
                            <option value="Pilihan">Pilihan</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="addCourseButton">Tambah</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Mata Kuliah Modal -->
<div class="modal fade" id="editCourseModal" tabindex="-1" aria-labelledby="editCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCourseModalLabel">Edit Mata Kuliah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCourseForm">
                    <div class="mb-3">
                        <label for="editKodeMk" class="form-label">Kode MK</label>
                        <input type="text" class="form-control text-start" id="editKodeMk" name="kode_mk" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editNamaMk" class="form-label">Nama MK</label>
                        <input type="text" class="form-control text-start" id="editNamaMk" name="nama_mk">
                    </div>
                    <div class="mb-3">
                        <label for="editDeskripsiMk" class="form-label">Deskripsi</label>
                        <textarea class="form-control text-start" id="editDeskripsiMk" name="deskripsi" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editSemesterMk" class="form-label">Semester</label>
                        <input type="number" class="form-control text-start" id="editSemesterMk" name="semester">
                    </div>
                    <div class="mb-3">
                        <label for="editSksTeori" class="form-label">SKS Teori</label>
                        <input type="number" class="form-control text-start" id="editSksTeori" name="sks_teori">
                    </div>
                    <div class="mb-3">
                        <label for="editSksPraktik" class="form-label">SKS Praktik</label>
                        <input type="number" class="form-control text-start" id="editSksPraktik" name="sks_praktik">
                    </div>
                    <div class="mb-3">
                        <label for="editStatusMk" class="form-label">Status Mata Kuliah</label>
                        <select class="form-control text-start" id="editStatusMk" name="status_mata_kuliah">
                            <option value="Wajib Prodi">Wajib Prodi</option>
                            <option value="Wajib Universitas">Wajib Universitas</option>
                            <option value="Pilihan">Pilihan</option>
                            </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="saveEditCourseButton">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
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
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Operasi berhasil dilakukan!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    body {
        background-color: #def4ff !important;;
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
        // Logika untuk menambah Mata Kuliah baru
        document.getElementById('addCourseButton').addEventListener('click', function() {
            const kodeMk = document.getElementById('kodeMk').value;
            const namaMk = document.getElementById('namaMk').value;
            const deskripsi = document.getElementById('deskripsi').value;
            const semester = document.getElementById('semester').value;
            const sksTeori = document.getElementById('sksTeori').value;
            const sksPraktik = document.getElementById('sksPraktik').value;
            const statusMataKuliah = document.getElementById('statusMataKuliah').value;

            const tbody = document.querySelector('#courseTable tbody');
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${kodeMk}</td>
                <td class="text-start">${namaMk}</td>
                <td>${deskripsi}</td>
                <td>${semester}</td>
                <td>${sksTeori}</td>
                <td>${sksPraktik}</td>
                <td>${statusMataKuliah}</td>
                <td>
                    <a href="#" class="btn btn-outline-success btn-sm edit-course" data-bs-toggle="modal" data-bs-target="#editCourseModal" data-kode="${kodeMk}" data-nama="${namaMk}" data-deskripsi="${deskripsi}" data-semester="${semester}" data-teori="${sksTeori}" data-praktik="${sksPraktik}" data-status="${statusMataKuliah}">Edit</a>
                    <a href="#" class="btn btn-outline-danger btn-sm delete-course" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" data-kode="${kodeMk}">Hapus</a>
                </td>
            `;
            tbody.appendChild(tr);

            document.getElementById('addCourseForm').reset();
            const addCourseModal = new bootstrap.Modal(document.getElementById('addCourseModal'));
            addCourseModal.hide();
        });

        // Logika untuk mengisi data form edit dengan data yang sesuai
        document.getElementById('editCourseModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const kodeMk = button.getAttribute('data-kode');
            const namaMk = button.getAttribute('data-nama');
            const deskripsi = button.getAttribute('data-deskripsi');
            const semester = button.getAttribute('data-semester');
            const sksTeori = button.getAttribute('data-teori');
            const sksPraktik = button.getAttribute('data-praktik');
            const statusMataKuliah = button.getAttribute('data-status');

            const modal = this;
            modal.querySelector('#editKodeMk').value = kodeMk;
            modal.querySelector('#editNamaMk').value = namaMk;
            modal.querySelector('#editDeskripsiMk').value = deskripsi;
            modal.querySelector('#editSemesterMk').value = semester;
            modal.querySelector('#editSksTeori').value = sksTeori;
            modal.querySelector('#editSksPraktik').value = sksPraktik;
            modal.querySelector('#editStatusMk').value = statusMataKuliah;
        });

        // Logika untuk menyimpan perubahan pada Mata Kuliah
        document.getElementById('saveEditCourseButton').addEventListener('click', function() {
            const kodeMk = document.getElementById('editKodeMk').value;
            const namaMk = document.getElementById('editNamaMk').value;
            const deskripsi = document.getElementById('editDeskripsiMk').value;
            const semester = document.getElementById('editSemesterMk').value;
            const sksTeori = document.getElementById('editSksTeori').value;
            const sksPraktik = document.getElementById('editSksPraktik').value;
            const statusMataKuliah = document.getElementById('editStatusMk').value;

            const rows = document.querySelectorAll('#courseTable tbody tr');
            rows.forEach(row => {
                if (row.children[0].textContent === kodeMk) {
                    row.children[1].textContent = namaMk;
                    row.children[2].textContent = deskripsi;
                    row.children[3].textContent = semester;
                    row.children[4].textContent = sksTeori;
                    row.children[5].textContent = sksPraktik;
                    row.children[6].textContent = statusMataKuliah;
                }
            });

            const editCourseModal = new bootstrap.Modal(document.getElementById('editCourseModal'));
            editCourseModal.hide();
        });

        // Logika untuk menghapus Mata Kuliah
        document.getElementById('deleteConfirmModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const kodeMk = button.getAttribute('data-kode');

            document.getElementById('confirmDeleteButton').addEventListener('click', function() {
                const rows = document.querySelectorAll('#courseTable tbody tr');
                rows.forEach(row => {
                    if (row.children[0].textContent === kodeMk) {
                        row.remove();
                    }
                });

                const deleteConfirmModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
                deleteConfirmModal.hide();

                const successToast = new bootstrap.Toast(document.getElementById('successToast'));
                successToast.show();
            }, { once: true });
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
