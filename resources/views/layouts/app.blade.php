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

        /* Styling Sidebar */
        #sidebar-wrapper {
            width: 250px;
            background-color: #0D6EFD; /* Biru utama Bootstrap */
            color: #fff;
            height: 100vh;
            position: fixed;
            display: flex;
            flex-direction: column;
        }

        .sidebar-heading {
            padding: 1.5rem;
            text-align: center;
            background: #025CE2; /* Biru lebih gelap */
            font-size: 1.2rem;
            font-weight: bold;
        }

        /* Styling Item Sidebar */
        .list-group-item {
            background: transparent !important;
            color: white !important;
            border: none;
            margin: 0.25rem 0;
            padding: 1rem;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s ease-in-out;
        }

        /* Hover Effect */
        .list-group-item:hover {
            background: #0B5ED7 !important; /* Biru lebih terang */
            color: #fff !important;
        }

        /* Aktif Item Lebih Mencolok */
        .list-group-item.active {
            background-color: #B3D4FF !important; /* Biru muda */
            color: #0D47A1 !important; /* Biru tua */
            font-weight: bold;
            border-left: 5px solid #0B5ED7; /* Border kiri tebal */
        }

        /* Tombol Logout */
        .logout-btn {
            width: 90%;
            margin: 10px auto;
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
    @yield('styles')
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
