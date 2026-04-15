<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    /**
     * Daftar presensi.
     * - Admin: semua presensi (bisa filter per mahasiswa/tanggal)
     * - Mahasiswa: presensi milik sendiri
     */
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Presensi::with('user')->latest('tanggal');

        if ($user->peran === 'mahasiswa') {
            $query->where('user_id', $user->id);
        }

        // Filter tanggal
        if ($request->filled('dari')) {
            $query->where('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->where('tanggal', '<=', $request->sampai);
        }

        $presensis = $query->paginate(20)->withQueryString();

        // Presensi hari ini (untuk mahasiswa)
        $hariIni = null;
        if ($user->peran === 'mahasiswa') {
            $hariIni = Presensi::hariIni($user->id);
        }

        return view('pages.presensi.index', compact('presensis', 'hariIni'));
    }

    /**
     * Proses Check-In.
     */
    public function checkIn(Request $request)
    {
        $user = Auth::user();

        if (Presensi::sudahCheckIn($user->id)) {
            return back()->with('error', 'Anda sudah melakukan Check-In hari ini.');
        }

        $request->validate([
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        Presensi::create([
            'user_id'  => $user->id,
            'tanggal'  => today(),
            'check_in' => now()->format('H:i:s'),
            'lat_in'   => $request->lat,
            'lng_in'   => $request->lng,
            'status'   => 'hadir',
        ]);

        return back()->with('success', 'Check-In berhasil pada ' . now()->format('H:i') . ' WIB.');
    }

    /**
     * Proses Check-Out.
     */
    public function checkOut(Request $request)
    {
        $user    = Auth::user();
        $presensi = Presensi::hariIni($user->id);

        if (! $presensi) {
            return back()->with('error', 'Anda belum melakukan Check-In hari ini.');
        }

        if ($presensi->check_out) {
            return back()->with('error', 'Anda sudah melakukan Check-Out hari ini.');
        }

        $request->validate([
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        $presensi->update([
            'check_out' => now()->format('H:i:s'),
            'lat_out'   => $request->lat,
            'lng_out'   => $request->lng,
        ]);

        return back()->with('success', 'Check-Out berhasil pada ' . now()->format('H:i') . ' WIB.');
    }

    /**
     * Admin: rekap presensi untuk export.
     */
    public function rekap(Request $request)
    {
        $query = Presensi::with('user')
            ->when($request->bulan, fn ($q) => $q->whereMonth('tanggal', $request->bulan))
            ->when($request->tahun, fn ($q) => $q->whereYear('tanggal', $request->tahun ?? now()->year))
            ->latest('tanggal');

        $presensis = $query->get();

        return view('pages.presensi.rekap', compact('presensis'));
    }
}
