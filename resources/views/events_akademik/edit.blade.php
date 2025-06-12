@extends('layouts.app')

@section('title', 'Edit Event Akademik')

@section('content')
<div class="container-fluid">
    <h1 class="dashboard-heading mt-4">Edit Event Akademik</h1>

    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            Form Edit Event Akademik
        </div>
        <div class="card-body">
            <form action="{{ route('event_akademik.update', $event->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Penting: Gunakan method PUT untuk update --}}
                <div class="mb-3">
                    <label for="tanggal_event" class="form-label">Tanggal Event</label>
                    <input type="date" class="form-control" id="tanggal_event" name="tanggal_event" value="{{ old('tanggal_event', $event->tanggal_event) }}" required>
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <input type="text" class="form-control" id="deskripsi" name="deskripsi" value="{{ old('deskripsi', $event->deskripsi) }}" required>
                </div>
                <div class="mb-3">
                    <label for="tipe" class="form-label">Tipe (untuk warna di kalender)</label>
                    <select class="form-select" id="tipe" name="tipe">
                        <option value="">Pilih Tipe (Opsional)</option>
                        <option value="info" {{ old('tipe', $event->tipe) == 'info' ? 'selected' : '' }}>Info (Biru)</option>
                        <option value="success" {{ old('tipe', $event->tipe) == 'success' ? 'selected' : '' }}>Penting (Hijau)</option>
                        <option value="warning" {{ old('tipe', $event->tipe) == 'warning' ? 'selected' : '' }}>Perhatian (Kuning)</option>
                        <option value="danger" {{ old('tipe', $event->tipe) == 'danger' ? 'selected' : '' }}>Urgensi (Merah)</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Perbarui Event</button>
                <a href="{{ route('event_akademik.index') }}" class="btn btn-secondary">Batal</a>
            </form>
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
    </style>
@endsection