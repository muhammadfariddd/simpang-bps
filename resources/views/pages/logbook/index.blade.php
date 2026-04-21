@extends('layouts.app')
@section('title', 'Logbook Harian')

@section('content')

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-header-title"><h5>Logbook Harian</h5></div>
                </div>
                <div class="col-auto">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Logbook</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="display:flex;flex-direction:column;gap:16px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                        <h5 style="margin:0;"><i class="ti ti-notebook"></i> Daftar Logbook</h5>
                        <div style="display:flex;gap:8px;align-items:center;">
                            @if(Auth::user()->peran === 'mahasiswa')
                                <a href="{{ route('logbook.create') }}" class="btn btn-primary">
                                    <i class="ti ti-plus"></i> Isi Logbook
                                </a>
                            @endif
                        </div>
                    </div>
                    @if(Auth::user()->peran === 'admin')
                    <div style="overflow-x:auto;">
                        <form method="GET" action="{{ route('logbook.index') }}"
                            style="display:flex;gap:16px;align-items:flex-end;flex-wrap:nowrap;min-width:max-content;padding-bottom:8px;">
                            <div>
                                <label class="form-label" style="font-size:12px;color:#8c98a4;font-weight:600;text-transform:uppercase;">Status</label>
                                <select name="status" class="form-control" style="min-width:140px;">
                                    <option value="">Semua Status</option>
                                    <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
                                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="revisi"    {{ request('status') === 'revisi'    ? 'selected' : '' }}>Revisi</option>
                                </select>
                            </div>
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
                                <a href="{{ route('laporan.export.logbook', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
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
                                    <th>Kategori</th>
                                    <th>Kegiatan</th>
                                    <th>Bukti</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logbooks as $lb)
                                    <tr>
                                        <td style="white-space:nowrap;">{{ $lb->tanggal->format('d M Y') }}</td>
                                        @if(Auth::user()->peran === 'admin')
                                            <td><strong>{{ $lb->user->nama_lengkap }}</strong></td>
                                        @endif
                                        <td><span class="badge bg-info">{{ $lb->kategori }}</span></td>
                                        <td>{{ Str::limit($lb->deskripsi_kegiatan, 80) }}</td>
                                        <td>
                                            @if($lb->file_bukti)
                                                <a href="{{ asset('storage/' . $lb->file_bukti) }}" target="_blank" class="btn btn-xs btn-outline-primary" style="font-size:11px;padding:2px 8px;">
                                                    <i class="ti ti-paperclip"></i> File
                                                </a>
                                            @endif
                                            @if($lb->link_bukti)
                                                <a href="{{ $lb->link_bukti }}" target="_blank" class="btn btn-xs btn-outline-secondary" style="font-size:11px;padding:2px 8px;">
                                                    <i class="ti ti-link"></i> Link
                                                </a>
                                            @endif
                                            @if(!$lb->file_bukti && !$lb->link_bukti)
                                                <span style="color:#8996a4;font-size:12px;">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($lb->status === 'disetujui')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($lb->status === 'revisi')
                                                <span class="badge bg-danger">Revisi</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('logbook.show', $lb->id) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            @if(Auth::user()->peran === 'mahasiswa' && $lb->status !== 'disetujui')
                                                <a href="{{ route('logbook.edit', $lb->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" style="text-align:center;padding:32px;color:#8996a4;">
                                            <i class="ti ti-notebook" style="font-size:2rem;"></i>
                                            <p style="margin:8px 0 0;">Belum ada logbook.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div style="padding:16px;">{{ $logbooks->links() }}</div>
                </div>
            </div>
        </div>
    </div>

@endsection
