@extends('layouts.app')

@section('title', 'Tambah CPMK')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-purple">Tambah CPMK</h2>

    <div class="p-4 shadow-sm rounded bg-white">
        <form method="POST" action="{{ route('cpmk.store') }}">
            @csrf

            <div class="mb-3">
                <label for="kode_cpl" class="form-label fw-bold">CPL</label>
                <select class="form-select @error('kode_cpl') is-invalid @enderror" id="kode_cpl" name="kode_cpl" required>
                    @foreach ($cpls as $cpl)
                        <option value="{{ $cpl->kode_cpl }}">{{ $cpl->kode_cpl }} - {{ $cpl->deskripsi }}</option>
                    @endforeach
                </select>
                @error('kode_cpl')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="kode_cpmk" class="form-label fw-bold">Nomor CPMK</label>
                <input type="number" class="form-control @error('kode_cpmk') is-invalid @enderror"
                    id="kode_cpmk" name="kode_cpmk" min="1" max="999" placeholder="Contoh: 2" required>
                <small class="text-muted">Contoh: 2 akan menjadi [KodeCPL]002</small>
                <small id="previewKode" class="form-text text-primary mt-1 d-block"></small>
                @error('kode_cpmk')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="mata_kuliah" class="form-label fw-bold">Mata Kuliah</label>
                <select class="form-select @error('mata_kuliah') is-invalid @enderror" id="mata_kuliah" name="mata_kuliah" required>
                    @foreach ($matakuliahs as $matakuliah)
                        <option value="{{ $matakuliah->kode_mk }}">{{ $matakuliah->kode_mk }} - {{ $matakuliah->nama_mk }}</option>
                    @endforeach
                </select>
                @error('mata_kuliah')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                    id="deskripsi" name="deskripsi" rows="5" placeholder="Masukkan deskripsi CPMK" required></textarea>
                @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="bobot" class="form-label fw-bold">Bobot</label>
                <input type="number" class="form-control @error('bobot') is-invalid @enderror" id="bobot" name="bobot" required>
                @error('bobot')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="pic" class="form-label fw-bold">PIC</label>
                <input type="text" class="form-control @error('pic') is-invalid @enderror" id="pic" name="pic" required>
                @error('pic')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('cpmk.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('styles')
<style>
    body {
        background-color: #def4ff;
    }

    .text-purple {
        color: #2f5f98;
    }

    .form-label {
        color: #2f5f98;
    }

    .form-control, .form-select {
        border-radius: 0.375rem;
    }

    .btn-primary {
        background-color: #2f5f98;
        border-color: #2f5f98;
    }

    .btn-primary:hover {
        background-color: #254d7b;
    }

    textarea.form-control {
        min-height: 150px;
    }

    .invalid-feedback {
        margin-top: 0.25rem;
        font-size: 0.875em;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cplSelect = document.getElementById('kode_cpl');
        const cpmkInput = document.getElementById('kode_cpmk');
        const preview = document.getElementById('previewKode');

        function updatePreview() {
            const cplCode = cplSelect.value || 'CPL000';
            const cplNumber = cplCode.replace('CPL', '');
            const inputVal = cpmkInput.value.padStart(3, '0');
            preview.textContent = `Kode CPMK akan menjadi: CPMK${cplNumber}${inputVal}`;
        }

        cplSelect.addEventListener('change', updatePreview);
        cpmkInput.addEventListener('input', updatePreview);
    });
</script>
@endsection
