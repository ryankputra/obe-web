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
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }
        .login-container {
            position: relative;
            z-index: 2;
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        .login-title {
            font-size: 1.75rem;
            font-weight: bold;
            color: #fff;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.52);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            width: 100%;
        }
        .login-card .form-control {
            margin-bottom: 1.5rem;
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 0.75rem;
            font-size: 1rem;
        }
        .login-card .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }
        .login-card .btn {
            width: 100%;
            background-color: #007bff;
            border: none;
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            color: #fff;
            transition: background-color 0.3s ease;
        }
        .login-card .btn:hover {
            background-color: #0056b3;
        }
        .login-card label {
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
            display: block;
            text-align: left;
        }
        .text-center a {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-title">
            Sistem Informasi Penilaian OBE
        </div>
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
                <!-- Tambahan Reset Password -->
                <div class="text-center mt-3">
                    <a href="{{ route('password.request') }}" class="text-decoration-none text-primary">
                        Lupa Password?
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
