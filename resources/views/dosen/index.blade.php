@extends('layouts.app')

@section('title', 'Daftar Dosen')

@section('content')
<div class="container-fluid">
    <h1 class="dashboard-heading mt-4">Daftar Dosen</h1>
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-end align-items-center">
            <button class="btn btn-success rounded-circle me-2 d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;" data-bs-toggle="modal" data-bs-target="#addLecturerModal">
                <i class="fas fa-plus text-white"></i>
            </button>
            <button class="btn btn-primary rounded-circle d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
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
                            <th>NIDN</th>
                            <th>Nama Dosen</th>
                            <th>Email</th>
                            <th>Jabatan</th>
                            <th>Kompetensi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dosen as $d)
                        <tr>
                            <td>{{ $d['nidn'] }}</td>
                            <td>{{ $d['nama'] }}</td>
                            <td>{{ $d['email'] }}</td>
                            <td>{{ $d['jabatan'] }}</td>
                            <td>{{ $d['kompetensi'] }}</td>
                            <td>
                                <a href="#" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#editLecturerModal" data-nidn="{{ $d['nidn'] }}" data-nama="{{ $d['nama'] }}" data-email="{{ $d['email'] }}" data-jabatan="{{ $d['jabatan'] }}" data-kompetensi="{{ $d['kompetensi'] }}">Edit</a>
                                <a href="#" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteLecturerModal" data-nidn="{{ $d['nidn'] }}">Hapus</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Form Tambah Dosen -->
<div class="modal fade" id="addLecturerModal" tabindex="-1" aria-labelledby="addLecturerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLecturerModalLabel">Tambah Dosen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addLecturerForm">
                    <div class="mb-3">
                        <label for="nidn" class="form-label">NIDN</label>
                        <input type="text" class="form-control" id="nidn" name="nidn">
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Dosen</label>
                        <input type="text" class="form-control" id="nama" name="nama">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <input type="text" class="form-control" id="jabatan" name="jabatan">
                    </div>
                    <div class="mb-3">
                        <label for="kompetensi" class="form-label">Kompetensi</label>
                        <input type="text" class="form-control" id="kompetensi" name="kompetensi">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="addLecturerButton">Tambah</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Form Edit Dosen -->
<div class="modal fade" id="editLecturerModal" tabindex="-1" aria-labelledby="editLecturerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLecturerModalLabel">Edit Dosen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editLecturerForm">
                    <div class="mb-3">
                        <label for="editNidn" class="form-label">NIDN</label>
                        <input type="text" class="form-control" id="editNidn" name="nidn" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editNama" class="form-label">Nama Dosen</label>
                        <input type="text" class="form-control" id="editNama" name="nama">
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="editJabatan" class="form-label">Jabatan</label>
                        <input type="text" class="form-control" id="editJabatan" name="jabatan">
                    </div>
                    <div class="mb-3">
                        <label for="editKompetensi" class="form-label">Kompetensi</label>
                        <input type="text" class="form-control" id="editKompetensi" name="kompetensi">
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
<div class="modal fade" id="deleteLecturerModal" tabindex="-1" aria-labelledby="deleteLecturerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteLecturerModalLabel">Hapus?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus dosen ini?
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
    body {
        background-color: #def4ff;
    }
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
    // Tambahkan logika untuk menambah dosen baru
    document.getElementById('addLecturerButton').addEventListener('click', function() {
        const nidn = document.getElementById('nidn').value;
        const nama = document.getElementById('nama').value;
        const email = document.getElementById('email').value;
        const jabatan = document.getElementById('jabatan').value;
        const kompetensi = document.getElementById('kompetensi').value;

        const tbody = document.querySelector('.table tbody');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${nidn}</td>
            <td>${nama}</td>
            <td>${email}</td>
            <td>${jabatan}</td>
            <td>${kompetensi}</td>
            <td>
                <a href="#" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#editLecturerModal" data-nidn="${nidn}" data-nama="${nama}" data-email="${email}" data-jabatan="${jabatan}" data-kompetensi="${kompetensi}">Edit</a>
                <a href="#" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteLecturerModal" data-nidn="${nidn}">Hapus</a>
            </td>
        `;
        tbody.appendChild(tr);

        document.getElementById('addLecturerForm').reset();
        const addLecturerModal = new bootstrap.Modal(document.getElementById('addLecturerModal'));
        addLecturerModal.hide();
    });

    // Logika untuk mengisi data form edit dengan data yang sesuai
    document.getElementById('editLecturerModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const nidn = button.getAttribute('data-nidn');
        const nama = button.getAttribute('data-nama');
        const email = button.getAttribute('data-email');
        const jabatan = button.getAttribute('data-jabatan');
        const kompetensi = button.getAttribute('data-kompetensi');

        const modal = this;
        modal.querySelector('#editNidn').value = nidn;
        modal.querySelector('#editNama').value = nama;
        modal.querySelector('#editEmail').value = email;
        modal.querySelector('#editJabatan').value = jabatan;
        modal.querySelector('#editKompetensi').value = kompetensi;
    });

    // Logika untuk menyimpan perubahan pada dosen
    document.getElementById('saveEditButton').addEventListener('click', function() {
        const nidn = document.getElementById('editNidn').value;
        const nama = document.getElementById('editNama').value;
        const email = document.getElementById('editEmail').value;
        const jabatan = document.getElementById('editJabatan').value;
        const kompetensi = document.getElementById('editKompetensi').value;

        const rows = document.querySelectorAll('.table tbody tr');
        rows.forEach(row => {
            if (row.children[0].textContent === nidn) {
                row.children[1].textContent = nama;
                row.children[2].textContent = email;
                row.children[3].textContent = jabatan;
                row.children[4].textContent = kompetensi;
            }
        });

        const editLecturerModal = new bootstrap.Modal(document.getElementById('editLecturerModal'));
        editLecturerModal.hide();
    });

    // Logika untuk menghapus dosen
    document.getElementById('deleteLecturerModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const nidn = button.getAttribute('data-nidn');

        document.getElementById('confirmDelete').addEventListener('click', function() {
            const rows = document.querySelectorAll('.table tbody tr');
            rows.forEach(row => {
                if (row.children[0].textContent === nidn) {
                    row.remove();
                }
            });

            const deleteLecturerModal = new bootstrap.Modal(document.getElementById('deleteLecturerModal'));
            deleteLecturerModal.hide();
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
