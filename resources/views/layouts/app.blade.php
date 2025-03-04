<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OBE-WEB')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body {
            display: flex;
        }
        #sidebar-wrapper {
            width: 250px;
            background-color: #007bff; /* Warna biru untuk sidebar */
            color: #fff;
            height: 100vh;
            position: fixed;
            display: flex;
            flex-direction: column;
        }
        .sidebar-heading {
            padding: 1.5rem;
            text-align: center;
            background: #0056b3; /* Warna biru lebih gelap untuk heading */
        }
        .list-group-item {
            background: #007bff;
            color: #fff;
            border: none;
            margin: 0.25rem 0; /* Tambahkan jarak antara item */
            padding-top: 1rem; /* Tambahkan jarak vertikal */
            padding-bottom: 1rem; /* Tambahkan jarak vertikal */
        }
        .list-group-item:hover {
            background: #0056b3; /* Warna biru lebih gelap untuk hover */
        }
        .list-group-item.bg-danger {
            background: #dc3545; /* Warna merah untuk logout */
            border-radius: 4px; /* Buat kotak tombol logout */
            text-align: center;
        }
        .list-group-item.bg-danger:hover {
            background: #c82333; /* Warna merah lebih gelap untuk hover logout */
        }
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            width: calc(100% - 250px);
        }
        .dashboard-heading {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 2rem;
            color: #333;
        }
        .chart-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #666;
        }
    </style>
</head>
<body>
    @include('partials.sidebar')
    <div class="main-content">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('scripts')
</body>
</html>
