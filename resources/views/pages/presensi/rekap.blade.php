@extends('layouts.app')
@section('title', 'Rekap Presensi Admin')

@section('content')

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-header-title"><h5>Rekap Presensi Mahasiswa</h5></div>
                </div>
                <div class="col-auto">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('presensi.index') }}">Presensi</a></li>
                        <li class="breadcrumb-item" aria-current="page">Rekap</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card" style="margin-bottom:16px;">
        <div class="card-body">
            <form method="GET" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
                <div>
                    <label class="form-label">Bulan</label>
                    <select name="bulan" class="form-control">
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" {{ (request('bulan', now()->month) == $m) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->locale('id')->isoFormat('MMMM') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Tahun</label>
                    <select name="tahun" class="form-control">
                        @foreach(range(now()->year - 1, now()->year + 1) as $y)
                            <option value="{{ $y }}" {{ (request('tahun', now()->year) == $y) ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Filter</button>
                </div>
                <div style="margin-left:auto;">
                    <a href="{{ route('laporan.export.kehadiran', ['bulan' => request('bulan', now()->month), 'tahun' => request('tahun', now()->year)]) }}"
                        class="btn btn-success">
                        <i class="ti ti-download"></i> Export CSV
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row" style="margin-bottom:8px;">
        @php
            $totalHadir = $presensis->where('status','hadir')->count();
            $totalIzin  = $presensis->where('status','izin')->count();
            $totalAlpha = $presensis->where('status','alpha')->count();
        @endphp
        <div class="col-md-4">
            <div class="card" style="margin-bottom:12px;border-left:4px solid #2ca87f;">
                <div class="card-body" style="display:flex;align-items:center;gap:16px;padding:16px 20px;">
                    <div style="font-size:28px;font-weight:700;color:#2ca87f;">{{ $totalHadir }}</div>
                    <div style="font-size:13px;color:#5b6b79;">Total Hadir</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style="margin-bottom:12px;border-left:4px solid #e58a00;">
                <div class="card-body" style="display:flex;align-items:center;gap:16px;padding:16px 20px;">
                    <div style="font-size:28px;font-weight:700;color:#e58a00;">{{ $totalIzin }}</div>
                    <div style="font-size:13px;color:#5b6b79;">Izin</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style="margin-bottom:12px;border-left:4px solid #dc2626;">
                <div class="card-body" style="display:flex;align-items:center;gap:16px;padding:16px 20px;">
                    <div style="font-size:28px;font-weight:700;color:#dc2626;">{{ $totalAlpha }}</div>
                    <div style="font-size:13px;color:#5b6b79;">Alpha</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Table --}}
    <div class="card">
        <div class="card-header">
            <h5 style="margin:0;">Detail Presensi</h5>
        </div>
        <div class="card-body" style="padding:0;">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Mahasiswa</th>
                            <th>NIM</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($presensis as $p)
                            <tr>
                                <td style="white-space:nowrap;">{{ $p->tanggal->format('d M Y') }}</td>
                                <td><strong>{{ $p->user->nama_lengkap }}</strong></td>
                                <td style="color:#8996a4;font-size:12px;">{{ $p->user->mahasiswa?->nim ?? '—' }}</td>
                                <td>{{ $p->check_in ?? '—' }}</td>
                                <td>{{ $p->check_out ?? '—' }}</td>
                                <td style="font-size:11px;color:#8996a4;">
                                    @if($p->lat_in)
                                        <span title="{{ $p->lat_in }}, {{ $p->lng_in }}">
                                            <i class="ti ti-map-pin" style="color:#4680ff;"></i>
                                            {{ number_format($p->lat_in, 4) }},{{ number_format($p->lng_in, 4) }}
                                        </span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @if($p->status === 'hadir')
                                        <span class="badge bg-success">Hadir</span>
                                    @elseif($p->status === 'izin')
                                        <span class="badge bg-warning">Izin</span>
                                    @else
                                        <span class="badge bg-danger">Alpha</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center;padding:32px;color:#8996a4;">
                                    <i class="ti ti-calendar-off" style="font-size:2rem;"></i>
                                    <p style="margin:8px 0 0;">Tidak ada data presensi untuk periode ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
