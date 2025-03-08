@extends('layouts.app')

@section('title', 'fakultasfst Sains dan Teknologi')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">fakultasfst Sains dan Teknologi</h1>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
        <div class="d-flex">
            <button class="btn btn-success rounded-circle me-2 d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;" data-bs-toggle="modal" data-bs-target="#addProdiModal">
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
                    <thead style="background-color: #2f5f98; color: #fff;">
                        <tr>
                            <th>Prodi</th>
                            <th>Jumlah Mahasiswa</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-start">Informatika</td>
                            <td>50</td>
                            <td>
                                <a href="{{ route('fakultasfst.sains-teknologi.informatika') }}" class="btn btn-outline-info btn-sm">Lihat</a>
                                <a href="#" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#editProdiModal" data-prodi="Informatika" data-jumlah="50">Edit</a>
                                <a href="#" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteProdiModal" data-prodi="Informatika">Hapus</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-start">Sistem Informasi</td>
                            <td>60</td>
                            <td>
                                <a href="{{ route('fakultasfst.sains-teknologi.sisteminformasi') }}" class="btn btn-outline-info btn-sm">Lihat</a>
                                <a href="#" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#editProdiModal" data-prodi="Sistem Informasi" data-jumlah="60">Edit</a>
                                <a href="#" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteProdiModal" data-prodi="Sistem Informasi">Hapus</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-start">RPL</td>
                            <td>55</td>
                            <td>
                                <a href="{{ route('fakultasfst.sains-teknologi.Rekayasaperangkatlunak') }}" class="btn btn-outline-info btn-sm">Lihat</a>
                                <a href="#" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#editProdiModal" data-prodi="RPL" data-jumlah="55">Edit</a>
                                <a href="#" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteProdiModal" data-prodi="RPL">Hapus</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Tambah Prodi -->
<div class="modal fade" id="addProdiModal" tabindex="-1" aria-labelledby="addProdiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProdiModalLabel">Tambah Prodi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addProdiForm">
                    <div class="mb-3">
                        <label for="prodiNama" class="form-label">Nama Prodi</label>
                        <input type="text" class="form-control" id="prodiNama" name="prodiNama">
                    </div>
                    <div class="mb-3">
                        <label for="jumlahMahasiswa" class="form-label">Jumlah Mahasiswa</label>
                        <input type="number" class="form-control" id="jumlahMahasiswa" name="jumlahMahasiswa">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="addProdiButton">Tambah</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Edit Prodi -->
<div class="modal fade" id="editProdiModal" tabindex="-1" aria-labelledby="editProdiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProdiModalLabel">Edit Prodi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProdiForm">
                    <div class="mb-3">
                        <label for="editProdiNama" class="form-label">Nama Prodi</label>
                        <input type="text" class="form-control text-start" id="editProdiNama" name="prodiNama" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editJumlahMahasiswa" class="form-label">Jumlah Mahasiswa</label>
                        <input type="number" class="form-control" id="editJumlahMahasiswa" name="jumlahMahasiswa">
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

<!-- Modal untuk Hapus Prodi -->
<div class="modal fade" id="deleteProdiModal" tabindex="-1" aria-labelledby="deleteProdiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProdiModalLabel">Hapus Prodi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus program studi ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteProdi">Hapus</button>
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

    .table thead {
        background-color: #2f5f98 !important;
        color: #fff !important;
    }

    .table th {
        background-color: #2f5f98 !important;
        color: #fff !important;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script>
<script>
    // Logika untuk menambah Prodi baru
    document.getElementById('addProdiButton').addEventListener('click', function() {
        const prodiNama = document.getElementById('prodiNama').value;
        const jumlahMahasiswa = document.getElementById('jumlahMahasiswa').value;

        const tbody = document.querySelector('.table tbody');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="text-start">${prodiNama}</td>
            <td>${jumlahMahasiswa}</td>
            <td>
                <a href="{{ route('fakultasfst.sains-teknologi.informatika') }}" class="btn btn-outline-info btn-sm">Lihat</a>
                <a href="#" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#editProdiModal" data-prodi="${prodiNama}" data-jumlah="${jumlahMahasiswa}">Edit</a>
                <a href="#" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteProdiModal" data-prodi="${prodiNama}">Hapus</a>
            </td>
        `;
        tbody.appendChild(tr);

        document.getElementById('addProdiForm').reset();
        const addProdiModal = new bootstrap.Modal(document.getElementById('addProdiModal'));
        addProdiModal.hide();
    });

    // Logika untuk mengisi data form edit dengan data yang sesuai
    document.getElementById('editProdiModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const prodiNama = button.getAttribute('data-prodi');
        const jumlahMahasiswa = button.getAttribute('data-jumlah');

        const modal = this;
        modal.querySelector('#editProdiNama').value = prodiNama;
        modal.querySelector('#editJumlahMahasiswa').value = jumlahMahasiswa;
    });

    // Logika untuk menyimpan perubahan pada Prodi
    document.getElementById('saveEditButton').addEventListener('click', function() {
        const prodiNama = document.getElementById('editProdiNama').value;
        const jumlahMahasiswa = document.getElementById('editJumlahMahasiswa').value;

        const rows = document.querySelectorAll('.table tbody tr');
        rows.forEach(row => {
            if (row.children[0].textContent === prodiNama) {
                row.children[1].textContent = jumlahMahasiswa;
            }
        });

        const editProdiModal = new bootstrap.Modal(document.getElementById('editProdiModal'));
        editProdiModal.hide();
    });

    // Logika untuk menghapus Prodi
    document.getElementById('deleteProdiModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const prodiNama = button.getAttribute('data-prodi');

        document.getElementById('confirmDeleteProdi').addEventListener('click', function() {
            const rows = document.querySelectorAll('.table tbody tr');
            rows.forEach(row => {
                if (row.children[0].textContent === prodiNama) {
                    row.remove();
                }
            });

            const deleteProdiModal = new bootstrap.Modal(document.getElementById('deleteProdiModal'));
            deleteProdiModal.hide();
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
