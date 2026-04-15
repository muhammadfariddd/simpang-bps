<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proyek extends Model
{
    protected $table = 'proyek';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'nama_proyek',
        'deskripsi',
        'progress_persen', // 0-100
        'status', // 'berjalan' | 'selesai'
        'file_laporan', // path file laporan akhir PDF
    ];

    protected $casts = [
        'progress_persen' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class, 'proyek_id');
    }
}
