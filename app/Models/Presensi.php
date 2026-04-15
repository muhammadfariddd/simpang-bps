<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presensi extends Model
{
    protected $table = 'presensi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'tanggal',
        'check_in',
        'check_out',
        'lat_in',
        'lng_in',
        'lat_out',
        'lng_out',
        'status', // 'hadir' | 'izin' | 'alpha'
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Apakah sudah check-in hari ini.
     */
    public static function sudahCheckIn(int $userId): bool
    {
        return self::where('user_id', $userId)
            ->whereDate('tanggal', today())
            ->exists();
    }

    /**
     * Apakah sudah check-out hari ini.
     */
    public static function sudahCheckOut(int $userId): bool
    {
        return self::where('user_id', $userId)
            ->whereDate('tanggal', today())
            ->whereNotNull('check_out')
            ->exists();
    }

    /**
     * Presensi hari ini untuk user.
     */
    public static function hariIni(int $userId): ?self
    {
        return self::where('user_id', $userId)
            ->whereDate('tanggal', today())
            ->first();
    }
}
