<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';
    protected $primaryKey = 'id';

    protected $fillable = [
        'admin_id',
        'judul',
        'isi',
        'target', // 'semua' | 'mahasiswa' | user_id tertentu (string)
        'is_pinned',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
