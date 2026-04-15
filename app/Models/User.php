<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id'; // tetap id (integer) — JANGAN ubah ini

    protected $fillable = [
        'nama_lengkap',
        'username',
        'email',
        'password',
        'peran', // 'admin' | 'mahasiswa'
        'foto',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─── CATATAN PENTING ──────────────────────────────────────────────
    // getAuthIdentifierName() DIHAPUS karena menyebabkan bug login:
    // Jika dikembalikan 'username', session menyimpan string 'admin',
    // lalu saat request berikutnya User::find('admin') gagal (id adalah
    // integer) → user tiba-tiba ter-logout.
    //
    // Auth::attempt(['username' => ..., 'password' => ...]) sudah bekerja
    // benar tanpa perlu override method ini — Laravel meng-query kolom
    // sesuai key yang diberikan ke attempt().
    // ─────────────────────────────────────────────────────────────────

    /**
     * Relasi ke profil mahasiswa.
     */
    public function mahasiswa(): HasOne
    {
        return $this->hasOne(Mahasiswa::class, 'user_id');
    }

    /**
     * Relasi: logbook milik user ini.
     */
    public function logbooks(): HasMany
    {
        return $this->hasMany(Logbook::class, 'user_id');
    }

    /**
     * Relasi: presensi milik user ini.
     */
    public function presensis(): HasMany
    {
        return $this->hasMany(Presensi::class, 'user_id');
    }
}
