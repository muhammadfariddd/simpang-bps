<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Login — SIMPANG-BPS Sistem Informasi Manajemen Progress & Jejak Digital Magang">
    <title>Login | SIMPANG-BPS</title>

    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('fonts/tabler-icons.min.css') }}">

    {{-- Toastify --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js" defer></script>

    @vite(['resources/css/app.css'])

    <style>
        /* Override untuk halaman login — background gradient */
        body {
            background: linear-gradient(135deg, #e8eef8 0%, #f2f4f7 100%);
        }

        .auth-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 12px 40px rgba(70, 128, 255, 0.12), 0 2px 8px rgba(0, 0, 0, 0.06);
            padding: 40px 36px;
            width: 100%;
            max-width: 420px;
        }

        .auth-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 8px;
        }

        .auth-logo img {
            max-height: 56px;
        }

        .auth-title {
            text-align: center;
            margin-bottom: 24px;
        }

        .auth-title h2 {
            font-size: 22px;
            font-weight: 700;
            color: #1d2630;
            margin: 0 0 4px;
        }

        .auth-title p {
            font-size: 13.5px;
            color: #8996a4;
            margin: 0;
        }

        .auth-divider {
            border: none;
            border-top: 1px solid #e7eaee;
            margin: 12px 0 20px;
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #8996a4;
            font-size: 17px;
            padding: 0;
            display: flex;
            align-items: center;
        }

        .password-toggle:hover {
            color: #4680ff;
        }
    </style>
</head>

<body>

    {{-- Pre-loader --}}
    <div class="loader-bg" id="page-loader">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <div class="auth-main">
        <div class="auth-card">

            {{-- Logo --}}
            <div class="auth-logo">
                <a href="{{ route('login') }}">
                    <img src="{{ asset('images/logo/logo.png') }}" alt="Logo SIMPANG-BPS">
                </a>
            </div>

            {{-- Title --}}
            <div class="auth-title">
                <h2>SIMPANG-BPS</h2>
                <p>Sistem Informasi Manajemen Progress & Jejak Digital Magang</p>
            </div>

            <hr class="auth-divider">

            {{-- Flash / Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger mb-3">
                    <i class="ti ti-circle-x"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger mb-3">
                    <i class="ti ti-circle-x"></i>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                {{-- Username --}}
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control"
                        placeholder="Masukkan username" value="{{ old('username') }}" autocomplete="off" required>
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Masukkan password" required style="padding-right: 42px;">
                        <button type="button" class="password-toggle" id="togglePass">
                            <i class="ti ti-eye" id="togglePassIcon"></i>
                        </button>
                    </div>
                </div>

                <hr class="auth-divider">

                {{-- Submit --}}
                <button type="submit" class="btn btn-primary"
                    style="width:100%; justify-content:center; padding:11px;">
                    <i class="ti ti-login"></i>
                    Sign In
                </button>
            </form>

        </div>
    </div>

    @vite(['resources/js/app.js'])

    <script>
        // Pre-loader dismiss
        window.addEventListener('load', function() {
            const loader = document.getElementById('page-loader');
            if (loader) {
                loader.classList.add('fade-out');
                setTimeout(() => loader.remove(), 500);
            }
        });

        // Password toggle
        const toggleBtn = document.getElementById('togglePass');
        const toggleIcon = document.getElementById('togglePassIcon');
        const pwField = document.getElementById('password');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                const isPass = pwField.type === 'password';
                pwField.type = isPass ? 'text' : 'password';
                toggleIcon.className = isPass ? 'ti ti-eye-off' : 'ti ti-eye';
            });
        }

        // Form validation with Toastify
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();

            if (!username || !password) {
                e.preventDefault();
                Toastify({
                    text: "Username dan Password wajib diisi!",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "#dc2626",
                        borderRadius: "8px"
                    }
                }).showToast();
            }
        });
    </script>

</body>

</html>
