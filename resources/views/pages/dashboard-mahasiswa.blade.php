@extends('layouts.app')
@section('title', 'Dashboard Mahasiswa')

@section('content')

    {{-- [ breadcrumb ] --}}
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-header-title">
                        <h5>Dashboard Saya</h5>
                    </div>
                </div>
                <div class="col-auto">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Pages</a></li>
                        <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Profil Ringkasan --}}
    @if ($mahasiswa)
        <div class="card"
            style="margin-bottom:16px; background:linear-gradient(135deg,#4680ff 0%,#6f42c1 100%); color:#fff;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 style="color:#fff;margin:0 0 4px;">
                            Selamat datang, {{ Auth::user()->nama_lengkap }}!
                        </h4>
                        <p style="margin:0;opacity:0.85;font-size:14px;">
                            <i class="ti ti-school"></i> {{ $mahasiswa->universitas }} — {{ $mahasiswa->jurusan }}
                            &nbsp;|&nbsp;
                            <i class="ti ti-building"></i> Divisi: {{ $mahasiswa->divisi ?? '-' }}
                        </p>
                        <p style="margin:4px 0 0;opacity:0.85;font-size:13px;">
                            <i class="ti ti-calendar"></i>
                            Periode: {{ $mahasiswa->periode_mulai?->format('d M Y') }} s/d
                            {{ $mahasiswa->periode_selesai?->format('d M Y') }}
                            &nbsp;|&nbsp;
                            <span class="badge"
                                style="background:rgba(255,255,255,0.25);">{{ ucfirst($mahasiswa->status) }}</span>
                        </p>
                    </div>
                    <div class="col-auto">
                        @if ($mahasiswa->status === 'selesai')
                            <span class="badge"
                                style="font-size:14px;background:rgba(255,255,255,0.25);padding:10px 16px;">
                                <i class="ti ti-award"></i> Magang Selesai
                            </span>
                        @else
                            <span class="badge"
                                style="font-size:14px;background:rgba(255,255,255,0.25);padding:10px 16px;">
                                <i class="ti ti-clock"></i> Sedang Berjalan
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- [ Stat Cards ] --}}
    <div class="row">

        {{-- Total Hadir --}}
        <div class="col-xl-3 col-md-6">
            <div class="card dashnum-card bg-primary-dark text-white overflow-hidden" style="margin-bottom:16px;">
                <span class="round small"></span>
                <span class="round big"></span>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="avtar avtar-lg">
                                <i class="text-white ti ti-user-check"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('presensi.index') }}" class="avtar avtar-s text-white"
                                style="background:rgba(0,0,0,0.2);text-decoration:none;">
                                <i class="ti ti-dots"></i>
                            </a>
                        </div>
                    </div>
                    <span class="text-white d-block f-34 f-w-500 my-2">{{ $totalHadir }}</span>
                    <p class="mb-0 opacity-50">Total Hari Hadir</p>
                </div>
            </div>
        </div>

        {{-- Logbook Disetujui --}}
        <div class="col-xl-3 col-md-6">
            <div class="card dashnum-card bg-success text-white overflow-hidden" style="margin-bottom:16px;">
                <span class="round small"></span>
                <span class="round big"></span>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="avtar avtar-lg">
                                <i class="text-white ti ti-notebook"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('logbook.index') }}" class="avtar avtar-s text-white"
                                style="background:rgba(0,0,0,0.2);text-decoration:none;">
                                <i class="ti ti-dots"></i>
                            </a>
                        </div>
                    </div>
                    <span class="text-white d-block f-34 f-w-500 my-2">{{ $logbookDisetujui }}</span>
                    <p class="mb-0 opacity-50">Logbook Disetujui</p>
                </div>
            </div>
        </div>

        {{-- Logbook Pending --}}
        <div class="col-xl-3 col-md-6">
            <div class="card dashnum-card bg-warning text-white overflow-hidden" style="margin-bottom:16px;">
                <span class="round small"></span>
                <span class="round big"></span>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="avtar avtar-lg">
                                <i class="text-white ti ti-clock"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('logbook.index') }}" class="avtar avtar-s text-white"
                                style="background:rgba(0,0,0,0.2);text-decoration:none;">
                                <i class="ti ti-dots"></i>
                            </a>
                        </div>
                    </div>
                    <span class="text-white d-block f-34 f-w-500 my-2">{{ $logbookPending }}</span>
                    <p class="mb-0 opacity-50">Logbook Pending</p>
                </div>
            </div>
        </div>

        {{-- Progress Proyek --}}
        <div class="col-xl-3 col-md-6">
            <div class="card dashnum-card bg-secondary-dark text-white overflow-hidden" style="margin-bottom:16px;">
                <span class="round small"></span>
                <span class="round big"></span>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="avtar avtar-lg">
                                <i class="text-white ti ti-clipboard-list"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('proyek.index') }}" class="avtar avtar-s text-white"
                                style="background:rgba(0,0,0,0.2);text-decoration:none;">
                                <i class="ti ti-dots"></i>
                            </a>
                        </div>
                    </div>
                    <span class="text-white d-block f-34 f-w-500 my-2">{{ $avgProgress }}%</span>
                    <p class="mb-0 opacity-50">Progress Proyek Rata-rata</p>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        {{-- Presensi Hari Ini --}}
        <div class="col-xl-4">
            <div class="card" style="margin-bottom:16px;">
                <div class="card-header">
                    <h5 style="margin:0;"><i class="ti ti-map-pin"></i> Presensi Hari Ini</h5>
                </div>
                <div class="card-body" style="text-align:center;">
                    @if (!$presensiHariIni)
                        <div style="padding:12px 0;">
                            <i class="ti ti-clock" style="font-size:2.5rem;color:#8996a4;"></i>
                            <p style="color:#8996a4;margin:8px 0 16px;">Belum Check-In hari ini</p>
                            <form method="POST" action="{{ route('presensi.check-in') }}" id="formCheckIn">
                                @csrf
                                <input type="hidden" name="lat" id="lat_in">
                                <input type="hidden" name="lng" id="lng_in">
                                <button type="submit" class="btn btn-success" style="width:100%;padding:12px;">
                                    <i class="ti ti-login"></i> Check-In Sekarang
                                </button>
                            </form>
                        </div>
                    @elseif(!$presensiHariIni->check_out)
                        <div style="padding:12px 0;">
                            <i class="ti ti-circle-check" style="font-size:2.5rem;color:#28a745;"></i>
                            <p style="color:#28a745;font-weight:600;margin:8px 0 4px;">Sudah Check-In</p>
                            <p style="color:#8996a4;font-size:13px;margin:0 0 16px;">
                                Pukul {{ $presensiHariIni->check_in }}
                            </p>
                            <form method="POST" action="{{ route('presensi.check-out') }}" id="formCheckOut">
                                @csrf
                                <input type="hidden" name="lat" id="lat_out">
                                <input type="hidden" name="lng" id="lng_out">
                                <button type="submit" class="btn btn-warning" style="width:100%;padding:12px;">
                                    <i class="ti ti-logout"></i> Check-Out Sekarang
                                </button>
                            </form>
                        </div>
                    @else
                        <div style="padding:12px 0;">
                            <i class="ti ti-check-all" style="font-size:2.5rem;color:#4680ff;"></i>
                            <p style="color:#4680ff;font-weight:600;margin:8px 0 4px;">Presensi Lengkap</p>
                            <p style="color:#8996a4;font-size:13px;margin:0;">
                                Check-In: {{ $presensiHariIni->check_in }}<br>
                                Check-Out: {{ $presensiHariIni->check_out }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Progress Proyek --}}
        <div class="col-xl-4">
            <div class="card" style="margin-bottom:16px;">
                <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
                    <h5 style="margin:0;"><i class="ti ti-clipboard-list"></i> Proyek Saya</h5>
                    <a href="{{ route('proyek.create') }}" class="btn btn-sm btn-primary">
                        <i class="ti ti-plus"></i>
                    </a>
                </div>
                <div class="card-body">
                    @forelse($projeks as $proyek)
                        <div style="margin-bottom:14px;">
                            <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                                <span
                                    style="font-size:13px;font-weight:500;">{{ Str::limit($proyek->nama_proyek, 30) }}</span>
                                <span style="font-size:12px;color:#8996a4;">{{ $proyek->progress_persen }}%</span>
                            </div>
                            <div style="background:#e7eaee;border-radius:4px;height:8px;">
                                <div
                                    style="width:{{ $proyek->progress_persen }}%;background:#4680ff;height:8px;border-radius:4px;">
                                </div>
                            </div>
                        </div>
                    @empty
                        <p style="color:#8996a4;text-align:center;font-size:13px;">
                            Belum ada proyek. <a href="{{ route('proyek.create') }}">Tambah sekarang</a>
                        </p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Logbook Terbaru --}}
        <div class="col-xl-4">
            <div class="card" style="margin-bottom:16px;">
                <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
                    <h5 style="margin:0;"><i class="ti ti-notebook"></i> Logbook Terbaru</h5>
                    <a href="{{ route('logbook.create') }}" class="btn btn-sm btn-success">
                        <i class="ti ti-plus"></i>
                    </a>
                </div>
                <div class="card-body" style="padding:0;">
                    @forelse($recentLogbooks as $lb)
                        <div style="padding:10px 16px;border-bottom:1px solid #e7eaee;font-size:13px;">
                            <div style="display:flex;justify-content:space-between;margin-bottom:2px;">
                                <span style="font-weight:500;">{{ $lb->tanggal->format('d M') }}</span>
                                @if ($lb->status === 'disetujui')
                                    <span class="badge bg-success" style="font-size:10px;">Disetujui</span>
                                @elseif($lb->status === 'revisi')
                                    <span class="badge bg-danger" style="font-size:10px;">Revisi</span>
                                @else
                                    <span class="badge bg-warning" style="font-size:10px;">Pending</span>
                                @endif
                            </div>
                            <span style="color:#8996a4;">{{ Str::limit($lb->deskripsi_kegiatan, 55) }}</span>
                        </div>
                    @empty
                        <div style="padding:16px;text-align:center;color:#8996a4;">
                            Belum ada logbook.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- Pengumuman --}}
    @if ($pengumuman->isNotEmpty())
        <div class="row">
            <div class="col-12">
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header">
                        <h5 style="margin:0;"><i class="ti ti-speakerphone"></i> Pengumuman</h5>
                    </div>
                    <div class="card-body">
                        @foreach ($pengumuman as $p)
                            <div class="alert {{ $p->is_pinned ? 'alert-primary' : 'alert-info' }}"
                                style="margin-bottom:8px;">
                                @if ($p->is_pinned)
                                    <i class="ti ti-pin"></i>
                                @endif
                                <strong>{{ $p->judul }}</strong>
                                <p style="margin:4px 0 0;font-size:13px;">{{ $p->isi }}</p>
                                <small style="color:#8996a4;">{{ $p->admin->nama_lengkap }} —
                                    {{ $p->created_at->diffForHumans() }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
    <script>
        // Ambil koordinat geolokasi sebelum submit presensi
        function getLocation(form, latField, lngField) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(pos) {
                    document.getElementById(latField).value = pos.coords.latitude;
                    document.getElementById(lngField).value = pos.coords.longitude;
                    form.submit();
                }, function() {
                    form.submit(); // submit tetap meski lokasi ditolak
                });
            } else {
                form.submit();
            }
        }

        const checkInForm = document.getElementById('formCheckIn');
        const checkOutForm = document.getElementById('formCheckOut');

        if (checkInForm) {
            checkInForm.addEventListener('submit', function(e) {
                e.preventDefault();
                getLocation(this, 'lat_in', 'lng_in');
            });
        }
        if (checkOutForm) {
            checkOutForm.addEventListener('submit', function(e) {
                e.preventDefault();
                getLocation(this, 'lat_out', 'lng_out');
            });
        }
    </script>
@endpush
