<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Logbook;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Rekap kehadiran mahasiswa.
     */
    public function kehadiran(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $query = Presensi::with('user')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->latest('tanggal');

        if (Auth::user()->peran === 'mahasiswa') {
            $query->where('user_id', Auth::id());
        }

        $presensis = $query->get();

        // Ringkasan per mahasiswa
        $ringkasan = Mahasiswa::with(['user', 'presensis' => function ($q) use ($bulan, $tahun) {
            $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        }])->where('status', 'aktif')->get();

        return view('pages.laporan.kehadiran', compact('presensis', 'ringkasan', 'bulan', 'tahun'));
    }

    /**
     * Rekap logbook.
     */
    public function logbook(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $query = Logbook::with('user')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->latest('tanggal');

        if (Auth::user()->peran === 'mahasiswa') {
            $query->where('user_id', Auth::id());
        }

        $logbooks = $query->get();

        return view('pages.laporan.logbook', compact('logbooks', 'bulan', 'tahun'));
    }

    /**
     * Export rekap kehadiran ke CSV (sederhana, tanpa package).
     */
    public function exportKehadiran(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $presensis = Presensi::with('user')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal')
            ->get();

        $filename = "rekap-kehadiran-{$bulan}-{$tahun}.csv";
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($presensis) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Nama', 'NIM', 'Tanggal', 'Check-In', 'Check-Out', 'Status']);
            foreach ($presensis as $p) {
                $mhs = $p->user->mahasiswa;
                fputcsv($handle, [
                    $p->user->nama_lengkap,
                    $mhs->nim ?? '-',
                    $p->tanggal->format('d/m/Y'),
                    $p->check_in  ?? '-',
                    $p->check_out ?? '-',
                    ucfirst($p->status),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export rekap logbook ke CSV.
     */
    public function exportLogbook(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $logbooks = Logbook::with('user')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal')
            ->get();

        $filename = "rekap-logbook-{$bulan}-{$tahun}.csv";
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($logbooks) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Nama', 'Tanggal', 'Kategori', 'Kegiatan', 'Status', 'Komentar Admin']);
            foreach ($logbooks as $l) {
                fputcsv($handle, [
                    $l->user->nama_lengkap,
                    $l->tanggal->format('d/m/Y'),
                    $l->kategori,
                    $l->deskripsi_kegiatan,
                    ucfirst($l->status),
                    $l->komentar_admin ?? '',
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
