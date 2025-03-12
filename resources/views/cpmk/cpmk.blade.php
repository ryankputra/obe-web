@extends('layouts.app')

@section('title', 'CPMK')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">CPMK</h1>
    <div class="d-flex justify-content-end align-items-center mb-3">
        <div class="d-flex">
            <button class="btn btn-success rounded-circle me-2 d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;" data-bs-toggle="modal" data-bs-target="#addCpmkModal">
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
                            <th>Kode CPMK</th>
                            <th>Deskripsi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cpmk as $item)
                        <tr>
                            <td>{{ $item['kode'] }}</td>
                            <td>{{ $item['deskripsi'] }}</td>
                            <td>
                                <a href="#" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#editCpmkModal" data-cpmk="{{ $item['kode'] }}" data-deskripsi="{{ $item['deskripsi'] }}" data-cpl="{{ $item['cpl'] }}">Edit</a>
                                <a href="#" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteCpmkModal" data-cpmk="{{ $item['kode'] }}">Hapus</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Tambah CPMK -->
<div class="modal fade" id="addCpmkModal" tabindex="-1" aria-labelledby="addCpmkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCpmkModalLabel">Tambah CPMK</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCpmkForm">
                    <div class="mb-3">
                        <label for="cpmkKode" class="form-label">Kode CPMK</label>
                        <input type="text" class="form-control" id="cpmkKode" name="cpmkKode">
                    </div>
                    <div class="mb-3">
                        <label for="cpmkDeskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="cpmkDeskripsi" name="cpmkDeskripsi" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="cplSelect" class="form-label">CPL</label>
                        <select class="form-control" id="cplSelect" name="cpl">
                            @foreach($cpl as $item)
                            <option value="{{ $item['kode'] }}">{{ $item['kode'] }} - {{ $item['deskripsi'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="addCpmkButton">Tambah</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Edit CPMK -->
<div class="modal fade" id="editCpmkModal" tabindex="-1" aria-labelledby="editCpmkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCpmkModalLabel">Edit CPMK</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCpmkForm">
                    <div class="mb-3">
                        <label for="editCpmkKode" class="form-label">Kode CPMK</label>
                        <input type="text" class="form-control text-start" id="editCpmkKode" name="cpmkKode" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editCpmkDeskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control text-start" id="editCpmkDeskripsi" name="cpmkDeskripsi" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editCplSelect" class="form-label">CPL</label>
                        <select class="form-control" id="editCplSelect" name="cpl">
                            @foreach($cpl as $item)
                            <option value="{{ $item['kode'] }}">{{ $item['kode'] }} - {{ $item['deskripsi'] }}</option>
                            @endforeach
                        </select>
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

<!-- Modal untuk Hapus CPMK -->
<div class="modal fade" id="deleteCpmkModal" tabindex="-1" aria-labelledby="deleteCpmkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCpmkModalLabel">Hapus CPMK</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus CPMK ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteCpmk">Hapus</button>
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
    // Logika untuk menambah CPMK baru
    document.getElementById('addCpmkButton').addEventListener('click', function() {
        const cpmkKode = document.getElementById('cpmkKode').value;
        const cpmkDeskripsi = document.getElementById('cpmkDeskripsi').value;
        const cpl = document.getElementById('cplSelect').value;

        const tbody = document.querySelector('.table tbody');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${cpmkKode}</td>
            <td>${cpmkDeskripsi}</td>
            <td>
                <a href="#" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#editCpmkModal" data-cpmk="${cpmkKode}" data-deskripsi="${cpmkDeskripsi}" data-cpl="${cpl}">Edit</a>
                <a href="#" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteCpmkModal" data-cpmk="${cpmkKode}">Hapus</a>
            </td>
        `;
        tbody.appendChild(tr);

        document.getElementById('addCpmkForm').reset();
        const addCpmkModal = new bootstrap.Modal(document.getElementById('addCpmkModal'));
        addCpmkModal.hide();
    });

    // Logika untuk mengisi data form edit dengan data yang sesuai
    document.getElementById('editCpmkModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const cpmkKode = button.getAttribute('data-cpmk');
        const cpmkDeskripsi = button.getAttribute('data-deskripsi');
        const cpl = button.getAttribute('data-cpl');

        const modal = this;
        modal.querySelector('#editCpmkKode').value = cpmkKode;
        modal.querySelector('#editCpmkDeskripsi').value = cpmkDeskripsi;
        modal.querySelector('#editCplSelect').value = cpl;
    });

    // Logika untuk menyimpan perubahan pada CPMK
    document.getElementById('saveEditButton').addEventListener('click', function() {
        const cpmkKode = document.getElementById('editCpmkKode').value;
        const cpmkDeskripsi = document.getElementById('editCpmkDeskripsi').value;
        const cpl = document.getElementById('editCplSelect').value;

        const rows = document.querySelectorAll('.table tbody tr');
        rows.forEach(row => {
            if (row.children[0].textContent === cpmkKode) {
                row.children[1].textContent = cpmkDeskripsi;
                row.children[2].querySelector('a[data-bs-target="#editCpmkModal"]').setAttribute('data-cpl', cpl);
            }
        });

        const editCpmkModal = new bootstrap.Modal(document.getElementById('editCpmkModal'));
        editCpmkModal.hide();
    });

    // Logika untuk menghapus CPMK
    document.getElementById('deleteCpmkModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const cpmkKode = button.getAttribute('data-cpmk');

        document.getElementById('confirmDeleteCpmk').addEventListener('click', function() {
            const rows = document.querySelectorAll('.table tbody tr');
            rows.forEach(row => {
                if (row.children[0].textContent === cpmkKode) {
                    row.remove();
                }
            });

            const deleteCpmkModal = new bootstrap.Modal(document.getElementById('deleteCpmkModal'));
            deleteCpmkModal.hide();
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
