<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetTransfersTable extends Migration
{
    public function up()
    {
        Schema::create('asset_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_id');
            $table->string('from_room');
            $table->string('to_room');
            $table->date('transfer_date');
            $table->text('reason');
            $table->string('approved_by')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed']);
            $table->timestamps();
            
            $table->foreign('barang_id')->references('id')->on('daftar_barang')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('asset_transfers');
    }
}
