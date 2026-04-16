@extends('layouts.app')
@section('title', 'Rekap Kehadiran')

@section('content')

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-header-title">
                        <h5>Rekap Kehadiran</h5>
                    </div>
                </div>
                <div class="col-auto">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Rekap Kehadiran</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card" style="margin-bottom:16px;">
        <div class="card-body">
            <div style="overflow-x:auto;">
                <form method="GET"
                    style="display:flex;gap:16px;align-items:flex-end;flex-wrap:nowrap;min-width:max-content;padding-bottom:8px;">
                    <div>
                        <label class="form-label"
                            style="font-size:12px;color:#8c98a4;font-weight:600;text-transform:uppercase;">Bulan</label>
                        <select name="bulan" class="form-control" style="min-width:120px;">
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->locale('id')->isoFormat('MMMM') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label"
                            style="font-size:12px;color:#8c98a4;font-weight:600;text-transform:uppercase;">Tahun</label>
                        <select name="tahun" class="form-control" style="min-width:100px;">
                            @foreach (range(now()->year - 1, now()->year + 1) as $y)
                                <option value="{{ $y }}" {{ ($tahun ?? now()->year) == $y ? 'selected' : '' }}>
                                    {{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Filter</button>
                    </div>
                    @if (Auth::user()->peran === 'admin')
                        <div style="margin-left:auto;">
                            <a href="{{ route('laporan.export.kehadiran', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                                class="btn btn-success">
                                <i class="ti ti-download"></i> Export CSV
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body" style="padding:0;">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    @if (Auth::user()->peran === 'admin')
                                        <th>Mahasiswa</th>
                                        <th>NIM</th>
                                    @endif
                                    <th>Check-In</th>
                                    <th>Check-Out</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($presensis as $p)
                                    <tr>
                                        <td>{{ $p->tanggal->format('d M Y') }}</td>
                                        @if (Auth::user()->peran === 'admin')
                                            <td>{{ $p->user->nama_lengkap }}</td>
                                            <td>{{ $p->user->mahasiswa?->nim ?? '—' }}</td>
                                        @endif
                                        <td>{{ $p->check_in ?? '—' }}</td>
                                        <td>{{ $p->check_out ?? '—' }}</td>
                                        <td>
                                            @if ($p->status === 'hadir')
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
                                        <td colspan="6" style="text-align:center;color:#8996a4;padding:24px;">Tidak ada
                                            data kehadiran.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
