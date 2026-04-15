<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'nim',
        'universitas',
        'jurusan',
        'divisi',
        'target_proyek',
        'periode_mulai',
        'periode_selesai',
        'status', // 'aktif' | 'selesai' | 'nonaktif'
        'nilai_akhir',
        'catatan_admin',
    ];

    protected $casts = [
        'periode_mulai'   => 'date',
        'periode_selesai' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function logbooks(): HasMany
    {
        return $this->hasMany(Logbook::class, 'user_id', 'user_id');
    }

    public function presensis(): HasMany
    {
        return $this->hasMany(Presensi::class, 'user_id', 'user_id');
    }

    public function projeks(): HasMany
    {
        return $this->hasMany(Proyek::class, 'user_id', 'user_id');
    }

    public function penilaian(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Penilaian::class, 'user_id', 'user_id');
    }

    /**
     * Hitung persentase progress: rata-rata progress semua proyek aktif.
     */
    public function getProgressAttribute(): int
    {
        $projeks = $this->projeks;
        if ($projeks->isEmpty()) return 0;
        return (int) $projeks->avg('progress_persen');
    }

    /**
     * Hitung total hari magang yang sudah dijalani.
     */
    public function getDurasiHariAttribute(): int
    {
        if (! $this->periode_mulai) return 0;
        $akhir = $this->periode_selesai ?? now();
        return (int) $this->periode_mulai->diffInDays($akhir);
    }
}
