@extends('layouts.app')
@section('title', 'Detail Mahasiswa')

@section('content')

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-header-title"><h5>Detail Mahasiswa</h5></div>
                </div>
                <div class="col-auto">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Mahasiswa</a></li>
                        <li class="breadcrumb-item" aria-current="page">Detail</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4">
            <div class="card" style="margin-bottom:16px;text-align:center;">
                <div class="card-body" style="padding:28px;">
                    <div style="width:80px;height:80px;border-radius:50%;background:#4680ff;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:28px;color:#fff;">
                        {{ strtoupper(substr($mahasiswa->user->nama_lengkap, 0, 1)) }}
                    </div>
                    <h5 style="margin:0 0 4px;">{{ $mahasiswa->user->nama_lengkap }}</h5>
                    <p style="color:#8996a4;margin:0 0 4px;">{{ $mahasiswa->nim }}</p>
                    <p style="color:#8996a4;font-size:13px;margin:0 0 12px;">{{ $mahasiswa->universitas }}</p>
                    @if($mahasiswa->status === 'aktif')
                        <span class="badge bg-success">Aktif</span>
                    @elseif($mahasiswa->status === 'selesai')
                        <span class="badge bg-primary">Selesai</span>
                    @else
                        <span class="badge bg-secondary">Nonaktif</span>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h6 style="margin:0;">Info Magang</h6></div>
                <div class="card-body" style="font-size:13px;">
                    <table style="width:100%;border-spacing:0 6px;border-collapse:separate;">
                        <tr><td style="color:#8996a4;">Jurusan</td><td>{{ $mahasiswa->jurusan ?? '—' }}</td></tr>
                        <tr><td style="color:#8996a4;">Divisi</td><td>{{ $mahasiswa->divisi ?? '—' }}</td></tr>
                        <tr><td style="color:#8996a4;">Mulai</td><td>{{ $mahasiswa->periode_mulai?->format('d M Y') ?? '—' }}</td></tr>
                        <tr><td style="color:#8996a4;">Selesai</td><td>{{ $mahasiswa->periode_selesai?->format('d M Y') ?? '—' }}</td></tr>
                        <tr><td style="color:#8996a4;">Target</td><td>{{ $mahasiswa->target_proyek ?? '—' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            {{-- Statistik --}}
            <div class="row">
                <div class="col-md-4">
                    <div class="card" style="margin-bottom:12px;text-align:center;">
                        <div class="card-body">
                            <div style="font-size:28px;font-weight:700;color:#28a745;">{{ $mahasiswa->presensis->count() }}</div>
                            <small style="color:#8996a4;">Total Presensi</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card" style="margin-bottom:12px;text-align:center;">
                        <div class="card-body">
                            <div style="font-size:28px;font-weight:700;color:#4680ff;">{{ $mahasiswa->logbooks->where('status','disetujui')->count() }}</div>
                            <small style="color:#8996a4;">Logbook Disetujui</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card" style="margin-bottom:12px;text-align:center;">
                        <div class="card-body">
                            <div style="font-size:28px;font-weight:700;color:#ffa500;">{{ $mahasiswa->progress }}%</div>
                            <small style="color:#8996a4;">Avg. Progress</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Proyek --}}
            <div class="card" style="margin-bottom:12px;">
                <div class="card-header"><h6 style="margin:0;">Proyek Magang</h6></div>
                <div class="card-body">
                    @forelse($mahasiswa->projeks as $p)
                        <div style="margin-bottom:10px;">
                            <div style="display:flex;justify-content:space-between;">
                                <span style="font-size:13px;font-weight:500;">{{ $p->nama_proyek }}</span>
                                <span style="font-size:12px;color:#8996a4;">{{ $p->progress_persen }}%</span>
                            </div>
                            <div style="background:#e7eaee;border-radius:4px;height:6px;margin-top:4px;">
                                <div style="width:{{ $p->progress_persen }}%;background:#4680ff;height:6px;border-radius:4px;"></div>
                            </div>
                        </div>
                    @empty
                        <p style="color:#8996a4;font-size:13px;">Belum ada proyek.</p>
                    @endforelse
                </div>
            </div>

            {{-- Penilaian --}}
            @if($mahasiswa->penilaian)
            <div class="card">
                <div class="card-header"><h6 style="margin:0;">Nilai Akhir</h6></div>
                <div class="card-body" style="text-align:center;">
                    <div style="font-size:42px;font-weight:700;color:#4680ff;">{{ $mahasiswa->penilaian->nilai_akhir }}</div>
                    <span class="badge bg-primary">{{ $mahasiswa->penilaian->predikat }}</span>
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-body" style="text-align:center;color:#8996a4;padding:24px;">
                    Belum ada penilaian akhir.
                    <br>
                    <a href="{{ route('penilaian.create', $mahasiswa->id) }}" class="btn btn-primary btn-sm" style="margin-top:8px;">
                        <i class="ti ti-star"></i> Beri Nilai
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div style="margin-top:8px;">
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('users.edit', $mahasiswa->id) }}" class="btn btn-warning" style="margin-left:8px;">
            <i class="ti ti-edit"></i> Edit Data
        </a>
    </div>

@endsection
