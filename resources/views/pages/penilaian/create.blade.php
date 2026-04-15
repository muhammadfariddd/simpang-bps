@extends('layouts.app')
@section('title', 'Form Penilaian')

@section('content')

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-header-title"><h5>Form Penilaian Akhir</h5></div>
                </div>
                <div class="col-auto">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('penilaian.index') }}">Penilaian</a></li>
                        <li class="breadcrumb-item" aria-current="page">Beri Nilai</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 style="margin:0;">
                        <i class="ti ti-star"></i> Penilaian: {{ $mahasiswa->user->nama_lengkap }}
                        <small style="color:#8996a4;font-size:13px;">({{ $mahasiswa->nim }} — {{ $mahasiswa->universitas }})</small>
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('penilaian.store') }}">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $mahasiswa->user_id }}">

                        @php
                            $kriteria = [
                                'kedisiplinan'   => 'Kedisiplinan',
                                'kualitas_kerja' => 'Kualitas Kerja',
                                'inisiatif'      => 'Inisiatif',
                                'kerjasama'      => 'Kerjasama Tim',
                                'komunikasi'     => 'Komunikasi',
                            ];
                            $existing = $mahasiswa->penilaian;
                        @endphp

                        <div class="row">
                            @foreach($kriteria as $field => $label)
                            <div class="col-md-6 mb-4">
                                <label class="form-label">{{ $label }} <span style="color:red;">*</span></label>
                                <input type="range" name="{{ $field }}" class="form-control"
                                    min="0" max="100"
                                    value="{{ old($field, $existing?->{$field} ?? 75) }}"
                                    oninput="document.getElementById('v_{{ $field }}').textContent = this.value">
                                <div style="text-align:center;font-size:20px;font-weight:700;color:#4680ff;" id="v_{{ $field }}">
                                    {{ old($field, $existing?->{$field} ?? 75) }}
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan / Komentar</label>
                            <textarea name="catatan" class="form-control" rows="3"
                                placeholder="Catatan umum mengenai kinerja mahasiswa...">{{ old('catatan', $existing?->catatan) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <input type="checkbox" id="tandai_selesai" name="tandai_selesai" value="1"
                                    {{ $mahasiswa->status === 'selesai' ? 'checked' : '' }}>
                                <label for="tandai_selesai" style="margin:0;">Tandai magang sebagai SELESAI (aktifkan sertifikat)</label>
                            </div>
                        </div>

                        <div style="display:flex;gap:8px;justify-content:flex-end;">
                            <a href="{{ route('penilaian.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy"></i> Simpan Penilaian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
