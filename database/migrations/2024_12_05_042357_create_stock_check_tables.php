<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users');
            $table->enum('status', ['ongoing', 'completed'])->default('ongoing');
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('last_updated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_check_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_check_id')->constrained('stock_checks')->onDelete('cascade');
            $table->foreignId('asset_id')->constrained('assets');
            $table->text('description')->nullable();
            $table->boolean('is_checked')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_check_items');
        Schema::dropIfExists('stock_checks');
    }
};
