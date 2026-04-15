@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')

    {{-- [ breadcrumb ] --}}
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-header-title">
                        <h5>Dashboard Admin</h5>
                    </div>
                </div>
                <div class="col-auto">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Pages</a></li>
                        <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- [ Stat Cards ] --}}
    <div class="row">

        {{-- Total Mahasiswa Aktif --}}
        <div class="col-xl-3 col-md-6">
            <div class="card dashnum-card bg-primary-dark text-white overflow-hidden" style="margin-bottom:16px;">
                <span class="round small"></span>
                <span class="round big"></span>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="avtar avtar-lg">
                                <i class="text-white ti ti-users"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('users.index') }}" class="avtar avtar-s text-white" style="background:rgba(0,0,0,0.2); text-decoration:none;">
                                <i class="ti ti-dots"></i>
                            </a>
                        </div>
                    </div>
                    <span class="text-white d-block f-34 f-w-500 my-2">{{ $totalMahasiswa }}</span>
                    <p class="mb-0 opacity-50">Mahasiswa Aktif</p>
                </div>
            </div>
        </div>

        {{-- Logbook Pending Validasi --}}
        <div class="col-xl-3 col-md-6">
            <div class="card dashnum-card bg-warning text-white overflow-hidden" style="margin-bottom:16px;">
                <span class="round small"></span>
                <span class="round big"></span>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="avtar avtar-lg">
                                <i class="text-white ti ti-notebook"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('logbook.index') }}?status=pending" class="avtar avtar-s text-white" style="background:rgba(0,0,0,0.2); text-decoration:none;">
                                <i class="ti ti-dots"></i>
                            </a>
                        </div>
                    </div>
                    <span class="text-white d-block f-34 f-w-500 my-2">{{ $totalLogbookBaru }}</span>
                    <p class="mb-0 opacity-50">Logbook Perlu Validasi</p>
                </div>
            </div>
        </div>

        {{-- Total Logbook --}}
        <div class="col-xl-3 col-md-6">
            <div class="card dashnum-card bg-success text-white overflow-hidden" style="margin-bottom:16px;">
                <span class="round small"></span>
                <span class="round big"></span>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="avtar avtar-lg">
                                <i class="text-white ti ti-file-check"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('logbook.index') }}" class="avtar avtar-s text-white" style="background:rgba(0,0,0,0.2); text-decoration:none;">
                                <i class="ti ti-dots"></i>
                            </a>
                        </div>
                    </div>
                    <span class="text-white d-block f-34 f-w-500 my-2">{{ $totalLogbook }}</span>
                    <p class="mb-0 opacity-50">Total Logbook</p>
                </div>
            </div>
        </div>

        {{-- Mahasiswa Selesai --}}
        <div class="col-xl-3 col-md-6">
            <div class="card dashnum-card bg-secondary-dark text-white overflow-hidden" style="margin-bottom:16px;">
                <span class="round small"></span>
                <span class="round big"></span>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="avtar avtar-lg">
                                <i class="text-white ti ti-award"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('users.index') }}" class="avtar avtar-s text-white" style="background:rgba(0,0,0,0.2); text-decoration:none;">
                                <i class="ti ti-dots"></i>
                            </a>
                        </div>
                    </div>
                    <span class="text-white d-block f-34 f-w-500 my-2">{{ $mahasiswaSelesai }}</span>
                    <p class="mb-0 opacity-50">Mahasiswa Selesai Magang</p>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        {{-- Logbook Pending --}}
        <div class="col-xl-7">
            <div class="card" style="margin-bottom:16px;">
                <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
                    <h5 style="margin:0;">Logbook Perlu Validasi</h5>
                    <a href="{{ route('logbook.index') }}?status=pending" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body" style="padding:0;">
                    @if($logbookPending->isEmpty())
                        <div style="padding:24px; text-align:center; color:#8996a4;">
                            <i class="ti ti-circle-check" style="font-size:2rem;"></i>
                            <p style="margin:8px 0 0;">Semua logbook sudah tervalidasi!</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover" style="margin:0;">
                                <thead>
                                    <tr>
                                        <th>Mahasiswa</th>
                                        <th>Tanggal</th>
                                        <th>Kategori</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logbookPending as $lb)
                                        <tr>
                                            <td>
                                                <strong>{{ $lb->user->nama_lengkap }}</strong>
                                            </td>
                                            <td>{{ $lb->tanggal->format('d M Y') }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $lb->kategori }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('logbook.show', $lb->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="ti ti-eye"></i> Review
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Progress Mahasiswa --}}
        <div class="col-xl-5">
            <div class="card" style="margin-bottom:16px;">
                <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
                    <h5 style="margin:0;">Progress Magang</h5>
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">Kelola</a>
                </div>
                <div class="card-body">
                    @forelse($mahasiswaAktif as $mhs)
                        @php $progress = $mhs->progress; @endphp
                        <div style="margin-bottom:16px;">
                            <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                                <span style="font-size:13px;font-weight:500;">{{ $mhs->user->nama_lengkap }}</span>
                                <span style="font-size:12px;color:#8996a4;">{{ $progress }}%</span>
                            </div>
                            <div style="background:#e7eaee;border-radius:4px;height:8px;">
                                <div style="width:{{ $progress }}%;background:{{ $progress >= 100 ? '#28a745' : ($progress >= 50 ? '#4680ff' : '#ffa500') }};height:8px;border-radius:4px;transition:width 0.4s;"></div>
                            </div>
                            <small style="color:#8996a4;">{{ $mhs->universitas }}</small>
                        </div>
                    @empty
                        <p style="color:#8996a4;text-align:center;">Belum ada mahasiswa aktif.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- Pengumuman --}}
    @if($pengumuman->isNotEmpty())
    <div class="row">
        <div class="col-12">
            <div class="card" style="margin-bottom:16px;">
                <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
                    <h5 style="margin:0;"><i class="ti ti-speakerphone"></i> Pengumuman Tersematkan</h5>
                    <a href="{{ route('pengumuman.create') }}" class="btn btn-sm btn-primary">
                        <i class="ti ti-plus"></i> Buat Pengumuman
                    </a>
                </div>
                <div class="card-body">
                    @foreach($pengumuman as $p)
                        <div class="alert alert-info" style="margin-bottom:8px;">
                            <strong>{{ $p->judul }}</strong>
                            <p style="margin:4px 0 0;font-size:13px;">{{ Str::limit($p->isi, 120) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

@endsection
