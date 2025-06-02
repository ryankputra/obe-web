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

    .back-button,
    .print-icon {
        font-size: 1.5rem;
        color: #545CD8;
        text-decoration: none;
    }
    .print-icon {
        cursor: pointer;
    }

    .table-penilaian thead th {
        background-color: rgb(0, 114, 202) !important;
        color: white !important;
        vertical-align: middle;
        padding: 0.9rem 0.75rem;
        font-size: 0.9rem;
        font-weight: bold;
        text-align: center;
        border: 1px solid #dee2e6;
    }

    .table-penilaian th.text-start,
    .table-penilaian td.text-start {
        text-align: left !important;
    }

    .table-penilaian tbody td {
        vertical-align: middle;
        padding: 0.8rem 0.75rem;
        font-size: 0.875rem;
        color: #5a5c69;
        border: 1px solid #dee2e6;
        text-align: center;
    }

    .table-penilaian td:first-child,
    .table-penilaian td:nth-child(2) {
        text-align: left !important;
    }

    .table-penilaian td input[type="number"] {
        width: 80px;
        text-align: center;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
        box-sizing: border-box;
    }

    .total-score {
        font-weight: bold;
    }

    .card.shadow {
        border: none;
    }

    .card-body.p-0 .table-responsive {
        overflow-x: auto;
    }

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
                    <table class="table table-bordered table-penilaian mb-0">
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
        if (!row.querySelector('td[colspan="7"]')) {
            const inputs = row.querySelectorAll('.nilai-input');
            const totalCell = row.querySelector('.total-score');
            function calculateAverage() {
                let sum = 0;
                let count = 0;
                inputs.forEach(input => {
                    const value = parseFloat(input.value);
                    if (!isNaN(value) && value >= 0 && value <= 100) { // Validasi sederhana
                        sum += value; //
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
                input.addEventListener('change', function() {
                    let value = parseFloat(this.value);
                    if (isNaN(value) || value < 0) {
                        this.value = '';
                    } else if (value > 100) {
                        this.value = 100;
                    }
                    calculateAverage();
                });
            });

            calculateAverage();
        }
    });
});
</script>
@endpush
