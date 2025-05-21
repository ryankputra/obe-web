@extends('layouts.app')

@section('title', 'Edit CPL')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-purple">Edit CPL</h2>

    <div class="p-4 shadow-sm rounded bg-white">
        <form method="POST" action="{{ route('cpl.update', $cpl->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="kode_cpl" class="form-label fw-bold">Kode CPL</label>
                <input type="text" class="form-control @error('kode_cpl') is-invalid @enderror"
                    id="kode_cpl" name="kode_cpl" value="{{ old('kode_cpl', $cpl->kode_cpl) }}" placeholder="Contoh: CPL001" required>
                @error('kode_cpl')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                    id="deskripsi" name="deskripsi" rows="5" placeholder="Masukkan deskripsi CPL" required>{{ old('deskripsi', $cpl->deskripsi) }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('cpl.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
