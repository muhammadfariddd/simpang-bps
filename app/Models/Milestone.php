<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Milestone extends Model
{
    protected $table = 'milestone';
    protected $primaryKey = 'id';

    protected $fillable = [
        'proyek_id',
        'nama_milestone',
        'deskripsi',
        'progress_persen',  // 0-100
        'target_selesai',
        'status', // 'belum' | 'proses' | 'selesai'
    ];

    protected $casts = [
        'target_selesai'  => 'date',
        'progress_persen' => 'integer',
    ];

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }
}
