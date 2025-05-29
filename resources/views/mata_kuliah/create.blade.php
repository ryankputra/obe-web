@extends('layouts.app')

@section('title', 'Tambah Mata Kuliah')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Tambah Mata Kuliah</h4>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('mata_kuliah.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="kode_mk" class="form-label">Kode Mata Kuliah</label>
                                <input type="text" name="kode_mk" id="kode_mk" class="form-control"
                                    value="{{ old('kode_mk') }}" required>
                                @error('kode_mk')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nama_mk" class="form-label">Nama Mata Kuliah</label>
                                <input type="text" name="nama_mk" id="nama_mk" class="form-control"
                                    value="{{ old('nama_mk') }}" required>
                                @error('nama_mk')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Dosen Pengampu</label>
                                <div id="dosen-buttons" class="d-flex flex-wrap gap-2">
                                    @foreach ($dosens as $dosen)
                                        <button type="button" class="btn btn-outline-primary dosen-btn"
                                            data-id="{{ $dosen->id }}">
                                            {{ $dosen->nama }} ({{ $dosen->nidn }})
                                        </button>
                                    @endforeach
                                </div>
                                <div id="dosen-hidden-inputs"></div>
                                @error('dosen_ids')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="semester" class="form-label">Semester</label>
                                    <select name="semester" id="semester" class="form-control" required>
                                        <option value="">-- Pilih Semester --</option>
                                        @for ($i = 1; $i <= 8; $i++)
                                            <option value="{{ $i }}"
                                                {{ old('semester') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('semester')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="sks_teori" class="form-label">SKS Teori</label>
                                    <input type="number" name="sks_teori" id="sks_teori" class="form-control"
                                        value="{{ old('sks_teori') }}" required>
                                    @error('sks_teori')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="sks_praktik" class="form-label">SKS Praktik</label>
                                    <input type="number" name="sks_praktik" id="sks_praktik" class="form-control"
                                        value="{{ old('sks_praktik') }}" required>
                                    @error('sks_praktik')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="status_mata_kuliah" class="form-label">Status Mata Kuliah</label>
                                <select name="status_mata_kuliah" id="status_mata_kuliah" class="form-control" required>
                                    <option value="Wajib Prodi"
                                        {{ old('status_mata_kuliah') == 'Wajib Prodi' ? 'selected' : '' }}>Wajib Prodi
                                    </option>
                                    <option value="Pilihan" {{ old('status_mata_kuliah') == 'Pilihan' ? 'selected' : '' }}>
                                        Pilihan</option>
                                    <option value="Wajib Fakultas"
                                        {{ old('status_mata_kuliah') == 'Wajib Fakultas' ? 'selected' : '' }}>Wajib
                                        Fakultas</option>
                                    <option value="Wajib Universitas"
                                        {{ old('status_mata_kuliah') == 'Wajib Universitas' ? 'selected' : '' }}>Wajib
                                        Universitas</option>
                                </select>
                                @error('status_mata_kuliah')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>


                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success me-2">
                                    <i class="fas fa-save me-1"></i> Simpan
                                </button>
                                <a href="{{ route('mata_kuliah.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .dosen-btn.active,
        .dosen-btn:active {
            background-color: #0072ca !important;
            color: #fff !important;
            border-color: #0072ca !important;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.dosen-btn');
            const hiddenInputs = document.getElementById('dosen-hidden-inputs');

            function addInput(id) {
                if (!hiddenInputs.querySelector('input[value="' + id + '"]')) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'dosen_ids[]';
                    input.value = id;
                    hiddenInputs.appendChild(input);
                }
            }

            function removeInput(id) {
                const input = hiddenInputs.querySelector('input[value="' + id + '"]');
                if (input) input.remove();
            }

            buttons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    this.classList.toggle('active');
                    if (this.classList.contains('active')) {
                        addInput(id);
                    } else {
                        removeInput(id);
                    }
                });
            });

            // On page load (for edit), ensure active buttons have hidden inputs
            buttons.forEach(btn => {
                if (btn.classList.contains('active')) {
                    addInput(btn.getAttribute('data-id'));
                }
            });
        });
    </script>

@endsection
