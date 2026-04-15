<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Logbook;
use App\Models\Presensi;
use App\Models\Proyek;
use App\Models\Pengumuman;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ────────────────────────────────────────────────
        $admin = User::create([
            'nama_lengkap' => 'Administrator BPS',
            'username'     => 'admin',
            'email'        => 'admin@bps.go.id',
            'password'     => Hash::make('admin123'),
            'peran'        => 'admin',
            'is_active'    => true,
        ]);

        // ── Mahasiswa 1 ──────────────────────────────────────────
        $mhs1 = User::create([
            'nama_lengkap' => 'Budi Santoso',
            'username'     => 'budi.santoso',
            'email'        => 'budi@student.ac.id',
            'password'     => Hash::make('password'),
            'peran'        => 'mahasiswa',
            'is_active'    => true,
        ]);
        Mahasiswa::create([
            'user_id'         => $mhs1->id,
            'nim'             => '21060123',
            'universitas'     => 'Universitas Diponegoro',
            'jurusan'         => 'Statistika',
            'divisi'          => 'Pengolahan Data',
            'target_proyek'   => 'Analisis Data Sensus Penduduk 2025',
            'periode_mulai'   => '2026-02-01',
            'periode_selesai' => '2026-05-31',
            'status'          => 'aktif',
        ]);

        // ── Mahasiswa 2 ──────────────────────────────────────────
        $mhs2 = User::create([
            'nama_lengkap' => 'Sari Dewi',
            'username'     => 'sari.dewi',
            'email'        => 'sari@student.ac.id',
            'password'     => Hash::make('password'),
            'peran'        => 'mahasiswa',
            'is_active'    => true,
        ]);
        Mahasiswa::create([
            'user_id'         => $mhs2->id,
            'nim'             => '21060456',
            'universitas'     => 'Universitas Gadjah Mada',
            'jurusan'         => 'Informatika',
            'divisi'          => 'IT Support',
            'target_proyek'   => 'Pengembangan Dashboard Web GIS Jepara',
            'periode_mulai'   => '2026-02-01',
            'periode_selesai' => '2026-05-31',
            'status'          => 'aktif',
        ]);

        // ── Sample Logbook ───────────────────────────────────────
        Logbook::create([
            'user_id'            => $mhs1->id,
            'tanggal'            => today()->subDays(1),
            'deskripsi_kegiatan' => 'Melakukan cleaning data hasil survei rumah tangga minggu lalu. Ditemukan ~120 record yang perlu diperbaiki missing value-nya.',
            'kategori'           => 'Pengolahan Data',
            'status'             => 'disetujui',
            'divalidasi_oleh'    => $admin->id,
            'divalidasi_pada'    => now(),
        ]);
        Logbook::create([
            'user_id'            => $mhs1->id,
            'tanggal'            => today(),
            'deskripsi_kegiatan' => 'Menyusun tabel distribusi frekuensi untuk data penduduk Kecamatan Jepara.',
            'kategori'           => 'Pengolahan Data',
            'status'             => 'pending',
        ]);
        Logbook::create([
            'user_id'            => $mhs2->id,
            'tanggal'            => today()->subDays(1),
            'deskripsi_kegiatan' => 'Setup environment server dan konfigurasi awal database untuk dashboard GIS.',
            'kategori'           => 'IT Support',
            'status'             => 'disetujui',
            'divalidasi_oleh'    => $admin->id,
            'divalidasi_pada'    => now(),
        ]);

        // ── Sample Presensi ──────────────────────────────────────
        Presensi::create([
            'user_id'   => $mhs1->id,
            'tanggal'   => today(),
            'check_in'  => '07:55:00',
            'status'    => 'hadir',
        ]);

        // ── Sample Proyek ────────────────────────────────────────
        Proyek::create([
            'user_id'        => $mhs1->id,
            'nama_proyek'    => 'Analisis Data Sensus Penduduk 2025',
            'deskripsi'      => 'Mengolah dan menganalisis data sensus penduduk Kabupaten Jepara 2025',
            'progress_persen' => 45,
            'status'         => 'berjalan',
        ]);
        Proyek::create([
            'user_id'        => $mhs2->id,
            'nama_proyek'    => 'Dashboard Web GIS Jepara',
            'deskripsi'      => 'Pengembangan dashboard peta interaktif data statistik wilayah',
            'progress_persen' => 60,
            'status'         => 'berjalan',
        ]);

        // ── Sample Pengumuman ────────────────────────────────────
        Pengumuman::create([
            'admin_id'  => $admin->id,
            'judul'     => 'Selamat Datang di SIMPANG-BPS!',
            'isi'       => 'Selamat datang di Sistem Informasi Manajemen Progress & Jejak Digital Magang BPS Kabupaten Jepara. Pastikan Anda selalu mengisi logbook harian dan melakukan presensi setiap hari kerja.',
            'target'    => 'semua',
            'is_pinned' => true,
        ]);
        Pengumuman::create([
            'admin_id'  => $admin->id,
            'judul'     => 'Jadwal Evaluasi Mingguan',
            'isi'       => 'Evaluasi mingguan akan dilaksanakan setiap Jumat pukul 14.00 WIB di ruang rapat lantai 2. Harap menyiapkan laporan perkembangan proyek masing-masing.',
            'target'    => 'mahasiswa',
            'is_pinned' => false,
        ]);
    }
}
