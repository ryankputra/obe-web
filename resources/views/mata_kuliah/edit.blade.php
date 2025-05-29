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
    const hiddenInputsContainer = document.getElementById('dosen-hidden-inputs');

    // Function to add hidden input for selected dosen
    function addDosenInput(id) {
        if (!hiddenInputsContainer.querySelector('input[name="dosen_ids[]"][value="' + id + '"]')) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'dosen_ids[]';
            input.value = id;
            hiddenInputsContainer.appendChild(input);
        }
    }

    // Function to remove hidden input for deselected dosen
    function removeDosenInput(id) {
        const input = hiddenInputsContainer.querySelector('input[name="dosen_ids[]"][value="' + id + '"]');
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

    // Initialize hidden inputs based on currently active buttons (e.g., from old input or existing data)
    // This part is handled by the Blade template rendering old('dosen_ids') or $mataKuliah->dosens
    // The script above ensures dynamic changes are reflected.
    // If you were populating `dosen-hidden-inputs` purely via JS on load, you'd do it here.
    // However, since Blade handles the initial state of hidden inputs based on `old()` or `$mataKuliah->dosens`,
    // we just need to make sure the buttons visually match that state.
    // The `in_array` check in the Blade for button's `active` class and for rendering initial hidden inputs handles this.
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
</style>
@endsection
