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
        height: 300px;
        width: 100%;
    }

    .table-sm th, .table-sm td {
        padding: 0.5rem;
        text-align: center;
        vertical-align: middle;
    }
</style>

<div class="container-fluid">
    <h1 class="dashboard-heading mt-4">Dashboard</h1>
    <div class="row">
        <!-- Jumlah Mahhasiswa Per Prodi -->
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

        <!-- Kalender Akademik -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span>Kalender Akademik</span>
                    <div>
                        <button class="btn btn-sm btn-light prev-month"><i class="fas fa-chevron-left"></i></button>
                        <button class="btn btn-sm btn-light next-month"><i class="fas fa-chevron-right"></i></button>
                        <button class="btn btn-sm btn-light today-btn">Hari Ini</button>
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
                            <!-- Calendar akan di-generate oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk Chart -->
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

<!-- Script Kalender -->
<script>
    let today = new Date();
    let currentMonth = today.getMonth();
    let currentYear = today.getFullYear();

    const monthNames = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.prev-month').addEventListener('click', () => {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            generateCalendar(currentMonth, currentYear);
        });

        document.querySelector('.next-month').addEventListener('click', () => {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            generateCalendar(currentMonth, currentYear);
        });

        document.querySelector('.today-btn').addEventListener('click', () => {
            today = new Date();
            currentMonth = today.getMonth();
            currentYear = today.getFullYear();
            generateCalendar(currentMonth, currentYear);
        });

        generateCalendar(currentMonth, currentYear);
    });

    function generateCalendar(month, year) {
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = 32 - new Date(year, month, 32).getDate();
        const calendarBody = document.getElementById('calendar-body');
        calendarBody.innerHTML = "";

        document.getElementById('monthYear').innerText = `${monthNames[month]} ${year}`;
        document.getElementById('todayDate').innerText = formatTodayDate();

        let date = 1;
        for (let i = 0; i < 6; i++) {
            const row = document.createElement('tr');

            for (let j = 0; j < 7; j++) {
                const cell = document.createElement('td');
                if (i === 0 && j < (firstDay === 0 ? 6 : firstDay - 1)) {
                    cell.innerHTML = "";
                } else if (date > daysInMonth) {
                    break;
                } else {
                    cell.innerHTML = date;

                    if (date === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                        cell.classList.add('bg-primary', 'text-white', 'fw-bold');
                    }

                    date++;
                }
                row.appendChild(cell);
            }

            calendarBody.appendChild(row);
        }
    }

    function formatTodayDate() {
        const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
        return today.toLocaleDateString('id-ID', options);
    }
</script>

@endsection