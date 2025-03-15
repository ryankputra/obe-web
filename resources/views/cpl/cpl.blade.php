@extends('layouts.app')

@section('title', 'CPL')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">CPL</h1>
    <div class="d-flex justify-content-end align-items-center mb-3">
        <div class="d-flex">
            <button class="btn btn-success rounded-circle me-2 d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;" data-bs-toggle="modal" data-bs-target="#addCplModal">
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
                            <th>Kode CPL</th>
                            <th>Deskripsi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cpl as $item)
                        <tr>
                            <td>{{ $item['kode'] }}</td>
                            <td>{{ $item['deskripsi'] }}</td>
                            <td>
                                <a href="#" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#editCplModal" data-cpl="{{ $item['kode'] }}" data-deskripsi="{{ $item['deskripsi'] }}">Edit</a>
                                <a href="#" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteCplModal" data-cpl="{{ $item['kode'] }}">Hapus</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Tambah CPL -->
<div class="modal fade" id="addCplModal" tabindex="-1" aria-labelledby="addCplModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCplModalLabel">Tambah CPL</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCplForm">
                    <div class="mb-3">
                        <label for="cplKode" class="form-label">Kode CPL</label>
                        <input type="text" class="form-control" id="cplKode" name="cplKode">
                    </div>
                    <div class="mb-3">
                        <label for="cplDeskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="cplDeskripsi" name="cplDeskripsi" rows="4"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="addCplButton">Tambah</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Edit CPL -->
<div class="modal fade" id="editCplModal" tabindex="-1" aria-labelledby="editCplModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCplModalLabel">Edit CPL</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCplForm">
                    <div class="mb-3">
                        <label for="editCplKode" class="form-label">Kode CPL</label>
                        <input type="text" class="form-control text-start" id="editCplKode" name="cplKode" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editCplDeskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control text-start" id="editCplDeskripsi" name="cplDeskripsi" rows="4"></textarea>
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

<!-- Modal untuk Hapus CPL -->
<div class="modal fade" id="deleteCplModal" tabindex="-1" aria-labelledby="deleteCplModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCplModalLabel">Hapus CPL</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus CPL ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteCpl">Hapus</button>
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
    // Logika untuk menambah CPL baru
    document.getElementById('addCplButton').addEventListener('click', function() {
        const cplKode = document.getElementById('cplKode').value;
        const cplDeskripsi = document.getElementById('cplDeskripsi').value;

        const tbody = document.querySelector('.table tbody');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${cplKode}</td>
            <td>${cplDeskripsi}</td>
            <td>
                <a href="#" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#editCplModal" data-cpl="${cplKode}" data-deskripsi="${cplDeskripsi}">Edit</a>
                <a href="#" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteCplModal" data-cpl="${cplKode}">Hapus</a>
            </td>
        `;
        tbody.appendChild(tr);

        document.getElementById('addCplForm').reset();
        const addCplModal = new bootstrap.Modal(document.getElementById('addCplModal'));
        addCplModal.hide();
    });

    // Logika untuk mengisi data form edit dengan data yang sesuai
    document.getElementById('editCplModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const cplKode = button.getAttribute('data-cpl');
        const cplDeskripsi = button.getAttribute('data-deskripsi');

        const modal = this;
        modal.querySelector('#editCplKode').value = cplKode;
        modal.querySelector('#editCplDeskripsi').value = cplDeskripsi;
    });

    // Logika untuk menyimpan perubahan pada CPL
    document.getElementById('saveEditButton').addEventListener('click', function() {
        const cplKode = document.getElementById('editCplKode').value;
        const cplDeskripsi = document.getElementById('editCplDeskripsi').value;

        const rows = document.querySelectorAll('.table tbody tr');
        rows.forEach(row => {
            if (row.children[0].textContent === cplKode) {
                row.children[1].textContent = cplDeskripsi;
            }
        });

                const editCplModal = new bootstrap.Modal(document.getElementById('editCplModal'));
        editCplModal.hide();
    });

    // Logika untuk menghapus CPL
    document.getElementById('deleteCplModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const cplKode = button.getAttribute('data-cpl');

        document.getElementById('confirmDeleteCpl').addEventListener('click', function() {
            const rows = document.querySelectorAll('.table tbody tr');
            rows.forEach(row => {
                if (row.children[0].textContent === cplKode) {
                    row.remove();
                }
            });

            const deleteCplModal = new bootstrap.Modal(document.getElementById('deleteCplModal'));
            deleteCplModal.hide();
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
