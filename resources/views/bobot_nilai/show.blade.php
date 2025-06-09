@extends('layouts.app')

@section('title', 'Atur Bobot CPMK - ' . $mataKuliah->nama_mk)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="dashboard-heading mt-4">Atur Bobot CPMK untuk {{ $mataKuliah->nama_mk }} ({{ $mataKuliah->kode_mk }})</h1>
        <a href="{{ route('bobot_nilai.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Mata Kuliah
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-list-ol me-2"></i> Daftar CPMK
        </div>
        <div class="card-body">
            @if (session('success_cpmk'))
                <div class="alert alert-success">
                    {{ session('success_cpmk') }}
                </div>
            @endif
            @if (session('error_cpmk'))
                <div class="alert alert-danger">
                    {{ session('error_cpmk') }}
                </div>
            @endif

            {{-- Jika bobot CPMK terhadap MK tidak diatur di halaman ini, form ini mungkin tidak diperlukan atau hanya untuk navigasi --}}
            {{-- Untuk saat ini, kita asumsikan form masih ada jika ada aksi lain, namun input bobot diubah jadi display --}}
            <form id="formDisplayBobotCpmkMk" method="GET" action="#"> {{-- Ubah method dan action jika form ini tidak lagi untuk submit bobot MK --}}
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="cpmkTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 15%;">ID CPMK</th>
                                <th style="width: 65%;">Deskripsi</th>
                                <th style="width: 20%;">Bobot CPMK</th>
                                {{-- Tambahkan kolom aksi jika perlu (misal, untuk mengatur jenis bobot per CPMK) --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mataKuliah->cpmks as $cpmk)
                                <tr>
                                    <td>
                                        {{-- Pastikan $cpmk->id adalah primary key dari model Cpmk --}}
                                        <a href="{{ route('bobot_nilai.cpmk.atur_jenis_penilaian', ['mataKuliah' => $mataKuliah->getKey(), 'cpmk' => $cpmk->id]) }}">{{ $cpmk->kode_cpmk }}</a>
                                    </td>
                                    <td class="text-start">{{ $cpmk->deskripsi }}</td>
                                    <td>
                                        {{-- Menampilkan bobot sebagai teks, bukan input --}}
                                        {{-- Pastikan $cpmk->bobot mengambil nilai yang benar (bobot CPMK terhadap MK ini) --}}
                                        @if(isset($cpmk->bobot) && is_numeric($cpmk->bobot))
                                            {{ $cpmk->bobot }}
                                        @else
                                            <span class="text-muted">Belum diatur</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada CPMK yang terkait dengan mata kuliah ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($mataKuliah->cpmks->isNotEmpty())
                <div class="alert alert-info mt-3">
                    Total bobot CPMK untuk mata kuliah ini harus 100%. Klik pada <strong>Kode CPMK</strong> untuk mengatur detail jenis penilaian (Keaktifan, Tugas, dll.) untuk CPMK tersebut.
                </div>
                {{-- Tombol simpan ini menjadi tidak relevan jika bobot tidak diinput di sini --}}
                {{-- <div class="mt-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Semua Bobot CPMK</button>
                </div> --}}
                @endif
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<style>
    body {
        background-color: #def4ff;
    }

    .dashboard-heading {
        font-size: 1.8rem; /* Disesuaikan agar mirip dengan halaman lain */
        font-weight: bold;
        color: #333;
    }

    .table thead th {
        background-color: rgb(0, 114, 202) !important;
        color: white !important;
        vertical-align: middle;
        text-align: center; /* Menambahkan text-align center untuk header tabel */
    }

    .table tbody tr:hover {
        background-color: #cceeff;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #b8daff;
        vertical-align: middle;
        text-align: center; /* Menambahkan text-align center untuk sel tabel */
    }
</style>
@endsection

@section('scripts')
{{-- Tambahkan JavaScript khusus untuk halaman ini jika diperlukan --}}
@endsection