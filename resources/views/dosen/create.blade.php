@extends('layouts.app')

@section('title', 'Tambah Dosen Baru')

@section('content')
<div class="container-fluid">
    <h1 class="dashboard-heading mt-4">Tambah Dosen Baru</h1>

    {{-- Notifikasi Sukses --}}
    @if (session('success_dosen_ajax')) {{-- Menggunakan key session yang berbeda untuk AJAX --}}
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success_dosen_ajax') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div id="successAjaxAlert" class="alert alert-success alert-dismissible fade show d-none" role="alert">
        {{-- Pesan sukses AJAX akan diisi oleh JavaScript --}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>


    {{-- Notifikasi Error Validasi Laravel (jika tidak menggunakan AJAX atau AJAX gagal di sisi server sebelum validasi) --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    {{-- Notifikasi Error AJAX --}}
    <div id="errorAjaxAlert" class="alert alert-danger alert-dismissible fade show d-none" role="alert">
        {{-- Pesan error AJAX akan diisi oleh JavaScript --}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>


    {{-- Tombol Aksi (Kembali) --}}
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-end align-items-center">
            <a href="{{ route('dosen.index') }}"
               class="btn btn-secondary rounded-circle me-2 d-flex justify-content-center align-items-center"
               title="Kembali ke Daftar Dosen"
               style="width: 40px; height: 40px;">
                <i class="fas fa-user-tie text-white"></i> {{-- Mengganti ikon panah dengan ikon dosen --}}
            </a>
        </div>
    </div>

    {{-- Form Tambah Dosen --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-user-plus me-2"></i> Form Input Dosen Baru
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('dosen.store') }}" id="addDosenForm">
                @csrf
                <div class="mb-3">
                    <label for="nidn" class="form-label">NIDN</label>
                    <input type="text" class="form-control" id="nidn" name="nidn" value="{{ old('nidn') }}" required>
                    <div class="invalid-feedback" data-field="nidn"></div>
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Dosen</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" required>
                    <div class="invalid-feedback" data-field="nama"></div>
                </div>
                <div class="mb-3">
                    <label for="gelar" class="form-label">Gelar</label>
                    <input type="text" class="form-control" id="gelar" name="gelar" value="{{ old('gelar') }}" required>
                    <div class="invalid-feedback" data-field="gelar"></div>
                </div>
                <div class="mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required> {{-- Mengubah class menjadi form-select --}}
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    <div class="invalid-feedback" data-field="jenis_kelamin"></div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                    <div class="invalid-feedback" data-field="email"></div>
                </div>
                <div class="mb-3">
                    <label for="kontak" class="form-label">Kontak</label>
                    <input type="text" class="form-control" id="kontak" name="kontak" value="{{ old('kontak') }}"> {{-- Mengubah type menjadi text untuk nomor telepon yang mungkin ada + atau spasi --}}
                    <div class="invalid-feedback" data-field="kontak"></div>
                </div>
                <div class="mb-3">
                    <label for="jabatan" class="form-label">Jabatan</label>
                    <input type="text" class="form-control" id="jabatan" name="jabatan" value="{{ old('jabatan') }}" required>
                    <div class="invalid-feedback" data-field="jabatan"></div>
                </div>
                <div class="mb-3">
                    <label for="kompetensi" class="form-label">Kompetensi</label>
                    <input type="text" class="form-control" id="kompetensi" name="kompetensi" value="{{ old('kompetensi') }}" required>
                    <div class="invalid-feedback" data-field="kompetensi"></div>
                </div>
                <div class="mb-3">
                    <label for="prodi_id" class="form-label">Prodi</label> {{-- Mengubah nama field agar konsisten jika ada relasi --}}
                    <select class="form-select" id="prodi_id" name="prodi_id" required> {{-- Mengubah class dan nama --}}
                        <option value="">-- Pilih Prodi --</option>
                        {{-- Asumsi Anda akan mengisi opsi ini dari controller, contoh: --}}
                        {{-- @foreach($prodis as $prodi) --}}
                        {{-- <option value="{{ $prodi->id }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama_prodi }}</option> --}}
                        {{-- @endforeach --}}
                        {{-- Untuk sementara, menggunakan opsi statis seperti di kode asli Anda --}}
                        <option value="Informatika" {{ old('prodi_id', old('prodi')) == 'Informatika' ? 'selected' : '' }}>Informatika</option>
                        <option value="Sistem Informasi" {{ old('prodi_id', old('prodi')) == 'Sistem Informasi' ? 'selected' : '' }}>Sistem Informasi</option>
                        <option value="Rekayasa Perangkat Lunak" {{ old('prodi_id', old('prodi')) == 'Rekayasa Perangkat Lunak' ? 'selected' : '' }}>Rekayasa Perangkat Lunak</option>
                    </select>
                    <div class="invalid-feedback" data-field="prodi_id"></div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                    <a href="{{ route('dosen.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts') {{-- Menggunakan @section('scripts') untuk JS --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('addDosenForm');
        const successAlert = document.getElementById('successAjaxAlert');
        const errorAlert = document.getElementById('errorAjaxAlert');

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            // Hapus pesan error sebelumnya
            document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            successAlert.classList.add('d-none');
            errorAlert.classList.add('d-none');

            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';
            submitButton.disabled = true;


            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json' // Penting untuk menerima respons JSON dari Laravel
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    // Jika respons bukan JSON atau status error, coba parse sebagai JSON untuk pesan error validasi
                    return response.json().then(errData => {
                        throw { status: response.status, data: errData };
                    }).catch(() => {
                        // Jika gagal parse JSON, lempar error umum
                        throw { status: response.status, data: { message: 'Terjadi kesalahan server.' } };
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.message) {
                    successAlert.innerHTML = data.message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    successAlert.classList.remove('d-none');
                }
                form.reset();
                document.getElementById('nidn').focus(); // Fokus kembali ke field pertama

                // Sembunyikan alert setelah 3 detik
                setTimeout(() => {
                    successAlert.classList.add('d-none');
                }, 3000);
            })
            .catch(error => {
                console.error(error);
                let errorMessage = 'Gagal menambahkan dosen. Silakan coba lagi.';
                if (error.data && error.data.message) {
                    errorMessage = error.data.message; // Pesan error utama dari server
                    if (error.data.errors) { // Jika ada error validasi
                        errorMessage += '<ul class="mb-0 mt-2">';
                        Object.keys(error.data.errors).forEach(key => {
                            const field = document.querySelector(`[name="${key}"]`);
                            if (field) {
                                field.classList.add('is-invalid');
                                const feedbackElement = field.parentElement.querySelector(`.invalid-feedback[data-field="${key}"]`);
                                if (feedbackElement) {
                                    feedbackElement.textContent = error.data.errors[key].join(', ');
                                }
                            }
                            errorMessage += `<li>${error.data.errors[key].join(', ')}</li>`;
                        });
                        errorMessage += '</ul>';
                    }
                }
                errorAlert.innerHTML = errorMessage + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                errorAlert.classList.remove('d-none');
            })
            .finally(() => {
                submitButton.innerHTML = originalButtonText;
                submitButton.disabled = false;
            });
        });
    });
</script>
@endsection

@section('styles')
<style>
    body {
        background-color: #def4ff; /* Latar belakang biru muda */
        font-family: 'Inter', sans-serif; /* Font yang konsisten */
    }

    .dashboard-heading {
        font-size: 2rem; /* Judul lebih besar */
        font-weight: bold;
        color: #333; /* Teks lebih gelap untuk judul */
        margin-bottom: 1.5rem; /* Jarak di bawah judul */
    }

    .card-header {
        /* background-color: rgb(0, 114, 202); Sudah diatur oleh bg-primary */
        /* color: white; Sudah diatur oleh text-white */
        font-weight: 500; /* Berat font medium untuk header kartu */
    }

    .form-label {
        font-weight: 500; /* Label sedikit lebih tebal */
    }

    .btn-primary {
        background-color: rgb(0, 114, 202);
        border-color: rgb(0, 114, 202);
    }
    .btn-primary:hover {
        background-color: #005ea0;
        border-color: #005ea0;
    }
    .btn-secondary {
        /* Gaya sekunder Bootstrap standar atau tema kustom Anda */
    }
    .btn-secondary:hover {
        /* Gaya hover sekunder Bootstrap standar atau tema kustom Anda */
    }

    .form-control, .form-select {
        border-radius: 0.375rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0072ca;
        box-shadow: 0 0 0 0.25rem rgba(0, 114, 202, 0.25);
    }

    .is-invalid {
        border-color: #dc3545; /* Warna bahaya Bootstrap */
    }
    .invalid-feedback {
        display: none; /* Sembunyikan secara default, tampilkan saat ada error */
        width: 100%;
        margin-top: 0.25rem;
        font-size: .875em;
        color: #dc3545;
    }
    .is-invalid ~ .invalid-feedback {
        display: block; /* Tampilkan jika field terkait is-invalid */
    }


    .btn.rounded-circle {
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    }
    .btn.rounded-circle:hover {
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
</style>
@endsection
