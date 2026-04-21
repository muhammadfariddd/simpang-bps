<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE presensi MODIFY COLUMN status ENUM('hadir', 'izin', 'alpha', 'telat') NOT NULL DEFAULT 'hadir'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE presensi MODIFY COLUMN status ENUM('hadir', 'izin', 'alpha') NOT NULL DEFAULT 'hadir'");
    }
};
