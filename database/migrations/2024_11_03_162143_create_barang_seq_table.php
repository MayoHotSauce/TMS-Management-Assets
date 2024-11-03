<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('barang_seq', function (Blueprint $table) {
            $table->id();
            $table->integer('next_val')->default(1);
            $table->timestamps();
        });

        // Insert initial sequence value
        DB::table('barang_seq')->insert([
            'next_val' => 1
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('barang_seq');
    }
}; 