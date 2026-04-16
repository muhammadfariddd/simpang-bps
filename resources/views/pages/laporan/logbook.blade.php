@extends('layouts.app')
@section('title', 'Rekap Logbook')

@section('content')

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-header-title">
                        <h5>Rekap Logbook</h5>
                    </div>
                </div>
                <div class="col-auto">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Rekap Logbook</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card" style="margin-bottom:16px;">
        <div class="card-body">
            <div style="overflow-x:auto;">
                <form method="GET" style="display:flex;gap:16px;align-items:flex-end;flex-wrap:nowrap;min-width:max-content;padding-bottom:8px;">
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
                            <a href="{{ route('laporan.export.logbook', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                                class="btn btn-success">
                                <i class="ti ti-download"></i> Export CSV
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="padding:0;">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            @if (Auth::user()->peran === 'admin')
                                <th>Mahasiswa</th>
                            @endif
                            <th>Kategori</th>
                            <th>Kegiatan</th>
                            <th>Bukti</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logbooks as $lb)
                            <tr>
                                <td>{{ $lb->tanggal->format('d M Y') }}</td>
                                @if (Auth::user()->peran === 'admin')
                                    <td>{{ $lb->user->nama_lengkap }}</td>
                                @endif
                                <td><span class="badge bg-info">{{ $lb->kategori }}</span></td>
                                <td>{{ Str::limit($lb->deskripsi_kegiatan, 100) }}</td>
                                <td>
                                    @if ($lb->file_bukti)
                                        <a href="{{ asset('storage/' . $lb->file_bukti) }}" target="_blank"
                                            style="font-size:12px;"><i class="ti ti-paperclip"></i></a>
                                    @elseif($lb->link_bukti)
                                        <a href="{{ $lb->link_bukti }}" target="_blank" style="font-size:12px;"><i
                                                class="ti ti-link"></i></a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @if ($lb->status === 'disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($lb->status === 'revisi')
                                        <span class="badge bg-danger">Revisi</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center;color:#8996a4;padding:24px;">
                                    Tidak ada logbook pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
