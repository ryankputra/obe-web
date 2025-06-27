@extends('layouts.app')

@section('title', 'Detail Mata Kuliah - ' . ($mataKuliah->nama_mk ?? 'Mata Kuliah'))

@section('styles')
    <style>
        body {
            background-color: #def4ff;
        }

        .page-title {
            font-size: 2rem;
            font-weight: bold;
            color: #545CD8;
            /* Warna dari show_mata_kuliah.blade.php */
            margin-bottom: 20px;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .back-button {
            font-size: 1.5rem;
            color: #545CD8;
            text-decoration: none;
        }

        .table-detail thead th {
            background-color: rgb(0, 114, 202) !important;
            color: white !important;
            vertical-align: middle;
            padding: 0.9rem 0.75rem;
            font-size: 0.9rem;
            font-weight: bold;
            text-align: center;
            border: 1px solid #dee2e6;
        }

        .table-detail tbody td {
            vertical-align: middle;
            padding: 0.8rem 0.75rem;
            font-size: 0.875rem;
            color: #5a5c69;
            border: 1px solid #dee2e6;
            text-align: center;
        }

        .table-detail td.text-start {
            text-align: left !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="header-container">
            <a href="{{ route('penilaian.index') }}" class="back-button" title="Kembali ke Daftar Mata Kuliah">
                &#x276E; </a>
            <h1 class="page-title mb-0">Detail Mata Kuliah: {{ $mataKuliah->nama_mk ?? 'N/A' }}</h1>
            <span></span> {{-- Placeholder untuk simetri jika diperlukan --}}
        </div>

        <div class="card shadow">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-detail mb-0">
                        <thead>
                            <tr>
                                <th style="width: 20%;">Kode CPMK</th>
                                <th style="width: 60%;" class="text-start">Deskripsi CPMK</th>
                                <th style="width: 20%;">Bobot CPMK</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mataKuliah->cpmks as $cpmk)
                                <tr>
                                    <td>
                                        {{-- Link ini akan menuju ke halaman input nilai, mungkin perlu parameter CPMK juga jika penilaian per CPMK --}}
                                        {{-- Untuk saat ini, kita asumsikan input nilai per mata kuliah, jadi Kode CPMK hanya sebagai info --}}
                                        {{-- Jika ingin input nilai per CPMK, route 'penilaian.mata_kuliah.input_nilai' perlu diubah untuk menerima cpmk_id --}}
                                        <a
                                            href="{{ route('penilaian.mata_kuliah.input_nilai', ['id_mata_kuliah' => $mataKuliah->kode_mk, 'cpmk_id' => $cpmk->id]) }}">
                                            {{ $cpmk->kode_cpmk ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td class="text-start">{{ $cpmk->deskripsi ?? 'N/A' }}</td>
                                    <td>
                                        {{-- Pastikan $cpmk->bobot adalah bobot CPMK terhadap MK --}}
                                        {{ $cpmk->bobot ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada CPMK yang terkait dengan mata kuliah
                                        ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Tambahkan di bawah tabel CPMK --}}
        <div class="card shadow mt-5">
            <div class="card-header bg-primary text-white">
                <b>Input Nilai Seluruh CPMK</b>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <form action="{{ route('penilaian.store.mass', ['id_mata_kuliah' => $mataKuliah->kode_mk]) }}"
                        method="POST">
                        @csrf
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;">Nama Mahasiswa</th>
                                    @foreach ($mataKuliah->cpmks as $cpmk)
                                        @php
                                            // Ambil jenis penilaian yang sudah ada bobotnya untuk CPMK ini
                                            $jenisBobots = \App\Models\BobotPenilaian::where('cpmk_id', $cpmk->id)
                                                ->where('bobot', '>', 0)
                                                ->get();
                                        @endphp
                                        @if ($jenisBobots->count())
                                            <th colspan="{{ $jenisBobots->count() }}" class="text-center">
                                                {{ $cpmk->kode_cpmk }}
                                            </th>
                                        @endif
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach ($mataKuliah->cpmks as $cpmk)
                                        @php
                                            $jenisBobots = \App\Models\BobotPenilaian::where('cpmk_id', $cpmk->id)
                                                ->where('bobot', '>', 0)
                                                ->get();
                                        @endphp
                                        @foreach ($jenisBobots as $jb)
                                            <th class="text-center">{{ ucfirst($jb->jenis_penilaian) }}<br><small>(Bobot:
                                                    {{ $jb->bobot }})</small></th>
                                        @endforeach
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mataKuliah->mahasiswas as $mhs)
                                    <tr>
                                        <td>{{ $mhs->nama }}</td>
                                        @foreach ($mataKuliah->cpmks as $cpmk)
                                            @php
                                                $jenisBobots = \App\Models\BobotPenilaian::where('cpmk_id', $cpmk->id)
                                                    ->where('bobot', '>', 0)
                                                    ->get();
                                                // Ambil penilaian untuk mahasiswa, mk, dan cpmk ini
                                                $penilaian = $mhs->penilaian->where('cpmk_id', $cpmk->id)->first();
                                            @endphp
                                            @foreach ($jenisBobots as $jb)
                                                <td>
                                                    <input type="number"
                                                        name="nilai[{{ $mhs->nim }}][{{ $cpmk->id }}][{{ strtolower($jb->jenis_penilaian) }}]"
                                                        value="{{ old('nilai.' . $mhs->nim . '.' . $cpmk->id . '.' . strtolower($jb->jenis_penilaian), $penilaian ? $penilaian->{strtolower($jb->jenis_penilaian)} : '') }}"
                                                        min="0" max="100"
                                                        class="form-control form-control-sm" />
                                                </td>
                                            @endforeach
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary">Simpan Semua Nilai</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
