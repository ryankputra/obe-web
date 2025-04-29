@extends('layouts.app') <!-- Sesuaikan dengan layout Anda -->

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
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
    </div>
</div>
@endsection