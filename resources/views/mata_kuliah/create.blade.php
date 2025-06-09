@extends('layouts.app')

@section('title', 'Tambah Mata Kuliah')

@section('content')
<div class="container-fluid">
    <h1 class="dashboard-heading mt-4">Tambah Mata Kuliah</h1>

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
            <i class="fas fa-plus-circle me-2"></i> Form Input Mata Kuliah
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('mata_kuliah.store') }}" class="row g-3">
                @csrf

                <div class="col-md-6">
                    <label for="kode_mk" class="form-label">Kode Mata Kuliah</label>
                    <input type="text" name="kode_mk" id="kode_mk" class="form-control @error('kode_mk') is-invalid @enderror"
                           value="{{ old('kode_mk') }}" required>
                    @error('kode_mk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="nama_mk" class="form-label">Nama Mata Kuliah</label>
                    <input type="text" name="nama_mk" id="nama_mk" class="form-control @error('nama_mk') is-invalid @enderror"
                           value="{{ old('nama_mk') }}" required>
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
                                {{ old('semester') == $i ? 'selected' : '' }}>
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
                           value="{{ old('sks_teori') }}" required min="0">
                    @error('sks_teori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="sks_praktik" class="form-label">SKS Praktik</label>
                    <input type="number" name="sks_praktik" id="sks_praktik" class="form-control @error('sks_praktik') is-invalid @enderror"
                           value="{{ old('sks_praktik') }}" required min="0">
                    @error('sks_praktik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-12">
                    <label for="status_mata_kuliah" class="form-label">Status Mata Kuliah</label>
                    <select name="status_mata_kuliah" id="status_mata_kuliah" class="form-select @error('status_mata_kuliah') is-invalid @enderror" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="Wajib Prodi"
                            {{ old('status_mata_kuliah') == 'Wajib Prodi' ? 'selected' : '' }}>
                            Wajib Prodi
                        </option>
                        <option value="Pilihan"
                            {{ old('status_mata_kuliah') == 'Pilihan' ? 'selected' : '' }}>
                            Pilihan
                        </option>
                        <option value="Wajib Fakultas"
                            {{ old('status_mata_kuliah') == 'Wajib Fakultas' ? 'selected' : '' }}>
                            Wajib Fakultas
                        </option>
                        <option value="Wajib Universitas"
                            {{ old('status_mata_kuliah') == 'Wajib Universitas' ? 'selected' : '' }}>
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
                                class="btn btn-outline-primary dosen-btn {{ in_array($dosen->id, old('dosen_ids', [])) ? 'active' : '' }}"
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
                    <div class="mb-3">
                        <input type="text" id="mahasiswa-search-input" class="form-control" placeholder="Cari Mahasiswa berdasarkan Nama atau NIM...">
                    </div>
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
                    <div id="mahasiswa-hidden-inputs"></div>
                    @error('mahasiswa_nims') <small class="text-danger">{{ $message }}</small> @enderror
                    @error('mahasiswa_nims.*') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dosenButtonsContainer = document.getElementById('dosen-buttons');
    const dosenHiddenInputsContainer = document.getElementById('dosen-hidden-inputs');

    const mahasiswaSearchInput = document.getElementById('mahasiswa-search-input');
    const mahasiswaSearchResultsTbody = document.getElementById('mahasiswa-search-results-tbody');
    const selectedMahasiswaTbody = document.getElementById('selected-mahasiswa-tbody');
    const mahasiswaHiddenInputsContainer = document.getElementById('mahasiswa-hidden-inputs');
    let selectedNimsSet = new Set();

    // --- FUNGSI UNTUK DOSEN ---
    function addDosenInput(id) {
        if (!dosenHiddenInputsContainer.querySelector('input[name="dosen_ids[]"][value="' + id + '"]')) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'dosen_ids[]';
            input.value = id;
            // ✅ PERBAIKAN: Menggunakan variabel yang benar 'dosenHiddenInputsContainer'
            dosenHiddenInputsContainer.appendChild(input);
        }
    }

    function removeDosenInput(id) {
        const input = dosenHiddenInputsContainer.querySelector('input[name="dosen_ids[]"][value="' + id + '"]');
        if (input) {
            input.remove();
        }
    }
    
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
    // --- AKHIR FUNGSI UNTUK DOSEN ---


    // --- FUNGSI UNTUK MAHASISWA (Search & Select) ---
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
            const params = new URLSearchParams({ q: query });
            const response = await fetch(`{{ route('mahasiswa.search.json') }}?${params.toString()}`);
            if (!response.ok) {
                throw new Error(`Network response was not ok: ${response.status}`);
            }
            const data = await response.json();
            renderSearchResults(data);
        } catch (error) {
            console.error("Fetch error:", error);
            mahasiswaSearchResultsTbody.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Error saat mencari data.</td></tr>';
        }
    }

    mahasiswaSearchInput.addEventListener('input', debounce(e => fetchMahasiswas(e.target.value), 300));

    mahasiswaSearchResultsTbody.addEventListener('click', e => {
        if (e.target.classList.contains('add-mahasiswa-btn')) {
            addMahasiswaToSelectedTable({ nim: e.target.dataset.nim, nama: e.target.dataset.nama });
        }
    });

    selectedMahasiswaTbody.addEventListener('click', e => {
        if (e.target.classList.contains('remove-selected-mahasiswa-btn')) {
            removeMahasiswaFromSelectedTable(e.target.dataset.nim);
        }
    });

    const repopulatedSelectedMahasiswas = {!! json_encode($repopulatedSelectedMahasiswas ?? []) !!};
    if (repopulatedSelectedMahasiswas.length > 0) {
        repopulatedSelectedMahasiswas.forEach(mhs => addMahasiswaToSelectedTable(mhs));
    }
    // --- AKHIR FUNGSI UNTUK MAHASISWA ---
});
</script>
@endsection

@section('styles')
<style>
    body {
        background-color: #def4ff;
        font-family: 'Inter', sans-serif;
    }
    .dashboard-heading {
        font-size: 2rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 1.5rem;
    }
    .card-header {
        font-weight: 500;
    }
    .form-label {
        font-weight: 500;
    }
    .dosen-btn {
        transition: all 0.3s ease;
        border-radius: 0.375rem;
    }
    .dosen-btn.active,
    .dosen-btn:active {
        background-color: #0072ca !important;
        color: #fff !important;
        border-color: #0072ca !important;
        box-shadow: 0 0 0 0.25rem rgba(0, 114, 202, 0.5);
    }
    .btn-outline-primary:hover {
        background-color: #005ea0;
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
    .form-control, .form-select {
        border-radius: 0.375rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0072ca;
        box-shadow: 0 0 0 0.25rem rgba(0, 114, 202, 0.25);
    }
    .is-invalid {
        border-color: #dc3545;
    }
    .invalid-feedback {
        display: block;
    }
    .btn.rounded-circle {
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    }
    .btn.rounded-circle:hover {
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
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