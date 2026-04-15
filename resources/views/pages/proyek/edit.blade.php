@extends('layouts.app')
@section('title', 'Update Proyek')

@section('content')

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-header-title"><h5>Update Progress Proyek</h5></div>
                </div>
                <div class="col-auto">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('proyek.index') }}">Proyek</a></li>
                        <li class="breadcrumb-item" aria-current="page">Update</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-7">
            <div class="card">
                <div class="card-header">
                    <h5 style="margin:0;"><i class="ti ti-edit"></i> Edit — {{ $proyek->nama_proyek }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('proyek.update', $proyek->id) }}" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Nama Proyek</label>
                            <input type="text" name="nama_proyek" class="form-control" value="{{ old('nama_proyek', $proyek->nama_proyek) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $proyek->deskripsi) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Progress (%)</label>
                            <input type="range" name="progress_persen" class="form-control"
                                min="0" max="100" value="{{ old('progress_persen', $proyek->progress_persen) }}"
                                oninput="document.getElementById('pval').textContent = this.value + '%'">
                            <div style="text-align:center;font-size:22px;font-weight:700;color:#4680ff;" id="pval">
                                {{ old('progress_persen', $proyek->progress_persen) }}%
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload Laporan Akhir (PDF)</label>
                            @if($proyek->file_laporan)
                                <p style="font-size:12px;color:#8996a4;">
                                    <a href="{{ asset('storage/' . $proyek->file_laporan) }}" target="_blank">
                                        <i class="ti ti-file-text"></i> Laporan saat ini
                                    </a>
                                </p>
                            @endif
                            <input type="file" name="file_laporan" class="form-control" accept=".pdf">
                            <small style="color:#8996a4;">Maks. 10 MB — Format: PDF</small>
                        </div>
                        <div style="display:flex;gap:8px;justify-content:flex-end;">
                            <a href="{{ route('proyek.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
