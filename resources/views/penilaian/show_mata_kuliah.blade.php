@extends('layouts.app')

@section('title', 'Input Nilai - ' . ($mataKuliah->nama_mk ?? 'Mata Kuliah'))

@section('styles')
<style>
    body {
        background-color: #def4ff; /* Diambil dari CSS Pilih Mata Kuliah */
    }

    /* Disesuaikan dari .penilaian-heading di CSS Pilih Mata Kuliah */
    .page-title {
        font-size: 2rem;
        font-weight: bold;
        color: #545CD8; /* Warna dari Pilih Mata Kuliah */
        margin-bottom: 20px; /* Dipertahankan dari styling awal show_mata_kuliah */
    }

    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .back-button,
    .print-icon {
        font-size: 1.5rem;
        color: #545CD8; /* Disesuaikan dengan warna .page-title baru */
        text-decoration: none;
    }
    .print-icon {
        cursor: pointer;
    }

    /* Menggunakan gaya tabel dari CSS Pilih Mata Kuliah, diterapkan ke .table-penilaian */
    .table-penilaian thead th {
        background-color: rgb(0, 114, 202) !important; /* Dari Pilih Mata Kuliah */
        color: white !important; /* Dari Pilih Mata Kuliah */
        vertical-align: middle;
        padding: 0.9rem 0.75rem; /* Dari Pilih Mata Kuliah */
        font-size: 0.9rem; /* Dari Pilih Mata Kuliah */
        font-weight: bold; /* Dari Pilih Mata Kuliah */
        text-align: center; /* Default untuk header tabel nilai */
        border: 1px solid #dee2e6; /* Dari Pilih Mata Kuliah */
    }

    .table-penilaian th.text-start,
    .table-penilaian td.text-start {
        text-align: left !important; /* Dari Pilih Mata Kuliah, jika ada kolom yg butuh ini */
    }

    .table-penilaian tbody td {
        vertical-align: middle;
        padding: 0.8rem 0.75rem; /* Dari Pilih Mata Kuliah */
        font-size: 0.875rem; /* Dari Pilih Mata Kuliah */
        color: #5a5c69; /* Dari Pilih Mata Kuliah */
        border: 1px solid #dee2e6; /* Dari Pilih Mata Kuliah */
        text-align: center; /* Default untuk sel data nilai */
    }

    /* Spesifik untuk halaman input nilai, dipertahankan */
    .table-penilaian td:first-child,
    .table-penilaian td:nth-child(2) {
        text-align: left !important; /* NIM dan Nama rata kiri */
    }

    .table-penilaian td input[type="number"] {
        width: 80px; /* Bisa disesuaikan jika perlu lebih lebar */
        text-align: center;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
        box-sizing: border-box; /* Untuk memastikan padding tidak menambah lebar total */
    }

    .total-score {
        font-weight: bold;
    }

    /* Gaya card dari CSS Pilih Mata Kuliah */
    .card.shadow {
        border: none;
    }

    .card-body.p-0 .table-responsive {
        overflow-x: auto;
    }

    /* Tombol aksi dipertahankan gayanya karena spesifik untuk halaman ini */
    .action-buttons .btn-batal {
        background-color: white;
        border: 1px solid #dc3545;
        color: #dc3545;
        padding: 8px 20px;
        border-radius: 5px;
        margin-right: 10px;
    }
    .action-buttons .btn-batal:hover {
        background-color: #dc3545;
        color: white;
    }
    .action-buttons .btn-simpan {
        background-color: #d4edda;
        border: 1px solid #28a745;
        color: #28a745;
        font-weight: bold;
        padding: 8px 20px;
        border-radius: 5px;
    }
    .action-buttons .btn-simpan:hover {
        background-color: #28a745;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="header-container">
        <a href="{{ route('penilaian.index') }}" class="back-button" title="Kembali">
            &#x276E; </a>
        <h1 class="page-title mb-0">{{ $mataKuliah->nama_mk ?? 'Input Nilai Mata Kuliah' }}</h1>
        <span class="print-icon" onclick="window.print()" title="Cetak Halaman">
            &#128424; </span>
    </div>

    <form action="{{ route('penilaian.store', ['id_mata_kuliah' => $mataKuliah->kode_mk ?? $mataKuliah->id]) }}" method="POST">
        @csrf
        <div class="card shadow">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-penilaian mb-0"> {{-- table-hover bisa ditambahkan jika diinginkan --}}
                        <thead>
                            <tr>
                                <th style="width: 10%;">NIM</th>
                                <th style="width: 25%;">Nama</th>
                                <th style="width: 10%;">Tugas</th>
                                <th style="width: 10%;">Keaktifan</th>
                                <th style="width: 10%;">UTS</th>
                                <th style="width: 10%;">UAS</th>
                                <th style="width: 15%;">Total (Rata-rata)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($mahasiswaDiKelas && !$mahasiswaDiKelas->isEmpty())
                                @foreach($mahasiswaDiKelas as $index => $mahasiswa)
                                    <tr>
                                        <td>{{ $mahasiswa->nim ?? 'N/A' }}</td>
                                        <td>{{ $mahasiswa->nama_mahasiswa ?? ($mahasiswa->nama ?? 'N/A') }}</td>
                                        <td>
                                            <input type="number" name="nilai[{{ $mahasiswa->id ?? $index }}][tugas]" class="form-control form-control-sm nilai-input" min="0" max="100"
                                                   value="{{ $mahasiswa->penilaian->tugas ?? old('nilai.'.($mahasiswa->id ?? $index).'.tugas') ?? '' }}" data-row="{{ $index }}">
                                        </td>
                                        <td>
                                            <input type="number" name="nilai[{{ $mahasiswa->id ?? $index }}][keaktifan]" class="form-control form-control-sm nilai-input" min="0" max="100"
                                                   value="{{ $mahasiswa->penilaian->keaktifan ?? old('nilai.'.($mahasiswa->id ?? $index).'.keaktifan') ?? '' }}" data-row="{{ $index }}">
                                        </td>
                                        <td>
                                            <input type="number" name="nilai[{{ $mahasiswa->id ?? $index }}][uts]" class="form-control form-control-sm nilai-input" min="0" max="100"
                                                   value="{{ $mahasiswa->penilaian->uts ?? old('nilai.'.($mahasiswa->id ?? $index).'.uts') ?? '' }}" data-row="{{ $index }}">
                                        </td>
                                        <td>
                                            <input type="number" name="nilai[{{ $mahasiswa->id ?? $index }}][uas]" class="form-control form-control-sm nilai-input" min="0" max="100"
                                                   value="{{ $mahasiswa->penilaian->uas ?? old('nilai.'.($mahasiswa->id ?? $index).'.uas') ?? '' }}" data-row="{{ $index }}">
                                        </td>
                                        <td class="total-score" id="total-{{ $index }}">0.00</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        {{-- Menggunakan ikon FontAwesome jika tersedia, jika tidak, bisa dikosongkan atau teks biasa --}}
                                        {{-- <i class="fas fa-info-circle fa-2x text-muted mb-2"></i><br> --}}
                                        Tidak ada mahasiswa yang terdaftar pada mata kuliah ini.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($mahasiswaDiKelas && !$mahasiswaDiKelas->isEmpty())
        <div class="text-end mt-4 action-buttons">
            <a href="{{ route('penilaian.index') }}" class="btn btn-batal">Batal</a>
            <button type="submit" class="btn btn-simpan">Simpan</button>
        </div>
        @endif
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rows = document.querySelectorAll('.table-penilaian tbody tr');

    rows.forEach((row, rowIndex) => {
        // Pastikan baris ini bukan baris 'Tidak ada mahasiswa'
        if (!row.querySelector('td[colspan="7"]')) {
            const inputs = row.querySelectorAll('.nilai-input');
            const totalCell = row.querySelector('.total-score');

            function calculateAverage() {
                let sum = 0;
                let count = 0;
                inputs.forEach(input => {
                    const value = parseFloat(input.value);
                    if (!isNaN(value) && value >= 0 && value <= 100) { // Validasi sederhana
                        sum += value;
                        count++;
                    }
                });
                const average = count > 0 ? (sum / count) : 0;
                if (totalCell) {
                    totalCell.textContent = average.toFixed(2);
                }
            }

            inputs.forEach(input => {
                input.addEventListener('input', calculateAverage);
                // Tambahkan validasi agar nilai tidak di luar 0-100 saat input
                input.addEventListener('change', function() {
                    let value = parseFloat(this.value);
                    if (isNaN(value) || value < 0) {
                        this.value = ''; // Atau 0
                    } else if (value > 100) {
                        this.value = 100;
                    }
                    calculateAverage(); // Hitung ulang setelah koreksi
                });
            });

            // Calculate initial average on page load if there are values
            calculateAverage();
        }
    });
});
</script>
@endpush
