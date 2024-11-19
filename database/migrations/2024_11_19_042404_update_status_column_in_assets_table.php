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
        // Hapus kolom status yang lama dan buat yang baru dengan ENUM yang diperbarui
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        DB::statement("ALTER TABLE assets ADD status ENUM('dipakai', 'tidak_dipakai', 'siap_dipakai', 'perbaikan', 'sedang_perbaikan') DEFAULT 'siap_dipakai'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke status enum yang sebelumnya
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        DB::statement("ALTER TABLE assets ADD status ENUM('dipakai', 'tidak_dipakai', 'siap_dipakai') DEFAULT 'siap_dipakai'");
    }
};
