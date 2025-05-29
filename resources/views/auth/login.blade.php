<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Penilaian UPITRA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-image: url('/images/background.png'); /* Pastikan path gambar ini benar */
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
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card .btn:hover {
            background-color: #0056b3;
        }
        .login-card .btn:disabled {
            background-color: #0056b3;
            opacity: 0.65;
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
        .password-container {
            position: relative;
            margin-bottom: 1.5rem; /* Tetap ada margin bawah untuk password container */
        }
        /* Menghapus margin-bottom dari form-control di dalam password-container karena sudah dihandle oleh .password-container */
        .password-container .form-control {
            margin-bottom: 0;
            padding-right: 40px; 
        }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 1.2rem;
            color: #333;
        }
        .alert { /* Sedikit margin atas untuk pesan error */
            margin-top: 0;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-title">
            Sistem Informasi Penilaian OBE
        </div>
        <div class="login-card">
            {{-- Bagian untuk menampilkan pesan error --}}
            @if ($errors->has('login'))
                <div class="alert alert-danger">
                    {{ $errors->first('login') }}
                </div>
            @endif
            {{-- Akhir bagian pesan error --}}

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    {{-- 'email' adalah nama field yang umum digunakan Laravel untuk username, sesuaikan jika berbeda --}}
                    {{-- value="{{ old('email') }}" akan menjaga Email yang diinput jika ada error validasi --}}
                    <input type="text" id="email" name="email" class="form-control" placeholder="Masukkan Email" required value="{{ old('email') }}">
                </div>
                <div class="form-group"> {{-- form-group untuk konsistensi styling jika diperlukan --}}
                    <label for="password">Password</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan Password" required>
                        <i class="bi bi-eye-slash password-toggle" id="togglePassword"></i>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" id="loginButton">
                    <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true" id="loginSpinner"></span>
                    <span id="loginButtonText">LOGIN</span>
                </button>
                <div class="text-center mt-3">
                    <a href="{{ route('password.request') }}" class="text-decoration-none text-primary">
                        Lupa Password?
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const passwordField = document.getElementById("password");
        const toggleIcon = document.getElementById("togglePassword");

        if (toggleIcon) {
            toggleIcon.addEventListener('click', function() {
                if (passwordField.type === "password") {
                    passwordField.type = "text";
                    toggleIcon.classList.remove("bi-eye-slash");
                    toggleIcon.classList.add("bi-eye");
                } else {
                    passwordField.type = "password";
                    toggleIcon.classList.remove("bi-eye");
                    toggleIcon.classList.add("bi-eye-slash");
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            const loginSpinner = document.getElementById('loginSpinner');
            const loginButtonText = document.getElementById('loginButtonText');

            if (loginForm && loginButton && loginSpinner && loginButtonText) {
                loginForm.addEventListener('submit', function() {
                    loginSpinner.classList.remove('d-none');
                    loginButtonText.textContent = 'MEMPROSES...';
                    loginButton.disabled = true;
                });
            }
        });
    </script>
</body>
</html>