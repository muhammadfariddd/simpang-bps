<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ────────────────────────────────────────────────
        User::create([
            'nama_lengkap' => 'Administrator BPS Kab. Jepara',
            'username'     => 'bps_jepara',
            'email'        => 'bps3320@bps.go.id',
            'password'     => Hash::make('simpang_3320'),
            'peran'        => 'admin',
            'is_active'    => true,
        ]);
    }
}
