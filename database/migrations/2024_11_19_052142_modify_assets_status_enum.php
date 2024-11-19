<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE assets MODIFY COLUMN status ENUM(
            'siap_dipakai',
            'sedang_dipakai',
            'dalam_perbaikan',
            'rusak',
            'siap_dipinjam',
            'sedang_dipinjam',
            'dimusnahkan'
        ) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE assets MODIFY COLUMN status ENUM(
            'available',
            'in_use',
            'maintenance',
            'retired'
        ) NOT NULL");
    }
};
