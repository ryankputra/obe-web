@extends('layouts.app')

@section('content')

<style>
    body {
        background-color: #def4ff;
    }

    .container-fluid {
        margin-top: 50px;
    }

    .card-body {
        margin-top: 20px;
    }

    .chart-container {
        position: relative;
        height: 300px; /* Sesuaikan tinggi chart jika perlu */
        width: 100%;
    }

    .table-sm th, .table-sm td {
        padding: 0.5rem;
        text-align: center;
        vertical-align: middle;
    }

    /* Style untuk event di kalender */
    .has-event {
        position: relative;
        cursor: pointer;
    }
    .has-event .event-indicator {
        position: absolute;
        bottom: 2px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 0.8em; /* Sedikit lebih besar agar lebih mudah terlihat */
        font-weight: bold; /* Pastikan tebal */
        z-index: 1; /* Pastikan di atas elemen lain jika ada tumpang tindih */
    }

    /* --- ATURAN CSS BARU/DIPERBAIKI UNTUK VISIBILITAS EVENT --- */
    /* Pastikan warna teks default untuk event indicator terlihat */
    .text-info { color: #0dcaf0 !important; }
    .text-success { color: #198754 !important; }
    .text-warning { color: #ffc107 !important; }
    .text-danger { color: #dc3545 !important; }
    .text-secondary { color: #6c757d !important; }


    /* Override warna event indicator jika sel kalender memiliki latar belakang bg-primary (seperti "Hari Ini") */
    /* Ini untuk memastikan kontras yang baik antara warna event dengan latar belakang biru */
    .table-bordered .bg-primary.text-white .event-indicator.text-info {
        color: white !important; /* Putih di biru */
    }
    .table-bordered .bg-primary.text-white .event-indicator.text-success {
        color: limegreen !important; /* Hijau terang di biru */
    }
    .table-bordered .bg-primary.text-white .event-indicator.text-warning {
        color: yellow !important; /* Kuning di biru */
    }
    .table-bordered .bg-primary.text-white .event-indicator.text-danger {
        color: white !important; /* Putih di biru */
    }
    .table-bordered .bg-primary.text-white .event-indicator.text-secondary {
        color: lightgray !important; /* Abu terang di biru */
    }
    /* --- AKHIR ATURAN CSS BARU/DIPERBAIKI --- */

</style>

<div class="container-fluid">
    <h1 class="dashboard-heading mt-4">Dashboard</h1>
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    Jumlah Mahasiswa Tiap Prodi
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="prodiChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span>Kalender Akademik</span>
                    <div>
                        <button class="btn btn-sm btn-light prev-month" aria-label="Bulan Sebelumnya"><i class="fas fa-chevron-left"></i></button>
                        <button class="btn btn-sm btn-light next-month" aria-label="Bulan Selanjutnya"><i class="fas fa-chevron-right"></i></button>
                        <button class="btn btn-sm btn-light today-btn" aria-label="Kembali ke Hari Ini">Hari Ini</button>
                    </div>
                </div>
                <div class="card-body text-center">
                    <h5 id="monthYear" class="mb-2"></h5>
                    <p id="todayDate" class="text-primary"></p>
                    <table class="table table-bordered table-sm mt-3">
                        <thead>
                            <tr>
                                <th>Min</th>
                                <th>Sen</th>
                                <th>Sel</th>
                                <th>Rab</th>
                                <th>Kam</th>
                                <th>Jum</th>
                                <th>Sab</th>
                            </tr>
                        </thead>
                        <tbody id="calendar-body">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Event Akademik (FULL WIDTH) --}}
    <div class="row mb-4"> {{-- Memulai baris baru untuk full width --}}
        <div class="col-12"> {{-- Kolom 12 untuk mengambil lebar penuh --}}
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    Daftar Event Akademik
                </div>
                <div class="card-body">
                    {{-- Definisi mapping tipe event ke label Bahasa Indonesia --}}
                    @php
                        $displayTypeMap = [
                            'info' => 'Info (Biru)',
                            'success' => 'Penting (Hijau)',
                            'warning' => 'Perhatian (Kuning)',
                            'danger' => 'Urgensi (Merah)',
                            // Tambahkan tipe lain jika ada
                        ];
                    @endphp

                    @if(count($events) > 0)
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Deskripsi</th>
                                    <th>Tipe</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $event)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($event->date)->format('d F Y') }}</td>
                                        <td>{{ $event->description }}</td>
                                        <td>
                                            <span class="badge bg-{{ $event->type ?? 'secondary' }}">
                                                {{ $displayTypeMap[$event->type] ?? ucfirst($event->type ?? 'Tidak Ada') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Tidak ada event akademik yang dijadwalkan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data dinamis dari controller
        const prodiData = {
            labels: [@foreach($prodiData as $data) '{{ $data['nama_prodi'] }}', @endforeach],
            datasets: [{
                label: 'Jumlah Mahasiswa',
                data: [@foreach($prodiData as $data) {{ $data['jumlah_mahasiswa'] }}, @endforeach],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(255, 205, 86, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 205, 86, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        };

        // Buat chart
        const ctx = document.getElementById('prodiChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: prodiData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 50
                        }
                    }
                }
            }
        });
    });
</script>

{{-- Definisi data event secara global sebelum skrip kalender eksternal dimuat --}}
<script>
    window.eventsData = []; // Inisialisasi secara global sebagai array kosong
    @if (auth()->check() && auth()->user()->role == 'admin')
        // Jika pengguna adalah admin, isi dengan data event dari controller
        window.eventsData = @json($events ?? []); 
    @endif
</script>

{{-- Memuat skrip kalender dari file eksternal ke stack 'scripts' --}}
@push('scripts')
<script src="{{ asset('js/dashboard-calendar.js') }}"></script>
@endpush

@endsection