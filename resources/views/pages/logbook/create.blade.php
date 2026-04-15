@extends('layouts.app')
@section('title', 'Isi Logbook Harian')

@section('content')

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-header-title"><h5>Isi Logbook Harian</h5></div>
                </div>
                <div class="col-auto">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('logbook.index') }}">Logbook</a></li>
                        <li class="breadcrumb-item" aria-current="page">Isi Baru</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 style="margin:0;"><i class="ti ti-notebook"></i> Form Logbook Harian</h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul style="margin:0;padding-left:20px;">
                                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('logbook.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="tanggal">Tanggal Kegiatan <span style="color:red;">*</span></label>
                                <input type="date" id="tanggal" name="tanggal" class="form-control"
                                    value="{{ old('tanggal', date('Y-m-d')) }}"
                                    max="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="kategori">Kategori Tugas <span style="color:red;">*</span></label>
                                <select id="kategori" name="kategori" class="form-control" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategoriList as $kat)
                                        <option value="{{ $kat }}" {{ old('kategori') === $kat ? 'selected' : '' }}>
                                            {{ $kat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="deskripsi_kegiatan">Deskripsi Kegiatan <span style="color:red;">*</span></label>
                            <textarea id="deskripsi_kegiatan" name="deskripsi_kegiatan" class="form-control"
                                rows="6" placeholder="Tuliskan kegiatan yang dilakukan secara detail..." required>{{ old('deskripsi_kegiatan') }}</textarea>
                            <small style="color:#8996a4;">Contoh: Melakukan input data survei 50 responden kecamatan X, kemudian cleaning data...</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="file_bukti">Upload Bukti (Foto/PDF)</label>
                                <input type="file" id="file_bukti" name="file_bukti" class="form-control"
                                    accept=".jpg,.jpeg,.png,.pdf">
                                <small style="color:#8996a4;">Maks. 5 MB — Format: JPG, PNG, PDF</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="link_bukti">Link Bukti (Opsional)</label>
                                <input type="url" id="link_bukti" name="link_bukti" class="form-control"
                                    placeholder="https://drive.google.com/..." value="{{ old('link_bukti') }}">
                                <small style="color:#8996a4;">Link Google Drive, GitHub, atau dokumen online lainnya</small>
                            </div>
                        </div>

                        <div style="display:flex;gap:8px;justify-content:flex-end;padding-top:8px;">
                            <a href="{{ route('logbook.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-send"></i> Kirim Logbook
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
