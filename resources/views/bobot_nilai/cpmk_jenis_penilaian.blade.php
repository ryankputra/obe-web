@extends('layouts.app')

@section('title', 'Atur Jenis Penilaian untuk ' . $cpmk->kode_cpmk)

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="dashboard-heading mt-4">Atur Jenis Penilaian</h1>
                <p class="lead mb-1">Mata Kuliah: {{ $mataKuliah->nama_mk }} ({{ $mataKuliah->kode_mk }})</p>
                <p>CPMK: <strong>{{ $cpmk->kode_cpmk }}</strong> - {{ $cpmk->deskripsi }}</p>
            </div>
            <a href="{{ route('bobot_nilai.show', $mataKuliah->getKey()) }}" class="btn btn-secondary align-self-start mt-4">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Bobot CPMK
            </a>
        </div>

        <div class="card">
            <div class="card-header bg-info text-white">
                <i class="fas fa-balance-scale me-2"></i> Bobot untuk Setiap Jenis Penilaian
            </div>
            <div class="card-body">
                @if (session('success_jenis'))
                    <div class="alert alert-success">
                        {{ session('success_jenis') }}
                    </div>
                @endif
                @if (session('error_jenis'))
                    <div class="alert alert-danger">
                        {{ session('error_jenis') }}
                    </div>
                @endif

                <form id="formBobotJenisPenilaian" method="POST"
                    action="{{ route('bobot_nilai.cpmk.store_jenis_penilaian', ['mataKuliah' => $mataKuliah->getKey(), 'cpmk' => $cpmk->id]) }}">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="jenisPenilaianTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 70%;">Jenis Penilaian</th>
                                    <th style="width: 30%;">Bobot</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jenisPenilaianList as $jenis)
                                    @php
                                        $jenisKey = strtolower(str_replace(' ', '_', $jenis));
                                        $maxBobot = $cpmk->bobot ?? 0;
                                    @endphp
                                    <tr>
                                        <td class="text-start">{{ $jenis }}</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-center"
                                                name="bobot_jenis[{{ $jenisKey }}]"
                                                value="{{ $existingBobot[$jenisKey] ?? old('bobot_jenis.' . $jenisKey, '') }}"
                                                min="0" max="{{ $maxBobot }}"
                                                placeholder="0-{{ $maxBobot }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-info mt-3">
                        Total bobot untuk semua jenis penilaian (Keaktifan, Tugas, Kuis, Proyek, UTS, UAS) yang diisi harus
                        sama dengan <b>{{ $cpmk->bobot }}</b> (bobot CPMK ini).
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Bobot Jenis
                            Penilaian</button>
                    </div>
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
            font-size: 1.8rem;
            /* Disesuaikan agar mirip dengan halaman lain */
            font-weight: bold;
            color: #333;
        }

        .table thead th {
            background-color: rgb(0, 114, 202) !important;
            color: white !important;
            vertical-align: middle;
            text-align: center;
            /* Menambahkan text-align center untuk header tabel */
        }

        .table tbody tr:hover {
            background-color: #cceeff;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #b8daff;
            vertical-align: middle;
            /* text-align: center; Dihilangkan agar jenis penilaian rata kiri, bobot tetap center karena inputnya text-center */
        }

        .table td.text-start {
            /* Memastikan kolom jenis penilaian tetap rata kiri */
            text-align: left !important;
        }
    </style>
@endsection

@section('scripts')
    {{-- Tambahkan JavaScript khusus untuk halaman ini jika diperlukan --}}
@endsection
