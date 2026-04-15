@extends('layouts.app')
@section('title', 'Edit Mahasiswa')

@section('content')

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-header-title"><h5>Edit Mahasiswa</h5></div>
                </div>
                <div class="col-auto">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Data Mahasiswa</a></li>
                        <li class="breadcrumb-item" aria-current="page">Edit</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 style="margin:0;"><i class="ti ti-edit"></i> Form Edit Mahasiswa Magang</h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul style="margin:0;padding-left:20px;">
                                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('users.update', $mahasiswa->id) }}">
                        @csrf @method('PUT')

                        <h6 style="border-bottom:1px solid #e7eaee;padding-bottom:8px;margin-bottom:16px;">Akun Login</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="nama_lengkap">Nama Lengkap <span style="color:red;">*</span></label>
                                <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $mahasiswa->user->nama_lengkap) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="username">Username (Tidak bisa diubah)</label>
                                <input type="text" id="username" class="form-control" value="{{ $mahasiswa->user->username }}" readonly disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $mahasiswa->user->email) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="password">Password Baru (Biarkan kosong jika tidak diubah)</label>
                                <input type="password" id="password" name="password" class="form-control">
                            </div>
                        </div>

                        <h6 style="border-bottom:1px solid #e7eaee;padding-bottom:8px;margin:8px 0 16px;">Profil Magang</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="nim">NIM <span style="color:red;">*</span></label>
                                <input type="text" id="nim" name="nim" class="form-control" value="{{ old('nim', $mahasiswa->nim) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="universitas">Universitas <span style="color:red;">*</span></label>
                                <input type="text" id="universitas" name="universitas" class="form-control" value="{{ old('universitas', $mahasiswa->universitas) }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="jurusan">Jurusan / Program Studi</label>
                                <input type="text" id="jurusan" name="jurusan" class="form-control" value="{{ old('jurusan', $mahasiswa->jurusan) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="divisi">Divisi / Bidang BPS</label>
                                <input type="text" id="divisi" name="divisi" class="form-control" value="{{ old('divisi', $mahasiswa->divisi) }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="target_proyek">Target / Deskripsi Proyek</label>
                            <input type="text" id="target_proyek" name="target_proyek" class="form-control" value="{{ old('target_proyek', $mahasiswa->target_proyek) }}">
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="periode_mulai">Tanggal Mulai <span style="color:red;">*</span></label>
                                <input type="date" id="periode_mulai" name="periode_mulai" class="form-control" value="{{ old('periode_mulai', $mahasiswa->periode_mulai?->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="periode_selesai">Tanggal Selesai <span style="color:red;">*</span></label>
                                <input type="date" id="periode_selesai" name="periode_selesai" class="form-control" value="{{ old('periode_selesai', $mahasiswa->periode_selesai?->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="status">Status Magang <span style="color:red;">*</span></label>
                                <select name="status" id="status" class="form-control">
                                    <option value="aktif" {{ (old('status') ?? $mahasiswa->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="selesai" {{ (old('status') ?? $mahasiswa->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="nonaktif" {{ (old('status') ?? $mahasiswa->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                        </div>

                        <div style="display:flex;gap:8px;justify-content:flex-end;padding-top:8px;">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
