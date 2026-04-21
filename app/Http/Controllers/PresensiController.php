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
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $query = Presensi::with('user')->latest('tanggal');

        if ($user->peran === 'mahasiswa') {
            $query->where('user_id', $user->id);
        }

        // Filter berdasarkan bulan & tahun
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        }

        $presensis = $query->paginate(20)->withQueryString();

        // Presensi hari ini (untuk mahasiswa)
        $hariIni = null;
        if ($user->peran === 'mahasiswa') {
            $hariIni = Presensi::hariIni($user->id);
        }

        return view('pages.presensi.index', compact('presensis', 'hariIni', 'bulan', 'tahun'));
    }

    /**
     * Memeriksa apakah IP aktif diperbolehkan (jaringan kantor).
     */
    private function isIpAllowed($ip)
    {
        $allowedIps = explode(',', env('ALLOWED_WIFI_IPS'));
        foreach ($allowedIps as $allowed) {
            $allowed = trim($allowed);

            if ($allowed === '*' || $ip === $allowed) {
                return true;
            }

            // Mendukung format wildcard (contoh: 10.133.20.* atau 192.168.*)
            if (str_contains($allowed, '*')) {
                $pattern = str_replace('*', '.*', str_replace('.', '\.', $allowed));
                if (preg_match('/^' . $pattern . '$/', $ip)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Mengambil konfigurasi jam operasional hari ini.
     */
    private function getJamOperasional()
    {
        $now = now();
        $day = $now->dayOfWeek; // 1 = Senin, ..., 5 = Jumat, 6 = Sabtu, 0 = Minggu

        $jamMasuk  = $now->copy()->setTime(7, 30, 0);
        $jamPulang = $now->copy()->setTime($day == 5 ? 16 : 16, $day == 5 ? 30 : 0, 0); // Jumat 16:30, selain Jumat 16:00
        $mulaiCheckIn = $jamMasuk->copy()->subHour(); // 1 jam sebelum jam masuk

        return (object)[
            'now' => $now,
            'isWeekend' => $now->isWeekend(),
            'masuk' => $jamMasuk,
            'pulang' => $jamPulang,
            'mulaiCheckIn' => $mulaiCheckIn,
        ];
    }

    /**
     * Proses Check-In.
     */
    public function checkIn(Request $request)
    {
        $user = Auth::user();

        // 1. Pengecekan IP Wifi Kantor
        $userIp = $request->ip();
        if (!$this->isIpAllowed($userIp)) {
            return back()->with('error', "Akses ditolak: IP Anda saat ini terbaca sebagai [{$userIp}]. Ini bukan IP WiFi Kantor. (Jika localhost/127.0.0.1, gunakan HP untuk mengetes!)");
        }

        $ops = $this->getJamOperasional();

        // 2. Pengecekan Hari Libur
        if ($ops->isWeekend) {
            return back()->with('error', 'Presensi tidak dapat dilakukan pada hari libur (Sabtu - Minggu).');
        }

        if (Presensi::sudahCheckIn($user->id)) {
            return back()->with('error', 'Anda sudah melakukan Check-In hari ini.');
        }

        // 3. Pengecekan Jendela Waktu Check-in
        if ($ops->now < $ops->mulaiCheckIn) {
            return back()->with('error', 'Belum masuk waktu absen. Anda hanya dapat check-in mulai pukul ' . $ops->mulaiCheckIn->format('H:i') . ' WIB.');
        }

        if ($ops->now > $ops->pulang) {
            return back()->with('error', 'Waktu absensi telah habis (melewati jam pulang). Anda tergolong Alpha hari ini.');
        }

        // 4. Penentuan Status Hadir / Telat
        $statusAbsen = 'hadir'; // Tergolong Masuk (On-time)
        $msg = 'Check-In berhasil (Tepat Waktu) pada ';
        if ($ops->now > $ops->masuk && $ops->now <= $ops->pulang) {
            $statusAbsen = 'telat';
            $msg = 'Check-In berhasil (Anda Telat) pada ';
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
            'status'   => $statusAbsen,
        ]);

        return back()->with('success', $msg . now()->format('H:i') . ' WIB.');
    }

    /**
     * Proses Check-Out.
     */
    public function checkOut(Request $request)
    {
        $user = Auth::user();

        // 1. Pengecekan IP Wifi Kantor
        $userIp = $request->ip();
        if (!$this->isIpAllowed($userIp)) {
            return back()->with('error', "Akses ditolak: IP Anda saat ini terbaca sebagai [{$userIp}]. Ini bukan IP WiFi Kantor. (Jika localhost/127.0.0.1, gunakan HP untuk mengetes!)");
        }

        $ops = $this->getJamOperasional();

        if ($ops->isWeekend) {
            return back()->with('error', 'Fitur Check-Out terkunci pada hari libur.');
        }

        // 2. Pengecekan Waktu Pulang
        if ($ops->now < $ops->pulang) {
            return back()->with('error', 'Belum masuk waktu pulang. Anda baru bisa melakukan Check-out tepat pada jam ' . $ops->pulang->format('H:i') . ' WIB.');
        }

        $presensi = Presensi::hariIni($user->id);

        if (! $presensi) {
            return back()->with('error', 'Anda belum melakukan Check-In hari ini, status Anda adalah Alpha.');
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

        return back()->with('success', 'Check-Out berhasil pada ' . now()->format('H:i') . ' WIB. Selamat beristirahat!');
    }

    /**
     * Admin: rekap presensi untuk export.
     */
    public function rekap(Request $request)
    {
        $query = Presensi::with('user')
            ->when($request->bulan, fn($q) => $q->whereMonth('tanggal', $request->bulan))
            ->when($request->tahun, fn($q) => $q->whereYear('tanggal', $request->tahun ?? now()->year))
            ->latest('tanggal');

        $presensis = $query->get();

        return view('pages.presensi.rekap', compact('presensis'));
    }
}
