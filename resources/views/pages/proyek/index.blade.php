@extends('layouts.app')
@section('title', 'Progress Proyek')

@section('content')

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-header-title"><h5>Progress Proyek</h5></div>
                </div>
                <div class="col-auto">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Proyek</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
                    <h5 style="margin:0;"><i class="ti ti-clipboard-list"></i> Daftar Proyek Magang</h5>
                    @if(Auth::user()->peran === 'mahasiswa')
                        <a href="{{ route('proyek.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus"></i> Tambah Proyek
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @forelse($proyeks as $proyek)
                        <div class="card" style="border:1px solid #e7eaee;margin-bottom:16px;">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 style="margin:0 0 4px;">{{ $proyek->nama_proyek }}</h6>
                                        @if(Auth::user()->peran === 'admin')
                                            <small style="color:#8996a4;"><i class="ti ti-user"></i> {{ $proyek->user->nama_lengkap }}</small>
                                        @endif
                                        @if($proyek->deskripsi)
                                            <p style="color:#8996a4;font-size:13px;margin:4px 0 0;">{{ $proyek->deskripsi }}</p>
                                        @endif
                                    </div>
                                    <div class="col-auto" style="text-align:right;">
                                        @if($proyek->status === 'selesai')
                                            <span class="badge bg-success">Selesai</span>
                                        @else
                                            <span class="badge bg-primary">Berjalan</span>
                                        @endif
                                        <div style="font-size:22px;font-weight:700;color:#4680ff;margin-top:4px;">
                                            {{ $proyek->progress_persen }}%
                                        </div>
                                    </div>
                                </div>

                                {{-- Progress Bar --}}
                                <div style="background:#e7eaee;border-radius:6px;height:10px;margin:12px 0 8px;">
                                    <div style="width:{{ $proyek->progress_persen }}%;background:{{ $proyek->progress_persen >= 100 ? '#28a745' : '#4680ff' }};height:10px;border-radius:6px;transition:width 0.5s;"></div>
                                </div>

                                {{-- Milestones --}}
                                @if($proyek->milestones->count())
                                    <div style="margin-top:12px;">
                                        <small style="color:#8996a4;font-weight:600;text-transform:uppercase;font-size:11px;">Milestones</small>
                                        <div style="margin-top:6px;display:flex;flex-wrap:wrap;gap:6px;">
                                            @foreach($proyek->milestones as $ms)
                                                <span class="badge {{ $ms->status === 'selesai' ? 'bg-success' : ($ms->status === 'proses' ? 'bg-warning' : 'bg-secondary') }}"
                                                    style="font-size:11px;font-weight:400;">
                                                    {{ $ms->nama_milestone }} ({{ $ms->progress_persen }}%)
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Actions --}}
                                @if(Auth::user()->peran === 'mahasiswa')
                                    <div style="margin-top:12px;display:flex;gap:6px;">
                                        <a href="{{ route('proyek.show', $proyek->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="ti ti-eye"></i> Detail & Milestone
                                        </a>
                                        <a href="{{ route('proyek.edit', $proyek->id) }}" class="btn btn-sm btn-warning">
                                            <i class="ti ti-edit"></i> Update Progress
                                        </a>
                                        <form method="POST" action="{{ route('proyek.destroy', $proyek->id) }}" onsubmit="return confirm('Hapus proyek ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button>
                                        </form>
                                    </div>
                                @else
                                    <div style="margin-top:12px;">
                                        <a href="{{ route('proyek.show', $proyek->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="ti ti-eye"></i> Lihat Detail
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center;padding:32px;color:#8996a4;">
                            <i class="ti ti-clipboard-list" style="font-size:2.5rem;"></i>
                            <p style="margin:8px 0 0;">Belum ada proyek.</p>
                            @if(Auth::user()->peran === 'mahasiswa')
                                <a href="{{ route('proyek.create') }}" class="btn btn-primary" style="margin-top:12px;">
                                    <i class="ti ti-plus"></i> Buat Proyek Pertama
                                </a>
                            @endif
                        </div>
                    @endforelse
                    {{ $proyeks->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection
