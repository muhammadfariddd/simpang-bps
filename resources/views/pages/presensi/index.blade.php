@extends('layouts.app')
@section('title', 'Presensi Digital')

@section('content')

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-header-title"><h5>Presensi Digital</h5></div>
                </div>
                <div class="col-auto">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Presensi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Check-in/out widget (hanya mahasiswa) --}}
    @if(Auth::user()->peran === 'mahasiswa')
    <div class="row">
        <div class="col-xl-4">
            <div class="card" style="margin-bottom:16px;text-align:center;">
                <div class="card-header">
                    <h5 style="margin:0;"><i class="ti ti-map-pin"></i> Presensi Hari Ini — {{ now()->format('d F Y') }}</h5>
                </div>
                <div class="card-body" style="padding:24px;">
                    @if(!$hariIni)
                        <i class="ti ti-clock" style="font-size:3rem;color:#8996a4;"></i>
                        <p style="color:#8996a4;margin:8px 0 16px;">Belum Check-In</p>
                        <form method="POST" action="{{ route('presensi.check-in') }}" id="formCheckIn">
                            @csrf
                            <input type="hidden" name="lat" id="lat_in">
                            <input type="hidden" name="lng" id="lng_in">
                            <button type="submit" class="btn btn-success btn-block" style="width:100%;padding:12px 0;">
                                <i class="ti ti-login" style="font-size:1.2rem;"></i> Check-In Sekarang
                            </button>
                        </form>
                    @elseif(!$hariIni->check_out)
                        <i class="ti ti-circle-check" style="font-size:3rem;color:#28a745;"></i>
                        <p style="color:#28a745;font-weight:700;font-size:18px;margin:8px 0 4px;">Check-In ✓</p>
                        <p style="color:#8996a4;margin:0 0 16px;">Pukul {{ $hariIni->check_in }}</p>
                        <form method="POST" action="{{ route('presensi.check-out') }}" id="formCheckOut">
                            @csrf
                            <input type="hidden" name="lat" id="lat_out">
                            <input type="hidden" name="lng" id="lng_out">
                            <button type="submit" class="btn btn-warning" style="width:100%;padding:12px 0;">
                                <i class="ti ti-logout" style="font-size:1.2rem;"></i> Check-Out Sekarang
                            </button>
                        </form>
                    @else
                        <i class="ti ti-check-all" style="font-size:3rem;color:#4680ff;"></i>
                        <p style="color:#4680ff;font-weight:700;font-size:16px;margin:8px 0 8px;">Presensi Hari Ini Lengkap ✓</p>
                        <table style="width:100%;font-size:13px;margin-top:8px;">
                            <tr>
                                <td style="color:#8996a4;padding:4px 0;">Check-In</td>
                                <td style="font-weight:600;">{{ $hariIni->check_in }}</td>
                            </tr>
                            <tr>
                                <td style="color:#8996a4;padding:4px 0;">Check-Out</td>
                                <td style="font-weight:600;">{{ $hariIni->check_out }}</td>
                            </tr>
                            <tr>
                                <td style="color:#8996a4;padding:4px 0;">Status</td>
                                <td><span class="badge bg-success">{{ ucfirst($hariIni->status) }}</span></td>
                            </tr>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Tabel Presensi --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="display:flex;flex-direction:column;gap:16px;">
                    <h5 style="margin:0;">Riwayat Presensi</h5>
                    @if(Auth::user()->peran === 'admin')
                    <div style="overflow-x:auto;">
                        <form method="GET" action="{{ route('presensi.index') }}"
                            style="display:flex;gap:16px;align-items:flex-end;flex-wrap:nowrap;min-width:max-content;padding-bottom:8px;">
                            <div>
                                <label class="form-label" style="font-size:12px;color:#8c98a4;font-weight:600;text-transform:uppercase;">Bulan</label>
                                <select name="bulan" class="form-control" style="min-width:120px;">
                                    @foreach (range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->locale('id')->isoFormat('MMMM') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label" style="font-size:12px;color:#8c98a4;font-weight:600;text-transform:uppercase;">Tahun</label>
                                <select name="tahun" class="form-control" style="min-width:100px;">
                                    @foreach (range(now()->year - 2, now()->year + 1) as $y)
                                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Filter</button>
                            </div>
                            <div style="margin-left:auto;">
                                <a href="{{ route('laporan.export.kehadiran', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                                    class="btn btn-success">
                                    <i class="ti ti-download"></i> Export CSV
                                </a>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
                <div class="card-body" style="padding:0;">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    @if(Auth::user()->peran === 'admin')
                                        <th>Mahasiswa</th>
                                    @endif
                                    <th>Check-In</th>
                                    <th>Check-Out</th>
                                    <th>Koordinat</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($presensis as $p)
                                    <tr>
                                        <td style="white-space:nowrap;">{{ $p->tanggal->format('d M Y') }}</td>
                                        @if(Auth::user()->peran === 'admin')
                                            <td>{{ $p->user->nama_lengkap }}</td>
                                        @endif
                                        <td>{{ $p->check_in ?? '—' }}</td>
                                        <td>{{ $p->check_out ?? '—' }}</td>
                                        <td style="font-size:11px;color:#8996a4;">
                                            @if($p->lat_in)
                                                <span title="Lokasi Check-In">{{ number_format($p->lat_in, 5) }}, {{ number_format($p->lng_in, 5) }}</span>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            @if($p->status === 'hadir')
                                                <span class="badge bg-success">Hadir Pagi</span>
                                            @elseif($p->status === 'telat')
                                                <span class="badge bg-secondary">Telat</span>
                                            @elseif($p->status === 'izin')
                                                <span class="badge bg-warning">Izin</span>
                                            @else
                                                <span class="badge bg-danger">Alpha</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align:center;padding:32px;color:#8996a4;">
                                            Belum ada data presensi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div style="padding:16px;">{{ $presensis->links() }}</div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
function getLocation(form, latField, lngField) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (pos) {
            document.getElementById(latField).value = pos.coords.latitude;
            document.getElementById(lngField).value = pos.coords.longitude;
            form.submit();
        }, function () { form.submit(); });
    } else { form.submit(); }
}

const ci = document.getElementById('formCheckIn');
const co = document.getElementById('formCheckOut');
if (ci) ci.addEventListener('submit', function(e){ e.preventDefault(); getLocation(this,'lat_in','lng_in'); });
if (co) co.addEventListener('submit', function(e){ e.preventDefault(); getLocation(this,'lat_out','lng_out'); });
</script>
@endpush
