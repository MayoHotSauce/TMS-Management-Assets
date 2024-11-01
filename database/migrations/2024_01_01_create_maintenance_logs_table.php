<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenanceLogsTable extends Migration
{
    public function up()
    {
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_id');
            $table->date('maintenance_date');
            $table->text('description');
            $table->decimal('cost', 10, 2)->default(0);
            $table->string('performed_by');
            $table->enum('status', ['completed', 'pending', 'scheduled']);
            $table->timestamps();
            
            $table->foreign('barang_id')->references('id')->on('daftar_barang')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('maintenance_logs');
    }
}