@extends('layouts.app')

@section('title', 'Edit Mata Kuliah')

@section('content')
<div class="container-fluid">
    <h1 class="dashboard-heading mt-4">Edit Mata Kuliah</h1>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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

    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-end align-items-center">
            <a href="{{ route('mata_kuliah.index') }}"
               class="btn btn-secondary rounded-circle me-2 d-flex justify-content-center align-items-center"
               title="Kembali ke Daftar Mata Kuliah"
               style="width: 40px; height: 40px;">
                <i class="fas fa-arrow-left text-white"></i>
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-edit me-2"></i> Form Edit Mata Kuliah
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('mata_kuliah.update', $mataKuliah->kode_mk) }}" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label for="kode_mk" class="form-label">Kode Mata Kuliah</label>
                    <input type="text" name="kode_mk" id="kode_mk" class="form-control @error('kode_mk') is-invalid @enderror"
                           value="{{ old('kode_mk', $mataKuliah->kode_mk) }}" required>
                    @error('kode_mk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="nama_mk" class="form-label">Nama Mata Kuliah</label>
                    <input type="text" name="nama_mk" id="nama_mk" class="form-control @error('nama_mk') is-invalid @enderror"
                           value="{{ old('nama_mk', $mataKuliah->nama_mk) }}" required>
                    @error('nama_mk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="semester" class="form-label">Semester</label>
                    <select name="semester" id="semester" class="form-select @error('semester') is-invalid @enderror" required>
                        <option value="">-- Pilih Semester --</option>
                        @for ($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}"
                                {{ old('semester', $mataKuliah->semester) == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                    @error('semester')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="sks_teori" class="form-label">SKS Teori</label>
                    <input type="number" name="sks_teori" id="sks_teori" class="form-control @error('sks_teori') is-invalid @enderror"
                           value="{{ old('sks_teori', $mataKuliah->sks_teori) }}" required min="0">
                    @error('sks_teori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="sks_praktik" class="form-label">SKS Praktik</label>
                    <input type="number" name="sks_praktik" id="sks_praktik" class="form-control @error('sks_praktik') is-invalid @enderror"
                           value="{{ old('sks_praktik', $mataKuliah->sks_praktik) }}" required min="0">
                    @error('sks_praktik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-12">
                    <label for="status_mata_kuliah" class="form-label">Status Mata Kuliah</label>
                    <select name="status_mata_kuliah" id="status_mata_kuliah" class="form-select @error('status_mata_kuliah') is-invalid @enderror" required>
                        <option value="Wajib Prodi"
                            {{ old('status_mata_kuliah', $mataKuliah->status_mata_kuliah) == 'Wajib Prodi' ? 'selected' : '' }}>
                            Wajib Prodi
                        </option>
                        <option value="Pilihan"
                            {{ old('status_mata_kuliah', $mataKuliah->status_mata_kuliah) == 'Pilihan' ? 'selected' : '' }}>
                            Pilihan
                        </option>
                        <option value="Wajib Fakultas"
                            {{ old('status_mata_kuliah', $mataKuliah->status_mata_kuliah) == 'Wajib Fakultas' ? 'selected' : '' }}>
                            Wajib Fakultas
                        </option>
                        <option value="Wajib Universitas"
                            {{ old('status_mata_kuliah', $mataKuliah->status_mata_kuliah) == 'Wajib Universitas' ? 'selected' : '' }}>
                            Wajib Universitas
                        </option>
                    </select>
                    @error('status_mata_kuliah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Dosen Pengampu</label>
                    <div id="dosen-buttons" class="d-flex flex-wrap gap-2 mb-2">
                        @foreach ($dosens as $dosen)
                            <button type="button"
                                class="btn btn-outline-primary dosen-btn {{ in_array($dosen->id, old('dosen_ids', $mataKuliah->dosens->pluck('id')->toArray())) ? 'active' : '' }}"
                                data-id="{{ $dosen->id }}">
                                {{ $dosen->nama }} ({{ $dosen->nidn }})
                            </button>
                        @endforeach
                    </div>
                    <div id="dosen-hidden-inputs">
                        @if(old('dosen_ids'))
                            @foreach (old('dosen_ids') as $dosen_id)
                                <input type="hidden" name="dosen_ids[]" value="{{ $dosen_id }}">
                            @endforeach
                        @else
                            @foreach ($mataKuliah->dosens as $dosen)
                                <input type="hidden" name="dosen_ids[]" value="{{ $dosen->id }}">
                            @endforeach
                        @endif
                    </div>
                    @error('dosen_ids')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    @error('dosen_ids.*')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Pilihan Mahasiswa dengan Search dan Tabel --}}
                <div class="col-12">
                    <label class="form-label">Mahasiswa Terdaftar (Opsional)</label>
                    <!-- Input Pencarian Mahasiswa -->
                    <div class="mb-3">
                        <input type="text" id="mahasiswa-search-input" class="form-control" placeholder="Cari Mahasiswa berdasarkan Nama atau NIM...">
                    </div>

                    <!-- Tabel Hasil Pencarian Mahasiswa -->
                    <div class="table-responsive mb-3" style="max-height: 200px; overflow-y: auto;" id="mahasiswa-search-results-container">
                        <table class="table table-sm table-bordered table-hover">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="mahasiswa-search-results-tbody">
                                <tr><td colspan="3" class="text-center text-muted">Ketik untuk mencari mahasiswa.</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Tabel Mahasiswa Terpilih -->
                    <label class="form-label">Mahasiswa Terpilih:</label>
                    <div class="table-responsive" id="selected-mahasiswa-container">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="selected-mahasiswa-tbody"></tbody>
                        </table>
                    </div>

                    <!-- Hidden inputs untuk menyimpan NIM mahasiswa terpilih -->
                    <div id="mahasiswa-hidden-inputs"></div>
                    @error('mahasiswa_nims') <small class="text-danger">{{ $message }}</small> @enderror
                    @error('mahasiswa_nims.*') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript for Dosen Selection --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dosenButtonsContainer = document.getElementById('dosen-buttons');
    const dosenHiddenInputsContainer = document.getElementById('dosen-hidden-inputs');

    // Variabel untuk Mahasiswa Search
    const mahasiswaSearchInput = document.getElementById('mahasiswa-search-input');
    const mahasiswaSearchResultsTbody = document.getElementById('mahasiswa-search-results-tbody');
    const selectedMahasiswaTbody = document.getElementById('selected-mahasiswa-tbody');
    const mahasiswaHiddenInputsContainer = document.getElementById('mahasiswa-hidden-inputs');
    let selectedNimsSet = new Set();

    // Function to add hidden input for selected dosen
    function addDosenInput(id) {
        if (!dosenHiddenInputsContainer.querySelector('input[name="dosen_ids[]"][value="' + id + '"]')) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'dosen_ids[]';
            input.value = id;
            dosenHiddenInputsContainer.appendChild(input);
        }
    }

    // Function to remove hidden input for deselected dosen
    function removeDosenInput(id) {
        const input = dosenHiddenInputsContainer.querySelector('input[name="dosen_ids[]"][value="' + id + '"]');
        if (input) {
            input.remove();
        }
    }

    // Event listener for dosen buttons
    dosenButtonsContainer.addEventListener('click', function(event) {
        if (event.target.classList.contains('dosen-btn')) {
            const button = event.target;
            const dosenId = button.getAttribute('data-id');
            button.classList.toggle('active');

            if (button.classList.contains('active')) {
                addDosenInput(dosenId);
            } else {
                removeDosenInput(dosenId);
            }
        }
    });

    // --- Fungsi untuk Mahasiswa (Search & Select) ---
    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    function addMahasiswaHiddenInput(nim) {
        if (!mahasiswaHiddenInputsContainer.querySelector('input[name="mahasiswa_nims[]"][value="' + nim + '"]')) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'mahasiswa_nims[]';
            input.value = nim;
            mahasiswaHiddenInputsContainer.appendChild(input);
        }
    }

    function removeMahasiswaHiddenInput(nim) {
        const input = mahasiswaHiddenInputsContainer.querySelector('input[name="mahasiswa_nims[]"][value="' + nim + '"]');
        if (input) input.remove();
    }

    function addMahasiswaToSelectedTable(mahasiswa) {
        if (selectedNimsSet.has(mahasiswa.nim)) return;
        selectedNimsSet.add(mahasiswa.nim);
        addMahasiswaHiddenInput(mahasiswa.nim);

        const tr = document.createElement('tr');
        tr.setAttribute('data-nim', mahasiswa.nim);
        tr.innerHTML = `
            <td>${mahasiswa.nim}</td>
            <td>${mahasiswa.nama}</td>
            <td><button type="button" class="btn btn-danger btn-sm remove-selected-mahasiswa-btn" data-nim="${mahasiswa.nim}">Hapus</button></td>
        `;
        selectedMahasiswaTbody.appendChild(tr);
        updateSearchResultsTable();
    }

    function removeMahasiswaFromSelectedTable(nim) {
        selectedNimsSet.delete(nim);
        removeMahasiswaHiddenInput(nim);
        const tr = selectedMahasiswaTbody.querySelector(`tr[data-nim="${nim}"]`);
        if (tr) tr.remove();
        updateSearchResultsTable();
    }

    function renderSearchResults(mahasiswas) {
        mahasiswaSearchResultsTbody.innerHTML = '';
        if (mahasiswas.length === 0) {
            mahasiswaSearchResultsTbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Tidak ada mahasiswa ditemukan.</td></tr>';
            return;
        }
        mahasiswas.forEach(mhs => {
            const tr = document.createElement('tr');
            const isSelected = selectedNimsSet.has(mhs.nim);
            tr.innerHTML = `
                <td>${mhs.nim}</td>
                <td>${mhs.nama}</td>
                <td>
                    <button type="button" class="btn btn-success btn-sm add-mahasiswa-btn" 
                            data-nim="${mhs.nim}" data-nama="${mhs.nama}" ${isSelected ? 'disabled' : ''}>
                        Tambah
                    </button>
                </td>
            `;
            mahasiswaSearchResultsTbody.appendChild(tr);
        });
    }
    
    function updateSearchResultsTable() {
        const addButtons = mahasiswaSearchResultsTbody.querySelectorAll('.add-mahasiswa-btn');
        addButtons.forEach(button => {
            button.disabled = selectedNimsSet.has(button.getAttribute('data-nim'));
        });
    }

    async function fetchMahasiswas(query) {
        if (query.trim() === '') {
            mahasiswaSearchResultsTbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Ketik untuk mencari mahasiswa.</td></tr>';
            return;
        }
        try {
            const params = new URLSearchParams();
            params.append('q', query);
            Array.from(selectedNimsSet).forEach(nim => params.append('selected_nims[]', nim));
            
            const response = await fetch(`{{ route('mahasiswa.search.json') }}?${params.toString()}`);
            if (!response.ok) {
                const errorText = await response.text();
                mahasiswaSearchResultsTbody.innerHTML = `<tr><td colspan="3" class="text-center text-danger">Gagal: ${response.status}</td></tr>`;
                throw new Error(`Network error: ${response.status} ${errorText}`);
            }
            const data = await response.json();
            renderSearchResults(data);
        } catch (error) {
            console.error("Fetch error:", error);
            if (!mahasiswaSearchResultsTbody.innerHTML.includes('text-danger')) {
                 mahasiswaSearchResultsTbody.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Error pencarian.</td></tr>';
            }
        }
    }

    mahasiswaSearchInput.addEventListener('input', debounce(e => fetchMahasiswas(e.target.value), 300));

    mahasiswaSearchResultsTbody.addEventListener('click', e => {
        if (e.target.classList.contains('add-mahasiswa-btn')) {
            addMahasiswaToSelectedTable({ nim: e.target.dataset.nim, nama: e.target.dataset.nama });
            e.target.disabled = true;
        }
    });

    selectedMahasiswaTbody.addEventListener('click', e => {
        if (e.target.classList.contains('remove-selected-mahasiswa-btn')) {
            removeMahasiswaFromSelectedTable(e.target.dataset.nim);
        }
    });

    // Inisialisasi Mahasiswa Terpilih dari data mata kuliah atau 'old' input
    const repopulatedSelectedMahasiswas = {!! json_encode($repopulatedSelectedMahasiswas) !!};
    repopulatedSelectedMahasiswas.forEach(mhs => addMahasiswaToSelectedTable(mhs));
    // --- Akhir Fungsi untuk Mahasiswa ---
});
</script>
@endsection

@section('styles')
<style>
    body {
        background-color: #def4ff; /* Light blue background */
        font-family: 'Inter', sans-serif; /* Consistent font */
    }

    .dashboard-heading {
        font-size: 2rem; /* Larger heading */
        font-weight: bold;
        color: #333; /* Darker text for heading */
        margin-bottom: 1.5rem; /* Spacing below heading */
    }

    .card-header {
        /* background-color: rgb(0, 114, 202); Already set by bg-primary */
        /* color: white; Already set by text-white */
        font-weight: 500; /* Medium font weight for card header */
    }

    .form-label {
        font-weight: 500; /* Slightly bolder labels */
    }

    /* Custom styles for dosen selection buttons */
    .dosen-btn {
        transition: all 0.3s ease;
        border-radius: 0.375rem; /* Standard Bootstrap border-radius */
    }

    .dosen-btn.active,
    .dosen-btn:active { /* Ensure :active also gets the style for immediate feedback */
        background-color: #0072ca !important; /* Primary color */
        color: #fff !important;
        border-color: #0072ca !important;
        box-shadow: 0 0 0 0.25rem rgba(0, 114, 202, 0.5); /* Focus ring like Bootstrap */
    }

    .btn-outline-primary:hover {
        background-color: #005ea0; /* Darker shade on hover */
        color: #fff;
    }

    .btn-primary {
        background-color: rgb(0, 114, 202);
        border-color: rgb(0, 114, 202);
    }
    .btn-primary:hover {
        background-color: #005ea0;
        border-color: #005ea0;
    }

    /* Ensure form controls have a consistent look */
    .form-control, .form-select {
        border-radius: 0.375rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0072ca;
        box-shadow: 0 0 0 0.25rem rgba(0, 114, 202, 0.25);
    }

    /* Style for invalid feedback */
    .is-invalid {
        border-color: #dc3545; /* Bootstrap danger color */
    }
    .invalid-feedback {
        display: block; /* Ensure it's always shown when present */
    }

    /* Rounded circle button for back action */
    .btn.rounded-circle {
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    }
    .btn.rounded-circle:hover {
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    /* Style untuk tabel mahasiswa agar header tetap terlihat saat scroll */
    #mahasiswa-search-results-container .table-light.sticky-top th {
        position: sticky;
        top: 0;
        z-index: 1;
    }
    #mahasiswa-search-results-container, #selected-mahasiswa-container {
        background-color: #fff;
    }
</style>
@endsection
