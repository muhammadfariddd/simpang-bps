<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\LaporanController;

// ─── Redirect root ke dashboard ─────────────────────────────────
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// ─── Auth Routes ─────────────────────────────────────────────────
Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ─── Protected Routes ────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard (semua role, view berbeda berdasarkan peran)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Presensi (semua role) ─────────────────────────────────────
    Route::prefix('presensi')->name('presensi.')->group(function () {
        Route::get('/',        [PresensiController::class, 'index'])->name('index');
        Route::post('/check-in',  [PresensiController::class, 'checkIn'])->name('check-in');
        Route::post('/check-out', [PresensiController::class, 'checkOut'])->name('check-out');

        // Admin only
        Route::get('/rekap', [PresensiController::class, 'rekap'])
            ->name('rekap')
            ->middleware('role:admin');
    });

    // ── Logbook (semua role, akses berbeda) ───────────────────────
    Route::resource('logbook', LogbookController::class);
    Route::post('/logbook/{id}/validasi', [LogbookController::class, 'validasi'])
        ->name('logbook.validasi')
        ->middleware('role:admin');

    // ── Proyek & Milestone (semua role) ───────────────────────────
    Route::resource('proyek', ProyekController::class);
    Route::post('/proyek/{proyek}/milestone', [ProyekController::class, 'storeMilestone'])
        ->name('proyek.milestone.store');
    Route::patch('/milestone/{id}', [ProyekController::class, 'updateMilestone'])
        ->name('milestone.update');

    // ── Pengumuman (semua read; admin create/delete) ──────────────
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::middleware('role:admin')->group(function () {
        Route::get('/pengumuman/create',    [PengumumanController::class, 'create'])->name('pengumuman.create');
        Route::post('/pengumuman',          [PengumumanController::class, 'store'])->name('pengumuman.store');
        Route::delete('/pengumuman/{id}',   [PengumumanController::class, 'destroy'])->name('pengumuman.destroy');
    });

    // ── Admin only ────────────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        // Manajemen Mahasiswa
        Route::resource('users', UserController::class);

        // Penilaian
        Route::get('/penilaian',               [PenilaianController::class, 'index'])->name('penilaian.index');
        Route::get('/penilaian/create/{mahasiswaId}', [PenilaianController::class, 'create'])->name('penilaian.create');
        Route::post('/penilaian',              [PenilaianController::class, 'store'])->name('penilaian.store');
        Route::get('/penilaian/{id}',          [PenilaianController::class, 'show'])->name('penilaian.show');

        // Laporan & Export
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/kehadiran', [LaporanController::class, 'kehadiran'])->name('kehadiran');
            Route::get('/logbook',   [LaporanController::class, 'logbook'])->name('logbook');
            Route::get('/export/kehadiran', [LaporanController::class, 'exportKehadiran'])->name('export.kehadiran');
            Route::get('/export/logbook',   [LaporanController::class, 'exportLogbook'])->name('export.logbook');
        });
    });

    // ── Mahasiswa: laporan pribadi ────────────────────────────────
    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('/laporan/kehadiran-saya', [LaporanController::class, 'kehadiran'])->name('laporan.kehadiran.saya');
        Route::get('/laporan/logbook-saya',   [LaporanController::class, 'logbook'])->name('laporan.logbook.saya');
    });

});
