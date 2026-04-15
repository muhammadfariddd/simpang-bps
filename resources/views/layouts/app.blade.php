<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Dashboard') — SIMPANG-BPS</title>
    <meta name="description" content="SIMPANG-BPS — Sistem Informasi Manajemen Progress & Jejak Digital Magang BPS">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="icon" href="{{ asset('images/favicon-32x32.png') }}" sizes="32x32" type="image/png">
    <link rel="icon" href="{{ asset('images/favicon-16x16.png') }}" sizes="16x16" type="image/png">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    {{-- Icon Fonts (Tabler Icons) --}}
    <link rel="stylesheet" href="{{ asset('fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome.css') }}">

    {{-- Toastify --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js" defer></script>

    {{-- NProgress — Top Loading Bar --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.css">
    <script src="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.min.js"></script>
    <style>
        /* Override NProgress: Trik Bar Panjang Tetap */
        #nprogress .bar {
            background: transparent !important;
            /* Matikan warna bar bawaan yang melar */
            height: 4px;
        }

        #nprogress .bar::before {
            content: "";
            display: block;
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 200px;
            /* Panjang biru yang akan selalu TEPAT / TETAP */
            background: #4680ff;
            /* Warna primary biru */
            border-radius: 4px;
        }

        #nprogress .peg {
            display: none !important;
            /* Sembunyikan peg bawaan karena sudah pakai shadow di ::before */
        }

        #nprogress .spinner-icon {
            border-top-color: #4680ff !important;
            border-left-color: #4680ff !important;
        }
    </style>

    {{-- App CSS (Tailwind v4 + Custom) --}}
    @vite(['resources/css/app.css'])

    @stack('styles')
</head>

<body>

    {{-- Pengecualian loader di sini. Loader hanya menggunakan NProgress Top Bar saja --}}

    {{-- ===== Sidebar ===== --}}
    @include('components.sidebar')

    {{-- ===== Header ===== --}}
    <header class="pc-header">
        <div class="header-wrapper">

            {{-- Logo Brand — pindah dari sidebar ke header --}}
            <a href="{{ route('dashboard') }}" class="header-brand">
                <img src="{{ asset('images/logo/logo.png') }}" alt="SIMPANG-BPS">
                <span class="header-brand-name">SIMPANG</span>
            </a>

            <div class="header-sub-wrapper">

                {{-- Hamburger Toggle — tampil di SEMUA ukuran layar --}}
                <button class="pc-head-link" id="hamburger-toggle" type="button" title="Toggle Sidebar">
                    <i class="ti ti-menu-2"></i>
                </button>

                {{-- Right: User Profile --}}
                <div class="ms-auto">
                    <ul class="list-unstyled">
                        <li class="dropdown pc-h-item">
                            <button class="user-pill-btn dropdown-toggle arrow-none" id="userDropdownBtn"
                                aria-expanded="false">
                                @if (Auth::user()->foto)
                                    <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto"
                                        class="user-avtar">
                                @else
                                    <img src="{{ asset('images/user/wa_profil.jpg') }}" alt="user"
                                        class="user-avtar">
                                @endif
                                <span class="user-gear"><i class="ti ti-settings"></i></span>
                            </button>
                            <div class="dropdown-menu" id="userDropdownMenu">
                                <div class="dropdown-header">
                                    <h4>
                                        @php
                                            date_default_timezone_set('Asia/Jakarta');
                                            $hour = (int) date('H');
                                            $greeting = match (true) {
                                                default => 'Selamat Malam',
                                                $hour >= 5 && $hour < 12 => 'Selamat Pagi',
                                                $hour >= 12 && $hour < 15 => 'Selamat Siang',
                                                $hour >= 15 && $hour < 18 => 'Selamat Sore',
                                            };
                                        @endphp
                                        {{ $greeting }},
                                        <span class="small text-muted">{{ Auth::user()->nama_lengkap }}</span>
                                    </h4>
                                    <p class="text-muted mb-0">
                                        {{ Auth::user()->peran === 'admin' ? 'Administrator' : 'Mahasiswa Magang' }}
                                        @if (Auth::user()->peran === 'mahasiswa' && Auth::user()->mahasiswa)
                                            <br><small>{{ Auth::user()->mahasiswa->universitas }}</small>
                                        @endif
                                    </p>
                                    <hr style="margin: 10px 0; border-color: #e7eaee;">
                                    <div class="profile-notification-scroll">
                                        <a href="{{ route('logout') }}" class="dropdown-item"
                                            onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                                            <i class="ti ti-logout"></i>
                                            <span>Logout</span>
                                        </a>
                                        <form id="logout-form-header" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>

        </div>
    </header>

    {{-- ===== Main Content ===== --}}
    <div class="pc-container">
        <div class="pc-content">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible" role="alert" id="flash-alert">
                    <i class="ti ti-circle-check"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" onclick="this.parentElement.remove()">×</button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible" role="alert" id="flash-alert-err">
                    <i class="ti ti-circle-x"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" onclick="this.parentElement.remove()">×</button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    {{-- ===== Footer ===== --}}
    <footer class="pc-footer">
        <div style="text-align:center;">
            <p style="margin:0; font-size:13px; color:#8996a4;">
                &copy; {{ date('Y') }} <strong>SIMPANG-BPS</strong> — Sistem Informasi Manajemen Progress & Jejak
                Digital Magang
                &nbsp;|&nbsp; BPS Kabupaten Jepara
            </p>
        </div>
    </footer>

    {{-- ===== Scripts ===== --}}
    @vite(['resources/js/app.js'])

    <script>
        // ── NProgress: tampil di PALING ATAS saat navigasi ─────
        NProgress.configure({
            showSpinner: false,
            trickleSpeed: 500
        });

        // Selesai saat halaman selesai dimuat
        document.addEventListener('DOMContentLoaded', function() {
            NProgress.done(true);
        });

        // Mulai saat klik link (navigasi antar halaman)
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a[href]');
            if (link && link.href &&
                !link.href.startsWith('#') &&
                !link.href.startsWith('javascript') &&
                link.target !== '_blank' &&
                !link.hasAttribute('data-bs-toggle')
            ) {
                NProgress.start();
            }
        });

        // Mulai saat submit form
        document.addEventListener('submit', function() {
            NProgress.start();
        });

        // ── User Dropdown toggle ────────────────────────────
        const dropBtn = document.getElementById('userDropdownBtn');
        const dropMenu = document.getElementById('userDropdownMenu');
        if (dropBtn && dropMenu) {
            dropBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                dropMenu.classList.toggle('show');
                dropBtn.setAttribute('aria-expanded', dropMenu.classList.contains('show'));
            });
            document.addEventListener('click', function() {
                dropMenu.classList.remove('show');
                dropBtn.setAttribute('aria-expanded', 'false');
            });
        }

        // ── Sidebar toggle — semua ukuran layar ─────────────────
        const hamburger = document.getElementById('hamburger-toggle');
        const sidebar = document.querySelector('.pc-sidebar');

        // Pada layar kecil (mobile/tablet), mulai dengan sidebar tersembunyi
        if (window.innerWidth < 992) {
            document.body.classList.add('sidebar-collapsed');
        }

        if (hamburger) {
            hamburger.addEventListener('click', function(e) {
                e.preventDefault();
                document.body.classList.toggle('sidebar-collapsed');
            });
        }

        // Tutup sidebar ketika klik di luar (hanya mobile)
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 992 && sidebar && hamburger) {
                if (!sidebar.contains(e.target) && !hamburger.contains(e.target)) {
                    document.body.classList.add('sidebar-collapsed');
                }
            }
        });

        // ── Bootstrap-style modal support ──────────────────
        document.querySelectorAll('[data-bs-toggle="modal"]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const target = document.querySelector(btn.getAttribute('data-bs-target'));
                if (target) {
                    target.style.display = 'flex';
                    target.classList.add('show');
                    document.body.style.overflow = 'hidden';
                }
            });
        });
        document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const modal = btn.closest('.modal');
                if (modal) {
                    modal.style.display = 'none';
                    modal.classList.remove('show');
                    document.body.style.overflow = '';
                }
            });
        });
        document.querySelectorAll('.modal').forEach(function(modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.style.display = 'none';
                    modal.classList.remove('show');
                    document.body.style.overflow = '';
                }
            });
        });

        // ── Auto dismiss flash alerts ───────────────────────
        setTimeout(function() {
            ['flash-alert', 'flash-alert-err'].forEach(function(id) {
                const el = document.getElementById(id);
                if (el) el.remove();
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>

</html>
