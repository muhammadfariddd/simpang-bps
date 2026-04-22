<p align="center">
  <img src="public/images/logo/logo.png" alt="Logo BPS" width="75"/>
</p>

<h1 align="center">SIMPANG</h1>

<p align="center">
  <strong>Sistem Informasi Manajemen Progress & Jejak Digital Magang BPS Jepara</strong><br>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

<hr>

## 📖 Tentang Aplikasi

**SIMPANG-BPS** adalah aplikasi operasional _web-based_ yang dirancang khusus untuk memenuhi standarisasi Badan Pusat Statistik (BPS) dalam mengelola, melacak, dan mengevaluasi seluruh aktivitas mahasiswa magang secara terpusat dan modern.

Dilengkapi dengan sistem presensi berikat Geofencing IP, proteksi manipulasi waktu, validasi hierarkis, dan manajemen dokumentasi (Logbook) yang efisien.

---

## ✨ Modul & Hak Akses

Sistem memetakan otorisasi ke dalam 2 pilar aktor utama:

### 👨‍🎓 Mahasiswa (Anak Magang)

| Fitur                      | Deskripsi                                                                                                        |
| -------------------------- | ---------------------------------------------------------------------------------------------------------------- |
| 📊 **Personal Dashboard**  | Memantau ringkasan statistik kegiatan dan status progres magang harian.                                          |
| 🕛 **Presensi Geofencing** | Check-in / Check-out harian. Terintegrasi proteksi Otomatisasi Jaringan (Hanya valid via IP Wi-Fi Kantor / BPS). |
| 📝 **Logbook Harian**      | Penyerahan dan rekam jejak laporan kerja (mendukung integrasi lampiran dan bukti dokumentasi).                   |
| 🚀 **Progres Proyek**      | Kendali penyusunan laporan dan pemantauan atas pencapaian resolusi/tugas yang didelegasikan.                     |

### 🏢 Pembimbing (Admin BPS)

| Fitur                    | Deskripsi                                                                                                              |
| ------------------------ | ---------------------------------------------------------------------------------------------------------------------- |
| 👥 **Data Mahasiswa**    | Otorisasi penambahan akun anak magang per gelombang secara tersetruktur.                                               |
| 📑 **Validasi Logbook**  | Mensupervisi, memberikan _approval_ / rejeksi, atau melampirkan catatan revisi pada jurnal Mahasiswa.                  |
| 📢 **Sistem Siaran**     | Modul pengumuman komunikasi (_Broadcast_) untuk internal peserta magang.                                               |
| 📈 **Atribut Penilaian** | Instrumen kalkulasi rapor nilai (Kedisiplinan, Keahlian, Sikap), terhubung langsung dengan sistem Eksport _CSV_ Rapor. |

---

## 🛠️ Persyaratan Lingkungan (Environment)

- **PHP** `^8.2`
- **Composer** `v2+`
- **Node.js** (Rekomendasi `v20 LTS`) & **NPM**
- **Database Server:** MySQL / MariaDB
- **Web Host Engine:** Apache / Nginx / Laragon Server

---

## 🚀 Standar Instalasi (Deployment Setup)

Gunakan resep prosedural standar berikut untuk mereplikasi sistem ini di mesin target / _Cloud Server_ organisasi:

```bash
# 1. Unduh / Clone Source Code
git clone https://github.com/USERNAME_ANDA/SIMPANG-BPS.git
cd SIMPANG-BPS

# 2. Susun Modul Dependencies PHP & Frontend NPM
composer install
npm install

# 3. Salin Skrip Cetak Biru Environments
cp .env.example .env
```

### ⚙️ Konfigurasi Koneksi (`.env`)

Buka file `.env` di text editor dan sesuaikan parameter berikut dengan jalur Root SQL Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_simpang
DB_USERNAME=root
DB_PASSWORD=
```

**🛡️ Setelan Keamanan Tinggi (Geofencing Absen):**  
Di file `.env` yang sama, Admin Komputer WAJIB menginjeksikan IP Wi-Fi BPS agar fitur anti-manipulasi terpicu:

```env
# Mendukung sistem wildcard subnet (*).
ALLOWED_WIFI_IPS=10.133.20.*,192.168.1.1
```

### 🧱 Eksekusi Kompilasi Akhir

Lempar serangkaian terminal _command_ Laravel ini untuk membentuk kunci sistem rahasia, merakit SQL, dan membekukan desain Tailwind CSS:

```bash
php artisan key:generate
php artisan storage:link

# Rakit seluruh Kerangka Tabel & Tanamkan Akun Admin
php artisan migrate:fresh --seed

# Wajib: Kompilasi Asset Visual JS & CSS sebelum live!
npm run build
```

_(Untuk testing lokal internal di mesin, gunakan `php artisan serve`)_

---

## 🔐 Kredensial Pintu Masuk (Master Admin)

Sesaat pasca Seeding `migrate:fresh` yang diinstruksikan pada nomor di atas, database SQL berada dalam keadan 'suci' (tersapu bersih). Anda bisa membuka pintu akses _Root_ menggunakan kredensial tunggal ini:

- **Username**: `bps_jepara`
- **Password**: `simpang_3320`

> ⚠️ **Catatan Fatal:** Sangat amat direkomendasikan untuk menukar _Password Master_ tersebut via Dashboard Profile Aplikasi seketika setelah serah terima panel dari Developer.

---

## 📦 Arsitektur Teknologi Topologi Layer

- **Backend Logic Framework:** Laravel 13.x
- **Styling UI:** Tailwind CSS v4
- **Compiler Modul:** Vite (Esbuild)
- **Komponen Fungsional Tambahan:** SweetAlert2, Toastify JS, NProgress, Tabler Vector Icons.

---

_(SIMPANG-BPS — Rekayasa komputasi untuk kemajuan administratif Badan Pusat Statistik Kabupaten Jepara)._
