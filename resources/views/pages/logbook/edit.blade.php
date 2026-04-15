@extends('layouts.app')
@section('title', 'Edit Logbook')

@section('content')

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-header-title"><h5>Edit Logbook</h5></div>
                </div>
                <div class="col-auto">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('logbook.index') }}">Logbook</a></li>
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
                    <h5 style="margin:0;"><i class="ti ti-edit"></i> Edit Logbook — {{ $logbook->tanggal->format('d M Y') }}</h5>
                </div>
                <div class="card-body">
                    @if($logbook->status === 'revisi' && $logbook->komentar_admin)
                        <div class="alert alert-warning">
                            <i class="ti ti-message"></i> <strong>Catatan Admin:</strong>
                            {{ $logbook->komentar_admin }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul style="margin:0;padding-left:20px;">
                                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('logbook.update', $logbook->id) }}" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="tanggal">Tanggal Kegiatan</label>
                                <input type="date" id="tanggal" name="tanggal" class="form-control"
                                    value="{{ old('tanggal', $logbook->tanggal->format('Y-m-d')) }}"
                                    max="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="kategori">Kategori Tugas</label>
                                <select id="kategori" name="kategori" class="form-control" required>
                                    @foreach($kategoriList as $kat)
                                        <option value="{{ $kat }}" {{ old('kategori', $logbook->kategori) === $kat ? 'selected' : '' }}>
                                            {{ $kat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="deskripsi_kegiatan">Deskripsi Kegiatan</label>
                            <textarea id="deskripsi_kegiatan" name="deskripsi_kegiatan" class="form-control" rows="6" required>{{ old('deskripsi_kegiatan', $logbook->deskripsi_kegiatan) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="file_bukti">Ganti Bukti File</label>
                                @if($logbook->file_bukti)
                                    <div style="margin-bottom:6px;">
                                        <a href="{{ asset('storage/' . $logbook->file_bukti) }}" target="_blank" style="font-size:12px;">
                                            <i class="ti ti-paperclip"></i> File saat ini
                                        </a>
                                    </div>
                                @endif
                                <input type="file" id="file_bukti" name="file_bukti" class="form-control"
                                    accept=".jpg,.jpeg,.png,.pdf">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="link_bukti">Link Bukti</label>
                                <input type="url" id="link_bukti" name="link_bukti" class="form-control"
                                    value="{{ old('link_bukti', $logbook->link_bukti) }}">
                            </div>
                        </div>

                        <div style="display:flex;gap:8px;justify-content:flex-end;padding-top:8px;">
                            <a href="{{ route('logbook.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-send"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
