# SIMPANG-BPS 🎯

**Sistem Informasi Manajemen Progress & Jejak Digital Magang BPS**

SIMPANG-BPS adalah aplikasi _web-based_ yang dikembangkan menggunakan **Laravel** dan **Tailwind CSS**. Sistem ini dirancang untuk memfasilitasi Badan Pusat Statistik (BPS) dalam mengelola, melacak, dan mengevaluasi kegiatan mahasiswa magang.

## ✨ Fitur Utama

Sistem memiliki dua tingkat hak akses (_Role_): **Admin** (Pihak BPS) dan **Mahasiswa** (Anak Magang).

### 👨‍🎓 Mahasiswa

- **Dashboard Personal:** Memantau ringkasan kegiatan dan status progres.
- **Presensi Harian / Absensi:** Fitur check-in dan check-out untuk pencatatan kehadiran.
- **Logbook Magang:** Melaporkan kegiatan keseharian magang beserta bukti dokumentasi (file foto/PDF).
- **Progres Proyek:** Menyusun dan melaporkan penyelesaian _milestone_ dari proyek magang yang dikerjakan.

### 🏢 Admin (Pembimbing BPS)

- **Manajemen Akun Mahasiswa:** Menambahkan dan mengelola data mahasiswa baru per gelombang/periode magang.
- **Validasi Logbook:** Menerima, Menolak (Revisi), dan memberikan catatan pada Logbook kegiatan mahasiswa.
- **Manajemen Pengumuman:** Menyiarkan informasi/berita magang kepada seluruh mahasiswa.
- **Penilaian & Laporan Akhir:** Memberikan parameter nilai kedisiplinan, kerjasama, dan keahlian, yang menghasilkan nilai akhir berstatus "selesai". Fitur rekapitulasi presensi dan logbook juga tersedia dalam bentuk ekspor _CSV_.

---

## 🛠️ Persyaratan Sistem (_Requirements_)

Pastikan server lokal/komputer Anda telah terinstal hal-hal berikut:

- **PHP** >= 8.2
- **Composer** v2+
- **Node.js** (Rekomendasi LTS: v20+) & **NPM**
- **MySQL** / MariaDB Database Server
- Web Server (Apache/Nginx/Laragon)

---

## 🚀 Cara Instalasi & Menjalankan (_Local Setup_)

Ikuti langkah-langkah di bawah untuk menjalankan program SIMPANG-BPS di komputer Anda.

**1. Clone reopsitori ini (Jika lewat Git):**

```bash
git clone https://github.com/USERNAME_ANDA/SIMPANG-BPS.git
cd SIMPANG-BPS
```

**2. Install dependencies PHP (Composer):**

```bash
composer install
```

**3. Install dependencies Node.js (NPM) untuk Tailwind CSS:**

```bash
npm install
```

**4. Mengatur konfiguasi Environment (`.env`):**  
Salin (copy) file `.env.example` menjadi file `.env`.

```bash
cp .env.example .env
```

Sesuaikan koneksi database Anda di file `.env` yang baru saja dibuat. Contoh:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_simpang
DB_USERNAME=root
DB_PASSWORD=
```

_(Jangan lupa sesuaikan juga `APP_URL` dengan local domain Anda, misalnya `http://simpang-bps.test` atau sesuaikan struktur folder Anda jika menggunakan Laragon)._

**Konfigurasi Keamanan (Geofencing IP):**  
Wajib bagi Admin/IT untuk menambahkan konfigurasi Wi-Fi di file `.env` sistem agar fitur Absensi berjalan di jaringan yang benar:

```env
# Sesuaikan IP dengan IP Publik / Subnet Jaringan Kantor
ALLOWED_WIFI_IPS=10.133.20.*,192.168.1.1
```

**5. Eksekusi Environment tambahan:**
Berikan generate kunci Laravel dan tautkan link Storage (wajib untuk agar upload gambar bisa dibaca web):

```bash
php artisan key:generate
php artisan storage:link
```

**6. Jalankan Migrasi & Seeder Database:**

```bash
php artisan migrate --seed
```

*(Perintah ini akan secara otomatis membuat struktur tabel database dan mengisi data *dummy*)*

**7. Compile / _Build_ Aset Front-End (Wajib):**

```bash
npm run build
```

**8. Jalankan Server Dev Lokal:**
Jika Anda memakai perintah `php artisan serve`, buka `http://127.0.0.1:8000`. Jika menggunakan Laragon, bisa langsung mengakses alamat lokal `.test` yang Anda sediakan.

---

## 🔐 Akses Login Default (Master Admin)

Jika Anda berhasil menjalankan skrip seeding (`migrate --seed`), database Anda akan berada dalam kondisi bersih dan hanya menyisakan secara esklusif satu (1) akun Admin utama. Anda bisa mencoba masuk menggunakan akun berikut:

**Admin Utama BPS:**

- Username: `bps_jepara`
- Password: `simpang_3320`

_(Sangat direkomendasikan untuk segera mengubah password admin dari dalam sistem setelah login pertama kali di server produksi!)_

---

## 📦 Stack Teknologi

- **Backend:** Laravel 13 (PHP)
- **Frontend / CSS:** Tailwind CSS v4 + native DOM Javascript
- **Database:** MySQL
- **Assets Building:** Vite
- **Icon Pack:** Tabler Icons

---

### _Catatan Deploy_

Jika Anda mem-push sistem ini ke _cPanel_ atau _Shared Hosting_, pastikan direktori `public/build/` telah dikomit dengan Git (telah diaktifkan di `.gitignore`), sehingga website Anda tidak kehilangan _styling_ saat rilis.
