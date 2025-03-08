@extends('layouts.app')

@section('title', 'Mahasiswa - FST - Informatika')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('fakultasfst.sains-teknologi') }}" class="btn btn-secondary back-btn">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>
    
    <h1 class="mb-4 text-purple">Mahasiswa - FST - Informatika</h1>
    
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-success rounded-circle me-2 d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;" data-bs-toggle="modal" data-bs-target="#addMahasiswaModal">
            <i class="fas fa-plus text-white"></i>
        </button>
        <button class="btn btn-primary rounded-circle d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
            <i class="fas fa-save text-white"></i>
        </button>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center mb-0">
                            <thead>
                                <tr>
                                    <th class="header-blue text-white">NIM</th>
                                    <th class="header-blue text-white">Nama</th>
                                    <th class="header-blue text-white">
                                        Angkatan
                                        <button class="btn btn-sm text-white filter-btn" data-column="angkatan">
                                            <i class="fas fa-filter"></i>
                                        </button>
                                    </th>
                                    <th class="header-blue text-white">Email</th>
                                    <th class="header-blue text-white">Kontak</th>
                                    <th class="header-blue text-white">
                                        Gender
                                        <button class="btn btn-sm text-white filter-btn" data-column="gender">
                                            <i class="fas fa-filter"></i>
                                        </button>
                                    </th>
                                    <th class="header-blue text-white">IPK</th>
                                    <th class="header-blue text-white">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mahasiswa as $mhs)
                                <tr>
                                    <td>{{ $mhs['nim'] }}</td>
                                    <td class="text-start">{{ $mhs['nama'] }}</td>
                                    <td>{{ $mhs['angkatan'] }}</td>
                                    <td>{{ $mhs['email'] }}</td>
                                    <td>{{ $mhs['kontak'] }}</td>
                                    <td>{{ $mhs['gender'] }}</td>
                                    <td>{{ $mhs['ipk'] }}</td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editMahasiswaModal" data-nim="{{ $mhs['nim'] }}" data-nama="{{ $mhs['nama'] }}" data-angkatan="{{ $mhs['angkatan'] }}" data-email="{{ $mhs['email'] }}" data-kontak="{{ $mhs['kontak'] }}" data-gender="{{ $mhs['gender'] }}" data-ipk="{{ $mhs['ipk'] }}">Edit</button>
                                        <button class="btn btn-outline-danger btn-sm delete-btn" data-bs-toggle="modal" data-bs-target="#deleteMahasiswaModal" data-nim="{{ $mhs['nim'] }}">Hapus</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Filter Angkatan -->
<div class="modal fade" id="filterAngkatanModal" tabindex="-1" aria-labelledby="filterAngkatanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterAngkatanModalLabel">Filter Angkatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="2022" id="angkatan2022" checked>
                    <label class="form-check-label" for="angkatan2022">2022</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="2023" id="angkatan2023" checked>
                    <label class="form-check-label" for="angkatan2023">2023</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="2024" id="angkatan2024" checked>
                    <label class="form-check-label" for="angkatan2024">2024</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="applyAngkatanFilter">Terapkan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Filter Gender -->
<div class="modal fade" id="filterGenderModal" tabindex="-1" aria-labelledby="filterGenderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterGenderModalLabel">Filter Gender</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Laki-laki" id="genderLaki" checked>
                    <label class="form-check-label" for="genderLaki">Laki-laki</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Perempuan" id="genderPerempuan" checked>
                    <label class="form-check-label" for="genderPerempuan">Perempuan</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="applyGenderFilter">Terapkan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Tambah Mahasiswa -->
<div class="modal fade" id="addMahasiswaModal" tabindex="-1" aria-labelledby="addMahasiswaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMahasiswaModalLabel">Tambah Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addMahasiswaForm">
                    <div class="mb-3">
                        <label for="nimMahasiswa" class="form-label">NIM</label>
                        <input type="text" class="form-control" id="nimMahasiswa" name="nimMahasiswa">
                    </div>
                    <div class="mb-3">
                        <label for="namaMahasiswa" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="namaMahasiswa" name="namaMahasiswa">
                    </div>
                    <div class="mb-3">
                        <label for="angkatanMahasiswa" class="form-label">Angkatan</label>
                        <input type="number" class="form-control" id="angkatanMahasiswa" name="angkatanMahasiswa">
                    </div>
                    <div class="mb-3">
                        <label for="emailMahasiswa" class="form-label">Email</label>
                        <input type="email" class="form-control" id="emailMahasiswa" name="emailMahasiswa">
                    </div>
                    <div class="mb-3">
                        <label for="kontakMahasiswa" class="form-label">Kontak</label>
                        <input type="text" class="form-control" id="kontakMahasiswa" name="kontakMahasiswa">
                    </div>
                    <div class="mb-3">
                        <label for="genderMahasiswa" class="form-label">Gender</label>
                        <select class="form-control" id="genderMahasiswa" name="genderMahasiswa">
                            <option value="Perempuan">Perempuan</option>
                            <option value="Laki-laki">Laki-laki</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="ipkMahasiswa" class="form-label">IPK</label>
                        <input type="number" step="0.01" class="form-control" id="ipkMahasiswa" name="ipkMahasiswa">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="addMahasiswaButton">Tambah</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Edit Mahasiswa -->
<div class="modal fade" id="editMahasiswaModal" tabindex="-1" aria-labelledby="editMahasiswaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMahasiswaModalLabel">Edit Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editMahasiswaForm">
                    <div class="mb-3">
                        <label for="editNimMahasiswa" class="form-label">NIM</label>
                        <input type="text" class="form-control text-start" id="editNimMahasiswa" name="nimMahasiswa" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editNamaMahasiswa" class="form-label">Nama</label>
                        <input type="text" class="form-control text-start" id="editNamaMahasiswa" name="namaMahasiswa">
                    </div>
                    <div class="mb-3">
                        <label for="editAngkatanMahasiswa" class="form-label">Angkatan</label>
                        <input type="number" class="form-control" id="editAngkatanMahasiswa" name="angkatanMahasiswa">
                    </div>
                    <div class="mb-3">
                        <label for="editEmailMahasiswa" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmailMahasiswa" name="emailMahasiswa">
                    </div>
                    <div class="mb-3">
                        <label for="editKontakMahasiswa" class="form-label">Kontak</label>
                        <input type="text" class="form-control" id="editKontakMahasiswa" name="kontakMahasiswa">
                    </div>
                    <div class="mb-3">
                        <label for="editGenderMahasiswa" class="form-label">Gender</label>
                        <select class="form-control" id="editGenderMahasiswa" name="genderMahasiswa">
                            <option value="Perempuan">Perempuan</option>
                            <option value="Laki-laki">Laki-laki</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editIpkMahasiswa" class="form-label">IPK</label>
                        <input type="number" step="0.01" class="form-control" id="editIpkMahasiswa" name="ipkMahasiswa">
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

<!-- Modal untuk Hapus Mahasiswa -->
<div class="modal fade" id="deleteMahasiswaModal" tabindex="-1" aria-labelledby="deleteMahasiswaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteMahasiswaModalLabel">Hapus Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus mahasiswa ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteMahasiswa">Hapus</button>
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
    
    .back-btn {
        background-color: #6c757d;
        color: white;
        border-radius: 5px;
        padding: 8px 15px;
        border: none;
    }
    
    .back-btn:hover {
        background-color: #5a6268;
        color: white;
    }
    
    .header-blue {
        background-color: #2f5f98 !important;
    }
    
    .text-purple {
        color: #5842a8 !important;
    }
    
    .table {
        background-color: white;
    }
    
    .table th {
        padding: 15px;
        vertical-align: middle;
    }
    
    .table td {
        padding: 12px;
        vertical-align: middle;
        border: 1px solid #dee2e6;
    }
    
    .filter-btn {
        background: none;
        border: none;
        padding: 0;
        margin-left: 5px;
    }
    
    .card {
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }
    
    .edit-btn, .delete-btn {
        padding: 4px 12px;
        border-radius: 4px;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script>
<script>
    // Logika untuk filter di header tabel
    document.querySelectorAll('.filter-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const column = this.getAttribute('data-column');
            if (column === 'angkatan') {
                const modal = new bootstrap.Modal(document.getElementById('filterAngkatanModal'));
                modal.show();
            } else if (column === 'gender') {
                const modal = new bootstrap.Modal(document.getElementById('filterGenderModal'));
                modal.show();
            }
        });
    });
    
    // Logika filter Angkatan
    document.getElementById('applyAngkatanFilter').addEventListener('click', function() {
        const selectedAngkatan = [];
        document.querySelectorAll('input[id^="angkatan"]:checked').forEach(checkbox => {
            selectedAngkatan.push(checkbox.value);
        });
        
        const rows = document.querySelectorAll('.table tbody tr');
        rows.forEach(row => {
            const angkatan = row.children[2].textContent;
            if (selectedAngkatan.includes(angkatan)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        const modal = bootstrap.Modal.getInstance(document.getElementById('filterAngkatanModal'));
        modal.hide();
    });
    
    // Logika filter Gender
    document.getElementById('applyGenderFilter').addEventListener('click', function() {
        const selectedGender = [];
        document.querySelectorAll('input[id^="gender"]:checked').forEach(checkbox => {
            selectedGender.push(checkbox.value);
        });
        
        const rows = document.querySelectorAll('.table tbody tr');
        rows.forEach(row => {
            const gender = row.children[5].textContent;
            if (selectedGender.includes(gender)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        const modal = bootstrap.Modal.getInstance(document.getElementById('filterGenderModal'));
        modal.hide();
    });

    // Logika untuk menambah Mahasiswa baru
    document.getElementById('addMahasiswaButton').addEventListener('click', function() {
        const nimMahasiswa = document.getElementById('nimMahasiswa').value;
        const namaMahasiswa = document.getElementById('namaMahasiswa').value;
        const angkatanMahasiswa = document.getElementById('angkatanMahasiswa').value;
        const emailMahasiswa = document.getElementById('emailMahasiswa').value;
        const kontakMahasiswa = document.getElementById('kontakMahasiswa').value;
        const genderMahasiswa = document.getElementById('genderMahasiswa').value;
        const ipkMahasiswa = document.getElementById('ipkMahasiswa').value;

        const tbody = document.querySelector('.table tbody');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${nimMahasiswa}</td>
            <td class="text-start">${namaMahasiswa}</td>
            <td>${angkatanMahasiswa}</td>
            <td>${emailMahasiswa}</td>
            <td>${kontakMahasiswa}</td>
            <td>${genderMahasiswa}</td>
            <td>${ipkMahasiswa}</td>
            <td>
                <button class="btn btn-outline-primary btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editMahasiswaModal" data-nim="${nimMahasiswa}" data-nama="${namaMahasiswa}" data-angkatan="${angkatanMahasiswa}" data-email="${emailMahasiswa}" data-kontak="${kontakMahasiswa}" data-gender="${genderMahasiswa}" data-ipk="${ipkMahasiswa}">Edit</button>
                <button class="btn btn-outline-danger btn-sm delete-btn" data-bs-toggle="modal" data-bs-target="#deleteMahasiswaModal" data-nim="${nimMahasiswa}">Hapus</button>
            </td>
        `;
        tbody.appendChild(tr);

        document.getElementById('addMahasiswaForm').reset();
        const addMahasiswaModal = new bootstrap.Modal(document.getElementById('addMahasiswaModal'));
        addMahasiswaModal.hide();
    });

    // Logika untuk mengisi data form edit dengan data yang sesuai
    document.getElementById('editMahasiswaModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const nimMahasiswa = button.getAttribute('data-nim');
        const namaMahasiswa = button.getAttribute('data-nama');
        const angkatanMahasiswa = button.getAttribute('data-angkatan');
        const emailMahasiswa = button.getAttribute('data-email');
        const kontakMahasiswa = button.getAttribute('data-kontak');
        const genderMahasiswa = button.getAttribute('data-gender');
        const ipkMahasiswa = button.getAttribute('data-ipk');

        const modal = this;
        modal.querySelector('#editNimMahasiswa').value = nimMahasiswa;
        modal.querySelector('#editNamaMahasiswa').value = namaMahasiswa;
        modal.querySelector('#editAngkatanMahasiswa').value = angkatanMahasiswa;
        modal.querySelector('#editEmailMahasiswa').value = emailMahasiswa;
        modal.querySelector('#editKontakMahasiswa').value = kontakMahasiswa;
        modal.querySelector('#editGenderMahasiswa').value = genderMahasiswa;
        modal.querySelector('#editIpkMahasiswa').value = ipkMahasiswa;
    });

    // Logika untuk menyimpan perubahan pada Mahasiswa
    document.getElementById('saveEditButton').addEventListener('click', function() {
        const nimMahasiswa = document.getElementById('editNimMahasiswa').value;
        const namaMahasiswa = document.getElementById('editNamaMahasiswa').value;
        const angkatanMahasiswa = document.getElementById('editAngkatanMahasiswa').value;
        const emailMahasiswa = document.getElementById('editEmailMahasiswa').value;
        const kontakMahasiswa = document.getElementById('editKontakMahasiswa').value;
        const genderMahasiswa = document.getElementById('editGenderMahasiswa').value;
        const ipkMahasiswa = document.getElementById('editIpkMahasiswa').value;

        const rows = document.querySelectorAll('.table tbody tr');
        rows.forEach(row => {
            if (row.children[0].textContent === nimMahasiswa) {
                row.children[1].textContent = namaMahasiswa;
                row.children[2].textContent = angkatanMahasiswa;
                row.children[3].textContent = emailMahasiswa;
                row.children[4].textContent = kontakMahasiswa;
                row.children[5].textContent = genderMahasiswa;
                row.children[6].textContent = ipkMahasiswa;
            }
        });

        const editMahasiswaModal = new bootstrap.Modal(document.getElementById('editMahasiswaModal'));
        editMahasiswaModal.hide();
    });

    // Logika untuk menghapus Mahasiswa
    document.getElementById('deleteMahasiswaModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const nimMahasiswa = button.getAttribute('data-nim');

        document.getElementById('confirmDeleteMahasiswa').addEventListener('click', function() {
            const rows = document.querySelectorAll('.table tbody tr');
            rows.forEach(row => {
                if (row.children[0].textContent === nimMahasiswa) {
                    row.remove();
                }
            });

            const deleteMahasiswaModal = new bootstrap.Modal(document.getElementById('deleteMahasiswaModal'));
            deleteMahasiswaModal.hide();
        }, { once: true });
    });
</script>
@endsection