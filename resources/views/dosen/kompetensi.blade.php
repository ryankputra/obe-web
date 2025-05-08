@extends('layouts.app')

@section('title', 'Kompetensi Dosen')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h4>Kompetensi Dosen</h4>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Nama Dosen:</h5>
                    <p>{{ $dosen->nama }}</p>
                </div>
                <div class="col-md-6">
                    <h5>NIDN:</h5>
                    <p>{{ $dosen->nidn }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h5>Kompetensi:</h5>
                    <div class="border p-3">
                        @if($dosen->kompetensi)
                            {!! nl2br(e($dosen->kompetensi)) !!}
                        @else
                            <p class="text-muted">Belum ada data kompetensi</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('dosen.index') }}" class="btn btn-secondary">
                    Kembali ke Daftar Dosen
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
@section('styles')
<style>
    body {
        background-color: #d8f0ff; /* biru muda seperti background daftar dosen */
    }

    .card {
        border: none;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #0072ca;
        color: white;
        padding: 1rem 1.25rem;
        border-bottom: none;
        border-radius: 0.375rem 0.375rem 0 0;
    }

    .card-header h4 {
        font-size: 1.5rem;
        font-weight: bold;
        margin: 0;
    }

    .card-body h5 {
        color: #333;
        font-weight: 600;
    }

    .card-body p {
        font-size: 16px;
        color: #333;
    }

    .border {
        background-color: #f4faff;
        border-radius: 5px;
        padding: 1rem;
    }

    .btn-secondary {
        background-color: #0072ca;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        font-weight: 500;
        border-radius: 0.375rem;
    }

    .btn-secondary:hover {
        background-color: #005fa3;
    }
</style>
@endsection