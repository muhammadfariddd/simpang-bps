<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Logbook extends Model
{
    protected $table = 'logbook';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'tanggal',
        'deskripsi_kegiatan',
        'kategori', // 'Pengolahan Data' | 'Survei' | 'Administrasi' | 'IT Support' | 'Lainnya'
        'file_bukti',       // path file upload
        'link_bukti',       // link eksternal
        'status',           // 'pending' | 'disetujui' | 'revisi'
        'komentar_admin',
        'divalidasi_oleh',  // user_id admin
        'divalidasi_pada',
    ];

    protected $casts = [
        'tanggal'         => 'date',
        'divalidasi_pada' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'divalidasi_oleh');
    }

    /**
     * Scope: logbook pending (belum divalidasi).
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: logbook disetujui.
     */
    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    /**
     * Kategori yang tersedia.
     */
    public static function kategoriList(): array
    {
        return ['Pengolahan Data', 'Survei', 'Administrasi', 'IT Support', 'Lainnya'];
    }
}
