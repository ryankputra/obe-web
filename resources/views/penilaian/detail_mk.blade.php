@extends('layouts.app')
@stack('scripts')

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
        <a href="{{ route('penilaian.index') }}" class="back-button" title="Kembali ke Daftar Mata Kuliah">&#x276E;</a>
        <h1 class="page-title mb-0">Detail Mata Kuliah: {{ $mataKuliah->nama_mk ?? 'N/A' }}</h1>
        <span></span>
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
                                <a href="{{ route('penilaian.mata_kuliah.input_nilai', ['id_mata_kuliah' => $mataKuliah->kode_mk, 'cpmk_id' => $cpmk->id]) }}">
                                    {{ $cpmk->kode_cpmk ?? 'N/A' }}
                                </a>
                            </td>
                            <td class="text-start">{{ $cpmk->deskripsi ?? 'N/A' }}</td>
                            <td>{{ $cpmk->bobot ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada CPMK yang terkait dengan mata kuliah ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow mt-5">
        <div class="card-header bg-primary text-white">
            <b>Input Nilai Seluruh CPMK</b>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <form action="{{ route('penilaian.store.mass', ['id_mata_kuliah' => $mataKuliah->kode_mk]) }}" method="POST">
                    @csrf
                    <table class="table table-bordered table-striped mb-0">
                    <thead>
                        <tr>
                            <th rowspan="2" style="vertical-align: middle;">Nama Mahasiswa</th>
                            @foreach ($mataKuliah->cpmks as $cpmk)
                                @php
                                    $jenisBobots = \App\Models\BobotPenilaian::where('cpmk_id', $cpmk->id)
                                        ->where('bobot', '>', 0)
                                        ->get();
                                @endphp
                                @if ($jenisBobots->count())
                                    <th colspan="{{ $jenisBobots->count() + 1 }}" class="text-center">
                                        {{ $cpmk->kode_cpmk }}
                                    </th>
                                @endif
                            @endforeach
                            <th rowspan="2" class="text-center text-success" style="vertical-align: middle;">Total Nilai (%)</th>
                        </tr>
                        <tr>
                            @foreach ($mataKuliah->cpmks as $cpmk)
                                @php
                                    $jenisBobots = \App\Models\BobotPenilaian::where('cpmk_id', $cpmk->id)
                                        ->where('bobot', '>', 0)
                                        ->get();
                                @endphp
                                @foreach ($jenisBobots as $jb)
                                    <th class="text-center">{{ ucfirst($jb->jenis_penilaian) }}<br><small>(Bobot: {{ $jb->bobot }})</small></th>
                                @endforeach
                                <th class="text-center text-primary">Total CPMK</th>
                            @endforeach
                        </tr>
                    </thead>

                        <tbody>
                        @foreach ($mataKuliah->mahasiswas as $mhs)
                        @php $totalNilaiAkhir = 0; @endphp
                        <tr>
                            <td>{{ $mhs->nama }}</td>
                            @foreach ($mataKuliah->cpmks as $cpmk)
                                @php
                                    $jenisBobots = \App\Models\BobotPenilaian::where('cpmk_id', $cpmk->id)
                                        ->where('bobot', '>', 0)
                                        ->get();
                                    $penilaian = $mhs->penilaian->where('cpmk_id', $cpmk->id)->first();
                                    $subtotal = 0;
                                @endphp
                                @foreach ($jenisBobots as $jb)
                                    @php
                                        $nilai = $penilaian ? $penilaian->{strtolower($jb->jenis_penilaian)} : 0;
                                        $bobotJenis = $jb->bobot ?? 0;
                                        $subtotal += ($nilai * $bobotJenis) / 100;
                                    @endphp
                                    <td>
                                        <input type="number"
                                            name="nilai[{{ $mhs->nim }}][{{ $cpmk->id }}][{{ strtolower($jb->jenis_penilaian) }}]"
                                            value="{{ old('nilai.' . $mhs->nim . '.' . $cpmk->id . '.' . strtolower($jb->jenis_penilaian), $nilai) }}"
                                            min="0" max="100"
                                            class="form-control form-control-sm nilai-input"
                                            data-nim="{{ $mhs->nim }}"
                                            data-cpmk="{{ $cpmk->id }}"
                                            data-bobot-jenis="{{ $jb->bobot }}"
                                            data-bobot-cpmk="{{ $cpmk->bobot ?? 0 }}"
                                            data-target="#total-{{ $mhs->nim }}"
                                            data-cpmk-total="#total-cpmk-{{ $mhs->nim }}-{{ $cpmk->id }}" />
                                    </td>
                                @endforeach
                                <td class="text-center text-primary fw-bold" id="total-cpmk-{{ $mhs->nim }}-{{ $cpmk->id }}">
                                    {{ number_format($subtotal, 2) }}
                                </td>
                                @php
                                    $bobotCpmk = $cpmk->bobot ?? 0;
                                    $totalNilaiAkhir += ($subtotal * $bobotCpmk) / 100;
                                @endphp
                            @endforeach
                            <td class="text-center fw-bold text-success" id="total-{{ $mhs->nim }}">
                                {{ number_format($totalNilaiAkhir, 2) }}%
                            </td>
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

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const inputs = document.querySelectorAll(".nilai-input");

    inputs.forEach(input => {
        input.addEventListener("input", hitungNilai);
    });

    function hitungNilai() {
        const nims = [...new Set([...document.querySelectorAll(".nilai-input")].map(i => i.dataset.nim))];

        nims.forEach(nim => {
            let totalSkor = 0;
            let totalBobot = 0;
            const nilaiMhs = [...document.querySelectorAll(`.nilai-input[data-nim="${nim}"]`)];
            const cpmkMap = {};

            nilaiMhs.forEach(input => {
                const cpmk = input.dataset.cpmk;
                const nilai = parseFloat(input.value) || 0;
                const bobotJenis = parseFloat(input.dataset.bobotJenis) || 0;
                const bobotCpmk = parseFloat(input.dataset.bobotCpmk) || 0;

                if (!cpmkMap[cpmk]) {
                    cpmkMap[cpmk] = { subtotal: 0, totalBobotJenis: 0, bobotCpmk };
                }

                cpmkMap[cpmk].subtotal += (nilai * bobotJenis);
                cpmkMap[cpmk].totalBobotJenis += bobotJenis;
            });

            // Hitung total nilai akhir dan update total per CPMK
            for (const cpmk in cpmkMap) {
                const sub = cpmkMap[cpmk];

                // Total per CPMK = subtotal dibagi total bobot jenis
                const totalCpmk = sub.totalBobotJenis > 0 ? (sub.subtotal / sub.totalBobotJenis) : 0;

                // Update total CPMK di tabel
                const totalCpmkEl = document.querySelector(`#total-cpmk-${nim}-${cpmk}`);
                if (totalCpmkEl) {
                    totalCpmkEl.innerText = totalCpmk.toFixed(2);
                }

                // Hitung skor akhir, kalikan dengan bobot CPMK (dalam persen)
                totalSkor += totalCpmk * sub.bobotCpmk;
                totalBobot += sub.bobotCpmk;
            }

            // Total akhir per mahasiswa
            const final = totalBobot > 0 ? (totalSkor / totalBobot) : 0;

            const target = document.querySelector(`#total-${nim}`);
            if (target) {
                target.innerText = final.toFixed(2) + '%';
            }
        });
    }

    hitungNilai();
});

    document.addEventListener("DOMContentLoaded", function () {
        const inputs = document.querySelectorAll(".nilai-input");

        inputs.forEach(input => {
            input.addEventListener("input", hitungNilai);
        });

        function hitungNilai() {
            const nims = [...new Set([...document.querySelectorAll(".nilai-input")].map(i => i.dataset.nim))];

            nims.forEach(nim => {
                let totalSkor = 0;
                let totalBobot = 0;
                const nilaiMhs = [...document.querySelectorAll(`.nilai-input[data-nim="${nim}"]`)];
                const cpmkMap = {};

                nilaiMhs.forEach(input => {
                    const cpmk = input.dataset.cpmk;
                    const nilai = parseFloat(input.value) || 0;
                    const bobotJenis = parseFloat(input.dataset.bobotJenis) || 0;
                    const bobotCpmk = parseFloat(input.dataset.bobotCpmk) || 0;

                    if (!cpmkMap[cpmk]) {
                        cpmkMap[cpmk] = { subtotal: 0, totalBobotJenis: 0, bobotCpmk };
                    }

                    cpmkMap[cpmk].subtotal += (nilai * bobotJenis);
                    cpmkMap[cpmk].totalBobotJenis += bobotJenis;
                });

                for (const cpmk in cpmkMap) {
                    const sub = cpmkMap[cpmk];
                    totalSkor += sub.subtotal * sub.bobotCpmk;
                    totalBobot += sub.totalBobotJenis * sub.bobotCpmk;
                }

                const final = totalBobot > 0 ? (totalSkor / totalBobot) : 0;
                const target = document.querySelector(`#total-${nim}`);
                if (target) {
                    target.innerText = final.toFixed(2) + '%';
                }
            });
        }

        hitungNilai();
    });
</script>
@endpush
@endsection
