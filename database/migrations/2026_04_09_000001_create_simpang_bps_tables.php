<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Profil Mahasiswa ────────────────────────────────────────────
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nim')->unique();
            $table->string('universitas');
            $table->string('jurusan')->nullable();
            $table->string('divisi')->nullable();        // divisi BPS tempat magang
            $table->string('target_proyek')->nullable(); // deskripsi singkat target
            $table->date('periode_mulai')->nullable();
            $table->date('periode_selesai')->nullable();
            $table->enum('status', ['aktif', 'selesai', 'nonaktif'])->default('aktif');
            $table->float('nilai_akhir')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });

        // ── Presensi ────────────────────────────────────────────────────
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->decimal('lat_in', 10, 7)->nullable();
            $table->decimal('lng_in', 10, 7)->nullable();
            $table->decimal('lat_out', 10, 7)->nullable();
            $table->decimal('lng_out', 10, 7)->nullable();
            $table->enum('status', ['hadir', 'izin', 'alpha'])->default('hadir');
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'tanggal']); // satu presensi per hari
        });

        // ── Logbook ─────────────────────────────────────────────────────
        Schema::create('logbook', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal');
            $table->text('deskripsi_kegiatan');
            $table->enum('kategori', ['Pengolahan Data', 'Survei', 'Administrasi', 'IT Support', 'Lainnya'])->default('Lainnya');
            $table->string('file_bukti')->nullable();
            $table->string('link_bukti')->nullable();
            $table->enum('status', ['pending', 'disetujui', 'revisi'])->default('pending');
            $table->text('komentar_admin')->nullable();
            $table->unsignedBigInteger('divalidasi_oleh')->nullable();
            $table->timestamp('divalidasi_pada')->nullable();
            $table->timestamps();

            $table->foreign('divalidasi_oleh')->references('id')->on('users')->nullOnDelete();
        });

        // ── Proyek ──────────────────────────────────────────────────────
        Schema::create('proyek', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama_proyek');
            $table->text('deskripsi')->nullable();
            $table->unsignedTinyInteger('progress_persen')->default(0);
            $table->enum('status', ['berjalan', 'selesai'])->default('berjalan');
            $table->string('file_laporan')->nullable(); // laporan akhir PDF
            $table->timestamps();
        });

        // ── Milestone ───────────────────────────────────────────────────
        Schema::create('milestone', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyek_id')->constrained('proyek')->cascadeOnDelete();
            $table->string('nama_milestone');
            $table->text('deskripsi')->nullable();
            $table->unsignedTinyInteger('progress_persen')->default(0);
            $table->date('target_selesai')->nullable();
            $table->enum('status', ['belum', 'proses', 'selesai'])->default('belum');
            $table->timestamps();
        });

        // ── Penilaian ───────────────────────────────────────────────────
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // mahasiswa
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedTinyInteger('kedisiplinan')->default(0);
            $table->unsignedTinyInteger('kualitas_kerja')->default(0);
            $table->unsignedTinyInteger('inisiatif')->default(0);
            $table->unsignedTinyInteger('kerjasama')->default(0);
            $table->unsignedTinyInteger('komunikasi')->default(0);
            $table->text('catatan')->nullable();
            $table->float('nilai_akhir')->nullable();
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('users')->nullOnDelete();
        });

        // ── Pengumuman ──────────────────────────────────────────────────
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('judul');
            $table->text('isi');
            $table->string('target')->default('semua'); // 'semua' | 'mahasiswa' | user_id
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
        Schema::dropIfExists('penilaian');
        Schema::dropIfExists('milestone');
        Schema::dropIfExists('proyek');
        Schema::dropIfExists('logbook');
        Schema::dropIfExists('presensi');
        Schema::dropIfExists('mahasiswa');
    }
};
