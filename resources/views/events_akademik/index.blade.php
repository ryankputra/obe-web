@extends('layouts.app')

@section('title', 'Manajemen Event Akademik')

@section('content')
<div class="container-fluid">
    <h1 class="dashboard-heading mt-4">Manajemen Event Akademik</h1>

    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('event_akademik.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Event
        </a>
    </div>

    <div class="card shadow mb-4">
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
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th>Tanggal Event</th>
                            <th>Deskripsi</th>
                            <th>Tipe</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($events as $event)
                            @php
                                $displayTypeName = $displayTypeMap[$event->tipe] ?? ucfirst($event->tipe ?? 'Tidak Ada');
                            @endphp
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($event->tanggal_event)->format('d F Y') }}</td>
                                <td class="text-start">{{ $event->deskripsi }}</td>
                                <td><span class="badge bg-{{ $event->tipe ?? 'secondary' }}">{{ $displayTypeName }}</span></td>
                                <td>
                                    <a href="{{ route('event_akademik.edit', $event->id) }}" class="btn btn-warning btn-sm me-1">Edit</a>
                                    <form action="{{ route('event_akademik.destroy', $event->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus event ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada event akademik ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
                {{ $events->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
    <style>
        body {
            background-color: #def4ff; /* Konsisten dengan dashboard */
        }
        .dashboard-heading {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }
        .card-header {
            background-color: rgb(0, 114, 202) !important; /* Warna biru primary dari dashboard */
            color: white !important;
        }
        .table thead th {
            background-color: rgb(0, 114, 202) !important; /* Warna biru primary untuk header tabel */
            color: white !important;
            vertical-align: middle;
            text-align: center;
        }
        /* Pastikan warna badge konsisten dengan dashboard */
        .badge.bg-info { background-color: #0dcaf0 !important; }
        .badge.bg-success { background-color: #198754 !important; }
        .badge.bg-warning { background-color: #ffc107 !important; }
        .badge.bg-danger { background-color: #dc3545 !important; }
        .badge.bg-secondary { background-color: #6c757d !important; }
    </style>
@endsection