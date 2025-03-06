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
            <button class="btn btn-success rounded-circle d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                <i class="fas fa-save text-white"></i>
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th rowspan="2">Kode MK</th>
                            <th rowspan="2">Nama MK</th>
                            <th rowspan="2">Deskripsi</th>
                            <th rowspan="2">Semester</th>
                            <th colspan="2">SKS</th>
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
                            <td>{{ $mk['nama_mk'] }}</td>
                            <td>{{ $mk['deskripsi'] }}</td>
                            <td>{{ $mk['semester'] }}</td>
                            <td>{{ $mk['sks_teori'] }}</td>
                            <td>{{ $mk['sks_praktik'] }}</td>
                            <td>
                                <a href="#" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#editCourseModal" data-kode="{{ $mk['kode_mk'] }}" data-nama="{{ $mk['nama_mk'] }}" data-deskripsi="{{ $mk['deskripsi'] }}" data-semester="{{ $mk['semester'] }}" data-teori="{{ $mk['sks_teori'] }}" data-praktik="{{ $mk['sks_praktik'] }}">Edit</a>
                                <a href="#" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" data-kode="{{ $mk['kode_mk'] }}">Hapus</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Form Tambah Mata Kuliah -->
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
                        <input type="text" class="form-control" id="kodeMk" name="kode_mk">
                    </div>
                    <div class="mb-3">
                        <label for="namaMk" class="form-label">Nama MK</label>
                        <input type="text" class="form-control" id="namaMk" name="nama_mk">
                    </div>
                    <div class="mb-3">
                        <label for="deskripsiMk" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsiMk" name="deskripsi"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="semesterMk" class="form-label">Semester</label>
                        <input type="number" class="form-control" id="semesterMk" name="semester">
                    </div>
                    <div class="mb-3">
                        <label for="sksTeori" class="form-label">SKS Teori</label>
                        <input type="number" class="form-control" id="sksTeori" name="sks_teori">
                    </div>
                    <div class="mb-3">
                        <label for="sksPraktik" class="form-label">SKS Praktik</label>
                        <input type="number" class="form-control" id="sksPraktik" name="sks_praktik">
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

<!-- Modal untuk Form Edit Mata Kuliah -->
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
                        <input type="text" class="form-control" id="editKodeMk" name="kode_mk" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editNamaMk" class="form-label">Nama MK</label>
                        <input type="text" class="form-control" id="editNamaMk" name="nama_mk">
                    </div>
                    <div class="mb-3">
                        <label for="editDeskripsiMk" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="editDeskripsiMk" name="deskripsi"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editSemesterMk" class="form-label">Semester</label>
                        <input type="number" class="form-control" id="editSemesterMk" name="semester">
                    </div>
                    <div class="mb-3">
                        <label for="editSksTeori" class="form-label">SKS Teori</label>
                        <input type="number" class="form-control" id="editSksTeori" name="sks_teori">
                    </div>
                    <div class="mb-3">
                        <label for="editSksPraktik" class="form-label">SKS Praktik</label>
                        <input type="number" class="form-control" id="editSksPraktik" name="sks_praktik">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="saveEditButton">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Hapus?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus mata kuliah ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .dashboard-heading {
        font-size: 2rem;
        font-weight: bold;
        color: #333;
    }
    
    /* Warna header tabel */
    .table thead th {
        background-color: rgb(0, 114, 202) !important; /* Warna biru */
        color: white !important;
    }

    .table tbody tr:hover {
        background-color: #f3e5f5;
    }

    .table-bordered th,
    .table-bordered td {
        border: 2px solid #dee2e6;
    }

    .table th {
        font-size: 16px;
        vertical-align: middle;
    }

    .table td {
        font-size: 14px;
        vertical-align: middle;
    }

    .btn-outline-success {
        border-color: #28a745;
        color: #28a745;
    }

    .btn-outline-danger {
        border-color: #dc3545;
        color: #dc3545;
    }

    /* Warna tombol tambah dan simpan */
    .btn-primary {
        background-color: #007bff !important; /* Biru */
        border: none;
    }

    .btn-success {
        background-color: #28a745 !important; /* Hijau */
        border: none;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script>
<script>
    // Tambahkan logika untuk menambah mata kuliah baru
    document.getElementById('addCourseButton').addEventListener('click', function() {
        const kodeMk = document.getElementById('kodeMk').value;
        const namaMk = document.getElementById('namaMk').value;
        const deskripsi = document.getElementById('deskripsiMk').value;
        const semester = document.getElementById('semesterMk').value;
        const sksTeori = document.getElementById('sksTeori').value;
        const sksPraktik = document.getElementById('sksPraktik').value;

        const tbody = document.querySelector('.table tbody');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${kodeMk}</td>
            <td>${namaMk}</td>
            <td>${deskripsi}</td>
            <td>${semester}</td>
            <td>${sksTeori}</td>
            <td>${sksPraktik}</td>
            <td>
                <a href="#" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#editCourseModal" data-kode="${kodeMk}" data-nama="${namaMk}" data-deskripsi="${deskripsi}" data-semester="${semester}" data-teori="${sksTeori}" data-praktik="${sksPraktik}">Edit</a>
                <a href="#" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" data-kode="${kodeMk}">Hapus</a>
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

        const modal = this;
        modal.querySelector('#editKodeMk').value = kodeMk;
        modal.querySelector('#editNamaMk').value = namaMk;
        modal.querySelector('#editDeskripsiMk').value = deskripsi;
        modal.querySelector('#editSemesterMk').value = semester;
        modal.querySelector('#editSksTeori').value = sksTeori;
        modal.querySelector('#editSksPraktik').value = sksPraktik;
    });

    // Logika untuk menyimpan perubahan pada mata kuliah
    document.getElementById('saveEditButton').addEventListener('click', function() {
        const kodeMk = document.getElementById('editKodeMk').value;
        const namaMk = document.getElementById('editNamaMk').value;
        const deskripsi = document.getElementById('editDeskripsiMk').value;
        const semester = document.getElementById('editSemesterMk').value;
        const sksTeori = document.getElementById('editSksTeori').value;
        const sksPraktik = document.getElementById('editSksPraktik').value;

        const rows = document.querySelectorAll('.table tbody tr');
        rows.forEach(row => {
            if (row.children[0].textContent === kodeMk) {
                row.children[1].textContent = namaMk;
                row.children[2].textContent = deskripsi;
                row.children[3].textContent = semester;
                row.children[4].textContent = sksTeori;
                row.children[5].textContent = sksPraktik;
            }
        });

        const editCourseModal = new bootstrap.Modal(document.getElementById('editCourseModal'));
        editCourseModal.hide();
    });

    // Logika untuk menghapus mata kuliah
    document.getElementById('deleteConfirmModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const kodeMk = button.getAttribute('data-kode');

        document.getElementById('confirmDelete').addEventListener('click', function() {
            const rows = document.querySelectorAll('.table tbody tr');
            rows.forEach(row => {
                if (row.children[0].textContent === kodeMk) {
                    row.remove();
                }
            });

            const deleteConfirmModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            deleteConfirmModal.hide();
        }, { once: true });
    });

    // Logika untuk menutup modal dengan tombol "Batal" dan "Silang"
    document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(button => {
        button.addEventListener('click', () => {
            const modal = bootstrap.Modal.getInstance(button.closest('.modal'));
            modal.hide();
        });
    });
</script>
@endsection
