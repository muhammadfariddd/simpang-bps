<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penilaian extends Model
{
    protected $table = 'penilaian';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',          // mahasiswa
        'admin_id',         // penilai
        'kedisiplinan',     // 0-100
        'kualitas_kerja',   // 0-100
        'inisiatif',        // 0-100
        'kerjasama',        // 0-100
        'komunikasi',       // 0-100
        'catatan',
        'nilai_akhir',      // rata-rata
    ];

    protected $casts = [
        'kedisiplinan'   => 'integer',
        'kualitas_kerja' => 'integer',
        'inisiatif'      => 'integer',
        'kerjasama'      => 'integer',
        'komunikasi'     => 'integer',
        'nilai_akhir'    => 'float',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Hitung nilai akhir otomatis.
     */
    public function hitungNilaiAkhir(): float
    {
        $komponen = [
            $this->kedisiplinan,
            $this->kualitas_kerja,
            $this->inisiatif,
            $this->kerjasama,
            $this->komunikasi,
        ];
        return round(array_sum($komponen) / count($komponen), 2);
    }

    /**
     * Label predikat berdasarkan nilai akhir.
     */
    public function getPredikatAttribute(): string
    {
        $nilai = $this->nilai_akhir ?? 0;
        return match(true) {
            $nilai >= 90 => 'Sangat Baik',
            $nilai >= 75 => 'Baik',
            $nilai >= 60 => 'Cukup',
            default      => 'Kurang',
        };
    }
}
