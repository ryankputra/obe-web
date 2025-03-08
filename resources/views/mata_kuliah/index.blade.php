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
                            <td>
                                <a href="#" class="btn btn-outline-success btn-sm edit-course" data-bs-toggle="modal" data-bs-target="#editCourseModal" data-kode="{{ $mk['kode_mk'] }}" data-nama="{{ $mk['nama_mk'] }}" data-deskripsi="{{ $mk['deskripsi'] }}" data-semester="{{ $mk['semester'] }}" data-teori="{{ $mk['sks_teori'] }}" data-praktik="{{ $mk['sks_praktik'] }}">Edit</a>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCourseModalLabel">Tambah Mata Kuliah Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCourseForm">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="kode_mk" class="form-label">Kode MK</label>
                            <input type="text" class="form-control" id="kode_mk" name="kode_mk" required>
                        </div>
                        <div class="col-md-6">
                            <label for="nama_mk" class="form-label">Nama MK</label>
                            <input type="text" class="form-control" id="nama_mk" name="nama_mk" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="semester" class="form-label">Semester</label>
                            <select class="form-select" id="semester" name="semester" required>
                                <option value="">Pilih Semester</option>
                                @for($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="sks_teori" class="form-label">SKS Teori</label>
                            <input type="number" class="form-control" id="sks_teori" name="sks_teori" min="0" value="0" required>
                        </div>
                        <div class="col-md-4">
                            <label for="sks_praktik" class="form-label">SKS Praktik</label>
                            <input type="number" class="form-control" id="sks_praktik" name="sks_praktik" min="0" value="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Mata Kuliah Modal -->
<div class="modal fade" id="editCourseModal" tabindex="-1" aria-labelledby="editCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCourseModalLabel">Edit Mata Kuliah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCourseForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_kode_mk_original" name="kode_mk_original">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_kode_mk" class="form-label">Kode MK</label>
                            <input type="text" class="form-control" id="edit_kode_mk" name="kode_mk" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_nama_mk" class="form-label">Nama MK</label>
                            <input type="text" class="form-control" id="edit_nama_mk" name="nama_mk" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="edit_semester" class="form-label">Semester</label>
                            <select class="form-select" id="edit_semester" name="semester" required>
                                <option value="">Pilih Semester</option>
                                @for($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_sks_teori" class="form-label">SKS Teori</label>
                            <input type="number" class="form-control" id="edit_sks_teori" name="sks_teori" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_sks_praktik" class="form-label">SKS Praktik</label>
                            <input type="number" class="form-control" id="edit_sks_praktik" name="sks_praktik" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
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
                <p>Apakah Anda yakin ingin menghapus mata kuliah ini?</p>
                <p class="fw-bold" id="delete_kode_nama"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Hapus</button>
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
        background-color: #def4ff;
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
        // Initialize toast
        const successToast = new bootstrap.Toast(document.getElementById('successToast'));
        
        // Show success message
        function showSuccessMessage() {
            successToast.show();
        }
        
        // Handle Add Course Form Submit
        document.getElementById('addCourseForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const kodeMk = document.getElementById('kode_mk').value;
            const namaMk = document.getElementById('nama_mk').value;
            const deskripsi = document.getElementById('deskripsi').value;
            const semester = document.getElementById('semester').value;
            const sksTeori = document.getElementById('sks_teori').value;
            const sksPraktik = document.getElementById('sks_praktik').value;
            
            // Validate SKS
            if (parseInt(sksTeori) + parseInt(sksPraktik) === 0) {
                alert('Total SKS (Teori + Praktik) harus lebih dari 0');
                return;
            }
            
            // Check if kode_mk already exists
            const rows = document.querySelectorAll('#courseTable tbody tr');
            for (let i = 0; i < rows.length; i++) {
                if (rows[i].cells[0].textContent.trim() === kodeMk) {
                    alert('Kode MK sudah ada, silahkan gunakan kode yang berbeda');
                    return;
                }
            }
            
            // Add new row to table
            const tbody = document.querySelector('#courseTable tbody');
            const newRow = tbody.insertRow();
            
            newRow.innerHTML = `
                <td>${kodeMk}</td>
                <td class="text-start">${namaMk}</td>
                <td>${deskripsi}</td>
                <td>${semester}</td>
                <td>${sksTeori}</td>
                <td>${sksPraktik}</td>
                <td>
                    <a href="#" class="btn btn-outline-success btn-sm edit-course" data-bs-toggle="modal" data-bs-target="#editCourseModal" data-kode="${kodeMk}" data-nama="${namaMk}" data-deskripsi="${deskripsi}" data-semester="${semester}" data-teori="${sksTeori}" data-praktik="${sksPraktik}">Edit</a>
                    <a href="#" class="btn btn-outline-danger btn-sm delete-course" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" data-kode="${kodeMk}">Hapus</a>
                </td>
            `;
            
            // Add event listeners to new buttons
            attachEditListener(newRow.querySelector('.edit-course'));
            attachDeleteListener(newRow.querySelector('.delete-course'));
            
            // Reset form and close modal
            this.reset();
            const modal = bootstrap.Modal.getInstance(document.getElementById('addCourseModal'));
            modal.hide();
            
            // Show success message
            showSuccessMessage();
        });
        
        // Handle Edit Course Form Submit
        document.getElementById('editCourseForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const originalKode = document.getElementById('edit_kode_mk_original').value;
            const newKode = document.getElementById('edit_kode_mk').value;
            const namaMk = document.getElementById('edit_nama_mk').value;
            const deskripsi = document.getElementById('edit_deskripsi').value;
            const semester = document.getElementById('edit_semester').value;
            const sksTeori = document.getElementById('edit_sks_teori').value;
            const sksPraktik = document.getElementById('edit_sks_praktik').value;
            
            // Validate SKS
            if (parseInt(sksTeori) + parseInt(sksPraktik) === 0) {
                alert('Total SKS (Teori + Praktik) harus lebih dari 0');
                return;
            }
            
            // If kode_mk is changed, check if new kode_mk already exists
            if (originalKode !== newKode) {
                const rows = document.querySelectorAll('#courseTable tbody tr');
                for (let i = 0; i < rows.length; i++) {
                    if (rows[i].cells[0].textContent.trim() === newKode) {
                        alert('Kode MK sudah ada, silahkan gunakan kode yang berbeda');
                        return;
                    }
                }
            }
            
            // Find and update the row
            const rows = document.querySelectorAll('#courseTable tbody tr');
            for (let i = 0; i < rows.length; i++) {
                if (rows[i].cells[0].textContent.trim() === originalKode) {
                    // Update row values
                    rows[i].cells[0].textContent = newKode;
                    rows[i].cells[1].textContent = namaMk;
                    rows[i].cells[2].textContent = deskripsi;
                    rows[i].cells[3].textContent = semester;
                    rows[i].cells[4].textContent = sksTeori;
                    rows[i].cells[5].textContent = sksPraktik;
                    
                    // Update data attributes for edit button
                    const editBtn = rows[i].querySelector('.edit-course');
                    editBtn.setAttribute('data-kode', newKode);
                    editBtn.setAttribute('data-nama', namaMk);
                    editBtn.setAttribute('data-deskripsi', deskripsi);
                    editBtn.setAttribute('data-semester', semester);
                    editBtn.setAttribute('data-teori', sksTeori);
                    editBtn.setAttribute('data-praktik', sksPraktik);
                    
                    // Update data attribute for delete button
                    const deleteBtn = rows[i].querySelector('.delete-course');
                    deleteBtn.setAttribute('data-kode', newKode);
                    
                    break;
                }
            }
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editCourseModal'));
            modal.hide();
            
            // Show success message
            showSuccessMessage();
        });
        
        // Function to attach edit listener
        function attachEditListener(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const kode = this.getAttribute('data-kode');
                const nama = this.getAttribute('data-nama');
                const deskripsi = this.getAttribute('data-deskripsi');
                const semester = this.getAttribute('data-semester');
                const teori = this.getAttribute('data-teori');
                const praktik = this.getAttribute('data-praktik');
                
                // Populate the edit form
                document.getElementById('edit_kode_mk_original').value = kode;
                document.getElementById('edit_kode_mk').value = kode;
                document.getElementById('edit_nama_mk').value = nama;
                document.getElementById('edit_deskripsi').value = deskripsi;
                document.getElementById('edit_semester').value = semester;
                document.getElementById('edit_sks_teori').value = teori;
                document.getElementById('edit_sks_praktik').value = praktik;
            });
        }
        
        // Function to attach delete listener
        function attachDeleteListener(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const kode = this.getAttribute('data-kode');
                const row = this.closest('tr');
                const nama = row.cells[1].textContent;
                
                document.getElementById('delete_kode_nama').textContent = `${kode} - ${nama}`;
                
                // Store the row reference for deletion
                document.getElementById('confirmDeleteBtn').setAttribute('data-row', row.rowIndex);
            });
        }
        
        // Handle Delete Confirmation
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            const rowIndex = this.getAttribute('data-row');
            const table = document.getElementById('courseTable');
            
            if (rowIndex) {
                table.deleteRow(rowIndex);
            }
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'));
            modal.hide();
            
            // Show success message
            showSuccessMessage();
        });
        
        // Handle Save All button (simulation)
        document.getElementById('saveCourseBtn').addEventListener('click', function() {
            // Show confirmation dialog
            if (confirm('Apakah Anda yakin ingin menyimpan semua perubahan?')) {
                // In a real application, this would send data to the server
                showSuccessMessage();
                
                // Optional: collect all data from table to show what would be saved
                const rows = document.querySelectorAll('#courseTable tbody tr');
                const data = [];
                
                rows.forEach(row => {
                    data.push({
                        kode_mk: row.cells[0].textContent,
                        nama_mk: row.cells[1].textContent,
                        deskripsi: row.cells[2].textContent,
                        semester: row.cells[3].textContent,
                        sks_teori: row.cells[4].textContent,
                        sks_praktik: row.cells[5].textContent
                    });
                });
                
                console.log('Data yang akan disimpan:', data);
            }
        });
        
        // Attach event listeners to existing buttons
        document.querySelectorAll('.edit-course').forEach(button => {
            attachEditListener(button);
        });
        
        document.querySelectorAll('.delete-course').forEach(button => {
            attachDeleteListener(button);
        });
    });
</script>
@endsection