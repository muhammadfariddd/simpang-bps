@extends('layouts.app')
@section('title', 'Pengumuman')

@section('content')

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-header-title"><h5>Pengumuman</h5></div>
                </div>
                <div class="col-auto">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Pengumuman</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-12">
            <div class="card">
                <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
                    <h5 style="margin:0;"><i class="ti ti-speakerphone"></i> Daftar Pengumuman</h5>
                    @if(Auth::user()->peran === 'admin')
                        <a href="{{ route('pengumuman.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus"></i> Buat Pengumuman
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @forelse($pengumumans as $p)
                        <div class="card" style="border:1px solid {{ $p->is_pinned ? '#4680ff' : '#e7eaee' }};margin-bottom:12px;">
                            <div class="card-body" style="padding:16px;">
                                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
                                    <div style="flex:1;">
                                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                                            @if($p->is_pinned)
                                                <i class="ti ti-pin" style="color:#4680ff;"></i>
                                                <span class="badge bg-primary" style="font-size:10px;">Tersematkan</span>
                                            @endif
                                            <h6 style="margin:0;">{{ $p->judul }}</h6>
                                        </div>
                                        <p style="color:#4d5763;font-size:14px;margin:0 0 8px;line-height:1.6;">{{ $p->isi }}</p>
                                        <div style="font-size:12px;color:#8996a4;">
                                            <i class="ti ti-user"></i> {{ $p->admin->nama_lengkap }}
                                            &nbsp;&middot;&nbsp;
                                            <i class="ti ti-clock"></i> {{ $p->created_at->diffForHumans() }}
                                            &nbsp;&middot;&nbsp;
                                            Target: <span class="badge bg-secondary" style="font-size:10px;">{{ $p->target }}</span>
                                        </div>
                                    </div>
                                    @if(Auth::user()->peran === 'admin')
                                        <div>
                                            <form method="POST" action="{{ route('pengumuman.destroy', $p->id) }}"
                                                onsubmit="return confirm('Hapus pengumuman ini?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center;padding:32px;color:#8996a4;">
                            <i class="ti ti-speakerphone" style="font-size:2.5rem;"></i>
                            <p style="margin:8px 0 0;">Belum ada pengumuman.</p>
                        </div>
                    @endforelse
                    {{ $pengumumans->links() }}
                </div>
            </div>
        </div>

        @if(Auth::user()->peran === 'admin')
        <div class="col-xl-4 col-12">
            <div class="card">
                <div class="card-header">
                    <h5 style="margin:0;"><i class="ti ti-send"></i> Kirim Pengumuman Cepat</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('pengumuman.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="judul">Judul</label>
                            <input type="text" id="judul" name="judul" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="isi">Isi Pengumuman</label>
                            <textarea id="isi" name="isi" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="target">Target</label>
                            <select id="target" name="target" class="form-control">
                                <option value="semua">Semua</option>
                                <option value="mahasiswa">Mahasiswa</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <input type="checkbox" id="is_pinned" name="is_pinned" value="1">
                                <label for="is_pinned" style="margin:0;font-size:13px;">Sematkan di atas</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%;">
                            <i class="ti ti-speakerphone"></i> Kirim Pengumuman
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>

@endsection
