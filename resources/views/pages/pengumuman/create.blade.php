@extends('layouts.app')
@section('title', 'Buat Pengumuman')

@section('content')

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-header-title"><h5>Buat Pengumuman</h5></div>
                </div>
                <div class="col-auto">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pengumuman.index') }}">Pengumuman</a></li>
                        <li class="breadcrumb-item" aria-current="page">Buat</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-7 col-12">
            <div class="card">
                <div class="card-header">
                    <h5 style="margin:0;"><i class="ti ti-speakerphone"></i> Form Pengumuman Baru</h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul style="margin:0;padding-left:20px;">
                                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pengumuman.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="judul">Judul Pengumuman <span style="color:red;">*</span></label>
                            <input type="text" id="judul" name="judul" class="form-control"
                                value="{{ old('judul') }}" required
                                placeholder="Contoh: Jadwal Evaluasi Mingguan">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="isi">Isi Pengumuman <span style="color:red;">*</span></label>
                            <textarea id="isi" name="isi" class="form-control" rows="6" required
                                placeholder="Tulis isi pengumuman secara lengkap...">{{ old('isi') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="target">Target Penerima</label>
                                <select id="target" name="target" class="form-control">
                                    <option value="semua" {{ old('target') === 'semua' ? 'selected' : '' }}>Semua Pengguna</option>
                                    <option value="mahasiswa" {{ old('target') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa Saja</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div style="display:flex;align-items:center;gap:10px;padding:10px 0;">
                                    <input type="checkbox" id="is_pinned" name="is_pinned" value="1"
                                        {{ old('is_pinned') ? 'checked' : '' }}
                                        style="width:16px;height:16px;accent-color:#4680ff;">
                                    <label for="is_pinned" style="margin:0;cursor:pointer;font-size:14px;">
                                        <i class="ti ti-pin"></i> Sematkan di atas (pinned)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div style="display:flex;gap:8px;justify-content:flex-end;padding-top:8px;border-top:1px solid #e7eaee;margin-top:8px;">
                            <a href="{{ route('pengumuman.index') }}" class="btn btn-outline-secondary">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-speakerphone"></i> Kirim Pengumuman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
