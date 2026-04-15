@extends('layouts.app')
@section('title', 'Manajemen Mahasiswa')

@section('content')

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-header-title"><h5>Data Mahasiswa</h5></div>
                </div>
                <div class="col-auto">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Data Mahasiswa</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
                    <h5 style="margin:0;"><i class="ti ti-users"></i> Daftar Mahasiswa Magang</h5>
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus"></i> Tambah Mahasiswa
                    </a>
                </div>
                <div class="card-body" style="padding:0;">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama / NIM</th>
                                    <th>Universitas</th>
                                    <th>Divisi</th>
                                    <th>Periode</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mahasiswas as $mhs)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $mhs->user->nama_lengkap }}</strong><br>
                                            <small style="color:#8996a4;">{{ $mhs->nim }}</small>
                                        </td>
                                        <td>
                                            {{ $mhs->universitas }}<br>
                                            <small style="color:#8996a4;">{{ $mhs->jurusan }}</small>
                                        </td>
                                        <td>{{ $mhs->divisi ?? '-' }}</td>
                                        <td style="font-size:12px;">
                                            {{ $mhs->periode_mulai?->format('d M Y') }}<br>
                                            s/d {{ $mhs->periode_selesai?->format('d M Y') }}
                                        </td>
                                        <td>
                                            @if($mhs->status === 'aktif')
                                                <span class="badge bg-success">Aktif</span>
                                            @elseif($mhs->status === 'selesai')
                                                <span class="badge bg-primary">Selesai</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('users.show', $mhs->id) }}" class="btn btn-sm btn-info" title="Detail">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="{{ route('users.edit', $mhs->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('users.destroy', $mhs->id) }}" style="display:inline;" onsubmit="return confirm('Nonaktifkan mahasiswa ini?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger" title="Nonaktifkan">
                                                    <i class="ti ti-user-off"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" style="text-align:center;padding:32px;color:#8996a4;">
                                            <i class="ti ti-users" style="font-size:2rem;"></i>
                                            <p style="margin:8px 0 0;">Belum ada mahasiswa terdaftar.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div style="padding:16px;">
                        {{ $mahasiswas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
