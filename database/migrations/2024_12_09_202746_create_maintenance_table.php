<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenanceTable extends Migration
{
    public function up()
    {
        Schema::create('maintenance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('assets');
            $table->date('maintenance_date');
            $table->text('description');
            $table->decimal('cost', 10, 2);
            $table->string('performed_by');
            $table->enum('status', ['scheduled', 'pending', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('maintenance');
    }
}
