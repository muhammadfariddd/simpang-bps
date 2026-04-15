@php $peran = Auth::user()->peran; @endphp

{{-- [ Sidebar Menu ] start --}}
<nav class="pc-sidebar" id="pc-sidebar">
    <div class="navbar-wrapper">


        {{-- Menu --}}
        <div class="navbar-content">
            <ul class="pc-navbar">

                {{-- ── Main Menu (semua role) ────────────────────── --}}
                <li class="pc-item pc-caption">
                    <label>Main Menu</label>
                </li>
                <li class="pc-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-smart-home"></i></span>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>

                {{-- ── Presensi (semua role) ──────────────────────── --}}
                <li class="pc-item pc-caption">
                    <label>Kehadiran</label>
                </li>
                <li class="pc-item {{ request()->routeIs('presensi.*') ? 'active' : '' }}">
                    <a href="{{ route('presensi.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-user-check"></i></span>
                        <span class="pc-mtext">Presensi Digital</span>
                    </a>
                </li>

                {{-- ── Logbook (semua role) ───────────────────────── --}}
                <li class="pc-item pc-caption">
                    <label>Jejak Digital</label>
                </li>
                <li class="pc-item {{ request()->routeIs('logbook.*') ? 'active' : '' }}">
                    <a href="{{ route('logbook.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-notebook"></i></span>
                        <span class="pc-mtext">Logbook Harian</span>
                        @if ($peran === 'admin')
                            @php $pendingLogbook = \App\Models\Logbook::where('status','pending')->count(); @endphp
                            @if ($pendingLogbook > 0)
                                <span class="badge bg-danger ms-auto text-white">{{ $pendingLogbook }}</span>
                            @endif
                        @endif
                    </a>
                </li>

                {{-- ── Proyek (semua role) ────────────────────────── --}}
                <li class="pc-item {{ request()->routeIs('proyek.*') ? 'active' : '' }}">
                    <a href="{{ route('proyek.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-clipboard-list"></i></span>
                        <span class="pc-mtext">Progress Proyek</span>
                    </a>
                </li>

                {{-- ── Pengumuman (semua role) ────────────────────── --}}
                <li class="pc-item {{ request()->routeIs('pengumuman.*') ? 'active' : '' }}">
                    <a href="{{ route('pengumuman.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-speakerphone"></i></span>
                        <span class="pc-mtext">Pengumuman</span>
                    </a>
                </li>

                {{-- ── Admin Only ──────────────────────────────────── --}}
                @if ($peran === 'admin')
                    <li class="pc-item pc-caption">
                        <label>Manajemen</label>
                    </li>

                    <li class="pc-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-users"></i></span>
                            <span class="pc-mtext">Data Mahasiswa</span>
                        </a>
                    </li>

                    <li class="pc-item {{ request()->routeIs('penilaian.*') ? 'active' : '' }}">
                        <a href="{{ route('penilaian.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-star"></i></span>
                            <span class="pc-mtext">Penilaian Akhir</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Laporan</label>
                    </li>

                    <li class="pc-item {{ request()->routeIs('laporan.kehadiran') ? 'active' : '' }}">
                        <a href="{{ route('laporan.kehadiran') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-calendar-stats"></i></span>
                            <span class="pc-mtext">Rekap Kehadiran</span>
                        </a>
                    </li>

                    <li class="pc-item {{ request()->routeIs('laporan.logbook') ? 'active' : '' }}">
                        <a href="{{ route('laporan.logbook') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-report"></i></span>
                            <span class="pc-mtext">Rekap Logbook</span>
                        </a>
                    </li>
                @endif

                {{-- ── Mahasiswa: E-Portfolio ──────────────────────── --}}
                @if ($peran === 'mahasiswa')
                    <li class="pc-item pc-caption">
                        <label>E-Portfolio</label>
                    </li>

                    <li class="pc-item {{ request()->routeIs('laporan.kehadiran.saya') ? 'active' : '' }}">
                        <a href="{{ route('laporan.kehadiran.saya') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-calendar-stats"></i></span>
                            <span class="pc-mtext">Rekap Kehadiran Saya</span>
                        </a>
                    </li>

                    <li class="pc-item {{ request()->routeIs('laporan.logbook.saya') ? 'active' : '' }}">
                        <a href="{{ route('laporan.logbook.saya') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-file-description"></i></span>
                            <span class="pc-mtext">Rekap Logbook Saya</span>
                        </a>
                    </li>

                    {{-- Sertifikat (hanya jika status selesai) --}}
                    @if (Auth::user()->mahasiswa && Auth::user()->mahasiswa->status === 'selesai')
                        <li class="pc-item">
                            <a href="#" class="pc-link" style="color: #28a745;">
                                <span class="pc-micon"><i class="ti ti-certificate"></i></span>
                                <span class="pc-mtext">Unduh Sertifikat</span>
                            </a>
                        </li>
                    @endif
                @endif

                {{-- ── Account ─────────────────────────────────────── --}}
                <li class="pc-item pc-caption">
                    <label>Account</label>
                </li>

                <li class="pc-item">
                    <a href="{{ route('logout') }}" class="pc-link"
                        onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                        <span class="pc-micon"><i class="ti ti-logout"></i></span>
                        <span class="pc-mtext">Logout</span>
                    </a>
                    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>

            </ul>
        </div>
    </div>
</nav>
{{-- [ Sidebar Menu ] end --}}
