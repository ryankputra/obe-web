@extends('layouts.app')

@section('title', 'Detail CPL')

@section('content')
<div class="container mt-4">
    <h2 style="color: #0056b3;">Bobot {{ $cpl->kode_cpl }}</h2>

    <a href="{{ route('cpl.index') }}" class="btn btn-secondary mt-2 mb-3">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Kode CPMK</th>
                    <th>Deskripsi</th>
                    <th>Bobot</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cpl->cpmks as $cpmk)
                    <tr>
                        <td>{{ $cpmk->kode_cpmk }}</td>
                        <td>{{ $cpmk->deskripsi }}</td>
                        <td class="text-center">{{ $cpmk->bobot }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" class="table-total">TOTAL</td>
                    <td class="table-total">{{ $cpl->cpmks->sum('bobot') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<style>
    body {
        background-color: #dff3fd;
    }
    .table th {
        background-color: #0056b3;
        color: white;
        text-align: center;
        border: 2px solid black;
    }
    .table td {
        border: 2px solid black;
    }
    .table-total {
        font-weight: bold;
        text-align: center;
    }
    .btn-icon {
        background-color: #45d169;
        color: white;
        border-radius: 50%;
        border: none;
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        margin-right: 10px;
    }
</style>
@endsection
