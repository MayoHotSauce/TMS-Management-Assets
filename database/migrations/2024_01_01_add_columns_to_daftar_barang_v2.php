<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToDaftarBarang extends Migration
{
    public function up()
    {
        Schema::table('daftar_barang', function (Blueprint $table) {
            $table->enum('condition', ['good', 'needs_maintenance', 'damaged'])->default('good');
            $table->date('last_maintenance')->nullable();
            $table->date('next_maintenance')->nullable();
            $table->enum('status', ['active', 'maintenance', 'retired'])->default('active');
        });
    }

    public function down()
    {
        Schema::table('daftar_barang', function (Blueprint $table) {
            $table->dropColumn(['condition', 'last_maintenance', 'next_maintenance', 'status']);
        });
    }
}