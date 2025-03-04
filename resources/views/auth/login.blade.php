<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Penilaian UPITRA</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('/images/background.png');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            position: relative;
        }
        /* Overlay untuk opacity background */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5); /* Opacity 50% */
            z-index: 1;
        }
        .login-container {
            position: relative;
            z-index: 2; /* Pastikan container di atas overlay */
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        .login-title {
            font-size: 1.75rem; /* Sesuaikan ukuran font */
            font-weight: bold;
            color: #fff; /* Warna teks putih */
            margin-bottom: 1.5rem; /* Sesuaikan jarak antara judul dan card */
            text-transform: uppercase; /* Jika teks huruf kapital */
            letter-spacing: 2px; /* Jarak antar huruf */
        }
        .login-card {
            background: rgba(255, 255, 255, 0.52); /* Latar belakang card semi-transparan */
            padding: 2rem;
            border-radius: 15px; /* Sesuaikan border radius */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); /* Efek shadow */
            width: 100%;
        }
        .login-card .form-control {
            margin-bottom: 1.5rem; /* Sesuaikan jarak antar input */
            border-radius: 8px; /* Sesuaikan border radius input */
            border: 1px solid #ddd; /* Warna border input */
            padding: 0.75rem;
            font-size: 1rem;
        }
        .login-card .form-control:focus {
            border-color: #007bff; /* Warna border saat input aktif */
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Efek shadow saat input aktif */
        }
        .login-card .btn {
            width: 100%;
            background-color: #007bff; /* Warna tombol */
            border: none;
            padding: 0.75rem;
            border-radius: 8px; /* Sesuaikan border radius tombol */
            font-size: 1rem;
            font-weight: bold;
            color: #fff; /* Warna teks tombol */
            transition: background-color 0.3s ease; /* Efek transisi */
        }
        .login-card .btn:hover {
            background-color: #0056b3; /* Warna tombol saat hover */
        }
        .login-card label {
            font-weight: bold;
            color: #333; /* Warna teks label */
            margin-bottom: 0.5rem; /* Jarak antara label dan input */
            display: block;
            text-align: left; /* Label rata kiri */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Judul di atas card -->
        <div class="login-title">
            Sistem Informasi Penilaian UPITRA
        </div>
        <!-- Card login -->
        <div class="login-card">
            @if ($errors->has('login'))
                <div class="alert alert-danger">
                    {{ $errors->first('login') }}
                </div>
            @endif
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">NIDN</label>
                    <input type="text" id="email" name="email" class="form-control" placeholder="Masukkan NIDN" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan Password" required>
                </div>
                <button type="submit" class="btn btn-primary">LOGIN</button>
            </form>
        </div>
    </div>
</body>
</html>
