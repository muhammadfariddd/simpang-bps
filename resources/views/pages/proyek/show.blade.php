@extends('layouts.app')
@section('title', 'Detail Proyek')

@section('content')

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-header-title">
                        <h5>Detail Proyek</h5>
                    </div>
                </div>
                <div class="col-auto">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('proyek.index') }}">Proyek</a></li>
                        <li class="breadcrumb-item" aria-current="page">Detail</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-7">
            <div class="card" style="margin-bottom:16px;">
                <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
                    <h5 style="margin:0;">{{ $proyek->nama_proyek }}</h5>
                    <span class="badge {{ $proyek->status === 'selesai' ? 'bg-success' : 'bg-primary' }}"
                        style="font-size:12px;">
                        {{ ucfirst($proyek->status) }}
                    </span>
                </div>
                <div class="card-body">
                    @if ($proyek->deskripsi)
                        <p style="color:#4d5763;line-height:1.6;">{{ $proyek->deskripsi }}</p>
                    @endif
                    <div style="margin:16px 0;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                            <span style="font-weight:600;">Progress Keseluruhan</span>
                            <span
                                style="font-weight:700;color:#4680ff;font-size:18px;">{{ $proyek->progress_persen }}%</span>
                        </div>
                        <div style="background:#e7eaee;border-radius:8px;height:14px;">
                            <div
                                style="width:{{ $proyek->progress_persen }}%;background:#4680ff;height:14px;border-radius:8px;transition:width 0.5s;">
                            </div>
                        </div>
                    </div>
                    @if ($proyek->file_laporan)
                        <a href="{{ asset('storage/' . $proyek->file_laporan) }}" target="_blank"
                            class="btn btn-outline-success btn-sm">
                            <i class="ti ti-file-text"></i> Unduh Laporan Akhir
                        </a>
                    @endif
                </div>
            </div>

            {{-- Milestones --}}
            <div class="card">
                <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
                    <h5 style="margin:0;"><i class="ti ti-flag"></i> Milestone</h5>
                </div>
                <div class="card-body">
                    @forelse($proyek->milestones as $ms)
                        <div style="border:1px solid #e7eaee;border-radius:8px;padding:12px;margin-bottom:10px;">
                            <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                                <strong style="font-size:14px;">{{ $ms->nama_milestone }}</strong>
                                <span
                                    class="badge {{ $ms->status === 'selesai' ? 'bg-success' : ($ms->status === 'proses' ? 'bg-warning' : 'bg-secondary') }}">
                                    {{ ucfirst($ms->status) }}
                                </span>
                            </div>
                            @if ($ms->deskripsi)
                                <p style="color:#8996a4;font-size:13px;margin:0 0 8px;">{{ $ms->deskripsi }}</p>
                            @endif
                            <div style="background:#e7eaee;border-radius:4px;height:6px;margin-bottom:4px;">
                                <div
                                    style="width:{{ $ms->progress_persen }}%;background:#4680ff;height:6px;border-radius:4px;">
                                </div>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-size:11px;color:#8996a4;">
                                <span>{{ $ms->progress_persen }}%</span>
                                @if ($ms->target_selesai)
                                    <span>Target: {{ $ms->target_selesai->format('d M Y') }}</span>
                                @endif
                            </div>
                            @if (Auth::user()->peran === 'mahasiswa')
                                <form method="POST" action="{{ route('milestone.update', $ms->id) }}"
                                    style="margin-top:8px;display:flex;gap:6px;align-items:center;">
                                    @csrf @method('PATCH')
                                    <select name="status" class="form-control" style="width:auto;font-size:12px;">
                                        <option value="belum" {{ $ms->status === 'belum' ? 'selected' : '' }}>Belum
                                        </option>
                                        <option value="proses" {{ $ms->status === 'proses' ? 'selected' : '' }}>Proses
                                        </option>
                                        <option value="selesai"{{ $ms->status === 'selesai' ? 'selected' : '' }}>Selesai
                                        </option>
                                    </select>
                                    <input type="number" name="progress_persen" value="{{ $ms->progress_persen }}"
                                        class="form-control" style="width:80px;font-size:12px;" min="0"
                                        max="100">
                                    <button class="btn btn-sm btn-primary">Update</button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p style="color:#8996a4;text-align:center;">Belum ada milestone.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Tambah Milestone (mahasiswa) --}}
        @if (Auth::user()->peran === 'mahasiswa')
            <div class="col-xl-5">
                <div class="card">
                    <div class="card-header">
                        <h5 style="margin:0;"><i class="ti ti-flag-plus"></i> Tambah Milestone</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('proyek.milestone.store', $proyek->id) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nama Milestone</label>
                                <input type="text" name="nama_milestone" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label class="form-label">Progress (%)</label>
                                    <input type="number" name="progress_persen" class="form-control" value="0"
                                        min="0" max="100">
                                </div>
                                <div class="col mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="belum">Belum</option>
                                        <option value="proses">Proses</option>
                                        <option value="selesai">Selesai</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Target Selesai</label>
                                <input type="date" name="target_selesai" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary" style="width:100%;">
                                <i class="ti ti-plus"></i> Tambah Milestone
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div style="margin-top:8px;">
        <a href="{{ route('proyek.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
        @if (Auth::user()->peran === 'mahasiswa')
            <a href="{{ route('proyek.edit', $proyek->id) }}" class="btn btn-warning" style="margin-left:8px;">
                <i class="ti ti-edit"></i> Update Progress
            </a>
        @endif
    </div>

@endsection
