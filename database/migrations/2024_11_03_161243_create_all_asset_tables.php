<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create daftar_barang table first
        Schema::create('daftar_barang', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('condition', ['good', 'needs_maintenance', 'damaged'])->default('good');
            $table->date('last_maintenance')->nullable();
            $table->date('next_maintenance')->nullable();
            $table->enum('status', ['active', 'maintenance', 'retired'])->default('active');
            $table->timestamps();
        });

        // Categories table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Rooms table
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('floor');
            $table->string('building');
            $table->integer('capacity')->nullable();
            $table->string('responsible_person')->nullable();
            $table->timestamps();
        });

        // Assets table
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('asset_tag')->unique();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('room_id')->constrained('rooms');
            $table->date('purchase_date');
            $table->decimal('purchase_cost', 10, 2);
            $table->enum('status', ['available', 'in_use', 'maintenance', 'retired']);
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Maintenance logs table
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

        // Asset Transfers table
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

    public function down(): void
    {
        Schema::dropIfExists('asset_transfers');
        Schema::dropIfExists('maintenance_logs');
        Schema::dropIfExists('assets');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('daftar_barang');
    }
}; 