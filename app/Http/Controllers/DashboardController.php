<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Logbook;
use App\Models\Presensi;
use App\Models\Proyek;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $peran = $user->peran;

        if ($peran === 'admin') {
            return $this->dashboardAdmin();
        }

        return $this->dashboardMahasiswa($user);
    }

    // ─── Dashboard Admin ───────────────────────────────────────────
    private function dashboardAdmin()
    {
        $totalMahasiswa    = Mahasiswa::where('status', 'aktif')->count();
        $totalLogbookBaru  = Logbook::where('status', 'pending')->count();
        $totalLogbook      = Logbook::count();
        $mahasiswaSelesai  = Mahasiswa::where('status', 'selesai')->count();

        // Distribusi mahasiswa per universitas
        $perUniversitas = Mahasiswa::select('universitas')
            ->selectRaw('COUNT(*) as jumlah')
            ->groupBy('universitas')
            ->get();

        // Aktivitas logbook 7 hari terakhir
        $aktivitasHarian = Logbook::selectRaw('DATE(tanggal) as tgl, COUNT(*) as jumlah')
            ->where('tanggal', '>=', now()->subDays(6))
            ->groupBy('tgl')
            ->orderBy('tgl')
            ->get()
            ->pluck('jumlah', 'tgl');

        // Logbook pending terbaru
        $logbookPending = Logbook::with('user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // Daftar mahasiswa aktif dengan progress
        $mahasiswaAktif = Mahasiswa::with(['user', 'projeks'])
            ->where('status', 'aktif')
            ->get();

        // Pengumuman pinned
        $pengumuman = Pengumuman::where('is_pinned', true)->latest()->take(3)->get();

        return view('pages.dashboard', compact(
            'totalMahasiswa',
            'totalLogbookBaru',
            'totalLogbook',
            'mahasiswaSelesai',
            'perUniversitas',
            'aktivitasHarian',
            'logbookPending',
            'mahasiswaAktif',
            'pengumuman'
        ));
    }

    // ─── Dashboard Mahasiswa ───────────────────────────────────────
    private function dashboardMahasiswa($user)
    {
        $mahasiswa = $user->mahasiswa;

        // Presensi: total hari hadir
        $totalHadir = Presensi::where('user_id', $user->id)
            ->where('status', 'hadir')
            ->count();

        // Logbook yang sudah disetujui
        $logbookDisetujui = Logbook::where('user_id', $user->id)
            ->where('status', 'disetujui')
            ->count();

        // Logbook pending milik user ini
        $logbookPending = Logbook::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        // Progress proyek (rata-rata)
        $projeks = Proyek::where('user_id', $user->id)->get();
        $avgProgress = $projeks->isEmpty() ? 0 : (int) $projeks->avg('progress_persen');

        // Presensi hari ini
        $presensiHariIni = Presensi::hariIni($user->id);

        // 5 logbook terbaru
        $recentLogbooks = Logbook::where('user_id', $user->id)
            ->latest('tanggal')
            ->take(5)
            ->get();

        // Pengumuman untuk mahasiswa
        $pengumuman = Pengumuman::whereIn('target', ['semua', 'mahasiswa'])
            ->orWhere('target', (string) $user->id)
            ->latest()
            ->take(3)
            ->get();

        return view('pages.dashboard-mahasiswa', compact(
            'mahasiswa',
            'totalHadir',
            'logbookDisetujui',
            'logbookPending',
            'avgProgress',
            'projeks',
            'presensiHariIni',
            'recentLogbooks',
            'pengumuman'
        ));
    }
}
