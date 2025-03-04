@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <h1 class="dashboard-heading mt-4">Dashboard</h1>
    <div class="row">
        <div class="col-md-6">
            <h2 class="chart-title">Nilai Rata-rata Mahasiswa</h2>
            <canvas id="pieChart"></canvas>
        </div>
        <div class="col-md-6">
            <h2 class="chart-title">Jumlah Mahasiswa Pertahun</h2>
            <canvas id="lineChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Pie Chart
var ctxPie = document.getElementById('pieChart').getContext('2d');
var pieChart = new Chart(ctxPie, {
    type: 'pie',
    data: {
        labels: ['A', 'B', 'C', 'D', 'E'],
        datasets: [{
            data: [32.6, 27.2, 16.3, 12, 12],
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
        }]
    },
    options: {
        responsive: true
    }
});

// Line Chart
var ctxLine = document.getElementById('lineChart').getContext('2d');
var lineChart = new Chart(ctxLine, {
    type: 'line',
    data: {
        labels: ['2020', '2021', '2022', '2023', '2024'],
        datasets: [{
            label: 'Jumlah Mahasiswa Pertahun',
            data: [200, 180, 250, 260, 210],
            borderColor: '#36A2EB',
            fill: false
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endsection
