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
                <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                    <h5 style="margin:0;"><i class="ti ti-notebook"></i> Daftar Logbook</h5>
                    <div style="display:flex;gap:8px;align-items:center;">
                        {{-- Filter Status (Admin) --}}
                        @if(Auth::user()->peran === 'admin')
                            <form method="GET" style="display:flex;gap:6px;">
                                <select name="status" class="form-control" style="width:auto;" onchange="this.form.submit()">
                                    <option value="">Semua Status</option>
                                    <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
                                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="revisi"    {{ request('status') === 'revisi'    ? 'selected' : '' }}>Revisi</option>
                                </select>
                            </form>
                        @endif
                        @if(Auth::user()->peran === 'mahasiswa')
                            <a href="{{ route('logbook.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus"></i> Isi Logbook
                            </a>
                        @endif
                    </div>
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
                                        <td style="max-width:260px;">{{ Str::limit($lb->deskripsi_kegiatan, 80) }}</td>
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
