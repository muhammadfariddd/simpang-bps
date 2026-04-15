@extends('layouts.app')
@section('title', 'Detail Penilaian')

@section('content')

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-header-title"><h5>Hasil Penilaian Akhir</h5></div>
                </div>
                <div class="col-auto">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Nilai Saya</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @if(!$penilaian)
        <div class="card">
            <div class="card-body" style="text-align:center;padding:48px;">
                <i class="ti ti-star" style="font-size:3rem;color:#8996a4;"></i>
                <p style="color:#8996a4;margin:12px 0 0;">Penilaian akhir belum tersedia. Tunggu admin memberikan penilaian setelah periode magang selesai.</p>
            </div>
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-xl-7">
                <div class="card">
                    <div class="card-header" style="text-align:center;background:linear-gradient(135deg,#4680ff,#6f42c1);color:#fff;">
                        <h4 style="margin:0 0 4px;color:#fff;">Nilai Akhir Magang</h4>
                        <div style="font-size:48px;font-weight:700;color:#fff;">{{ $penilaian->nilai_akhir }}</div>
                        <span style="font-size:16px;background:rgba(255,255,255,0.2);padding:4px 16px;border-radius:20px;">
                            {{ $penilaian->predikat }}
                        </span>
                    </div>
                    <div class="card-body">
                        @php
                            $kriteria = [
                                'Kedisiplinan'   => $penilaian->kedisiplinan,
                                'Kualitas Kerja' => $penilaian->kualitas_kerja,
                                'Inisiatif'      => $penilaian->inisiatif,
                                'Kerjasama'      => $penilaian->kerjasama,
                                'Komunikasi'     => $penilaian->komunikasi,
                            ];
                        @endphp
                        @foreach($kriteria as $label => $nilai)
                            <div style="margin-bottom:14px;">
                                <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                                    <span style="font-size:13px;">{{ $label }}</span>
                                    <span style="font-weight:600;color:#4680ff;">{{ $nilai }}/100</span>
                                </div>
                                <div style="background:#e7eaee;border-radius:4px;height:8px;">
                                    <div style="width:{{ $nilai }}%;background:#4680ff;height:8px;border-radius:4px;"></div>
                                </div>
                            </div>
                        @endforeach

                        @if($penilaian->catatan)
                            <div class="alert alert-info" style="margin-top:16px;">
                                <strong>Catatan Admin:</strong><br>
                                {{ $penilaian->catatan }}
                            </div>
                        @endif

                        <p style="font-size:12px;color:#8996a4;text-align:center;margin-top:16px;">
                            Dinilai oleh {{ $penilaian->admin?->nama_lengkap ?? 'Admin' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
