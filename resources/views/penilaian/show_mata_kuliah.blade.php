@extends('layouts.app')

@section('title', 'Input Nilai - ' . ($mataKuliah->nama_mk ?? 'Mata Kuliah'))

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

        .table-penilaian thead th {
            background-color: rgb(0, 114, 202) !important;
            color: white !important;
            vertical-align: middle;
            text-align: center;
        }

        .table-penilaian tbody td {
            vertical-align: middle;
            text-align: center;
        }

        .table-penilaian td:nth-child(1),
        .table-penilaian td:nth-child(2) {
            text-align: left !important;
        }

        .table-penilaian td input[type="number"] {
            width: 80px;
            text-align: center;
        }

        .total-score {
            font-weight: bold;
            background-color: #e9ecef;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="header-container">
            <a href="{{ route('penilaian.mata_kuliah.detail', ['id_mata_kuliah' => $mataKuliah->kode_mk]) }}"
                class="btn btn-secondary" title="Kembali">&#x276E; Kembali</a>
            <h1 class="page-title mb-0">{{ $mataKuliah->nama_mk ?? 'Input Nilai Mata Kuliah' }}</h1>
            <button class="btn btn-info" onclick="window.print()" title="Cetak Halaman">&#128424; Cetak</button>
        </div>

        <form action="{{ route('penilaian.store', ['id_mata_kuliah' => $mataKuliah->kode_mk, 'cpmk_id' => $cpmk->id]) }}"
            method="POST">
            @csrf
            <div class="card shadow">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-penilaian mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Mahasiswa</th>
                                    @foreach ($bobotPenilaian as $jenis => $bobot)
                                        <th>{{ ucfirst($jenis) }} (Bobot: {{ $bobot }})</th>
                                    @endforeach
                                    <th>Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mahasiswaDiKelas as $mhs)
                                    @php
                                        // Jika $mhs->penilaian adalah koleksi (hasMany)
                                        $penilaian = null;
                                        if (
                                            $mhs->penilaian &&
                                            $mhs->penilaian instanceof \Illuminate\Support\Collection
                                        ) {
                                            $penilaian = $mhs->penilaian->where('cpmk_id', $cpmk->id)->first();
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $mhs->nama }}</td>
                                        @foreach ($bobotPenilaian as $jenis => $bobot)
                                            <td>
                                                <input type="number" name="nilai[{{ $mhs->nim }}][{{ $jenis }}]"
                                                    value="{{ old('nilai.' . $mhs->nim . '.' . $jenis, $penilaian->{$jenis} ?? '') }}"
                                                    min="0" max="100" class="form-control" />
                                            </td>
                                        @endforeach
                                        <td>{{ $penilaian->nilai_akhir ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if ($mahasiswaDiKelas->isNotEmpty())
                <div class="text-end mt-4">
                    <a href="{{ route('penilaian.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Nilai</button>
                </div>
            @endif
        </form>
    </div>
@endsection

{{-- PENTING: Ubah 'const' menjadi 'window.' untuk membuat variabel global --}}
<script>
    // Variabel ini diisi oleh data dari controller dan dibuat global
    window.bobotPenilaian = @json($bobotPenilaian ?? []);
</script>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi ini menghitung nilai akhir terbobot untuk satu baris
            function calculateWeightedTotal(row) {
                const inputs = row.querySelectorAll('input.nilai-input');
                const totalCell = row.querySelector('.total-score');

                if (!inputs.length || !totalCell) return;

                let totalWeightedScore = 0;

                inputs.forEach(input => {
                    const nilai = parseFloat(input.value);
                    const jenis = input.dataset.jenis; // Ambil jenis dari atribut 'data-jenis'

                    // Akses bobotPenilaian melalui window.bobotPenilaian
                    if (window.bobotPenilaian && typeof window.bobotPenilaian[jenis] !== 'undefined' && !
                        isNaN(nilai)) {
                        const bobot = parseFloat(window.bobotPenilaian[jenis]);

                        if (!isNaN(bobot)) {
                            // Rumus: (nilai * (bobot / 100))
                            totalWeightedScore += nilai * (bobot / 100);
                        }
                    }
                });

                // Tampilkan hasil dengan 2 angka desimal
                totalCell.textContent = totalWeightedScore.toFixed(2);
            }

            const tableBody = document.querySelector('.table-penilaian tbody');

            if (tableBody) {
                // Event listener untuk menghitung ulang saat nilai diubah
                tableBody.addEventListener('input', function(e) {
                    if (e.target.classList.contains('nilai-input')) {
                        const row = e.target.closest('tr');
                        if (row) {
                            calculateWeightedTotal(row);
                        }
                    }
                });

                // Hitung nilai awal untuk semua baris saat halaman pertama kali dimuat
                tableBody.querySelectorAll('tr').forEach(row => {
                    if (row.querySelector('.nilai-input')) {
                        calculateWeightedTotal(row);
                    }
                });

                // Untuk memastikan, tampilkan bobot yang digunakan di console browser
                console.log('Bobot yang digunakan untuk perhitungan:', window.bobotPenilaian);
            }
        });
    </script>
@endpush
