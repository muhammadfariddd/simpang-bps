@extends('layouts.app')
@section('title', 'Detail Logbook')

@section('content')

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-header-title">
                        <h5>Detail Logbook</h5>
                    </div>
                </div>
                <div class="col-auto">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('logbook.index') }}">Logbook</a></li>
                        <li class="breadcrumb-item" aria-current="page">Detail</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
                    <h5 style="margin:0;">
                        <i class="ti ti-notebook"></i> Logbook — {{ $logbook->tanggal->format('d F Y') }}
                    </h5>
                    @if ($logbook->status === 'disetujui')
                        <span class="badge bg-success" style="font-size:13px;">Disetujui</span>
                    @elseif($logbook->status === 'revisi')
                        <span class="badge bg-danger" style="font-size:13px;">Perlu Revisi</span>
                    @else
                        <span class="badge bg-warning" style="font-size:13px;">Menunggu Validasi</span>
                    @endif
                </div>
                <div class="card-body">
                    <table style="width:100%;font-size:14px;border-collapse:separate;border-spacing:0 6px;">
                        <tr>
                            <td style="color:#8996a4;width:160px;">Mahasiswa</td>
                            <td><strong>{{ $logbook->user->nama_lengkap }}</strong></td>
                        </tr>
                        <tr>
                            <td style="color:#8996a4;">Tanggal</td>
                            <td>{{ $logbook->tanggal->format('l, d F Y') }}</td>
                        </tr>
                        <tr>
                            <td style="color:#8996a4;">Kategori</td>
                            <td><span class="badge bg-info">{{ $logbook->kategori }}</span></td>
                        </tr>
                        <tr>
                            <td style="color:#8996a4;vertical-align:top;">Kegiatan</td>
                            <td>
                                <div style="background:#f8f9fa;border-radius:8px;line-height:1.6;">
                                    {{ $logbook->deskripsi_kegiatan }}
                                </div>
                            </td>
                        </tr>
                        @if ($logbook->file_bukti)
                            <tr>
                                <td style="color:#8996a4;">File Bukti</td>
                                <td>
                                    <a href="{{ asset('storage/' . $logbook->file_bukti) }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-paperclip"></i> Lihat File
                                    </a>
                                </td>
                            </tr>
                        @endif
                        @if ($logbook->link_bukti)
                            <tr>
                                <td style="color:#8996a4;">Link Bukti</td>
                                <td><a href="{{ $logbook->link_bukti }}" target="_blank">{{ $logbook->link_bukti }}</a></td>
                            </tr>
                        @endif
                        @if ($logbook->komentar_admin)
                            <tr>
                                <td style="color:#8996a4;vertical-align:top;">Komentar Admin</td>
                                <td>
                                    <div
                                        style="background:{{ $logbook->status === 'revisi' ? '#fff3cd' : '#d1e7dd' }};padding:10px;border-radius:8px;">
                                        <i class="ti ti-message"></i> {{ $logbook->komentar_admin }}
                                        <br><small style="color:#8996a4;">— {{ $logbook->validator?->nama_lengkap }} pada
                                            {{ $logbook->divalidasi_pada?->format('d M Y H:i') }}</small>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        {{-- Panel Validasi (Admin) --}}
        @if (Auth::user()->peran === 'admin' && $logbook->status === 'pending')
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 style="margin:0;"><i class="ti ti-check"></i> Validasi Logbook</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('logbook.validasi', $logbook->id) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Komentar / Feedback (Opsional)</label>
                                <textarea name="komentar_admin" class="form-control" rows="4"
                                    placeholder="Berikan feedback atau catatan untuk mahasiswa..."></textarea>
                            </div>
                            <div style="display:flex;gap:8px;">
                                <button type="submit" name="aksi" value="disetujui" class="btn btn-success"
                                    style="flex:1;">
                                    <i class="ti ti-check"></i> Setujui
                                </button>
                                <button type="submit" name="aksi" value="revisi" class="btn btn-warning"
                                    style="flex:1;">
                                    <i class="ti ti-refresh"></i> Revisi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

    </div>

    <div style="margin-top:8px;">
        <a href="{{ route('logbook.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </div>

@endsection
