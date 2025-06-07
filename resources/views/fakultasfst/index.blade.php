@extends('layouts.app')

@section('title', 'Fakultas FST Sains dan Teknologi')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4 text-purple">Fakultas Sains dan Teknologi</h1>

        {{-- Notifikasi Sukses/Error --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-success rounded-circle me-2 d-flex justify-content-center align-items-center"
                style="width: 40px; height: 40px;" data-bs-toggle="modal" data-bs-target="#addProdiModal"
                title="Tambah Prodi">
                <i class="fas fa-plus text-white"></i>
            </button>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        {{-- Inline style di thead ini akan diprioritaskan untuk thead element, 
                             namun style untuk th di bawahnya juga penting --}}
                        <thead style="background-color: #2f5f98; color: #fff;">
                            <tr>
                                <th>No.</th>
                                <th>Prodi</th>
                                <th>Jumlah Mahasiswa</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($prodis as $index => $prodi)
                                <tr data-id="{{ $prodi->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-start">{{ $prodi->nama_prodi }}</td>
                                    <td>{{ $prodi->mahasiswas_count }}</td>
                                    <td>
                                        @if ($prodi->status == 'aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('mahasiswa.index', ['prodi_id' => $prodi->id]) }}"
                                            class="btn btn-outline-info btn-sm" title="Lihat Mahasiswa Prodi Ini"><i
                                                class="fas fa-users"></i></a>
                                        <button class="btn btn-outline-primary btn-sm edit-btn"
                                            data-id="{{ $prodi->id }}" data-nama="{{ $prodi->nama_prodi }}"
                                            data-status="{{ $prodi->status }}" title="Edit Prodi">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm delete-btn"
                                            data-id="{{ $prodi->id }}" data-nama="{{ $prodi->nama_prodi }}"
                                            title="Hapus Prodi">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data program studi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if (method_exists($prodis, 'links'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $prodis->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="addProdiModal" tabindex="-1" aria-labelledby="addProdiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProdiModalLabel">Tambah Prodi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addProdiForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="prodiNama" class="form-label">Nama Prodi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="prodiNama" name="nama_prodi" required>
                        </div>
                        <div class="mb-3">
                            <label for="prodiStatus" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="prodiStatus" name="status" required>
                                <option value="aktif" selected>Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editProdiModal" tabindex="-1" aria-labelledby="editProdiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProdiModalLabel">Edit Prodi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editProdiForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editProdiId" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editProdiNama" class="form-label">Nama Prodi <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editProdiNama" name="nama_prodi" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProdiStatus" class="form-label">Status <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="editProdiStatus" name="status" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteProdiModal" tabindex="-1" aria-labelledby="deleteProdiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteProdiModalLabel">Konfirmasi Hapus Prodi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteProdiForm">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="deleteProdiId" name="id">
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus program studi <strong id="prodiToDelete"
                            class="text-danger"></strong>? Tindakan ini tidak dapat diurungkan.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        body {
            background-color: #def4ff;
            /* DIKEMBALIKAN ke warna awal Anda */
        }

        .table thead {
            /* Ini akan menargetkan elemen thead */
            /* background-color: #2f5f98 !important; */
            /* Sebenarnya tidak perlu jika th sudah di-style atau ada inline style di thead */
            /* color: #fff !important; */
        }

        .table th {
            /* Style untuk semua sel header (th) DIKEMBALIKAN */
            background-color: #2f5f98 !important;
            color: #fff !important;
            vertical-align: middle;
            /* Ini tambahan yang bermanfaat */
        }

        .table td {
            /* Ditambahkan untuk konsistensi vertical-align */
            vertical-align: middle;
        }

        .text-purple {
            /* DIKEMBALIKAN ke definisi awal Anda (hitam) */
            color: rgb(0, 0, 0) !important;
            /* font-weight: bold; */
            /* font-weight bold tidak ada di style awal Anda untuk kelas ini */
        }

        /* Style tambahan yang bermanfaat dan tidak terkait langsung dengan isu "putih" dipertahankan */
        .btn-outline-info {
            color: #0dcaf0;
            border-color: #0dcaf0;
        }

        .btn-outline-info:hover {
            color: #000;
            background-color: #0dcaf0;
            border-color: #0dcaf0;
        }

        .form-select {
            border-radius: 0.25rem;
        }

        .badge {
            font-size: 0.85em;
        }

        /* Add these new styles */
        .modal-backdrop {
            z-index: 1040 !important;
        }

        .modal {
            z-index: 1045 !important;
        }

        /* Ensure dropdowns appear above modals */
        .dropdown-menu {
            z-index: 1055 !important;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        xintegrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addProdiModalElement = document.getElementById('addProdiModal');
            const addProdiModal = addProdiModalElement ? new bootstrap.Modal(addProdiModalElement) : null;
            const editProdiModalElement = document.getElementById('editProdiModal');
            const editProdiModal = editProdiModalElement ? new bootstrap.Modal(editProdiModalElement) : null;
            const deleteProdiModalElement = document.getElementById('deleteProdiModal');
            const deleteProdiModal = deleteProdiModalElement ? new bootstrap.Modal(deleteProdiModalElement) : null;

            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const nama = this.dataset.nama;
                    const status = this.dataset.status;

                    document.getElementById('editProdiId').value = id;
                    document.getElementById('editProdiNama').value = nama;
                    document.getElementById('editProdiStatus').value = status;

                    if (editProdiModal) editProdiModal.show();
                });
            });

            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const nama = this.dataset.nama;

                    document.getElementById('deleteProdiId').value = id;
                    document.getElementById('prodiToDelete').textContent = nama;

                    if (deleteProdiModal) deleteProdiModal.show();
                });
            });

            function handleFormSubmit(formId, url, method, successCallback) {
                const form = document.getElementById(formId);
                if (!form) return;

                form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const formData = new FormData(form);

                        let dynamicUrl = url;
                        if (method.toUpperCase() === 'PUT' || method.toUpperCase() === 'DELETE') {
                            const id = formData.get('id') || (method.toUpperCase() === 'PUT' ? document
                                .getElementById('editProdiId').value : document.getElementById(
                                    'deleteProdiId').value);
                            if (id) {
                                dynamicUrl = url.replace(':id', id);
                            } else {
                                console.error(
                                    'ID for PUT/DELETE operation not found in form data or hidden input.');
                                alert('Terjadi kesalahan: ID tidak ditemukan untuk operasi ini.');
                                return;
                            }
                        }

                        fetch(dynamicUrl, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        ?.getAttribute('content') || form.querySelector('[name="_token"]')
                                        ?.value,
                                    'Accept': 'application/json',
                                },
                                body: formData
                            })
                            .then(response => {
                                if (!response.ok) {
                                    return response.json().then(errData => {
                                        throw {
                                            status: response.status,
                                            data: errData
                                        };
                                    });
                                }
                                return response.json();
                            })
                            .then data => {
                                if (data.redirect_url) {
                                    window.location.href = data.redirect_url;
                                } else {
                                    location.reload();
                                }
                                if (successCallback) successCallback();
                            })
                    .catch(errorInfo => {
                        console.error('Error:', errorInfo);
                        let errorMessage = 'Terjadi kesalahan. Periksa konsol untuk detail.';
                        if (errorInfo && errorInfo.data && errorInfo.data.message) {
                            errorMessage = errorInfo.data.message;
                            if (errorInfo.data.errors) {
                                for (const key in errorInfo.data.errors) {
                                    errorMessage += `\n- ${errorInfo.data.errors[key].join(', ')}`;
                                }
                            }
                        } else if (errorInfo && errorInfo.message) {
                            errorMessage = errorInfo.message;
                        }
                        alert(errorMessage);
                    });
                });
        }

        handleFormSubmit('addProdiForm', "{{ route('fakultasfst.prodi.store') }}", 'POST', () => {
            if (addProdiModal) addProdiModal.hide();
        }); handleFormSubmit('editProdiForm', "{{ url('fakultasfst/prodi') }}/:id", 'PUT', () => {
            if (editProdiModal) editProdiModal.hide();
        }); handleFormSubmit('deleteProdiForm', "{{ url('fakultasfst/prodi') }}/:id", 'DELETE', () => {
            if (deleteProdiModal) deleteProdiModal.hide();
        });

        const alertNode = document.querySelector('.alert-dismissible.show');
        if (alertNode && typeof bootstrap !== 'undefined' && bootstrap.Alert) {
            setTimeout(function() {
                new bootstrap.Alert(alertNode).close();
            }, 5000);
        } else if (alertNode) {
            setTimeout(function() {
                alertNode.classList.remove('show');
                setTimeout(() => alertNode.remove(), 150);
            }, 5000);
        }
        });
    </script>
@endsection
