<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - GreSOY</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            margin: 0;
            /* background gradient hijau */
            background: linear-gradient(135deg, #77ff76, #00912b);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .login-card {
            width: 420px;
            max-width: 90%;
            background: #ffffff;
            border-radius: 30px;
            padding: 40px 40px 45px;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.18);
            text-align: center;
        }

        .login-logo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .login-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .login-title {
            font-size: 36px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 600;
            text-align: left;
            width: 100%;
        }

        .login-input {
            border-radius: 999px;
            padding: 10px 18px;
            border: 1px solid #dcdcdc;
            box-shadow: none;
        }

        .login-input:focus {
            border-color: #00a82d;
            box-shadow: 0 0 0 0.15rem rgba(0, 168, 45, 0.25);
        }

        .btn-login {
            border-radius: 999px;
            padding: 12px 10px;
            font-size: 18px;
            font-weight: 600;
            border: none;
            width: 100%;
            background-color: #00a82d;
            color: #ffffff;
        }

        .btn-login:hover {
            background-color: #008f25;
        }
    </style>
</head>
<body>

<div class="login-card">

    {{-- Logo di atas (ganti src sesuai file logo kamu) --}}
    <div class="login-logo">
        {{-- Jika punya file logo: --}}
       <img src="{{ asset('images/gressoy-logo.png') }}" alt="GreSOY Logo">
    </div>

    <div class="login-title">LOGIN</div>

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        {{-- Username --}}
        <div class="mb-3 text-start">
            <label for="username" class="form-label">Username</label>
            <input
                type="text"
                id="username"
                name="username"
                class="form-control login-input @error('username') is-invalid @enderror"
                placeholder="Tulis Username Anda"
                value="{{ old('username') }}"
                required
            >
            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-4 text-start">
            <label for="password" class="form-label">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                class="form-control login-input @error('password') is-invalid @enderror"
                placeholder="Tulis Password Anda"
                required
            >
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tombol Login --}}
        <button type="submit" class="btn btn-login">
            LOGIN
        </button>
    </form>
</div>

{{-- Bootstrap JS (opsional) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
