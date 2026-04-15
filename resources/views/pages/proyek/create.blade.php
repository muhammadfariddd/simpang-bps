@extends('layouts.app')
@section('title', 'Buat Proyek')

@section('content')

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-header-title"><h5>Buat Proyek Baru</h5></div>
                </div>
                <div class="col-auto">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('proyek.index') }}">Proyek</a></li>
                        <li class="breadcrumb-item" aria-current="page">Buat</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-7">
            <div class="card">
                <div class="card-header">
                    <h5 style="margin:0;"><i class="ti ti-clipboard-list"></i> Form Proyek Magang</h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul style="margin:0;padding-left:20px;">
                                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('proyek.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="nama_proyek">Nama Proyek <span style="color:red;">*</span></label>
                            <input type="text" id="nama_proyek" name="nama_proyek" class="form-control"
                                value="{{ old('nama_proyek') }}" required
                                placeholder="mis: Analisis Data Sensus Penduduk 2025">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="deskripsi">Deskripsi Proyek</label>
                            <textarea id="deskripsi" name="deskripsi" class="form-control" rows="4"
                                placeholder="Jelaskan tujuan dan scope proyek...">{{ old('deskripsi') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="progress_persen">Progress Awal (%) <span style="color:red;">*</span></label>
                            <input type="range" id="progress_persen" name="progress_persen" class="form-control"
                                min="0" max="100" value="{{ old('progress_persen', 0) }}"
                                oninput="document.getElementById('progress_val').textContent = this.value + '%'">
                            <div style="text-align:center;font-size:22px;font-weight:700;color:#4680ff;" id="progress_val">
                                {{ old('progress_persen', 0) }}%
                            </div>
                        </div>
                        <div style="display:flex;gap:8px;justify-content:flex-end;">
                            <a href="{{ route('proyek.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-plus"></i> Buat Proyek
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
