@extends('layouts.app')

@section('title', 'Edit CPL')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">Edit CPL</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('cpl.update', $cpl->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="kode_cpl" class="form-label">Kode CPL</label>
                    <input type="text" class="form-control @error('kode_cpl') is-invalid @enderror" id="kode_cpl" name="kode_cpl" value="{{ old('kode_cpl', $cpl->kode_cpl) }}" required>
                    @error('kode_cpl')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4" required>{{ old('deskripsi', $cpl->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('cpl.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .form-control {
        margin-bottom: 1.5rem;
    }
    
    textarea.form-control {
        min-height: 150px;
    }
    
    .invalid-feedback {
        margin-top: -1rem;
        margin-bottom: 1rem;
        color: #dc3545;
    }
</style>
@endsection