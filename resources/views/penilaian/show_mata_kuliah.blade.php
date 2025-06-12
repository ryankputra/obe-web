@extends('layouts.app')

@section('title', 'Input Nilai - ' . ($mataKuliah->nama_mk ?? 'Mata Kuliah'))

@section('styles')
<style>
    body { background-color: #def4ff; }
    .page-title { font-size: 2rem; font-weight: bold; color: #545CD8; margin-bottom: 20px; }
    .header-container { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .table-penilaian thead th { background-color: rgb(0, 114, 202) !important; color: white !important; vertical-align: middle; text-align: center; }
    .table-penilaian tbody td { vertical-align: middle; text-align: center; }
    .table-penilaian td:nth-child(1), .table-penilaian td:nth-child(2) { text-align: left !important; }
    .table-penilaian td input[type="number"] { width: 80px; text-align: center; }
    .total-score { font-weight: bold; background-color: #e9ecef; }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="header-container">
        <a href="{{ route('penilaian.index') }}" class="btn btn-secondary" title="Kembali">&#x276E; Kembali</a>
        <h1 class="page-title mb-0">{{ $mataKuliah->nama_mk ?? 'Input Nilai Mata Kuliah' }}</h1>
        <button class="btn btn-info" onclick="window.print()" title="Cetak Halaman">&#128424; Cetak</button>
    </div>

    <form action="{{ route('penilaian.store', ['id_mata_kuliah' => $mataKuliah->kode_mk]) }}" method="POST">
        @csrf
        <div class="card shadow">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-penilaian mb-0">
                        <thead>
                            <tr>
                                <th style="width: 10%;">NIM</th>
                                <th style="width: 20%;">Nama</th>
                                <th style="width: 10%;">Keaktifan</th>
                                <th style="width: 10%;">Tugas</th>
                                <th style="width: 10%;">Proyek</th>
                                <th style="width: 10%;">Kuis</th>
                                <th style="width: 10%;">UTS</th>
                                <th style="width: 10%;">UAS</th>
                                <th style="width: 10%;">Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mahasiswaDiKelas as $mahasiswa)
                                <tr>
                                    <td>{{ $mahasiswa->nim }}</td>
                                    <td>{{ $mahasiswa->nama_mahasiswa ?? $mahasiswa->nama }}</td>
                                    <td>
                                        <input type="number" name="nilai[{{ $mahasiswa->nim }}][keaktifan]" class="form-control form-control-sm mx-auto nilai-input" min="0" max="100" value="{{ old('nilai.'.$mahasiswa->nim.'.keaktifan', $mahasiswa->penilaian->keaktifan ?? '') }}" data-jenis="keaktifan">
                                    </td>
                                    <td>
                                        <input type="number" name="nilai[{{ $mahasiswa->nim }}][tugas]" class="form-control form-control-sm mx-auto nilai-input" min="0" max="100" value="{{ old('nilai.'.$mahasiswa->nim.'.tugas', $mahasiswa->penilaian->tugas ?? '') }}" data-jenis="tugas">
                                    </td>
                                    <td>
                                        <input type="number" name="nilai[{{ $mahasiswa->nim }}][proyek]" class="form-control form-control-sm mx-auto nilai-input" min="0" max="100" value="{{ old('nilai.'.$mahasiswa->nim.'.proyek', $mahasiswa->penilaian->proyek ?? '') }}" data-jenis="proyek">
                                    </td>
                                    <td>
                                        <input type="number" name="nilai[{{ $mahasiswa->nim }}][kuis]" class="form-control form-control-sm mx-auto nilai-input" min="0" max="100" value="{{ old('nilai.'.$mahasiswa->nim.'.kuis', $mahasiswa->penilaian->kuis ?? '') }}" data-jenis="kuis">
                                    </td>
                                    <td>
                                        <input type="number" name="nilai[{{ $mahasiswa->nim }}][uts]" class="form-control form-control-sm mx-auto nilai-input" min="0" max="100" value="{{ old('nilai.'.$mahasiswa->nim.'.uts', $mahasiswa->penilaian->uts ?? '') }}" data-jenis="uts">
                                    </td>
                                    <td>
                                        <input type="number" name="nilai[{{ $mahasiswa->nim }}][uas]" class="form-control form-control-sm mx-auto nilai-input" min="0" max="100" value="{{ old('nilai.'.$mahasiswa->nim.'.uas', $mahasiswa->penilaian->uas ?? '') }}" data-jenis="uas">
                                    </td>
                                    <td class="total-score">0.00</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">Tidak ada mahasiswa yang terdaftar pada mata kuliah ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($mahasiswaDiKelas->isNotEmpty())
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
document.addEventListener('DOMContentLoaded', function () {
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
            if (window.bobotPenilaian && typeof window.bobotPenilaian[jenis] !== 'undefined' && !isNaN(nilai)) {
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
        tableBody.addEventListener('input', function (e) {
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