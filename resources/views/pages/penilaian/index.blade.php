@extends('layouts.app')
@section('title', 'Penilaian Akhir')

@section('content')

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-header-title"><h5>Penilaian Akhir Mahasiswa</h5></div>
                </div>
                <div class="col-auto">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Penilaian</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 style="margin:0;"><i class="ti ti-star"></i> E-Assessment — Daftar Mahasiswa</h5>
                </div>
                <div class="card-body" style="padding:0;">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Universitas</th>
                                    <th>Status Magang</th>
                                    <th>Nilai Akhir</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mahasiswas as $mhs)
                                    <tr>
                                        <td><strong>{{ $mhs->user->nama_lengkap }}</strong></td>
                                        <td>{{ $mhs->nim }}</td>
                                        <td>{{ $mhs->universitas }}</td>
                                        <td>
                                            @if($mhs->status === 'selesai')
                                                <span class="badge bg-success">Selesai</span>
                                            @elseif($mhs->status === 'aktif')
                                                <span class="badge bg-primary">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($mhs->penilaian)
                                                <strong style="color:#4680ff;">{{ $mhs->penilaian->nilai_akhir }}</strong>
                                                <small style="color:#8996a4;">/100 ({{ $mhs->penilaian->predikat }})</small>
                                            @else
                                                <span style="color:#8996a4;">Belum dinilai</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('penilaian.create', $mhs->id) }}" class="btn btn-sm btn-primary">
                                                <i class="ti ti-{{ $mhs->penilaian ? 'edit' : 'star' }}"></i>
                                                {{ $mhs->penilaian ? 'Edit Nilai' : 'Beri Nilai' }}
                                            </a>
                                            @if($mhs->penilaian)
                                                <a href="{{ route('penilaian.show', $mhs->penilaian->id) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align:center;padding:32px;color:#8996a4;">
                                            Belum ada mahasiswa.
                                        </td>
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
